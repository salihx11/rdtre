<?php
/* ═══════════════════════════════════════════
   creator.php  —  Public Creator Profile
   /creator.php?u=sofia_xo
   ═══════════════════════════════════════════ */
session_start();
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli("sql107.infinityfree.com","if0_40250610","5cOYv3nYvbV6cVw","if0_40250610_fanapp1",3306);
if ($conn->connect_error) $conn = null;
define('CDN','https://marcos.xo.je/uploads/');

/* ── Resolve creator ── */
$slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/','', $_GET['u'] ?? '')));
if (!$slug) { header("Location: /"); exit(); }

$creator = null;
if ($conn) {
    $st = $conn->prepare("SELECT * FROM creators WHERE username=? LIMIT 1");
    if ($st) { $st->bind_param("s",$slug); $st->execute(); $creator=$st->get_result()->fetch_assoc(); $st->close(); }
}

/* ── 404 ── */
if (!$creator) {
    http_response_code(404);
    ?><!DOCTYPE html>
    <html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Not Found — Fanfan</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>*{margin:0;padding:0;box-sizing:border-box}body{font-family:Sora,sans-serif;background:#f8f7f5;display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;padding:24px}.n{font-family:serif;font-size:80px;font-weight:700;color:#e8547a;line-height:1}.b{display:inline-flex;align-items:center;gap:6px;margin-top:22px;font-size:13px;font-weight:600;color:#e8547a;border:1.5px solid rgba(232,84,122,.3);padding:10px 22px;border-radius:50px;text-decoration:none}p{color:#9b9489;margin:8px 0 4px;font-size:14px}</style>
    </head><body><div>
    <div class="n">404</div><p>Creator not found</p>
    <code style="background:#ece9e3;padding:3px 12px;border-radius:8px;font-size:13px;color:#3c3830;display:inline-block;margin-top:4px">@<?= htmlspecialchars($slug) ?></code>
    <br><a href="/" class="b">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>Back to Fanfan</a>
    </div></body></html>
    <?php exit();
}

/* ── Helpers ── */
function av_url($c) {
    if (empty($c['profile_image'])) return '';
    $f = $c['profile_image'];
    if (strpos($f,'http')===0) return $f;
    if (strpos($f,'creators/')===0 || strpos($f,'users/')===0) return CDN.$f;
    return CDN.'creators/'.$c['id'].'/cover/'.$f;
}
function post_url($cid,$f) {
    if (empty($f)) return '';
    return strpos($f,'http')===0 ? $f : CDN.'creators/'.$cid.'/posts/'.$f;
}
function ic($n,$sz=16,$xtra='') {
    $d=[
        'back'      =>'<polyline points="15 18 9 12 15 6"/>',
        'heart'     =>'<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
        'heartf'    =>'<path fill="currentColor" stroke="none" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
        'message'   =>'<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        'check'     =>'<polyline points="20 6 9 17 4 12"/>',
        'lock'      =>'<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'star'      =>'<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'starf'     =>'<polygon fill="currentColor" stroke="none" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'video'     =>'<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>',
        'flag'      =>'<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>',
        'settings'  =>'<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
        'x'         =>'<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'eye'       =>'<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
        'home'      =>'<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'users'     =>'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'grid'      =>'<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
        'image'     =>'<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>',
        'snap'      =>'<path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.8 13.4c-.1.1-1.2.3-1.7.4-.1.3-.3.7-.6.7s-.6-.2-.9-.2c-.2 0-.4.1-.6.1-.5 0-.9-.3-1-.3-.4.2-.8.4-1.2.4-.6 0-1.1-.3-1.4-.3-.3 0-.5.1-.7.1-.3 0-.5-.3-.6-.7-.4-.1-1.5-.2-1.7-.4-.1-.1-.1-.3 0-.4.2-.1.8-.2 1.3-.4.2-.4.5-1 .6-1.8h-.3c-.2 0-.4-.2-.4-.4s.2-.4.4-.4h.2c0-.2.1-.4.1-.6V11c0-1.5 1.3-2.7 2.9-2.7h.4c1.6 0 2.9 1.2 2.9 2.7v.6h.2c.2 0 .4.2.4.4s-.2.4-.4.4h-.3c.1.8.4 1.4.6 1.8.5.2 1.1.3 1.3.4.1.1.1.2 0 .4z"/>',
        'telegram'  =>'<line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>',
        'verified'  =>'<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'external'  =>'<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>',
        'chevron-r' =>'<polyline points="9 18 15 12 9 6"/>',
        'gem'       =>'<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'plus'      =>'<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
        'user-plus' =>'<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>',
    ];
    $path = $d[$n] ?? '<circle cx="12" cy="12" r="10"/>';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$sz.'" height="'.$sz.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0" '.$xtra.'>'.$path.'</svg>';
}

/* ── Session / auth ── */
$cid        = (int)$creator['id'];
$uid        = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$uname      = $_SESSION['username'] ?? '';
$subscribed = false;
$is_owner   = false;
$user_gems  = 0;

if ($uid && $conn) {
    $st = $conn->prepare("SELECT gems FROM users WHERE id=? LIMIT 1");
    if ($st) { $st->bind_param("i",$uid); $st->execute(); $ug=$st->get_result()->fetch_assoc(); $user_gems=(int)($ug['gems']??0); $st->close(); }
    $st = $conn->prepare("SELECT id FROM subscriptions WHERE user_id=? AND creator_id=? AND is_active=1 LIMIT 1");
    if ($st) { $st->bind_param("ii",$uid,$cid); $st->execute(); $st->store_result(); $subscribed=($st->num_rows>0); $st->close(); }
    $st = $conn->prepare("SELECT id FROM creators WHERE id=? AND user_id=? LIMIT 1");
    if ($st) { $st->bind_param("ii",$cid,$uid); $st->execute(); $st->store_result(); $is_owner=($st->num_rows>0); $st->close(); }
}
$can_see = ($subscribed || $is_owner);

/* ── POST actions ── */
$msg = $err = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && $uid && $conn) {
    $act = $_POST['action'] ?? '';

    if ($act==='subscribe' && !$subscribed && !$is_owner) {
        $cost = 1000;
        if ($user_gems < $cost) {
            $err = 'You don\'t have enough gems. You need '.number_format($cost).' gems. <a href="/dashboard/buygems.php" style="color:var(--rose);font-weight:700;text-decoration:underline">Buy gems</a>';
        } else {
            $conn->begin_transaction();
            try {
                $conn->query("UPDATE users SET gems=gems-$cost WHERE id=$uid AND gems>=$cost");
                if ($conn->affected_rows < 1) throw new Exception("gems");
                $conn->query("INSERT INTO subscriptions (user_id,creator_id,is_active,created_at) VALUES ($uid,$cid,1,NOW()) ON DUPLICATE KEY UPDATE is_active=1,created_at=NOW()");
                $dn = $conn->real_escape_string("Subscribed to @".$creator['username']);
                $conn->query("INSERT INTO gem_transactions (user_id,amount,type,description,created_at) VALUES ($uid,-$cost,'subscription','$dn',NOW())");
                $conn->commit();
                $subscribed = true; $can_see = true; $user_gems -= $cost;
                $msg = 'Subscribed! You now have full access to '.htmlspecialchars($creator['display_name']?:$creator['username']).'\'s content.';
                // Notifications
                if (function_exists('notif_subscribed')) {
                    notif_subscribed($conn, $uid, $creator['display_name']?:$creator['username'], $creator['username']);
                    if (!empty($creator['user_id'])) notif_subscription_new($conn, (int)$creator['user_id'], $_SESSION['username']??'someone', $cid);
                }
            } catch(Exception $e) { $conn->rollback(); $err = 'Subscription failed. Please try again.'; }
        }
    }

    if ($act==='like' && isset($_POST['post_id'])) {
        $pid = (int)$_POST['post_id'];
        $lc  = $conn->query("SELECT id FROM post_likes WHERE post_id=$pid AND user_id=$uid LIMIT 1");
        if ($lc && $lc->num_rows > 0) {
            $conn->query("DELETE FROM post_likes WHERE post_id=$pid AND user_id=$uid");
            $conn->query("UPDATE posts SET likes=GREATEST(0,likes-1) WHERE id=$pid");
        } else {
            $conn->query("INSERT IGNORE INTO post_likes (post_id,user_id,created_at) VALUES ($pid,$uid,NOW())");
            $conn->query("UPDATE posts SET likes=likes+1 WHERE id=$pid");
        }
        header("Location: /creator.php?u=".urlencode($creator['username'])."#p$pid"); exit();
    }

    if ($act==='report' && isset($_POST['post_id'])) {
        $pid = (int)$_POST['post_id'];
        $rsn = $conn->real_escape_string(substr($_POST['reason']??'Inappropriate',0,200));
        $ex  = $conn->query("SELECT id FROM post_reports WHERE post_id=$pid AND user_id=$uid LIMIT 1");
        if (!$ex || $ex->num_rows===0)
            $conn->query("INSERT INTO post_reports (post_id,user_id,reason,created_at) VALUES ($pid,$uid,'$rsn',NOW())");
        header("Location: /creator.php?u=".urlencode($creator['username'])); exit();
    }
}

