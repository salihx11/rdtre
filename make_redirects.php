<?php
// make_redirects.php
// Run ONCE at: yoursite.com/make_redirects.php
// Creates username.php redirect for every creator in the DB
// DELETE this file after running!

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("sql107.infinityfree.com","if0_40250610","5cOYv3nYvbV6cVw","if0_40250610_fanapp1",3306);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

$root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$res  = $conn->query("SELECT id, username FROM creators WHERE username != '' ORDER BY id ASC");

$created = [];
$skipped = [];
$errors  = [];

while ($row = $res->fetch_assoc()) {
    $uname = preg_replace('/[^a-zA-Z0-9_]/', '', $row['username']);
    if (!$uname) continue;

    $filepath = $root . '/' . $uname . '.php';
    $content  = '<?php header("Location: /creator.php?u=' . $uname . '"); exit(); ?>';

    if (file_exists($filepath)) {
        $skipped[] = $uname . '.php (already exists)';
    } else {
        $result = @file_put_contents($filepath, $content);
        if ($result !== false) {
            $created[] = $uname . '.php';
        } else {
            $errors[] = $uname . '.php (write failed - check permissions)';
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Make Redirects</title>
<style>
body{font-family:monospace;background:#0a0809;color:#f5f0eb;padding:30px;max-width:600px}
h2{color:#e8547a;margin-bottom:20px}
.ok{color:#22c55e} .skip{color:#d4a843} .err{color:#f87171}
.box{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:16px;margin:12px 0}
a{color:#e8547a}
</style>
</head>
<body>
<h2>Creator Redirect Files</h2>

<?php if (!empty($created)): ?>
<div class="box">
<div class="ok">✓ Created (<?= count($created) ?>):</div>
<?php foreach ($created as $f): ?>
  <div style="margin-top:6px">→ <strong><?= htmlspecialchars($f) ?></strong></div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($skipped)): ?>
<div class="box">
<div class="skip">~ Skipped (<?= count($skipped) ?>):</div>
<?php foreach ($skipped as $f): ?>
  <div style="margin-top:4px">→ <?= htmlspecialchars($f) ?></div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
<div class="box">
<div class="err">✗ Failed (<?= count($errors) ?>):</div>
<?php foreach ($errors as $f): ?>
  <div style="margin-top:4px">→ <?= htmlspecialchars($f) ?></div>
<?php endforeach; ?>
<p style="margin-top:10px;font-size:12px;color:#7a6e66">Make sure htdocs/ is writable (chmod 755)</p>
</div>
<?php endif; ?>

<?php if (empty($created) && empty($errors)): ?>
<div class="box"><div class="skip">Nothing to do — all files already exist.</div></div>
<?php endif; ?>

<div class="box" style="border-color:rgba(232,84,122,.3)">
  <div style="color:#e8547a;font-weight:bold;margin-bottom:8px">⚠ DELETE THIS FILE NOW</div>
  <div style="font-size:13px;color:#c4b8ad">Remove <code>make_redirects.php</code> from your htdocs folder for security.</div>
</div>

<p style="margin-top:20px">
  Test: <a href="/sofia_xo.php">sofia_xo.php</a> &nbsp;|&nbsp;
  <a href="/creator.php?u=sofia_xo">creator.php?u=sofia_xo</a>
</p>
</body>
</html>