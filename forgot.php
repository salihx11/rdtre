<?php
session_start();

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("sql107.infinityfree.com","if0_40250610","5cOYv3nYvbV6cVw","if0_40250610_fanapp1",3306);
if ($conn->connect_error) { $conn = null; }

$msg = $err = "";
$step  = 'request';
$token = isset($_GET['token']) ? trim($_GET['token']) : '';
if ($token) $step = 'reset';

// ── STEP 1: Request reset link ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_reset'])) {
    if (!$conn) { $err = "Database unavailable."; }
    else {
        $email = trim($_POST['email']);
        // Add columns safely
        $conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS reset_token VARCHAR(100) DEFAULT NULL");
        $conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS reset_expires DATETIME DEFAULT NULL");

        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user    = $result->fetch_assoc();
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600);
            $upd = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
            $upd->bind_param("ssi", $token, $expires, $user['id']);
            $upd->execute();
            $upd->close();

            $link    = "https://marcos.xo.je/forgot.php?token=" . $token;
            $subject = "Reset your Fanfan password";
            $body    = "Hi " . $user['username'] . ",\n\nReset your password here (valid 1 hour):\n\n" . $link . "\n\nIf you didn't request this, ignore this email.\n\n— Fanfan Team";
            $headers = "From: noreply@marcos.xo.je\r\nX-Mailer: PHP/" . phpversion();
            $sent    = mail($email, $subject, $body, $headers);

            if ($sent) {
                $msg = "✅ Reset link sent to <strong>" . htmlspecialchars($email) . "</strong>. Check your inbox (and spam).";
            } else {
                // Show link directly if mail() not configured
                $msg = "⚠️ Email not configured on this server. <a href='$link' style='color:inherit;font-weight:700'>Click here to reset directly →</a>";
            }
        } else {
            $msg = "✅ If that email exists, a reset link has been sent.";
        }
        $stmt->close();
    }
}