/* ── Load posts ── */
$posts = [];
if ($conn) {
    $lq = $uid ? "(SELECT COUNT(*) FROM post_likes WHERE post_id=p.id AND user_id=$uid)" : "0";
    $st = $conn->prepare("SELECT p.*,(SELECT COUNT(*) FROM post_likes WHERE post_id=p.id) AS like_count,($lq) AS user_liked FROM posts p WHERE p.creator_id=? ORDER BY p.created_at DESC LIMIT 80");
    if ($st) { $st->bind_param("i",$cid); $st->execute(); $rs=$st->get_result(); while($r=$rs->fetch_assoc()) $posts[]=$r; $st->close(); }
}

/* ── Computed vars ── */
$avatar     = av_url($creator);
$dname      = $creator['display_name'] ?: $creator['username'];
$handle     = $creator['username'];
$bio_raw    = $creator['bio'] ?? '';
$snap       = $creator['snap_contact'] ?? '';
$tg         = $creator['telegram_contact'] ?? '';
$followers  = number_format((int)($creator['fake_followers'] ?? 0));
$total_likes= number_format((int)($creator['fake_likes'] ?? 0));
$post_count = count($posts);
$is_active  = (int)($creator['is_active'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($dname) ?> (@<?= htmlspecialchars($handle) ?>) — Fanfan</title>
<meta name="description" content="<?= htmlspecialchars(substr($bio_raw,0,160)) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#f8f7f5;--white:#fff;--ink:#16130f;--ink2:#3c3830;--muted:#9b9489;
  --border:#ece9e3;--border2:#ddd8d0;
  --rose:#e8547a;--rose2:#c73d61;--rosebg:#fef1f4;--rosebdr:rgba(232,84,122,.18);
  --gold:#d4a843;--goldbg:#fef9ec;--goldbdr:rgba(212,168,67,.22);
  --green:#22c55e;--greenbg:#f0fdf4;--greenbdr:rgba(34,197,94,.2);
  --r:12px;--sh:0 1px 10px rgba(22,19,15,.06);--sh2:0 8px 32px rgba(22,19,15,.11);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
body{font-family:'Sora',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh;overflow-x:hidden}
a{text-decoration:none;color:inherit}
img{display:block;max-width:100%}
button,input,select,textarea{font-family:'Sora',sans-serif}
svg{display:inline-block;vertical-align:middle;flex-shrink:0}

/* ══ TOPBAR ══════════════════════════════════════ */
.topbar{
  position:sticky;top:0;z-index:200;
  height:54px;background:var(--white);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;padding:0 20px;gap:12px;
  backdrop-filter:blur(20px);
}
.tb-back{
  display:inline-flex;align-items:center;gap:5px;
  padding:7px 14px;border-radius:50px;
  border:1.5px solid var(--border2);
  font-size:12px;font-weight:600;color:var(--ink2);
  transition:all .18s;white-space:nowrap;flex-shrink:0;cursor:pointer;
  background:none;
}
.tb-back:hover{border-color:var(--ink);color:var(--ink)}
.tb-logo{
  display:flex;align-items:center;gap:8px;
  font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;color:var(--ink);
}
.tb-logo-mark{
  width:28px;height:28px;border-radius:7px;
  background:linear-gradient(135deg,var(--rose),#a02040);
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.tb-logo-mark svg{color:#fff;width:13px;height:13px}
.tb-right{display:flex;align-items:center;gap:7px;margin-left:auto}
.tb-btn{
  display:inline-flex;align-items:center;gap:5px;
  padding:7px 14px;border-radius:50px;font-size:12px;font-weight:600;
  transition:all .18s;white-space:nowrap;cursor:pointer;border:none;
}
.tb-btn-outline{border:1.5px solid var(--border2);color:var(--ink2);background:transparent}
.tb-btn-outline:hover{border-color:var(--rose);color:var(--rose);background:var(--rosebg)}
.tb-btn-rose{background:var(--rose);color:#fff;box-shadow:0 3px 12px rgba(232,84,122,.22)}
.tb-btn-rose:hover{background:var(--rose2)}

/* ══ COVER ═══════════════════════════════════════ */
.cover{
  height:200px;position:relative;overflow:hidden;
  background:linear-gradient(140deg,#f4eded 0%,#ece2df 40%,#f0ebe8 100%);
}
.cover img{width:100%;height:100%;object-fit:cover;opacity:.55}
.cover-fade{
  position:absolute;inset:0;
  background:linear-gradient(to bottom,transparent 40%,rgba(248,247,245,.95) 100%);
}

/* ══ MAIN LAYOUT ══════════════════════════════════ */
.wrap{max-width:935px;margin:0 auto;padding:0 20px 80px}
.content-layout{display:flex;gap:28px;margin-top:24px}
.main-content{flex:1;min-width:0}
.sidebar{
  width:260px;flex-shrink:0;
  display:flex;flex-direction:column;gap:18px;
}
@media(max-width:800px){
  .content-layout{flex-direction:column}
  .sidebar{width:100%}
}

/* ══ PROFILE HEADER ══════════════════════════════ */
.ph{
  display:flex;align-items:flex-end;gap:28px;
  margin-top:-44px;padding-bottom:22px;
  border-bottom:1px solid var(--border);
  position:relative;z-index:2;
  flex-wrap:wrap;
}
/* Avatar */
.av-wrap{flex-shrink:0;position:relative}
.av-ring{
  padding:3px;border-radius:50%;
  background:linear-gradient(135deg,var(--rose),var(--gold));
  display:inline-block;box-shadow:0 4px 18px rgba(232,84,122,.25);
}
.av-ring.no-ring{background:var(--border)}
.av{
  width:96px;height:96px;border-radius:50%;
  background:linear-gradient(135deg,var(--rose),#a02040);
  display:flex;align-items:center;justify-content:center;
  font-family:'Cormorant Garamond',serif;
  font-size:40px;font-weight:600;color:#fff;
  overflow:hidden;border:3px solid var(--white);
}
.av img{width:100%;height:100%;object-fit:cover}

/* Profile info */
.ph-info{flex:1;min-width:220px;padding-bottom:4px}
.ph-toprow{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:8px}
.ph-name{font-size:21px;font-weight:700;color:var(--ink);line-height:1.1}
.verified{
  display:inline-flex;align-items:center;gap:4px;
  background:var(--rosebg);color:var(--rose);
  font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;
  border:1px solid var(--rosebdr);white-space:nowrap;
}

/* Online status pill */
.online-pill{
  display:inline-flex;align-items:center;gap:5px;
  padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;white-space:nowrap;
}
.online-pill.active{background:var(--greenbg);color:var(--green);border:1px solid var(--greenbdr)}
.online-dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 0 2px rgba(34,197,94,.2)}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
.online-dot{animation:pulse 2s ease-in-out infinite}

/* Handle */
.ph-handle{font-size:12px;color:var(--muted);margin-bottom:10px;display:flex;align-items:center;gap:8px}

/* Stat row — Instagram style */
.ph-stats{display:flex;gap:28px;margin-bottom:14px;flex-wrap:wrap}
.pst{text-align:center}
.pst-v{font-size:17px;font-weight:700;color:var(--ink);display:block;line-height:1.1}
.pst-l{font-size:11px;color:var(--muted);margin-top:2px;letter-spacing:.2px}

/* Action buttons */
.ph-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}

/* Subscribe button */
.btn-sub{
  display:inline-flex;align-items:center;gap:8px;
  padding:10px 24px;border-radius:8px;font-size:13px;font-weight:700;
  background:var(--rose);color:#fff;border:none;cursor:pointer;
  transition:all .2s;box-shadow:0 4px 16px rgba(232,84,122,.28);
  white-space:nowrap;
}
.btn-sub:hover{background:var(--rose2);transform:translateY(-1px);box-shadow:0 7px 22px rgba(232,84,122,.33)}
.btn-sub.subbed{
  background:var(--greenbg);color:var(--green);
  border:1.5px solid var(--greenbdr);box-shadow:none;cursor:default;
}
.btn-sub.subbed:hover{transform:none;box-shadow:none}

/* Message button — subscribers only */
.btn-msg{
  display:inline-flex;align-items:center;gap:7px;
  padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;
  background:var(--white);color:var(--ink2);
  border:1.5px solid var(--border2);cursor:pointer;
  transition:all .2s;white-space:nowrap;text-decoration:none;
}
.btn-msg:hover{border-color:var(--rose);color:var(--rose);background:var(--rosebg)}
.btn-msg.locked{opacity:.45;pointer-events:none;cursor:not-allowed}

/* Owner bar */
.owner-strip{
  background:var(--goldbg);border:1px solid var(--goldbdr);
  border-radius:var(--r);padding:12px 16px;margin:16px 0;
  display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;
}
.owner-strip p{font-size:12px;color:#92650a;display:flex;align-items:center;gap:7px;font-weight:500}
.oacts{display:flex;gap:8px}
.obtn{
  display:inline-flex;align-items:center;gap:6px;
  padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;
  transition:all .18s;cursor:pointer;text-decoration:none;border:none;
}
.obtn-out{border:1.5px solid var(--goldbdr);color:#92650a;background:transparent}
.obtn-out:hover{background:rgba(212,168,67,.1)}
.obtn-rose{background:var(--rose);color:#fff;box-shadow:0 3px 10px rgba(232,84,122,.2)}
.obtn-rose:hover{background:var(--rose2)}

/* ══ ALERTS ══════════════════════════════════════ */
.alert{
  padding:12px 16px;border-radius:var(--r);font-size:13px;
  margin:14px 0;display:flex;align-items:flex-start;gap:10px;line-height:1.5;
}
.a-ok{background:var(--greenbg);border:1px solid var(--greenbdr);color:#15803d}
.a-err{background:var(--rosebg);border:1px solid var(--rosebdr);color:var(--rose2)}
.a-err a{color:var(--rose);font-weight:700;text-decoration:underline}
.a-err a:hover{color:var(--rose2)}

/* ══ BIO SECTION ══════════════════════════════════ */
.bio-sec{padding:18px 0 14px;border-bottom:1px solid var(--border)}
.bio-text{
  font-size:13px;color:var(--ink2);line-height:1.9;
  max-width:540px;white-space:pre-line;
}
.contacts{display:flex;gap:8px;margin-top:14px;flex-wrap:wrap}
.cpill{
  display:inline-flex;align-items:center;gap:7px;
  font-size:12px;font-weight:600;padding:7px 14px;
  border-radius:8px;border:1.5px solid var(--border2);
  color:var(--ink2);transition:all .2s;background:var(--white);
}
.cpill:hover{border-color:var(--rose);color:var(--rose);background:var(--rosebg)}
.cpill.locked{opacity:.4;pointer-events:none;background:var(--bg);cursor:default}
.cpill-lock-hint{font-size:11px;color:var(--muted);padding:7px 0;display:flex;align-items:center;gap:6px}

/* ══ LOCK CARD ════════════════════════════════════ */
.lock-card{
  background:var(--white);border:1px solid var(--border);
  border-radius:var(--r);padding:0;margin:22px 0;
  box-shadow:var(--sh);overflow:hidden;
}
.lc-top{
  background:linear-gradient(135deg,#1a0e16,#2a1520);
  padding:28px;display:flex;align-items:center;gap:20px;
}
.lc-icon{
  width:56px;height:56px;border-radius:12px;
  background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);
  display:flex;align-items:center;justify-content:center;
  color:rgba(255,255,255,.7);flex-shrink:0;
}
.lc-text h3{font-size:16px;font-weight:700;color:#fff;margin-bottom:5px}
.lc-text p{font-size:12px;color:rgba(255,255,255,.55);line-height:1.6}
.lc-bottom{
  padding:20px;display:flex;align-items:center;
  justify-content:space-between;flex-wrap:wrap;gap:12px;
  border-top:1px solid var(--border);background:var(--white);
}
.lc-price{
  display:flex;align-items:center;gap:10px;
}
.lc-price-ic{
  width:36px;height:36px;border-radius:9px;
  background:var(--goldbg);border:1px solid var(--goldbdr);
  display:flex;align-items:center;justify-content:center;color:var(--gold);flex-shrink:0;
}
.lc-price-val{font-size:15px;font-weight:700;color:var(--ink)}
.lc-price-sub{font-size:11px;color:var(--muted);margin-top:1px}

/* ══ TABS ═════════════════════════════════════════ */
.tabs{display:flex;border-bottom:1px solid var(--border);margin:20px 0 0}
.tab{
  display:flex;align-items:center;gap:6px;
  padding:10px 16px;font-size:12px;font-weight:600;
  color:var(--muted);border-bottom:2px solid transparent;
  cursor:pointer;transition:all .18s;margin-bottom:-1px;
}
.tab:hover{color:var(--ink2)}
.tab.on{color:var(--ink);border-bottom-color:var(--ink)}
.tab-ct{
  background:rgba(22,19,15,.07);color:var(--muted);
  font-size:10px;font-weight:700;padding:2px 6px;border-radius:20px;
}
.tab.on .tab-ct{background:var(--rosebg);color:var(--rose)}

/* ══ POST GRID — Instagram 3-col ════════════════ */
.post-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:3px;margin-top:3px}
.pc{
  position:relative;aspect-ratio:1;overflow:hidden;
  background:#e8e4df;cursor:pointer;
}
.pc img,.pc video{width:100%;height:100%;object-fit:cover;transition:transform .28s;pointer-events:none}
.pc:hover img,.pc:hover video{transform:scale(1.05)}
.pc.blurred img,.pc.blurred video{filter:blur(16px);transform:scale(1.1)}
.pc-lock{
  position:absolute;inset:0;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;
  background:rgba(248,247,245,.5);backdrop-filter:blur(1px);pointer-events:none;
}
.pc-lock svg{color:var(--muted);width:18px;height:18px}
.pc-lock p{font-size:9px;font-weight:600;color:var(--muted);letter-spacing:.3px}
/* Hover overlay (unlocked posts only) */
.pc-ov{
  position:absolute;inset:0;
  background:rgba(22,19,15,.6);
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
  opacity:0;transition:opacity .2s;
}
.pc:not(.blurred):hover .pc-ov{opacity:1}
.pc-likes{
  display:flex;align-items:center;gap:5px;
  font-size:14px;font-weight:700;color:#fff;
}
.pc-acts{display:flex;gap:6px}
.pc-act{
  padding:6px 13px;border-radius:7px;font-size:11px;font-weight:700;
  cursor:pointer;border:none;transition:all .18s;
}
.pc-act-like{background:var(--rose);color:#fff}
.pc-act-like:hover{background:var(--rose2)}
.pc-act-liked{background:#fff;color:var(--rose)}
.pc-act-rep{background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.22)}
.pc-act-rep:hover{background:rgba(239,68,68,.45)}
.vid-badge{
  position:absolute;top:7px;right:7px;
  background:rgba(22,19,15,.6);color:#fff;
  font-size:10px;font-weight:600;padding:3px 8px;border-radius:6px;
  display:flex;align-items:center;gap:3px;pointer-events:none;
}
/* Empty */
.grid-empty{
  grid-column:1/-1;text-align:center;
  padding:64px 20px;color:var(--muted);
}
.grid-empty svg{color:var(--border);width:44px;height:44px;display:block;margin:0 auto 14px}
.grid-empty h3{font-size:15px;color:var(--ink2);font-weight:600;margin-bottom:6px}

/* ══ SIDEBAR CARDS ════════════════════════════════ */
.side-card{
  background:var(--white);border:1px solid var(--border);
  border-radius:14px;padding:18px 16px;
  box-shadow:var(--sh);
}
.side-hdr{
  font-size:13px;font-weight:700;color:var(--ink);
  margin-bottom:14px;display:flex;align-items:center;gap:8px;
  letter-spacing:-.2px;
}
.side-hdr svg{color:var(--rose)}
.gems-short{
  display:flex;align-items:center;justify-content:space-between;
  padding:12px 0;border-bottom:1px dashed var(--border);
  margin-bottom:12px;
}
.gems-val{font-weight:700;color:var(--rose);font-size:15px}
.gems-buy{
  background:var(--rose);color:#fff;border:none;
  padding:5px 14px;border-radius:30px;font-size:11px;font-weight:700;cursor:pointer;
  transition:all .18s;text-decoration:none;display:inline-flex;align-items:center;gap:5px;
}
.gems-buy:hover{background:var(--rose2)}

/* ══ POST MODAL ════════════════════════════════ */
.pmo{
  position:fixed;inset:0;z-index:400;
  background:rgba(16,12,10,.78);backdrop-filter:blur(6px);
  display:none;align-items:center;justify-content:center;padding:20px;
}
.pmo.open{display:flex;animation:moFade .18s ease}
@keyframes moFade{from{opacity:0}to{opacity:1}}
.pmo-box{
  background:var(--white);border-radius:16px;
  width:100%;max-width:780px;max-height:92vh;
  display:flex;overflow:hidden;
  box-shadow:0 24px 72px rgba(0,0,0,.26);
  animation:moIn .22s cubic-bezier(.16,1,.3,1);
}
@keyframes moIn{from{transform:scale(.96)}to{transform:scale(1)}}
/* Media panel */
.pmo-media{
  flex:1;min-width:0;background:#0c0a09;
  display:flex;align-items:center;justify-content:center;
  position:relative;overflow:hidden;
}
/* Sidebar */
.pmo-side{
  width:320px;flex-shrink:0;display:flex;flex-direction:column;
  border-left:1px solid var(--border);
}
.pmo-hdr{
  display:flex;align-items:center;gap:10px;
  padding:14px 16px;border-bottom:1px solid var(--border);
}
.pmo-av{
  width:34px;height:34px;border-radius:50%;
  background:linear-gradient(135deg,var(--rose),#a02040);
  display:flex;align-items:center;justify-content:center;
  font-size:13px;font-weight:700;color:#fff;overflow:hidden;flex-shrink:0;
}
.pmo-av img{width:100%;height:100%;object-fit:cover}
.pmo-uname{font-size:13px;font-weight:700;color:var(--ink)}
.pmo-ts{font-size:11px;color:var(--muted);margin-top:1px}
.pmo-close{
  margin-left:auto;width:30px;height:30px;border-radius:8px;
  border:1.5px solid var(--border);background:var(--white);
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;flex-shrink:0;color:var(--ink2);transition:all .18s;
}
.pmo-close:hover{border-color:var(--rose);color:var(--rose);background:var(--rosebg)}
.pmo-cap{
  flex:1;overflow-y:auto;padding:16px;
  font-size:13px;color:var(--ink2);line-height:1.7;
  scrollbar-width:thin;
}
.pmo-cap::-webkit-scrollbar{width:3px}
.pmo-cap::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}
.pmo-likes-row{
  display:flex;align-items:center;gap:6px;
  padding:12px 16px;border-top:1px solid var(--border);
  font-size:13px;font-weight:600;color:var(--ink);
}
.pmo-foot{
  padding:12px 16px;border-top:1px solid var(--border);
  display:flex;flex-direction:column;gap:8px;
}
.pmo-acts{display:flex;gap:8px}
.pmo-btn{
  display:inline-flex;align-items:center;gap:6px;
  padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;
  border:1.5px solid var(--border);background:var(--white);color:var(--ink2);
  cursor:pointer;transition:all .18s;
}
.pmo-btn:hover{border-color:var(--rose);color:var(--rose);background:var(--rosebg)}
.pmo-btn.liked{color:var(--rose);border-color:var(--rosebdr);background:var(--rosebg)}
.pmo-rep-row{display:flex;gap:8px}
.pmo-rep-sel{
  flex:1;padding:8px 10px;font-size:12px;border-radius:8px;
  border:1.5px solid var(--border);background:var(--white);color:var(--ink);outline:none;
  transition:border-color .18s;
}
.pmo-rep-sel:focus{border-color:var(--rose)}
.pmo-rep-go{
  padding:8px 14px;border-radius:8px;background:rgba(239,68,68,.1);
  color:rgb(220,38,38);border:1.5px solid rgba(239,68,68,.22);
  font-size:12px;font-weight:600;cursor:pointer;transition:all .18s;white-space:nowrap;
}
.pmo-rep-go:hover{background:rgba(239,68,68,.18)}
/* Blur overlay inside modal */
.pmo-blur-ov{
  position:absolute;inset:0;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:14px;padding:28px;text-align:center;
  background:rgba(12,10,9,.72);
}
.pmo-blur-ov h3{font-size:16px;font-weight:700;color:#fff}
.pmo-blur-ov p{font-size:12px;color:rgba(255,255,255,.55);line-height:1.6;max-width:240px}
.pmo-sub-btn{
  display:inline-flex;align-items:center;gap:8px;
  padding:11px 24px;background:var(--rose);color:#fff;
  border-radius:8px;font-size:13px;font-weight:700;
  border:none;cursor:pointer;transition:all .2s;
}
.pmo-sub-btn:hover{background:var(--rose2);transform:translateY(-1px)}

/* ══ MESSAGE MODAL ════════════════════════════════ */
.msgmo{
  position:fixed;inset:0;z-index:400;
  background:rgba(16,12,10,.72);backdrop-filter:blur(6px);
  display:none;align-items:center;justify-content:center;padding:20px;
}
.msgmo.open{display:flex}
.msgmo-box{
  background:var(--white);border-radius:16px;padding:28px;
  width:100%;max-width:400px;
  box-shadow:0 20px 60px rgba(0,0,0,.2);
  animation:moIn .22s cubic-bezier(.16,1,.3,1);
}
.msgmo-title{font-size:15px;font-weight:700;color:var(--ink);display:flex;align-items:center;gap:8px;margin-bottom:4px}
.msgmo-sub{font-size:12px;color:var(--muted);margin-bottom:18px}
.msgmo-ta{
  width:100%;padding:11px 13px;border:1.5px solid var(--border);
  border-radius:10px;font-size:13px;font-family:'Sora',sans-serif;
  color:var(--ink);resize:none;min-height:100px;outline:none;transition:border-color .18s;
}
.msgmo-ta:focus{border-color:var(--rose)}
.msgmo-foot{display:flex;gap:10px;margin-top:14px}
.msgmo-cancel{flex:1;padding:11px;border:1.5px solid var(--border);border-radius:9px;background:#fff;color:var(--ink2);font-size:13px;font-weight:600;cursor:pointer;transition:all .18s}
.msgmo-cancel:hover{border-color:var(--ink)}
.msgmo-send{flex:1;padding:11px;background:var(--rose);border:none;border-radius:9px;color:#fff;font-size:13px;font-weight:700;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:7px}
.msgmo-send:hover{background:var(--rose2)}

/* ══ RESPONSIVE ════════════════════════════════════ */
@media(max-width:700px){
  .pmo-box{flex-direction:column;max-width:440px}
  .pmo-side{width:100%;border-left:none;border-top:1px solid var(--border)}
  .post-grid{grid-template-columns:repeat(3,1fr)}
  .ph{gap:16px}
  .av{width:78px;height:78px;font-size:32px}
  .ph-stats{gap:18px}
}
@media(max-width:480px){
  .wrap{padding:0 12px 60px}
  .topbar{padding:0 12px}
  .post-grid{grid-template-columns:repeat(3,1fr);gap:2px}
}
::-webkit-scrollbar{width:4px;height:4px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}
</style>
</head>
<body>

<!-- ══ TOPBAR ══ -->
<header class="topbar">
  <button class="tb-back" onclick="history.length>1?history.back():window.location='/'">
    <?= ic('back',13) ?> Back
  </button>
  <a href="/" class="tb-logo">
    <div class="tb-logo-mark"><?= ic('heart',13) ?></div>
    fanfan
  </a>
  <div class="tb-right">
    <?php if ($uid): ?>
      <a href="/dashboard/" class="tb-btn tb-btn-outline"><?= ic('home',13) ?> Home</a>
    <?php else: ?>
      <a href="/auth/login.php"  class="tb-btn tb-btn-outline">Sign in</a>
      <a href="/auth/signup.php" class="tb-btn tb-btn-rose"><?= ic('user-plus',12) ?> Join free</a>
    <?php endif; ?>
  </div>
</header>

<!-- ══ COVER ══ -->
<div class="cover">
  <?php if ($avatar): ?><img src="<?= htmlspecialchars($avatar) ?>" alt=""><?php endif; ?>
  <div class="cover-fade"></div>
</div>

<!-- ══ MAIN ══ -->
<div class="wrap">

  <?php if ($msg): ?>
  <div class="alert a-ok"><?= ic('check',14) ?> <?= $msg ?></div>
  <?php endif; ?>
  <?php if ($err): ?>
  <div class="alert a-err"><?= ic('star',14,'style="color:var(--rose)"') ?> <?= $err ?></div>
  <?php endif; ?>

  <!-- ══ PROFILE HEADER ══ -->
  <div class="ph">
    <!-- Avatar -->
    <div class="av-wrap">
      <div class="av-ring <?= !$avatar?'no-ring':'' ?>">
        <div class="av">
          <?php if ($avatar): ?><img src="<?= htmlspecialchars($avatar) ?>" alt="">
          <?php else: echo strtoupper(substr($dname,0,1)); endif; ?>
        </div>
      </div>
    </div>

    <!-- Info -->
    <div class="ph-info">
      <!-- Name row -->
      <div class="ph-toprow">
        <span class="ph-name"><?= htmlspecialchars($dname) ?></span>
        <span class="verified"><?= ic('verified',10) ?> Creator</span>
        <?php if ($is_active): ?>
          <span class="online-pill active"><span class="online-dot"></span>Online</span>
        <?php endif; ?>
      </div>

      <!-- Handle -->
      <div class="ph-handle">@<?= htmlspecialchars($handle) ?></div>

      <!-- Stats -->
      <div class="ph-stats">
        <div class="pst"><span class="pst-v"><?= $post_count ?></span><span class="pst-l">posts</span></div>
        <div class="pst"><span class="pst-v"><?= $followers ?></span><span class="pst-l">followers</span></div>
        <div class="pst"><span class="pst-v"><?= $total_likes ?></span><span class="pst-l">likes</span></div>
      </div>

      <!-- Action buttons -->
      <div class="ph-actions">
        <?php if ($is_owner): ?>
          <a href="/dashboard/creator/posts.php" class="btn-sub" style="background:var(--gold)"><?= ic('settings',14) ?> Edit Profile</a>
          <a href="/dashboard/" class="btn-msg"><?= ic('home',14) ?> Dashboard</a>
        <?php elseif ($subscribed): ?>
          <div class="btn-sub subbed"><?= ic('check',14) ?> Subscribed</div>
          <a href="/dashboard/messages.php" class="btn-msg"><?= ic('message',14) ?> Message</a>
        <?php elseif ($uid): ?>
          <form method="POST" style="display:contents">
            <input type="hidden" name="action" value="subscribe">
            <button type="submit" class="btn-sub"><?= ic('user-plus',14) ?> Subscribe</button>
          </form>
          <button class="btn-msg locked" title="Subscribe to send a message"><?= ic('message',14) ?> Message</button>
        <?php else: ?>
          <a href="/auth/signup.php?ref=<?= urlencode($handle) ?>" class="btn-sub"><?= ic('user-plus',14) ?> Subscribe</a>
          <a href="/auth/login.php" class="btn-msg"><?= ic('message',14) ?> Message</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ══ OWNER BAR ══ -->
  <?php if ($is_owner): ?>
  <div class="owner-strip">
    <p><?= ic('star',13,'style="color:var(--gold)"') ?> You're viewing your own creator page</p>
    <div class="oacts">
      <a href="/creator.php?u=<?= urlencode($handle) ?>" class="obtn obtn-out"><?= ic('eye',12) ?> Preview</a>
      <a href="/dashboard/creator/posts.php" class="obtn obtn-rose"><?= ic('settings',12) ?> Creator Studio</a>
    </div>
  </div>
  <?php endif; ?>

  <!-- ══ MAIN LAYOUT with SIDEBAR ══ -->
  <div class="content-layout">
    <!-- Main Content Column -->
    <div class="main-content">
      
      <!-- ══ BIO ══ -->
      <?php if ($bio_raw || $snap || $tg): ?>
      <div class="bio-sec">
        <?php if ($bio_raw): ?>
          <div class="bio-text"><?= htmlspecialchars($bio_raw) ?></div>
        <?php endif; ?>

        <?php if ($snap || $tg): ?>
        <div class="contacts">
          <?php if ($snap): ?>
            <?php if ($can_see): ?>
              <a href="https://snapchat.com/add/<?= urlencode($snap) ?>" target="_blank" rel="noopener" class="cpill">
                <?= ic('snap',14) ?> <?= htmlspecialchars($snap) ?> <?= ic('external',11,'style="color:var(--muted)"') ?>
              </a>
            <?php else: ?>
              <div class="cpill locked"><?= ic('snap',14) ?> Snapchat <?= ic('lock',11) ?></div>
            <?php endif; ?>
          <?php endif; ?>
          <?php if ($tg): ?>
            <?php if ($can_see): ?>
              <a href="https://t.me/<?= urlencode($tg) ?>" target="_blank" rel="noopener" class="cpill">
                <?= ic('telegram',14) ?> <?= htmlspecialchars($tg) ?> <?= ic('external',11,'style="color:var(--muted)"') ?>
              </a>
            <?php else: ?>
              <div class="cpill locked"><?= ic('telegram',14) ?> Telegram <?= ic('lock',11) ?></div>
            <?php endif; ?>
          <?php endif; ?>
          <?php if (!$can_see && ($snap || $tg)): ?>
            <div class="cpill-lock-hint"><?= ic('lock',11) ?> Subscribe to unlock contact details</div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- ══ LOCK CARD — non-subscribers ══ -->
      <?php if (!$can_see && $uid): ?>
      <div class="lock-card">
        <div class="lc-top">
          <div class="lc-icon"><?= ic('lock',24) ?></div>
          <div class="lc-text">
            <h3>Exclusive content</h3>
            <p>Subscribe to <?= htmlspecialchars($dname) ?> and get full access to all posts, exclusive media, and direct contact details.</p>
          </div>
        </div>
        <div class="lc-bottom">
          <div class="lc-price">
            <div class="lc-price-ic"><?= ic('gem',16) ?></div>
            <div>
              <div class="lc-price-val">1,000 gems / month</div>
              <div class="lc-price-sub">You have <?= number_format($user_gems) ?> gems</div>
            </div>
          </div>
          <form method="POST" style="display:contents">
            <input type="hidden" name="action" value="subscribe">
            <button type="submit" class="btn-sub"><?= ic('check',13) ?> Subscribe now</button>
          </form>
        </div>
      </div>
      <?php elseif (!$can_see && !$uid): ?>
      <div class="lock-card">
        <div class="lc-top">
          <div class="lc-icon"><?= ic('lock',24) ?></div>
          <div class="lc-text">
            <h3>Exclusive content</h3>
            <p>Create a free account to subscribe to <?= htmlspecialchars($dname) ?> and unlock all posts and contact details.</p>
          </div>
        </div>
        <div class="lc-bottom">
          <div class="lc-price">
            <div class="lc-price-ic"><?= ic('gem',16) ?></div>
            <div>
              <div class="lc-price-val">1,000 gems per month</div>
              <div class="lc-price-sub">Join free to get started</div>
            </div>
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="/auth/login.php"  class="btn-msg"><?= ic('back',13) ?> Sign in</a>
            <a href="/auth/signup.php" class="btn-sub"><?= ic('user-plus',13) ?> Join free</a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- ══ TABS ══ -->
      <div class="tabs">
        <div class="tab on"><?= ic('grid',13) ?> Posts <span class="tab-ct"><?= $post_count ?></span></div>
      </div>

      <!-- ══ POST GRID ══ -->
      <?php if (empty($posts)): ?>
      <div class="post-grid">
        <div class="grid-empty">
          <?= ic('image',44,'style="color:var(--border);display:block;margin:0 auto 14px"') ?>
          <h3>No posts yet</h3>
          <p style="font-size:13px;color:var(--muted);margin-top:4px">This creator hasn't posted anything yet.</p>
        </div>
      </div>
      <?php else: ?>
      <div class="post-grid">
        <?php foreach ($posts as $i => $p):
          $murl    = post_url($cid, $p['media_file']);
          $is_vid  = ($p['media_type'] === 'video');
          $showblur = (int)$p['is_blurred'] && !$can_see;
          $liked   = (int)$p['user_liked'] > 0;
        ?>
        <div class="pc <?= $showblur?'blurred':'' ?>" id="p<?= (int)$p['id'] ?>" onclick="openPost(<?= $i ?>)">
          <?php if ($murl): ?>
            <?php if ($is_vid): ?>
              <video src="<?= htmlspecialchars($murl) ?>" muted preload="metadata" playsinline></video>
              <div class="vid-badge"><?= ic('video',10) ?></div>
            <?php else: ?>
              <img src="<?= htmlspecialchars($murl) ?>" alt="" loading="lazy">
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($showblur): ?>
          <div class="pc-lock">
            <?= ic('lock',18,'style="color:var(--muted)"') ?>
          </div>
          <?php else: ?>
          <div class="pc-ov">
            <div class="pc-likes">
              <?= ic('heartf',14,'style="color:#fff"') ?> <?= number_format((int)$p['like_count']) ?>
            </div>
            <?php if ($uid && $can_see): ?>
            <div class="pc-acts">
              <form method="POST" style="display:contents" onclick="event.stopPropagation()">
                <input type="hidden" name="action" value="like">
                <input type="hidden" name="post_id" value="<?= (int)$p['id'] ?>">
                <button type="submit" class="pc-act <?= $liked?'pc-act-liked':'pc-act-like' ?>">
                  <?= ic($liked?'heartf':'heart',11) ?> <?= $liked?'Liked':'Like' ?>
                </button>
              </form>
              <button class="pc-act pc-act-rep" onclick="event.stopPropagation();openPost(<?=$i?>,'report')">
                <?= ic('flag',11) ?> Report
              </button>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      
    </div><!-- .main-content -->
    
    <!-- Sidebar Column - Only Your Gems Card -->
    <div class="sidebar">
      
      <!-- Your Gems Card -->
      <?php if ($uid): ?>
      <div class="side-card">
        <div class="side-hdr"><?= ic('gem',14) ?> Your Gems</div>
        <div class="gems-short">
          <span style="font-size:13px;color:var(--muted)">Balance:</span>
          <span class="gems-val"><?= number_format($user_gems) ?></span>
        </div>
        <a href="/dashboard/buygems.php" class="gems-buy" style="width:100%;justify-content:center"><?= ic('plus',11) ?> Buy Gems</a>
      </div>
      <?php endif; ?>
      
    </div><!-- .sidebar -->
  </div><!-- .content-layout -->

</div><!-- .wrap -->

<!-- ══ POST MODAL ══ -->
<div class="pmo" id="pmo" onclick="if(event.target===this)closePost()">
  <div class="pmo-box" id="pmo-box">
    <!-- Populated by JS -->
  </div>
</div>

<!-- ══ MESSAGE MODAL ══ -->
<?php if ($can_see && $uid): ?>
<div class="msgmo" id="msgmo" onclick="if(event.target===this)closeMsgMo()">
  <div class="msgmo-box">
    <div class="msgmo-title"><?= ic('message',16) ?> Send a Message</div>
    <div class="msgmo-sub">Message <?= htmlspecialchars($dname) ?></div>
    <form action="/dashboard/messages.php" method="GET">
      <input type="hidden" name="c" value="<?= (int)$cid ?>">
      <textarea class="msgmo-ta" name="draft" placeholder="Write your message..." maxlength="500"></textarea>
      <div class="msgmo-foot">
        <button type="button" class="msgmo-cancel" onclick="closeMsgMo()">Cancel</button>
        <button type="submit" class="msgmo-send"><?= ic('send',13) ?> Go to Chat</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
var POSTS = <?= json_encode(array_map(function($p) use ($cid, $can_see) {
    $show = !(int)$p['is_blurred'] || $can_see;
    return [
        'id'    => (int)$p['id'],
        'url'   => $show ? post_url($cid, $p['media_file']) : '',
        'vid'   => $p['media_type']==='video',
        'cap'   => $p['caption'] ?? '',
        'likes' => (int)$p['like_count'],
        'liked' => (int)$p['user_liked'] > 0,
        'ts'    => $p['created_at'],
        'show'  => $show,
    ];
}, $posts), JSON_UNESCAPED_SLASHES) ?>;

var UID   = <?= $uid ?>;
var DNAME = <?= json_encode(htmlspecialchars($dname)) ?>;
var HANDL = <?= json_encode($handle) ?>;
var AVURL = <?= json_encode($avatar) ?>;
var SUBD  = <?= $can_see ? 'true' : 'false' ?>;
var CID   = <?= $cid ?>;

/* SVG snippets for JS-injected HTML */
var SVG = {
  heartf: '<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
  heart:  '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
  flag:   '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>',
  lock:   '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:rgba(255,255,255,.55)"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
  msg:    '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
  send:   '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>',
  check:  '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
  x:      '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
  uplus:  '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
};

function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
function ago(ts){
  var s=Math.floor((Date.now()-new Date(ts))/1000);
  if(s<60)return 'just now';
  if(s<3600)return Math.floor(s/60)+'m ago';
  if(s<86400)return Math.floor(s/3600)+'h ago';
  return new Date(ts).toLocaleDateString('en',{month:'short',day:'numeric'});
}

function openPost(idx, mode) {
  var p = POSTS[idx]; if (!p) return;
  var avH = AVURL ? '<img src="'+esc(AVURL)+'" alt="">' : '<span>'+esc(DNAME.charAt(0))+'</span>';

  /* Media panel */
  var mediaH = '';
  if (p.show) {
    if (p.vid) {
      mediaH = '<video src="'+esc(p.url)+'" controls muted playsinline style="width:100%;max-height:600px;display:block;background:#000"></video>';
    } else {
      mediaH = '<img src="'+esc(p.url)+'" style="width:100%;max-height:600px;object-fit:contain;display:block;background:#0c0a09" alt="" loading="lazy">';
    }
  } else {
    /* Blurred media (thumbnail effect) */
    if (p.vid) {
      mediaH = '<video src="" style="width:100%;max-height:600px;display:block;background:#111;filter:blur(20px)" muted preload="none"></video>';
    } else {
      mediaH = '<div style="width:100%;height:340px;background:linear-gradient(135deg,#1a0e16,#261220)"></div>';
    }
    /* Lock overlay */
    mediaH += '<div class="pmo-blur-ov">'
      + '<div>'+SVG.lock+'</div>'
      + '<h3>Exclusive Content</h3>'
      + '<p>Subscribe to '+esc(DNAME)+' to unlock this post and all exclusive content</p>';
    if (UID) {
      mediaH += '<form method="POST" style="display:contents"><input type="hidden" name="action" value="subscribe"><button type="submit" class="pmo-sub-btn">'+SVG.check+' Subscribe — 1,000 gems</button></form>';
    } else {
      mediaH += '<a href="/auth/signup.php" class="pmo-sub-btn">'+SVG.uplus+' Join free to subscribe</a>';
    }
    mediaH += '</div>';
  }

  /* Side panel */
  /* Header */
  var sideH = '<div class="pmo-hdr">'
    + '<div class="pmo-av">'+avH+'</div>'
    + '<div><div class="pmo-uname">'+esc(DNAME)+'</div><div class="pmo-ts">@'+esc(HANDL)+' &middot; '+ago(p.ts)+'</div></div>'
    + '<button class="pmo-close" onclick="closePost()">'+SVG.x+'</button></div>';

  /* Caption */
  sideH += '<div class="pmo-cap">';
  if (p.cap) sideH += '<p style="margin-bottom:10px">'+esc(p.cap)+'</p>';
  sideH += '</div>';

  /* Likes row */
  if (p.show) {
    sideH += '<div class="pmo-likes-row">'+SVG.heartf+' '+p.likes+' likes</div>';
  }

  /* Footer */
  if (p.show && UID && SUBD) {
    var likeBtn = p.liked
      ? '<form method="POST" style="display:contents"><input type="hidden" name="action" value="like"><input type="hidden" name="post_id" value="'+p.id+'"><button type="submit" class="pmo-btn liked">'+SVG.heartf+' Liked</button></form>'
      : '<form method="POST" style="display:contents"><input type="hidden" name="action" value="like"><input type="hidden" name="post_id" value="'+p.id+'"><button type="submit" class="pmo-btn">'+SVG.heart+' Like</button></form>';
    sideH += '<div class="pmo-foot">'
      + '<div class="pmo-acts">'
      + likeBtn
      + '<button class="pmo-btn" onclick="toggleRep('+p.id+')">'+SVG.flag+' Report</button>'
      + '</div>'
      + '<div id="rep-row-'+p.id+'" style="display:none">'
      + '<form method="POST" style="display:contents">'
      + '<div class="pmo-rep-row">'
      + '<input type="hidden" name="action" value="report">'
      + '<input type="hidden" name="post_id" value="'+p.id+'">'
      + '<select name="reason" class="pmo-rep-sel"><option>Inappropriate content</option><option>Spam</option><option>Nudity</option><option>Hate speech</option><option>Other</option></select>'
      + '<button type="submit" class="pmo-rep-go">Submit</button>'
      + '</div></form></div>'
      + '</div>';
  } else if (p.show && UID && !SUBD) {
    /* Logged in but not subscribed — allow liking visible (non-blurred) posts */
    sideH += '<div class="pmo-foot"><div class="pmo-acts">'
      + (p.liked
          ? '<form method="POST" style="display:contents"><input type="hidden" name="action" value="like"><input type="hidden" name="post_id" value="'+p.id+'"><button type="submit" class="pmo-btn liked">'+SVG.heartf+' Liked</button></form>'
          : '<form method="POST" style="display:contents"><input type="hidden" name="action" value="like"><input type="hidden" name="post_id" value="'+p.id+'"><button type="submit" class="pmo-btn">'+SVG.heart+' Like</button></form>')
      + '</div></div>';
  }

  /* Inject */
  document.getElementById('pmo-box').innerHTML =
    '<div class="pmo-media" style="position:relative">'+mediaH+'</div>'
    + '<div class="pmo-side">'+sideH+'</div>';

  document.getElementById('pmo').classList.add('open');
  document.body.style.overflow = 'hidden';

  if (mode==='report') {
    var rr = document.getElementById('rep-row-'+p.id);
    if (rr) rr.style.display = '';
  }
  if (p.show && p.vid) {
    var v = document.querySelector('#pmo video');
    if (v) { v.muted=true; v.play().catch(function(){}); }
  }
}

function closePost() {
  document.getElementById('pmo').classList.remove('open');
  document.body.style.overflow = '';
  var v = document.querySelector('#pmo video');
  if (v) v.pause();
}

function toggleRep(pid) {
  var el = document.getElementById('rep-row-'+pid);
  if (el) el.style.display = el.style.display==='none' ? '' : 'none';
}

<?php if ($can_see && $uid): ?>
function openMsgMo() { document.getElementById('msgmo').classList.add('open'); document.body.style.overflow='hidden'; }
function closeMsgMo() { document.getElementById('msgmo').classList.remove('open'); document.body.style.overflow=''; }
/* Wire Message button if it exists */
document.addEventListener('DOMContentLoaded', function() {
  var mb = document.querySelector('.btn-msg:not(.locked)');
  if (mb && mb.tagName === 'BUTTON') {
    mb.addEventListener('click', function(e){ e.preventDefault(); openMsgMo(); });
  }
});
<?php endif; ?>

document.addEventListener('keydown',function(e){
  if (e.key==='Escape') {
    closePost();
    <?php if ($can_see && $uid): ?>closeMsgMo();<?php endif; ?>
  }
});
</script>
</body>
</html>