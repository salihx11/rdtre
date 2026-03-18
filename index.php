<?php
mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("sql107.infinityfree.com","if0_40250610","5cOYv3nYvbV6cVw","if0_40250610_fanapp1",3306);
if($conn->connect_error){ $conn=null; }
define('UPL','https://marcos.xo.je/uploads/');

$creator_count=0; $user_count=0; $creators_rows=[];
if($conn){
  $r=$conn->query("SELECT COUNT(*) c FROM creators WHERE is_active=1");
  if($r){$t=$r->fetch_assoc();$creator_count=(int)$t['c'];}
  $r2=$conn->query("SELECT COUNT(*) c FROM users");
  if($r2){$t=$r2->fetch_assoc();$user_count=(int)$t['c'];}
  $r3=$conn->query("SELECT id,username,display_name,profile_image,fake_followers,fake_likes FROM creators WHERE is_active=1 ORDER BY fake_followers DESC LIMIT 8");
  if($r3){while($row=$r3->fetch_assoc()){$creators_rows[]=$row;}}
}

$ph=[
  ['🌸','Sophia Rose','sophia',12400,89000],
  ['🦋','Luna Blue','luna',8900,54000],
  ['🌙','Nova Dark','nova',21000,120000],
  ['🎀','Ruby Red','ruby',5600,33000],
  ['💫','Stella Moon','stella',14000,76000],
  ['🌺','Aria Sun','aria',9200,42000],
  ['✨','Celeste','celeste',18000,95000],
  ['🦄','Mia Belle','mia',7800,38000],
];
$mosaic_ph=[
  ['🌸','Sophia','12.4k'],['🦋','Luna','8.9k'],['🌙','Nova','21k'],
  ['🎀','Ruby','5.6k'],['💫','Stella','14k'],['🌺','Aria','9k'],
];
$emojis=['🌸','🦋','🌙','🎀','💫','🌺','✨','🦄'];
$display=count($creators_rows)>0?$creators_rows:array_map(function($p){
  return['id'=>0,'username'=>$p[2],'display_name'=>$p[1],'profile_image'=>null,'fake_followers'=>$p[3],'fake_likes'=>$p[4]];
},$ph);
$disp_count=max($creator_count,50);
$disp_users=max($user_count,1200);