// ── STEP 2: Set new password ────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_reset'])) {
    if (!$conn) { $err = "Database unavailable."; }
    else {
        $token    = trim($_POST['token']);
        $password = trim($_POST['password']);
        $confirm  = trim($_POST['confirm']);

        if ($password !== $confirm) {
            $err = "Passwords do not match.";
        } elseif (strlen($password) < 6) {
            $err = "Password must be at least 6 characters.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW() LIMIT 1");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user   = $result->fetch_assoc();
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
                $upd->bind_param("si", $hashed, $user['id']);
                $upd->execute();
                $upd->close();
                $msg  = "✅ Password updated! <a href='/login.php'>Login now →</a>";
                $step = 'done';
            } else {
                $err = "Invalid or expired reset link. Please request a new one.";
                $step = 'request';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Forgot Password — Fanfan</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--pink:#f472b6;--purple:#a855f7;--dark:#080810;--card:#0f0f1a;--border:rgba(244,114,182,0.15);--text:#e2d9f3;--muted:#6b6b8a}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow-x:hidden}
body::before{content:'';position:fixed;inset:0;z-index:0;background:radial-gradient(ellipse 60% 50% at 30% 30%,rgba(168,85,247,0.12) 0%,transparent 60%),radial-gradient(ellipse 50% 60% at 70% 70%,rgba(244,114,182,0.10) 0%,transparent 60%)}
body::after{content:'';position:fixed;inset:0;z-index:0;background-image:linear-gradient(rgba(244,114,182,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(244,114,182,0.04) 1px,transparent 1px);background-size:40px 40px;pointer-events:none}
.wrap{position:relative;z-index:1;width:100%;max-width:420px;animation:slideUp .6s cubic-bezier(.16,1,.3,1) both}
@keyframes slideUp{from{opacity:0;transform:translateY(32px)}to{opacity:1;transform:translateY(0)}}
.logo{text-align:center;margin-bottom:36px}
.logo-mark{display:inline-flex;align-items:center;gap:10px}
.logo-mark .icon{width:44px;height:44px;background:linear-gradient(135deg,var(--pink),var(--purple));border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 0 24px rgba(244,114,182,0.4)}
.logo-mark h1{font-family:'Playfair Display',serif;font-size:28px;background:linear-gradient(135deg,#fff 30%,var(--pink));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:36px 32px;box-shadow:0 0 0 1px rgba(244,114,182,0.05),0 32px 64px rgba(0,0,0,0.6),inset 0 1px 0 rgba(255,255,255,0.05)}
.icon-big{text-align:center;font-size:52px;margin-bottom:20px;animation:bounce 1.2s ease infinite alternate}
@keyframes bounce{from{transform:translateY(0)}to{transform:translateY(-8px)}}
.card-title{font-family:'Playfair Display',serif;font-size:22px;margin-bottom:8px;color:#fff}
.card-sub{font-size:13px;color:var(--muted);margin-bottom:24px;line-height:1.7}
.fg{margin-bottom:16px}
.fg label{display:block;font-size:11px;font-weight:500;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px}
.fg input{width:100%;padding:13px 16px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:10px;color:white;font-size:14px;font-family:'DM Sans',sans-serif;outline:none;transition:all .2s}
.fg input:focus{border-color:var(--pink);background:rgba(244,114,182,0.05);box-shadow:0 0 0 3px rgba(244,114,182,0.1)}
.fg input::placeholder{color:#3a3a5c}
.strength-bar{height:3px;border-radius:2px;background:#1a1a2e;margin-top:8px;overflow:hidden}
.strength-fill{height:100%;border-radius:2px;transition:width .3s,background .3s;width:0%}
.btn-submit{width:100%;padding:15px;background:linear-gradient(135deg,var(--pink),var(--purple));border:none;border-radius:12px;color:white;font-size:15px;font-weight:600;font-family:'DM Sans',sans-serif;cursor:pointer;transition:all .2s;margin-top:4px}
.btn-submit:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(244,114,182,0.4)}
.alert{padding:13px 16px;border-radius:10px;font-size:13px;margin-bottom:20px;line-height:1.7}
.alert-err{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#fca5a5}
.alert-ok{background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#86efac}
.alert a{color:inherit;font-weight:700}
.card-footer{text-align:center;margin-top:24px;font-size:13px;color:var(--muted)}
.card-footer a{color:var(--pink);text-decoration:none;font-weight:500}
.card-footer a:hover{text-decoration:underline}
.done-wrap{text-align:center;padding:16px 0}
</style>
</head>
<body>
<div class="wrap">
  <div class="logo">
    <div class="logo-mark">
      <div class="icon">🎭</div>
      <h1>fanfan</h1>
    </div>
  </div>

  <div class="card">

    <?php if ($step === 'request'): ?>
      <div class="icon-big">🔐</div>
      <div class="card-title">Forgot password?</div>
      <p class="card-sub">Enter your email and we'll send you a reset link.</p>

      <?php if ($msg): ?><div class="alert alert-ok"><?= $msg ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-err">⚠️ <?= htmlspecialchars($err) ?></div><?php endif; ?>

      <form method="POST" action="">
        <div class="fg">
          <label>Your Email</label>
          <input type="email" name="email" placeholder="you@email.com" required autocomplete="off">
        </div>
        <button type="submit" name="request_reset" class="btn-submit">Send Reset Link →</button>
      </form>

    <?php elseif ($step === 'reset'): ?>
      <div class="icon-big">🔑</div>
      <div class="card-title">Set new password</div>
      <p class="card-sub">Choose a strong new password for your account.</p>

      <?php if ($err): ?><div class="alert alert-err">⚠️ <?= htmlspecialchars($err) ?></div><?php endif; ?>

      <form method="POST" action="">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <div class="fg">
          <label>New Password</label>
          <input type="password" name="password" id="pwdInput" placeholder="Min. 6 characters" required>
          <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
        </div>
        <div class="fg">
          <label>Confirm Password</label>
          <input type="password" name="confirm" placeholder="Repeat password" required>
        </div>
        <button type="submit" name="do_reset" class="btn-submit">Reset Password →</button>
      </form>

    <?php elseif ($step === 'done'): ?>
      <div class="done-wrap">
        <div style="font-size:56px;margin-bottom:16px">🎉</div>
        <div class="card-title" style="text-align:center">All done!</div>
        <p class="card-sub" style="text-align:center">Your password has been updated.</p>
        <?php if ($msg): ?><div class="alert alert-ok" style="margin-top:16px"><?= $msg ?></div><?php endif; ?>
      </div>
    <?php endif; ?>

  </div>

  <div class="card-footer">
    Remember your password? <a href="/auth/login.php">Sign in</a>
  </div>
</div>

<script>
var pi = document.getElementById('pwdInput');
if (pi) {
  pi.addEventListener('input', function() {
    var v = this.value, f = document.getElementById('strengthFill');
    var p=0, c='#ef4444';
    if(v.length>=6){p=33;c='#f97316';}
    if(v.length>=10){p=66;c='#eab308';}
    if(v.length>=12&&/[A-Z]/.test(v)&&/[0-9]/.test(v)){p=100;c='#22c55e';}
    f.style.width=p+'%'; f.style.background=c;
  });
}
</script>
</body>
</html>