function av_url($c){
  if(empty($c['profile_image'])) return '';
  $f=$c['profile_image'];
  if(strpos($f,'http')===0) return $f;
  if(strpos($f,'creators/')===0) return UPL.$f;
  return UPL.'creators/'.$c['id'].'/cover/'.$f;
}
function fmt_k($n){
  $n=(int)$n;
  if($n>=1000000) return round($n/1000000,1).'M';
  if($n>=1000) return round($n/1000,1).'k';
  return number_format($n);
}
?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Fanfan — Connect with Your Favourite Creators</title>
<meta name="description" content="Subscribe to exclusive content, chat directly with creators, send gifts and unlock their world — all in one place.">
<meta name="theme-color" content="#c9283e">
<meta property="og:title" content="Fanfan — Connect with Your Favourite Creators">
<meta property="og:description" content="Exclusive content, direct chat, gem gifts and more.">
<meta property="og:type" content="website">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600;1,700&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<script>(function(){var t=localStorage.getItem('ff-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
<style>
/* reset */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
ul{list-style:none}
button{cursor:pointer;font-family:inherit}

/* tokens light */
:root{
  --rose:#c9283e;--rose-h:#a01e30;
  --rose-l:rgba(201,40,62,.09);--rose-l2:rgba(201,40,62,.18);
  --gold:#b07d2a;--gold-l:rgba(176,125,42,.11);
  --green:#14a85c;
  --bg:#f8f5f0;--bg2:#f0ece5;--bg3:#e7e2d8;
  --card:#ffffff;
  --glass:rgba(252,250,247,.82);
  --ink:#17110a;--ink2:#3d3228;--ink3:#6b5c4e;--muted:#9b8c7d;
  --border:rgba(23,17,10,.08);--brdl:rgba(23,17,10,.04);
  --sh:0 1px 3px rgba(23,17,10,.05),0 6px 24px rgba(23,17,10,.07);
  --sh2:0 4px 8px rgba(23,17,10,.06),0 20px 60px rgba(23,17,10,.11);
  --fd:'Cormorant Garamond',Georgia,serif;
  --fb:'DM Sans',system-ui,sans-serif;
  --max:1200px;--navh:66px;--r:14px;--r2:20px;
}
/* tokens dark */
[data-theme=dark]{
  --bg:#0e0b08;--bg2:#141009;--bg3:#1c1610;
  --card:#1a1410;
  --glass:rgba(14,11,8,.88);
  --ink:#f2ece3;--ink2:#c9b9a6;--ink3:#8a7768;--muted:#5e5047;
  --border:rgba(242,236,227,.07);--brdl:rgba(242,236,227,.03);
  --sh:0 1px 3px rgba(0,0,0,.3),0 6px 24px rgba(0,0,0,.36);
  --sh2:0 4px 8px rgba(0,0,0,.28),0 20px 60px rgba(0,0,0,.5);
}

body{
  font-family:var(--fb);background:var(--bg);color:var(--ink);
  overflow-x:hidden;font-size:15px;line-height:1.7;
  transition:background .3s,color .3s;
}
.si{max-width:var(--max);margin:0 auto;padding:0 24px}
section{padding:96px 0}

/* section labels */
.stag{
  display:inline-flex;align-items:center;gap:7px;font-size:10px;font-weight:600;
  letter-spacing:2.5px;text-transform:uppercase;color:var(--rose);
  background:var(--rose-l);border:1px solid var(--rose-l2);
  padding:5px 15px;border-radius:50px;margin-bottom:16px;
}
.stitle{
  font-family:var(--fd);font-size:clamp(28px,4.5vw,52px);
  font-weight:700;color:var(--ink);line-height:1.08;margin-bottom:14px;
}
.stitle em{color:var(--rose);font-style:italic;font-weight:600}
.ssub{font-size:15px;color:var(--ink3);line-height:1.85;margin-bottom:48px;max-width:520px}

/* ═══ NAV ═══ */
#nav{
  position:fixed;top:0;left:0;right:0;height:var(--navh);z-index:300;
  display:flex;align-items:center;padding:0 24px;gap:8px;
  transition:background .3s,border-bottom .3s,box-shadow .3s;
}
#nav.scrolled{
  background:var(--glass);border-bottom:1px solid var(--border);
  box-shadow:0 8px 32px rgba(0,0,0,.07);
  backdrop-filter:blur(24px) saturate(1.5);
  -webkit-backdrop-filter:blur(24px) saturate(1.5);
}
[data-theme=dark] #nav.scrolled{background:rgba(14,11,8,.92);border-bottom-color:rgba(255,255,255,.055);box-shadow:0 8px 32px rgba(0,0,0,.45)}

.nav-logo{display:flex;align-items:center;gap:10px;flex-shrink:0;font-family:var(--fd);font-size:22px;font-weight:700;color:var(--ink)}
.nav-logo img{height:36px;width:auto;border-radius:8px;display:block}
.nav-logo-fb{display:none;align-items:center;gap:10px}
.nav-logo-icon{width:34px;height:34px;flex-shrink:0;border-radius:9px;background:linear-gradient(140deg,var(--rose),#8a0f20);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(201,40,62,.35)}
.nav-logo-icon svg{width:17px;height:17px;stroke:#fff;fill:none;stroke-width:2}

.nav-links{display:none;align-items:center;gap:0;margin:0 auto}
.nav-links a{font-size:13.5px;font-weight:500;color:var(--ink3);padding:8px 16px;border-radius:50px;transition:color .2s,background .2s}
.nav-links a:hover{color:var(--ink);background:var(--bg2)}

.nav-actions{display:none;align-items:center;gap:10px;margin-left:auto;flex-shrink:0}

.theme-tog{width:36px;height:36px;border-radius:50%;background:var(--bg2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--ink3);transition:all .2s;flex-shrink:0}
.theme-tog:hover{color:var(--rose);border-color:var(--rose-l2);background:var(--rose-l)}
.theme-tog svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2}
.i-sun{display:none}.i-moon{display:block}
[data-theme=dark] .i-sun{display:block}[data-theme=dark] .i-moon{display:none}

.btn-ghost{font-size:13.5px;font-weight:500;color:var(--ink2);padding:9px 20px;border-radius:50px;border:1px solid var(--border);transition:all .2s}
.btn-ghost:hover{color:var(--rose);border-color:var(--rose-l2);background:var(--rose-l)}
.btn-cta{font-size:13.5px;font-weight:600;color:#fff;padding:9px 22px;border-radius:50px;background:var(--rose);box-shadow:0 4px 18px rgba(201,40,62,.28);transition:all .2s}
.btn-cta:hover{background:var(--rose-h);transform:translateY(-1px);box-shadow:0 8px 24px rgba(201,40,62,.4)}

.nav-ham{display:flex;flex-direction:column;gap:5px;padding:8px;background:none;border:none;margin-left:auto}
.nav-ham span{display:block;width:22px;height:2px;background:var(--ink);border-radius:2px;transition:all .28s}
.nav-ham.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.nav-ham.open span:nth-child(2){opacity:0;transform:scaleX(0)}
.nav-ham.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}

.nav-drawer{display:none;position:fixed;top:var(--navh);left:0;right:0;background:var(--card);border-bottom:1px solid var(--border);padding:8px 16px 24px;z-index:299;flex-direction:column;gap:2px}
.nav-drawer.open{display:flex;animation:drawerIn .22s ease}
@keyframes drawerIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:none}}
.nav-drawer a{font-size:15px;font-weight:500;color:var(--ink2);padding:13px 16px;border-radius:10px;transition:all .18s}
.nav-drawer a:hover{color:var(--ink);background:var(--bg2)}
.nd-divider{height:1px;background:var(--border);margin:8px 0}
.nd-cta{display:flex;gap:10px;margin-top:6px;padding:0 4px}
.nd-cta a{flex:1;text-align:center;font-size:14px;font-weight:600;padding:13px;border-radius:11px}
.nd-cta .btn-cta{background:var(--rose);color:#fff;border-radius:11px;box-shadow:none}
.nd-theme{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-top:1px solid var(--border);margin-top:8px}
.nd-theme span{font-size:13px;color:var(--muted)}

/* ═══ HERO ═══ */
.hero{
  position:relative;overflow:hidden;
  padding:calc(var(--navh) + 80px) 0 96px;
  min-height:100svh;display:flex;align-items:center;
}
.hero::before{
  content:'';position:absolute;width:70vw;height:70vw;max-width:760px;
  background:radial-gradient(circle,rgba(201,40,62,.08),transparent 65%);
  top:-15%;right:-10%;z-index:0;pointer-events:none;animation:orb 12s ease-in-out infinite;
}
.hero::after{
  content:'';position:absolute;width:55vw;height:55vw;max-width:620px;
  background:radial-gradient(circle,rgba(176,125,42,.06),transparent 65%);
  bottom:-15%;left:-10%;z-index:0;pointer-events:none;animation:orb 16s ease-in-out infinite reverse;
}
[data-theme=dark] .hero::before{background:radial-gradient(circle,rgba(201,40,62,.17),transparent 65%)}
[data-theme=dark] .hero::after{background:radial-gradient(circle,rgba(176,125,42,.11),transparent 65%)}
@keyframes orb{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(24px,-24px) scale(1.07)}}

.hero-grid{
  position:absolute;inset:0;z-index:0;pointer-events:none;
  background-image:linear-gradient(rgba(23,17,10,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(23,17,10,.035) 1px,transparent 1px);
  background-size:50px 50px;
  -webkit-mask-image:radial-gradient(ellipse 80% 80% at 50% 40%,black 20%,transparent 100%);
  mask-image:radial-gradient(ellipse 80% 80% at 50% 40%,black 20%,transparent 100%);
}
[data-theme=dark] .hero-grid{background-image:linear-gradient(rgba(242,236,227,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(242,236,227,.035) 1px,transparent 1px)}

.hero-inner{position:relative;z-index:1;max-width:var(--max);margin:0 auto;padding:0 24px;display:grid;grid-template-columns:1fr;gap:56px;align-items:center;width:100%}

.live-pill{display:inline-flex;align-items:center;gap:9px;width:fit-content;font-size:12px;font-weight:500;color:var(--ink2);background:var(--card);border:1px solid var(--border);padding:7px 16px 7px 12px;border-radius:50px;margin-bottom:28px;box-shadow:var(--sh)}
.live-dot{width:7px;height:7px;background:var(--green);border-radius:50%;flex-shrink:0;box-shadow:0 0 0 3px rgba(20,168,92,.18);animation:ldot 2.2s ease-in-out infinite}
@keyframes ldot{0%,100%{box-shadow:0 0 0 3px rgba(20,168,92,.18)}50%{box-shadow:0 0 0 7px rgba(20,168,92,0)}}

.hero-h1{font-family:var(--fd);font-size:clamp(46px,9vw,84px);font-weight:700;color:var(--ink);line-height:1.0;letter-spacing:-.5px;margin-bottom:22px}
.hero-h1 em{color:var(--rose);font-style:italic;font-weight:600;position:relative;display:inline-block}
.hero-h1 em::after{content:'';position:absolute;bottom:-3px;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--rose),transparent);border-radius:2px;opacity:.4}
.hero-p{font-size:16px;color:var(--ink3);line-height:1.82;margin-bottom:38px;max-width:480px}

.cta-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:48px}
.btn-hero{display:inline-flex;align-items:center;gap:9px;font-size:15px;font-weight:600;color:#fff;background:var(--rose);padding:14px 32px;border-radius:50px;box-shadow:0 8px 30px rgba(201,40,62,.32);transition:all .25s cubic-bezier(.25,.8,.25,1)}
.btn-hero:hover{background:var(--rose-h);transform:translateY(-2px);box-shadow:0 14px 42px rgba(201,40,62,.42)}
.btn-hero svg{width:15px;height:15px;stroke:#fff;fill:none;stroke-width:2.5;flex-shrink:0}
.btn-outline{display:inline-flex;align-items:center;gap:9px;font-size:15px;font-weight:500;color:var(--ink2);border:1px solid var(--border);padding:13px 26px;border-radius:50px;background:var(--card);box-shadow:var(--sh);transition:all .22s}
.btn-outline:hover{color:var(--rose);border-color:var(--rose-l2);transform:translateY(-1px)}

.hero-stats{display:flex;gap:0;flex-wrap:wrap}
.hero-stats .stat{display:flex;flex-direction:column;gap:3px;padding-right:28px}
.hero-stats .stat:not(:last-child){border-right:1px solid var(--border);margin-right:28px}
.hero-stats .num{font-family:var(--fd);font-size:32px;font-weight:700;color:var(--ink);line-height:1}
.hero-stats .lbl{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1.5px;font-weight:500}

/* mosaic */
.hero-right{display:none;position:relative}
.mosaic{display:grid;grid-template-columns:repeat(3,1fr);grid-template-rows:145px 145px 145px;gap:10px}
.mc{border-radius:16px;overflow:hidden}
.mc.tall{grid-row:span 2}
.mc-inner{width:100%;height:100%;background:var(--bg3);border:1px solid var(--border);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;overflow:hidden;position:relative;transition:transform .35s cubic-bezier(.25,.8,.25,1)}
.mc-inner:hover{transform:scale(1.04)}
.mc-inner img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.mc-inner::after{content:'';position:absolute;inset:0;z-index:1;background:linear-gradient(to top,rgba(14,11,8,.65) 0%,transparent 50%);opacity:0;transition:opacity .3s}
.mc-inner:hover::after{opacity:1}
.mc-emoji{font-size:26px;position:relative;z-index:2}
.mc-name{font-size:9px;font-weight:600;color:var(--ink2);position:relative;z-index:2;text-align:center;padding:0 6px}
.mc-stat{font-size:8px;color:var(--rose);position:relative;z-index:2;font-weight:600}

.float-badge{position:absolute;bottom:-18px;left:50%;transform:translateX(-50%);background:var(--card);border:1px solid var(--border);border-radius:50px;padding:11px 20px;display:flex;align-items:center;gap:13px;white-space:nowrap;z-index:2;box-shadow:var(--sh2)}
.fb-avas{display:flex}
.fb-avas span{width:30px;height:30px;border-radius:50%;background:linear-gradient(140deg,var(--rose),#8a0f20);border:2.5px solid var(--card);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;margin-left:-8px}
.fb-avas span:first-child{margin-left:0}
.fb-text{display:flex;flex-direction:column;gap:1px}
.fb-text strong{font-size:12px;font-weight:600;color:var(--ink)}
.fb-text span{font-size:10px;color:var(--muted)}

/* ═══ MARQUEE ═══ */
.marquee{border-top:1px solid var(--border);border-bottom:1px solid var(--border);overflow:hidden;padding:15px 0;background:var(--bg2)}
.mtrack{display:flex;gap:30px;width:max-content;animation:marq 42s linear infinite}
.mtrack:hover{animation-play-state:paused}
@keyframes marq{from{transform:translateX(0)}to{transform:translateX(-33.333%)}}
.mi{display:flex;align-items:center;gap:8px;font-size:12px;font-weight:500;color:var(--ink3);letter-spacing:.4px;white-space:nowrap}
.mi svg{width:12px;height:12px;stroke:var(--rose);fill:none;stroke-width:2;flex-shrink:0}
.msep{color:var(--rose);font-size:7px;opacity:.5}

/* ═══ HOW IT WORKS ═══ */
.how-sec{background:var(--bg2);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
.steps{display:grid;grid-template-columns:1fr;gap:14px}
.step{background:var(--card);border:1px solid var(--border);border-radius:var(--r2);padding:30px 26px;position:relative;overflow:hidden;transition:border-color .3s,transform .3s,box-shadow .3s;box-shadow:var(--sh)}
.step::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--rose-l) 0%,transparent 55%);opacity:0;transition:opacity .3s;pointer-events:none}
.step:hover{border-color:var(--rose-l2);transform:translateY(-5px);box-shadow:var(--sh2)}
.step:hover::before{opacity:1}
.step-num{font-family:var(--fd);font-size:68px;font-weight:700;color:var(--brdl);position:absolute;top:8px;right:18px;line-height:1;pointer-events:none;transition:color .3s;user-select:none}
.step:hover .step-num{color:var(--rose-l)}
.step-icon{width:50px;height:50px;border-radius:13px;background:var(--rose-l);border:1px solid var(--rose-l2);display:flex;align-items:center;justify-content:center;margin-bottom:16px;position:relative;z-index:1;transition:all .28s}
.step:hover .step-icon{background:var(--rose);box-shadow:0 6px 20px rgba(201,40,62,.3)}
.step-icon svg{width:22px;height:22px;stroke:var(--rose);fill:none;stroke-width:1.8;transition:stroke .28s}
.step:hover .step-icon svg{stroke:#fff}
.step h3{font-size:16px;font-weight:600;color:var(--ink);margin-bottom:8px;position:relative;z-index:1}
.step p{font-size:13.5px;color:var(--ink3);line-height:1.72;position:relative;z-index:1}

/* ═══ CREATOR GRID ═══ */
.cgrid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
.ccard{background:var(--card);border:1px solid var(--border);border-radius:var(--r2);overflow:hidden;display:block;transition:transform .28s cubic-bezier(.25,.8,.25,1),border-color .28s,box-shadow .28s;box-shadow:var(--sh)}
.ccard:hover{transform:translateY(-6px);border-color:var(--rose-l2);box-shadow:var(--sh2)}
.ccover{height:88px;background:var(--bg3);overflow:hidden;position:relative}
.ccover img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.ccard:hover .ccover img{transform:scale(1.07)}
.ccover-bg{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:30px;background:linear-gradient(140deg,var(--rose-l),var(--gold-l))}
.cava-wrap{padding:0 14px;margin-top:-21px;position:relative;z-index:1}
.cava{width:42px;height:42px;border-radius:50%;border:3px solid var(--card);background:linear-gradient(140deg,var(--rose),#8a0f20);display:flex;align-items:center;justify-content:center;font-size:18px;overflow:hidden;object-fit:cover}
img.cava{display:block}
.cbody{padding:9px 14px 15px}
.cbody h3{font-size:13.5px;font-weight:600;color:var(--ink);margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cuname{font-size:11px;color:var(--muted);margin-bottom:9px}
.cmeta{display:flex;gap:12px;font-size:11px;color:var(--ink3);margin-bottom:12px;flex-wrap:wrap}
.cmeta .v{color:var(--ink);font-weight:700}
.sbtn{display:flex;align-items:center;justify-content:center;gap:6px;padding:9px;background:var(--rose-l);border:1px solid var(--rose-l2);border-radius:50px;font-size:12px;font-weight:600;color:var(--rose);transition:all .22s}
.sbtn svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2.5}
.ccard:hover .sbtn{background:var(--rose);color:#fff;border-color:var(--rose);box-shadow:0 4px 14px rgba(201,40,62,.28)}

/* ═══ FEATURES ═══ */
.feat-grid{display:grid;grid-template-columns:1fr;gap:12px}
.feat{background:var(--card);border:1px solid var(--border);border-radius:var(--r2);padding:24px;display:flex;align-items:flex-start;gap:16px;transition:all .28s;box-shadow:var(--sh)}
.feat:hover{border-color:var(--rose-l2);transform:translateY(-3px);box-shadow:var(--sh2)}
.feat-ic{width:46px;height:46px;border-radius:12px;flex-shrink:0;background:var(--rose-l);border:1px solid var(--rose-l2);display:flex;align-items:center;justify-content:center;transition:all .25s}
.feat:hover .feat-ic{background:var(--rose);box-shadow:0 5px 18px rgba(201,40,62,.28)}
.feat-ic svg{width:21px;height:21px;stroke:var(--rose);fill:none;stroke-width:1.8;transition:stroke .25s}
.feat:hover .feat-ic svg{stroke:#fff}
.feat-bd h3{font-size:15px;font-weight:600;color:var(--ink);margin-bottom:5px}
.feat-bd p{font-size:13px;color:var(--ink3);line-height:1.7}

/* ═══ GEM PACKAGES ═══ */
.gems-sec{background:var(--bg2);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
.gcards{display:grid;grid-template-columns:1fr;gap:18px;max-width:420px;margin:0 auto}
.gcard{background:var(--card);border:1px solid var(--border);border-radius:var(--r2);padding:28px 24px;position:relative;display:flex;flex-direction:column;transition:transform .28s,border-color .28s,box-shadow .28s;box-shadow:var(--sh)}
.gcard:hover{transform:translateY(-5px);border-color:var(--rose-l2);box-shadow:var(--sh2)}
.gcard.pop{border-color:rgba(176,125,42,.35);background:linear-gradient(175deg,rgba(176,125,42,.07) 0%,var(--card) 55%);order:-1}
.gcard.pop:hover{border-color:rgba(176,125,42,.55)}
.g-badge{position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:linear-gradient(90deg,var(--gold),#8a5f14);color:#fff;font-size:10px;font-weight:700;padding:4px 18px;border-radius:50px;white-space:nowrap;letter-spacing:.8px;text-transform:uppercase;box-shadow:0 4px 12px rgba(176,125,42,.4)}
.g-icon{width:52px;height:52px;border-radius:14px;background:var(--rose-l);border:1px solid var(--rose-l2);display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.gcard.pop .g-icon{background:var(--gold-l);border-color:rgba(176,125,42,.25)}
.g-icon svg{width:24px;height:24px;stroke:var(--rose);fill:none;stroke-width:1.8}
.gcard.pop .g-icon svg{stroke:var(--gold)}
.g-amount{font-family:var(--fd);font-size:36px;font-weight:700;color:var(--ink);line-height:1;margin-bottom:3px}
.g-amount span{font-size:14px;color:var(--muted);font-weight:400;font-family:var(--fb)}
.g-label{font-size:10px;color:var(--muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:1.5px;font-weight:600}
.g-price{font-size:22px;font-weight:700;color:var(--rose);margin-bottom:18px}
.gcard.pop .g-price{color:var(--gold)}
.g-price sub{font-size:12px;color:var(--muted);font-weight:400;font-family:var(--fb)}
.g-feats{flex:1;display:flex;flex-direction:column;gap:9px;margin-bottom:22px}
.g-feats li{font-size:13px;color:var(--ink2);display:flex;align-items:center;gap:8px}
.g-feats li svg{width:14px;height:14px;stroke:var(--rose);fill:none;stroke-width:2.5;flex-shrink:0}
.gcard.pop .g-feats li svg{stroke:var(--gold)}
.g-btn{display:flex;align-items:center;justify-content:center;gap:7px;padding:13px;background:var(--rose);color:#fff;font-size:14px;font-weight:600;border-radius:50px;transition:all .22s;box-shadow:0 4px 14px rgba(201,40,62,.24)}
.g-btn svg{width:14px;height:14px;stroke:#fff;fill:none;stroke-width:2.5}
.g-btn:hover{background:var(--rose-h);transform:translateY(-1px);box-shadow:0 8px 24px rgba(201,40,62,.38)}
.gcard.pop .g-btn{background:var(--gold);box-shadow:0 4px 14px rgba(176,125,42,.3)}
.gcard.pop .g-btn:hover{background:#8a5f14;box-shadow:0 8px 24px rgba(176,125,42,.42)}

/* ═══ GIFTS ═══ */
.gifts-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.gift-card{background:var(--card);border:1px solid var(--border);border-radius:var(--r2);padding:26px 14px 20px;text-align:center;transition:transform .28s,border-color .28s,box-shadow .28s;box-shadow:var(--sh)}
.gift-card:hover{transform:translateY(-5px);border-color:var(--rose-l2);box-shadow:var(--sh2)}
.gift-emoji{font-size:42px;margin-bottom:11px;display:block;animation:gfl 3s ease-in-out infinite;animation-delay:calc(var(--i,0)*.35s)}
@keyframes gfl{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
.gift-name{font-size:13.5px;font-weight:600;color:var(--ink);margin-bottom:9px}
.gift-price{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;color:var(--gold);background:var(--gold-l);border:1px solid rgba(176,125,42,.2);padding:4px 12px;border-radius:50px}

/* ═══ SOCIAL PROOF ═══ */
.proof-sec{background:var(--bg2);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
.proof-grid{display:grid;grid-template-columns:1fr;gap:14px}
.proof-card{background:var(--card);border:1px solid var(--border);border-radius:var(--r2);padding:26px;box-shadow:var(--sh)}
.proof-stars{display:flex;gap:2px;margin-bottom:13px;color:var(--gold);font-size:15px}
.proof-text{font-family:var(--fd);font-size:17px;font-style:italic;color:var(--ink2);line-height:1.6;margin-bottom:16px}
.proof-author{display:flex;align-items:center;gap:11px}
.proof-ava{width:36px;height:36px;border-radius:50%;flex-shrink:0;background:linear-gradient(140deg,var(--rose),#8a0f20);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff}
.proof-name{font-size:13px;font-weight:600;color:var(--ink)}
.proof-role{font-size:11px;color:var(--muted)}

/* ═══ CTA BAND ═══ */
.cta-band{padding:96px 0;text-align:center;position:relative;overflow:hidden;background:var(--bg)}
.cta-band::before{content:'';position:absolute;inset:0;z-index:0;pointer-events:none;background:radial-gradient(ellipse 65% 80% at 50% 50%,rgba(201,40,62,.07) 0%,transparent 65%)}
[data-theme=dark] .cta-band::before{background:radial-gradient(ellipse 65% 80% at 50% 50%,rgba(201,40,62,.14) 0%,transparent 65%)}
.cta-band .ci{max-width:600px;margin:0 auto;padding:0 24px;position:relative;z-index:1}
.cta-band h2{font-family:var(--fd);font-size:clamp(32px,5.5vw,56px);font-weight:700;color:var(--ink);line-height:1.06;margin-bottom:14px}
.cta-band h2 em{color:var(--rose);font-style:italic}
.cta-band p{font-size:16px;color:var(--ink3);margin-bottom:34px;line-height:1.8}
.btn-cta-xl{display:inline-flex;align-items:center;gap:9px;font-size:15px;font-weight:600;color:#fff;background:var(--rose);padding:15px 40px;border-radius:50px;box-shadow:0 8px 32px rgba(201,40,62,.32);transition:all .25s cubic-bezier(.25,.8,.25,1)}
.btn-cta-xl:hover{background:var(--rose-h);transform:translateY(-2px);box-shadow:0 16px 48px rgba(201,40,62,.44)}
.btn-cta-xl svg{width:15px;height:15px;stroke:#fff;fill:none;stroke-width:2.5}

/* ═══ FOOTER ═══ */
footer{border-top:1px solid var(--border);padding:48px 0 32px;background:var(--bg2)}
.fi{max-width:var(--max);margin:0 auto;padding:0 24px;display:flex;flex-direction:column;align-items:center;gap:22px;text-align:center}
.flogo{display:flex;align-items:center;gap:10px;font-family:var(--fd);font-size:20px;font-weight:700;color:var(--ink)}
.flogo img{height:30px;width:auto;border-radius:7px}
.flogo-fb{display:none;align-items:center;gap:10px}
.f-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(140deg,var(--rose),#8a0f20);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.f-icon svg{width:15px;height:15px;stroke:#fff;fill:none;stroke-width:2}
.flinks{display:flex;gap:20px;flex-wrap:wrap;justify-content:center}
.flinks a{font-size:13px;color:var(--ink3);transition:color .18s}
.flinks a:hover{color:var(--rose)}
.f-social{display:flex;gap:9px}
.f-social a{width:34px;height:34px;border-radius:50%;background:var(--bg3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--ink3);transition:all .2s}
.f-social a:hover{color:var(--rose);border-color:var(--rose-l2);background:var(--rose-l)}
.f-social a svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2}
.fcopy{font-size:12px;color:var(--muted)}

/* reveal */
.reveal{opacity:0;transform:translateY(26px);transition:opacity .6s cubic-bezier(.25,.8,.25,1),transform .6s cubic-bezier(.25,.8,.25,1)}
.reveal.visible{opacity:1;transform:none}

/* scrollbar */
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}

/* responsive */
@media(min-width:540px){
  .gifts-grid{grid-template-columns:repeat(3,1fr)}
  .feat-grid{grid-template-columns:repeat(2,1fr)}
  .proof-grid{grid-template-columns:repeat(2,1fr)}
}
@media(min-width:768px){
  section{padding:110px 0}
  #nav{padding:0 32px}
  .nav-links{display:flex}.nav-actions{display:flex}.nav-ham{display:none}
  .hero-inner{grid-template-columns:1fr 1fr;gap:64px}.hero-right{display:block}
  .steps{grid-template-columns:repeat(3,1fr);gap:18px}
  .cgrid{grid-template-columns:repeat(3,1fr)}
  .gcards{grid-template-columns:repeat(3,1fr);max-width:100%}.gcard.pop{order:0}
  .gifts-grid{grid-template-columns:repeat(5,1fr)}
  .proof-grid{grid-template-columns:repeat(3,1fr)}
  .feat-grid{grid-template-columns:repeat(3,1fr)}
  .fi{flex-direction:row;justify-content:space-between;text-align:left}.flinks{justify-content:flex-start}
}
@media(min-width:1024px){
  section{padding:120px 0}
  .cgrid{grid-template-columns:repeat(4,1fr);gap:18px}
  .ccover{height:96px}
  .mosaic{grid-template-rows:150px 150px 150px}
}
@media(max-width:400px){
  .hero-h1{font-size:42px}
  .cgrid{grid-template-columns:1fr}
  .cta-row{flex-direction:column;align-items:stretch}
  .btn-hero,.btn-outline{justify-content:center;text-align:center}
  .hero-stats .stat{padding-right:20px;margin-right:20px}
}
</style>
</head>
<body>

<!-- NAV -->
<nav id="nav">
  <a href="/" class="nav-logo">
    <img src="/logo.png" alt="Fanfan" style="height:36px;width:auto;border-radius:8px"
      onerror="this.style.display='none';document.getElementById('nl-fb').style.display='flex'">
    <span id="nl-fb" class="nav-logo-fb">
      <span class="nav-logo-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>
      fanfan
    </span>
  </a>
  <ul class="nav-links">
    <li><a href="#creators">Creators</a></li>
    <li><a href="#how">How it works</a></li>
    <li><a href="#gems">Gems</a></li>
    <li><a href="#gifts">Gifts</a></li>
  </ul>
  <div class="nav-actions">
    <button class="theme-tog" id="themeTog" type="button" aria-label="Toggle theme">
      <svg class="i-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      <svg class="i-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
    </button>
    <a href="/auth/login.php" class="btn-ghost">Sign in</a>
    <a href="/auth/signup.php" class="btn-cta">Join free</a>
  </div>
  <button class="nav-ham" id="navHam" aria-label="Menu"><span></span><span></span><span></span></button>
</nav>

<div class="nav-drawer" id="navDrawer">
  <a href="#creators" onclick="closeDrawer()">Creators</a>
  <a href="#how" onclick="closeDrawer()">How it works</a>
  <a href="#gems" onclick="closeDrawer()">Gems</a>
  <a href="#gifts" onclick="closeDrawer()">Gifts</a>
  <div class="nd-divider"></div>
  <div class="nd-cta">
    <a href="/auth/login.php" class="btn-ghost">Sign in</a>
    <a href="/auth/signup.php" class="btn-cta">Join free</a>
  </div>
  <div class="nd-theme">
    <span>Theme</span>
    <button class="theme-tog" id="themeTog2" type="button">
      <svg class="i-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      <svg class="i-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
    </button>
  </div>
</div>


<!-- HERO -->
<section class="hero">
  <div class="hero-grid"></div>
  <div class="hero-inner">
    <div class="hero-left">
      <div class="live-pill">
        <span class="live-dot"></span>
        <strong><?= number_format($disp_users) ?>+</strong>&nbsp;members inside right now
      </div>
      <h1 class="hero-h1">Your favourite<br>creators,<br><em>closer than ever.</em></h1>
      <p class="hero-p">Subscribe to exclusive content, chat one-on-one, send gifts and unlock their world — all in one beautiful place.</p>
      <div class="cta-row">
        <a href="/auth/signup.php" class="btn-hero">Start for free <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
        <a href="#creators" class="btn-outline">Browse creators</a>
      </div>
      <div class="hero-stats">
        <div class="stat"><div class="num"><?= $disp_count ?>+</div><div class="lbl">Creators</div></div>
        <div class="stat"><div class="num"><?= fmt_k($disp_users) ?>+</div><div class="lbl">Members</div></div>
        <div class="stat"><div class="num">💎</div><div class="lbl">Gem gifts</div></div>
      </div>
    </div>
    <div class="hero-right">
      <div class="mosaic">
        <?php foreach($mosaic_ph as $i=>$m):
          $c=isset($creators_rows[$i])?$creators_rows[$i]:null;
          $av=$c?av_url($c):'';
        ?>
        <div class="mc <?= ($i===0||$i===4)?'tall':'' ?>">
          <div class="mc-inner">
            <?php if($av): ?>
              <img src="<?= htmlspecialchars($av) ?>" alt="" loading="lazy">
            <?php else: ?>
              <span class="mc-emoji"><?= $m[0] ?></span>
              <span class="mc-name"><?= $c ? htmlspecialchars($c['display_name']?:$c['username']) : $m[1] ?></span>
              <span class="mc-stat">♥ <?= $c ? fmt_k((int)$c['fake_followers']) : $m[2] ?></span>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="float-badge">
        <div class="fb-avas"><span>S</span><span>L</span><span>N</span></div>
        <div class="fb-text"><strong>New creators weekly</strong><span>✨ Join the community</span></div>
      </div>
    </div>
  </div>
</section>


<!-- MARQUEE -->
<div class="marquee" aria-hidden="true">
  <div class="mtrack">
    <?php for($i=0;$i<3;$i++): ?>
    <span class="mi"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>Exclusive Content</span><span class="msep">◆</span>
    <span class="mi"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Direct Chat</span><span class="msep">◆</span>
    <span class="mi"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>Gem Gifts</span><span class="msep">◆</span>
    <span class="mi"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Locked Posts</span><span class="msep">◆</span>
    <span class="mi"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>Creators You Love</span><span class="msep">◆</span>
    <span class="mi"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.43 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Snap &amp; Telegram</span><span class="msep">◆</span>
    <?php endfor; ?>
  </div>
</div>


<!-- HOW IT WORKS -->
<section class="how-sec" id="how">
  <div class="si">
    <div class="stag">How it works</div>
    <h2 class="stitle">Simple. Beautiful. <em>Intimate.</em></h2>
    <p class="ssub">Three steps to get closer to the creators you love.</p>
    <div class="steps">
      <div class="step reveal">
        <div class="step-num">01</div>
        <div class="step-icon"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
        <h3>Create your account</h3>
        <p>Sign up in seconds with email, Google or Telegram. No credit card needed to explore.</p>
      </div>
      <div class="step reveal" style="transition-delay:.12s">
        <div class="step-num">02</div>
        <div class="step-icon"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
        <h3>Get your gems</h3>
        <p>Buy gem packages once, spend them across the platform — subscribe, unlock posts, send gifts.</p>
      </div>
      <div class="step reveal" style="transition-delay:.24s">
        <div class="step-num">03</div>
        <div class="step-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
        <h3>Connect &amp; enjoy</h3>
        <p>Chat privately, unlock exclusive content and send beautiful gifts to the creators you love.</p>
      </div>
    </div>
  </div>
</section>


<!-- CREATORS -->
<section id="creators">
  <div class="si">
    <div class="stag">Featured creators</div>
    <h2 class="stitle">Meet our <em>creators</em></h2>
    <p class="ssub">Browse and subscribe to exclusive content from creators you love.</p>
    <div class="cgrid">
      <?php foreach($display as $i=>$c):
        $name  = htmlspecialchars($c['display_name']?:$c['username']);
        $uname = htmlspecialchars($c['username']);
        $av    = av_url($c);
        $emoji = $emojis[$i%8];
      ?>
      <a href="/creator.php?u=<?= $uname ?>" class="ccard reveal" style="transition-delay:<?= ($i%4)*.08 ?>s">
        <div class="ccover">
          <?php if($av): ?><img src="<?= htmlspecialchars($av) ?>" alt="" loading="lazy">
          <?php else: ?><div class="ccover-bg"><?= $emoji ?></div><?php endif; ?>
        </div>
        <div class="cava-wrap">
          <?php if($av): ?><img src="<?= htmlspecialchars($av) ?>" class="cava" alt="" loading="lazy">
          <?php else: ?><div class="cava"><?= $emoji ?></div><?php endif; ?>
        </div>
        <div class="cbody">
          <h3><?= $name ?></h3>
          <div class="cuname">@<?= $uname ?></div>
          <div class="cmeta">
            <span><span class="v"><?= fmt_k((int)$c['fake_followers']) ?></span> followers</span>
            <span><span class="v"><?= fmt_k((int)$c['fake_likes']) ?></span> likes</span>
          </div>
          <div class="sbtn"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>Subscribe</div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- FEATURES -->
<section class="how-sec" id="features">
  <div class="si">
    <div class="stag">Platform features</div>
    <h2 class="stitle">Everything you <em>need</em></h2>
    <p class="ssub">Built for genuine, intimate connections between fans and creators.</p>
    <div class="feat-grid">
      <div class="feat reveal"><div class="feat-ic"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><div class="feat-bd"><h3>Direct Messaging</h3><p>Chat privately one-on-one. Send photos and build a real connection.</p></div></div>
      <div class="feat reveal" style="transition-delay:.08s"><div class="feat-ic"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div><div class="feat-bd"><h3>Exclusive Content</h3><p>Unlock blurred photos and videos exclusive to subscribers only.</p></div></div>
      <div class="feat reveal" style="transition-delay:.16s"><div class="feat-ic"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div><div class="feat-bd"><h3>Gem Gifts</h3><p>Send beautiful virtual gifts powered by gems — from lollipops to diamonds.</p></div></div>
      <div class="feat reveal" style="transition-delay:.24s"><div class="feat-ic"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.43 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div><div class="feat-bd"><h3>Snap &amp; Telegram Unlocks</h3><p>Unlock creator social contacts with gems and stay connected off-platform.</p></div></div>
      <div class="feat reveal" style="transition-delay:.32s"><div class="feat-ic"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div><div class="feat-bd"><h3>Monthly Subscriptions</h3><p>Subscribe with gems for full unlimited access to everything a creator posts.</p></div></div>
      <div class="feat reveal" style="transition-delay:.40s"><div class="feat-ic"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><div class="feat-bd"><h3>Safe &amp; Secure</h3><p>Your privacy and payments fully protected. Buy gems with total confidence.</p></div></div>
    </div>
  </div>
</section>


<!-- GEM PACKAGES -->
<section class="gems-sec" id="gems">
  <div class="si">
    <div class="stag">Gem packages</div>
    <h2 class="stitle">Power your <em>passion</em></h2>
    <p class="ssub">Buy gems once, use them across the entire platform — subscribe, chat, unlock and gift.</p>
    <div class="gcards">
      <div class="gcard reveal">
        <div class="g-icon"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
        <div class="g-amount">1,000 <span>gems</span></div>
        <div class="g-label">Starter Pack</div>
        <div class="g-price">$29.99 <sub>/month</sub></div>
        <ul class="g-feats">
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Subscribe to 1 creator</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Unlock exclusive posts</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Send gifts</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Direct chat access</li>
        </ul>
        <a href="/auth/signup.php" class="g-btn">Get Started <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
      </div>
      <div class="gcard pop reveal" style="transition-delay:.1s">
        <div class="g-badge">Most Popular</div>
        <div class="g-icon"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
        <div class="g-amount">2,500 <span>gems</span></div>
        <div class="g-label">Plus Pack</div>
        <div class="g-price">$49.99 <sub>/month</sub></div>
        <ul class="g-feats">
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Subscribe to 3 creators</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Unlock all post types</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Send premium gifts</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Priority chat access</li>
        </ul>
        <a href="/auth/signup.php" class="g-btn">Get Plus <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
      </div>
      <div class="gcard reveal" style="transition-delay:.2s">
        <div class="g-icon"><svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
        <div class="g-amount">5,000 <span>gems</span></div>
        <div class="g-label">Pro Pack</div>
        <div class="g-price">$89.99 <sub>/month</sub></div>
        <ul class="g-feats">
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Unlimited creator access</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Unlock Snap &amp; Telegram</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>VIP gifts &amp; ring sends</li>
          <li><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>VIP chat badge</li>
        </ul>
        <a href="/auth/signup.php" class="g-btn">Go Pro <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
      </div>
    </div>
  </div>
</section>


<!-- GIFTS -->
<section id="gifts">
  <div class="si">
    <div class="stag">Virtual gifts</div>
    <h2 class="stitle">Show them you <em>care</em></h2>
    <p class="ssub">Send beautiful virtual gifts to your favourite creators and make their day unforgettable.</p>
    <div class="gifts-grid">
      <div class="gift-card reveal" style="--i:0"><span class="gift-emoji">🍭</span><div class="gift-name">Lollipop</div><div class="gift-price">💎 250 gems</div></div>
      <div class="gift-card reveal" style="--i:1;transition-delay:.07s"><span class="gift-emoji">❤️</span><div class="gift-name">Heart</div><div class="gift-price">💎 500 gems</div></div>
      <div class="gift-card reveal" style="--i:2;transition-delay:.14s"><span class="gift-emoji">🌹</span><div class="gift-name">Rose</div><div class="gift-price">💎 750 gems</div></div>
      <div class="gift-card reveal" style="--i:3;transition-delay:.21s"><span class="gift-emoji">💍</span><div class="gift-name">Ring</div><div class="gift-price">💎 1,000 gems</div></div>
      <div class="gift-card reveal" style="--i:4;transition-delay:.28s"><span class="gift-emoji">💎</span><div class="gift-name">Diamond</div><div class="gift-price">💎 2,000 gems</div></div>
    </div>
  </div>
</section>


<!-- SOCIAL PROOF -->
<section class="proof-sec">
  <div class="si">
    <div class="stag">Members love it</div>
    <h2 class="stitle">Thousands already <em>inside</em></h2>
    <p class="ssub">Real people. Real connections. Here's what they say.</p>
    <div class="proof-grid">
      <div class="proof-card reveal">
        <div class="proof-stars">★★★★★</div>
        <div class="proof-text">"Finally a platform that feels personal. My favourite creator actually replied to me — it changed everything."</div>
        <div class="proof-author"><div class="proof-ava">J</div><div><div class="proof-name">James K.</div><div class="proof-role">Member since 2024</div></div></div>
      </div>
      <div class="proof-card reveal" style="transition-delay:.1s">
        <div class="proof-stars">★★★★★</div>
        <div class="proof-text">"The gem system is genius. I unlocked her Snap and we've been chatting daily. 100% worth every gem."</div>
        <div class="proof-author"><div class="proof-ava">M</div><div><div class="proof-name">Marco R.</div><div class="proof-role">Plus member</div></div></div>
      </div>
      <div class="proof-card reveal" style="transition-delay:.2s">
        <div class="proof-stars">★★★★★</div>
        <div class="proof-text">"I sent a rose gift and she posted about it. Not just content — it's a real connection."</div>
        <div class="proof-author"><div class="proof-ava">A</div><div><div class="proof-name">Alex T.</div><div class="proof-role">Pro member</div></div></div>
      </div>
    </div>
  </div>
</section>


<!-- FINAL CTA -->
<section class="cta-band">
  <div class="ci">
    <h2>Ready to meet your <em>favourites?</em></h2>
    <p>Join thousands of fans already inside. Your first subscription is just one click away.</p>
    <a href="/auth/signup.php" class="btn-cta-xl">Create your free account <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
  </div>
</section>


<!-- FOOTER -->
<footer>
  <div class="fi">
    <a href="/" class="flogo">
      <img src="/logo.png" alt="Fanfan" style="height:30px;width:auto;border-radius:7px"
        onerror="this.style.display='none';document.getElementById('fl-fb').style.display='flex'">
      <span id="fl-fb" class="flogo-fb">
        <span class="f-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>
        fanfan
      </span>
    </a>
    <div class="flinks">
      <a href="#">About</a><a href="#creators">Creators</a>
      <a href="#">Privacy</a><a href="#">Terms</a><a href="#">Support</a>
    </div>
    <div class="f-social">
      <a href="#" aria-label="X"><svg viewBox="0 0 24 24"><path d="M4 4l16 16M20 4L4 20"/></svg></a>
      <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></a>
      <a href="#" aria-label="Telegram"><svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></a>
    </div>
    <div class="fcopy">© <?= date('Y') ?> Fanfan. All rights reserved.</div>
  </div>
</footer>


<script>
function toggleTheme(){
  var n=document.documentElement.getAttribute('data-theme')==='dark'?'light':'dark';
  document.documentElement.setAttribute('data-theme',n);
  localStorage.setItem('ff-theme',n);
}
document.getElementById('themeTog').addEventListener('click',toggleTheme);
document.getElementById('themeTog2').addEventListener('click',toggleTheme);

var nav=document.getElementById('nav');
function updNav(){nav.classList.toggle('scrolled',window.scrollY>10);}
window.addEventListener('scroll',updNav,{passive:true});
updNav();

var ham=document.getElementById('navHam'),drawer=document.getElementById('navDrawer'),open=false;
ham.addEventListener('click',function(){
  open=!open;ham.classList.toggle('open',open);
  drawer.classList.toggle('open',open);
  document.body.style.overflow=open?'hidden':'';
});
function closeDrawer(){
  open=false;ham.classList.remove('open');drawer.classList.remove('open');document.body.style.overflow='';
}
document.addEventListener('click',function(e){
  if(open&&!ham.contains(e.target)&&!drawer.contains(e.target))closeDrawer();
});
window.addEventListener('resize',function(){if(window.innerWidth>=768)closeDrawer();});

var io=new IntersectionObserver(function(entries){
  entries.forEach(function(e){if(e.isIntersecting)e.target.classList.add('visible');});
},{threshold:0.08,rootMargin:'0px 0px -30px 0px'});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el);});
</script>
</body>
</html>