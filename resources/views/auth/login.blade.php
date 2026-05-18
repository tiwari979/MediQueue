<!-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Login — MediQueue HMS</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--c1:#051923;--c2:#003554;--teal:#00B4A0;--teal2:#00D4BD;--text:#E2EAF0;--text2:#8BA3B5;--text3:#4A6070;--border:rgba(255,255,255,0.07);--border2:rgba(255,255,255,0.13);--red:#E83B3B;}
body{font-family:'Sora',sans-serif;background:var(--c1);color:var(--text);min-height:100vh;display:flex;-webkit-font-smoothing:antialiased}
.left{width:55%;background:var(--c2);position:relative;display:flex;flex-direction:column;overflow:hidden}
.l-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.015) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.015) 1px,transparent 1px);background-size:42px 42px}
.l-glow1{position:absolute;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(0,164,155,.12) 0%,transparent 70%);top:-150px;right:-150px;pointer-events:none}
.l-glow2{position:absolute;width:350px;height:350px;border-radius:50%;background:radial-gradient(circle,rgba(5,130,202,.09) 0%,transparent 70%);bottom:-80px;left:-80px;pointer-events:none}
.l-inner{position:relative;z-index:1;padding:2.5rem;display:flex;flex-direction:column;height:100%}
.l-brand{display:flex;align-items:center;gap:12px;margin-bottom:auto}
.l-icon{width:42px;height:42px;border-radius:13px;background:linear-gradient(135deg,var(--teal),#0582CA);display:flex;align-items:center;justify-content:center;font-size:21px;box-shadow:0 5px 18px rgba(0,180,160,.3)}
.l-bname{font-size:1rem;font-weight:700;color:#fff;line-height:1.1}
.l-btag{font-size:9px;color:var(--text2);letter-spacing:.12em;text-transform:uppercase;margin-top:2px}
.l-hero{margin:auto 0}
.l-eyebrow{font-size:10.5px;color:var(--teal2);text-transform:uppercase;letter-spacing:.15em;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:9px}
.l-eyebrow::before{content:'';width:22px;height:2px;background:var(--teal2);border-radius:1px}
h1.l-h{font-family:'Crimson Pro',serif;font-size:3rem;font-weight:600;color:#fff;line-height:1.12;margin-bottom:1.1rem}
h1.l-h span{color:var(--teal2)}
.l-p{font-size:13.5px;color:var(--text2);line-height:1.75;max-width:400px;margin-bottom:2rem}
.feats{display:flex;flex-direction:column;gap:13px}
.feat{display:flex;align-items:center;gap:13px}
.feat-ic{width:36px;height:36px;border-radius:9px;background:rgba(0,180,160,.1);border:1px solid rgba(0,180,160,.2);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
.feat-tx{font-size:12.5px;color:var(--text2);line-height:1.45}
.feat-tx strong{color:var(--text);font-weight:600}
.l-foot{font-size:10.5px;color:var(--text3);margin-top:auto;padding-top:1.5rem;border-top:1px solid var(--border)}

/* RIGHT */
.right{flex:1;display:flex;align-items:center;justify-content:center;padding:3rem;background:var(--c1)}
.login-box{width:100%;max-width:410px}
.login-box h2{font-family:'Crimson Pro',serif;font-size:1.9rem;font-weight:600;color:#fff;margin-bottom:5px}
.login-box .sub{font-size:13px;color:var(--text2);margin-bottom:1.75rem;line-height:1.55}

.demo-box{background:rgba(0,180,160,.06);border:1px solid rgba(0,180,160,.2);border-radius:10px;padding:13px 15px;margin-bottom:1.5rem}
.demo-title{font-size:10.5px;font-weight:600;color:var(--teal2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px}
.demo-row{display:flex;justify-content:space-between;font-size:12px;color:var(--text2);margin-bottom:3px}
.demo-val{font-family:monospace;color:var(--text);background:rgba(255,255,255,.06);padding:1px 8px;border-radius:4px;font-size:11.5px}

.form-grp{margin-bottom:1rem}
.form-lbl{display:block;font-size:10.5px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;margin-bottom:6px}
.form-inp{width:100%;background:rgba(255,255,255,.05);border:1px solid var(--border2);border-radius:10px;padding:11px 15px;font-size:13.5px;color:var(--text);font-family:'Sora',sans-serif;outline:none;transition:all .2s}
.form-inp:focus{border-color:var(--teal);background:rgba(0,180,160,.04)}
.form-inp::placeholder{color:var(--text3)}
.form-inp.err{border-color:var(--red)}
.err-txt{font-size:11px;color:var(--red);margin-top:4px}
.pwd-wrap{position:relative}
.pwd-wrap .form-inp{padding-right:44px}
.pwd-eye{position:absolute;right:13px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:15px;opacity:.5;background:none;border:none;color:var(--text);transition:opacity .2s}
.pwd-eye:hover{opacity:1}

.form-opts{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem}
.chk{display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--text2);cursor:pointer}
.chk input{accent-color:var(--teal);width:14px;height:14px;cursor:pointer}
.forgot{font-size:12.5px;color:var(--teal2)}

.btn-login{width:100%;padding:12px;background:var(--teal);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;font-family:'Sora',sans-serif;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:9px}
.btn-login:hover{background:var(--teal2);transform:translateY(-1px);box-shadow:0 5px 18px rgba(0,180,160,.28)}
.btn-login:disabled{opacity:.7;cursor:not-allowed;transform:none}

.spin{display:none;width:16px;height:16px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:sp .7s linear infinite}
@keyframes sp{to{transform:rotate(360deg)}}
@keyframes shake{0%,100%{transform:translateX(0)}25%,75%{transform:translateX(-5px)}50%{transform:translateX(5px)}}
.shake{animation:shake .4s ease}

.l-foot-login{font-size:11.5px;color:var(--text3);text-align:center;margin-top:1.25rem}
.l-foot-login a{color:var(--teal2)}
</style>
</head>
<body>

<div class="left">
  <div class="l-grid"></div>
  <div class="l-glow1"></div><div class="l-glow2"></div>
  <div class="l-inner">
    <div class="l-brand">
      <div class="l-icon">🏥</div>
      <div><div class="l-bname">MediQueue HMS</div><div class="l-btag">Hospital Management System</div></div>
    </div>
    <div class="l-hero">
      <!-- <div class="l-eyebrow">Laravel MVC · INT221 Project</div> -->
      <!-- <h1 class="l-h">Smart Hospital<br><span>Queue & Resource</span><br>Management</h1> -->
      <!-- <p class="l-p">A comprehensive hospital management platform built on Laravel MVC — integrating OPD queue optimization, real-time bed tracking, patient admissions, and inventory control.</p> -->
      <!-- <div class="feats">
        <div class="feat"><div class="feat-ic">🔄</div><div class="feat-tx"><strong>M/M/c Queuing Model</strong> — Token generation with priority queuing for all OPD departments</div></div>
        <div class="feat"><div class="feat-ic">🛏️</div><div class="feat-tx"><strong>Real-time Bed Tracking</strong> — Ward-wise availability with instant allocation on admission</div></div>
        <div class="feat"><div class="feat-ic">💊</div><div class="feat-tx"><strong>Inventory Intelligence</strong> — Auto-alerts for low stock, expiry tracking, dispensing logs</div></div>
      </div>
    </div>
    <div class="l-foot">© 2026 MediQueue HMS &nbsp;·&nbsp; Built with Laravel MVC &nbsp;·&nbsp; Project &nbsp;·&nbsp; NIC Integration Ready</div>
  </div>
</div> -->

<!-- <div class="right">
  <div class="login-box">
    <h2>Welcome back</h2>
    <p class="sub">Sign in to access the Hospital Management Dashboard. CSRF protected, session-based auth via Laravel Middleware.</p>

    <div class="demo-box">
      <div class="demo-title">Demo Credentials</div>
      <div class="demo-row"><span>Admin</span><span class="demo-val">admin@hospital.com / admin123</span></div>
      <div class="demo-row"><span>Doctor</span><span class="demo-val">doctor@hospital.com / doc123</span></div>
      <div class="demo-row"><span>Staff</span><span class="demo-val">staff@hospital.com / staff123</span></div>
    </div>

    {{-- ─── Laravel Login Form with CSRF ─── --}}
    <form method="POST" action="{{ route('login.post') }}" id="login-form">
      @csrf

      <div class="form-grp">
        <label class="form-lbl" for="email">Email Address</label>
        <input class="form-inp {{ $errors->has('email') ? 'err' : '' }}"
               type="email" id="email" name="email"
               value="{{ old('email', 'admin@hospital.com') }}"
               placeholder="your@hospital.com" required autofocus>
        @error('email')
          <div class="err-txt">{{ $message }}</div>
        @enderror
      </div> -->

      <!-- <div class="form-grp">
        <label class="form-lbl" for="password">Password</label>
        <div class="pwd-wrap">
          <input class="form-inp {{ $errors->has('password') ? 'err' : '' }}"
                 type="password" id="password" name="password"
                 value="admin123" placeholder="Enter your password" required>
          <button type="button" class="pwd-eye" onclick="togglePwd()" id="eye">👁️</button>
        </div>
        @error('password')
          <div class="err-txt">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-opts">
        <label class="chk"><input type="checkbox" name="remember" checked> Remember me</label>
        <a class="forgot" href="#">Forgot password?</a>
      </div>

      <button type="submit" class="btn-login" id="login-btn">
        <span id="btn-txt">Sign In to Dashboard</span>
        <div class="spin" id="spin"></div>
        <span id="btn-arr">→</span>
      </button>
    </form>

    <div class="l-foot-login">
      Protected by CSRF Token &nbsp;·&nbsp; Laravel Session Auth &nbsp;·&nbsp;
      <a href="#">Privacy Policy</a>
    </div>
  </div>
</div>

<script>
function togglePwd(){
  const p=document.getElementById('password'), e=document.getElementById('eye');
  p.type=p.type==='password'?'text':'password';
  e.textContent=p.type==='password'?'👁️':'🙈';
}
document.getElementById('login-form').addEventListener('submit',function(){
  const btn=document.getElementById('login-btn');
  document.getElementById('btn-txt').style.display='none';
  document.getElementById('btn-arr').style.display='none';
  document.getElementById('spin').style.display='block';
  btn.disabled=true;
});
</script>
</body>
</html> -->


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MediQueue HMS — Sign In</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}

:root{
  --ink:#0B1120;
  --ink2:#1A2640;
  --teal:#00C9A7;
  --teal2:#00E8C3;
  --gold:#C9A84C;
  --gold2:#E8C96A;
  --text:#E8EDF5;
  --text2:#8A9BB5;
  --text3:#4A5A72;
  --border:rgba(255,255,255,0.07);
  --border2:rgba(255,255,255,0.14);
  --glass:rgba(11,17,32,0.75);
}

html,body{height:100%;overflow:hidden}
body{
  font-family:'DM Sans',sans-serif;
  background:var(--ink);
  color:var(--text);
  -webkit-font-smoothing:antialiased;
}

/* ── ANIMATED BACKGROUND ───────────────────────────────── */
.bg{
  position:fixed;inset:0;z-index:0;
  background:radial-gradient(ellipse 80% 60% at 20% 50%, #0D2137 0%, transparent 60%),
             radial-gradient(ellipse 60% 80% at 80% 20%, #071828 0%, transparent 60%),
             linear-gradient(135deg, #060E1A 0%, #0B1828 40%, #061220 100%);
}

/* Floating orbs */
.orb{position:absolute;border-radius:50%;filter:blur(80px);animation:drift 12s ease-in-out infinite}
.orb1{width:500px;height:500px;background:radial-gradient(circle,rgba(0,201,167,0.12),transparent 70%);top:-100px;left:-100px;animation-delay:0s}
.orb2{width:400px;height:400px;background:radial-gradient(circle,rgba(201,168,76,0.08),transparent 70%);top:30%;right:-80px;animation-delay:-4s}
.orb3{width:350px;height:350px;background:radial-gradient(circle,rgba(0,100,180,0.1),transparent 70%);bottom:-80px;left:30%;animation-delay:-8s}
@keyframes drift{
  0%,100%{transform:translate(0,0) scale(1)}
  33%{transform:translate(30px,-20px) scale(1.05)}
  66%{transform:translate(-20px,30px) scale(0.95)}
}

/* Grid overlay */
.grid-overlay{
  position:absolute;inset:0;
  background-image:
    linear-gradient(rgba(0,201,167,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0,201,167,0.03) 1px, transparent 1px);
  background-size:60px 60px;
  mask-image:radial-gradient(ellipse 70% 70% at 50% 50%, black 30%, transparent 100%);
}

/* Pulse rings */
.rings{position:absolute;left:18%;top:50%;transform:translate(-50%,-50%)}
.ring{
  position:absolute;border-radius:50%;border:1px solid rgba(0,201,167,0.08);
  top:50%;left:50%;transform:translate(-50%,-50%);
  animation:expand 6s ease-out infinite;
}
.ring:nth-child(1){width:200px;height:200px;animation-delay:0s}
.ring:nth-child(2){width:350px;height:350px;animation-delay:1.5s}
.ring:nth-child(3){width:500px;height:500px;animation-delay:3s}
.ring:nth-child(4){width:650px;height:650px;animation-delay:4.5s}
@keyframes expand{
  0%{opacity:0;transform:translate(-50%,-50%) scale(0.8)}
  20%{opacity:1}
  100%{opacity:0;transform:translate(-50%,-50%) scale(1.2)}
}

/* Floating medical crosses */
.crosses{position:absolute;inset:0;overflow:hidden;pointer-events:none}
.cross{
  position:absolute;color:rgba(0,201,167,0.06);
  font-size:20px;animation:floatUp linear infinite;
}
@keyframes floatUp{
  0%{transform:translateY(100vh) rotate(0deg);opacity:0}
  10%{opacity:1}
  90%{opacity:0.5}
  100%{transform:translateY(-100px) rotate(180deg);opacity:0}
}

/* ── LAYOUT ─────────────────────────────────────────────── */
.shell{
  position:relative;z-index:1;
  display:flex;height:100vh;
}

/* ── LEFT PANEL ─────────────────────────────────────────── */
.left{
  width:55%;
  display:flex;flex-direction:column;
  padding:3rem;
  position:relative;
}

.brand{display:flex;align-items:center;gap:14px;margin-bottom:auto}
.brand-mark{
  width:46px;height:46px;border-radius:14px;
  background:linear-gradient(135deg,var(--teal),#007A65);
  display:flex;align-items:center;justify-content:center;
  font-size:22px;
  box-shadow:0 0 30px rgba(0,201,167,0.35),0 4px 16px rgba(0,0,0,0.4);
  position:relative;
  animation:pulse-glow 3s ease-in-out infinite;
}
@keyframes pulse-glow{
  0%,100%{box-shadow:0 0 20px rgba(0,201,167,0.3),0 4px 16px rgba(0,0,0,0.4)}
  50%{box-shadow:0 0 40px rgba(0,201,167,0.5),0 4px 16px rgba(0,0,0,0.4)}
}
.brand-text{line-height:1}
.brand-name{font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;color:#fff;letter-spacing:.02em}
.brand-tag{font-size:9px;color:var(--text2);letter-spacing:.16em;text-transform:uppercase;margin-top:3px}

/* Hero text */
.hero{margin:auto 0;padding-bottom:2rem}
.hero-eyebrow{
  display:inline-flex;align-items:center;gap:8px;
  font-size:10.5px;color:var(--teal);text-transform:uppercase;letter-spacing:.18em;font-weight:500;
  margin-bottom:1.5rem;
  padding:5px 14px;
  border:1px solid rgba(0,201,167,0.2);
  border-radius:20px;
  background:rgba(0,201,167,0.05);
}
.hero-eyebrow::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--teal);animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}

.hero-title{
  font-family:'Cormorant Garamond',serif;
  font-size:4.2rem;font-weight:300;
  line-height:1.08;
  color:#fff;
  margin-bottom:1.5rem;
  letter-spacing:-.01em;
}
.hero-title em{
  font-style:italic;
  color:var(--teal);
  font-weight:300;
}
.hero-title .gold{color:var(--gold)}

.hero-desc{
  font-size:14px;color:var(--text2);
  line-height:1.8;max-width:420px;
  margin-bottom:2.5rem;
  font-weight:300;
}

/* Stats row */
.stats{display:flex;gap:0;margin-bottom:2.5rem}
.stat{
  padding:0 2rem 0 0;
  border-right:1px solid var(--border);
  margin-right:2rem;
}
.stat:last-child{border-right:none}
.stat-num{
  font-family:'Cormorant Garamond',serif;
  font-size:2rem;font-weight:600;
  color:var(--teal);line-height:1;
}
.stat-lbl{font-size:11px;color:var(--text3);margin-top:3px;letter-spacing:.05em}

/* Feature pills */
.features{display:flex;flex-wrap:wrap;gap:8px}
.feat{
  display:flex;align-items:center;gap:7px;
  padding:7px 13px;
  border:1px solid var(--border);
  border-radius:20px;
  font-size:12px;color:var(--text2);
  background:rgba(255,255,255,0.02);
  transition:all .2s;
}
.feat:hover{border-color:rgba(0,201,167,0.3);color:var(--text);background:rgba(0,201,167,0.05)}
.feat-dot{width:5px;height:5px;border-radius:50%;background:var(--teal);flex-shrink:0}

/* Footer */
.left-footer{
  font-size:11px;color:var(--text3);
  padding-top:1.5rem;
  border-top:1px solid var(--border);
  display:flex;gap:2rem;
}

/* ── RIGHT PANEL ────────────────────────────────────────── */
.right{
  flex:1;
  display:flex;align-items:center;justify-content:center;
  padding:2.5rem;
  position:relative;
}

/* Glass card */
.glass-card{
  width:100%;max-width:420px;
  background:rgba(13,22,38,0.85);
  backdrop-filter:blur(24px);
  border:1px solid rgba(255,255,255,0.1);
  border-radius:24px;
  padding:2.5rem;
  box-shadow:
    0 0 0 1px rgba(0,201,167,0.08),
    0 30px 80px rgba(0,0,0,0.5),
    0 0 100px rgba(0,201,167,0.04) inset;
  animation:card-in 0.6s cubic-bezier(0.16,1,0.3,1) both;
}
@keyframes card-in{
  from{opacity:0;transform:translateY(24px) scale(0.98)}
  to{opacity:1;transform:translateY(0) scale(1)}
}

/* Decorative top bar */
.card-topbar{
  height:2px;
  background:linear-gradient(90deg,transparent,var(--teal),var(--gold),transparent);
  margin:-2.5rem -2.5rem 2.5rem;
  border-radius:24px 24px 0 0;
  opacity:.6;
}

.card-title{
  font-family:'Cormorant Garamond',serif;
  font-size:2rem;font-weight:600;
  color:#fff;margin-bottom:6px;
  letter-spacing:-.01em;
}
.card-sub{font-size:13px;color:var(--text2);margin-bottom:2rem;line-height:1.5;font-weight:300}

/* Form */
.form-grp{margin-bottom:1.1rem;position:relative}
.form-lbl{
  display:block;font-size:10.5px;font-weight:500;
  color:var(--text2);text-transform:uppercase;
  letter-spacing:.1em;margin-bottom:7px;
}
.form-inp{
  width:100%;
  background:rgba(255,255,255,0.04);
  border:1px solid rgba(255,255,255,0.1);
  border-radius:12px;
  padding:12px 16px;
  font-size:14px;color:var(--text);
  font-family:'DM Sans',sans-serif;
  outline:none;
  transition:all .2s;
}
.form-inp:focus{
  border-color:var(--teal);
  background:rgba(0,201,167,0.05);
  box-shadow:0 0 0 3px rgba(0,201,167,0.08);
}
.form-inp::placeholder{color:var(--text3)}
.form-inp.err{border-color:#E83B3B;background:rgba(232,59,59,0.04)}
select.form-inp option{background:#0D1626}

.err-msg{font-size:11px;color:#F87171;margin-top:5px;display:block}

.pwd-wrap{position:relative}
.pwd-wrap .form-inp{padding-right:46px}
.eye-btn{
  position:absolute;right:14px;top:50%;transform:translateY(-50%);
  background:none;border:none;cursor:pointer;
  color:var(--text3);font-size:16px;padding:4px;
  transition:color .18s;
}
.eye-btn:hover{color:var(--text2)}

.form-opts{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:1.5rem;font-size:13px;
}
.chk-wrap{display:flex;align-items:center;gap:8px;color:var(--text2);cursor:pointer}
.chk-wrap input{accent-color:var(--teal);width:15px;height:15px;cursor:pointer}
.forgot{color:var(--teal);text-decoration:none;font-size:13px;transition:opacity .18s}
.forgot:hover{opacity:.7}

/* Submit button */
.btn-submit{
  width:100%;padding:14px;
  background:linear-gradient(135deg,var(--teal),#00A088);
  color:#fff;border:none;border-radius:12px;
  font-size:14px;font-weight:500;
  cursor:pointer;
  font-family:'DM Sans',sans-serif;
  transition:all .22s;
  display:flex;align-items:center;justify-content:center;gap:10px;
  position:relative;overflow:hidden;
  letter-spacing:.02em;
}
.btn-submit::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,0.12),transparent);
  opacity:0;transition:opacity .2s;
}
.btn-submit:hover{transform:translateY(-1px);box-shadow:0 8px 30px rgba(0,201,167,0.35)}
.btn-submit:hover::before{opacity:1}
.btn-submit:active{transform:translateY(0)}
.btn-submit:disabled{opacity:.7;cursor:not-allowed;transform:none}

.spin{
  display:none;width:18px;height:18px;
  border:2px solid rgba(255,255,255,.3);
  border-top-color:#fff;border-radius:50%;
  animation:sp .7s linear infinite;flex-shrink:0;
}
@keyframes sp{to{transform:rotate(360deg)}}

/* Role selector */
.role-label{
  font-size:10.5px;font-weight:500;color:var(--text2);
  text-transform:uppercase;letter-spacing:.1em;
  margin-bottom:8px;display:block;
}
.role-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:7px;margin-bottom:1.25rem}
.role-btn{
  padding:9px 6px;border-radius:10px;
  border:1px solid rgba(255,255,255,0.08);
  background:rgba(255,255,255,0.02);
  cursor:pointer;text-align:center;
  font-family:'DM Sans',sans-serif;
  transition:all .18s;
}
.role-btn:hover{border-color:rgba(0,201,167,0.3);background:rgba(0,201,167,0.05)}
.role-btn.sel{border-color:var(--teal);background:rgba(0,201,167,0.1)}
.role-ico{font-size:19px;display:block;margin-bottom:4px}
.role-nm{font-size:11px;color:var(--text2);font-weight:500}
.role-btn.sel .role-nm{color:var(--teal)}

.divider{
  display:flex;align-items:center;gap:10px;
  font-size:11px;color:var(--text3);
  margin:1.1rem 0;
}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border)}

.card-footer{
  font-size:11px;color:var(--text3);
  text-align:center;margin-top:1.25rem;
  line-height:1.6;
}
.card-footer a{color:var(--teal);text-decoration:none}

/* Alert for errors */
.alert-error{
  background:rgba(232,59,59,0.08);
  border:1px solid rgba(232,59,59,0.25);
  border-radius:10px;padding:10px 14px;
  font-size:12.5px;color:#F87171;
  margin-bottom:1.1rem;
  display:flex;align-items:center;gap:8px;
  animation:card-in .3s ease both;
}

/* Shake */
@keyframes shake{
  0%,100%{transform:translateX(0)}
  20%,60%{transform:translateX(-5px)}
  40%,80%{transform:translateX(5px)}
}
.shake{animation:shake .4s ease}
</style>
</head>
<body>

<!-- Animated background -->
<div class="bg">
  <div class="grid-overlay"></div>
  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>
  <div class="rings">
    <div class="ring"></div>
    <div class="ring"></div>
    <div class="ring"></div>
    <div class="ring"></div>
  </div>
  <div class="crosses" id="crosses"></div>
</div>

<div class="shell">

  <!-- ── LEFT PANEL ─────────────────────────────────────── -->
  <div class="left">
    <div class="brand">
      <div class="brand-mark">🏥</div>
      <div class="brand-text">
        <div class="brand-name">MediQueue HMS</div>
        <!-- <div class="brand-tag">Hospital Management System</div> -->
      </div>
    </div>

    <div class="hero">
      <div class="hero-eyebrow">Hospital Management System</div>

      <h1 class="hero-title">
        Smarter<br>
        <em>Hospitals,</em><br>
        Better <span class="gold">Care.</span>
      </h1>

      <p class="hero-desc">
        A comprehensive platform for managing OPD queues, bed availability, patient admissions, and inventory — built on Laravel MVC for Government and Private hospitals.
      </p>

      <div class="stats">
        <div class="stat">
          <div class="stat-num">4</div>
          <div class="stat-lbl">Core Modules</div>
        </div>
        <div class="stat">
          <div class="stat-num">M/M/c</div>
          <div class="stat-lbl">Queue Model</div>
        </div>
        <div class="stat">
          <div class="stat-num">NIC</div>
          <div class="stat-lbl">Ready</div>
        </div>
      </div>

      <div class="features">
        <div class="feat"><div class="feat-dot"></div>OPD Queue Management</div>
        <div class="feat"><div class="feat-dot"></div>Real-time Bed Tracking</div>
        <div class="feat"><div class="feat-dot"></div>Patient Admission</div>
        <div class="feat"><div class="feat-dot"></div>Inventory Control</div>
        <div class="feat"><div class="feat-dot"></div>Analytics & Reports</div>
      </div>
    </div>

    <div class="left-footer">
      <span>© 2026 MediQueue HMS</span>
      <span>Built with Laravel MVC</span>
      <span>City-wide Integration Ready</span>
    </div>
  </div>

  <!-- ── RIGHT PANEL ────────────────────────────────────── -->
  <div class="right">
    <div class="glass-card" id="card">
      <div class="card-topbar"></div>

      <div class="card-title">Sign in</div>
      <div class="card-sub">Access your hospital management dashboard securely.</div>

      {{-- Error from Laravel --}}
      @if($errors->any())
        <div class="alert-error">⚠ {{ $errors->first() }}</div>
      @endif
      @if(session('success'))
        <div style="background:rgba(0,201,167,0.08);border:1px solid rgba(0,201,167,0.25);border-radius:10px;padding:10px 14px;font-size:12.5px;color:var(--teal);margin-bottom:1.1rem">
          ✓ {{ session('success') }}
        </div>
      @endif

      {{-- Role quick-select --}}
      <span class="role-label">Select your role</span>
      <div class="role-grid">
        <button class="role-btn sel" type="button" onclick="setRole(this,'admin@hospital.com','admin123')">
          <span class="role-ico">👨‍💼</span><span class="role-nm">Admin</span>
        </button>
        <button class="role-btn" type="button" onclick="setRole(this,'doctor@hospital.com','doc123')">
          <span class="role-ico">👨‍⚕️</span><span class="role-nm">Doctor</span>
        </button>
        <button class="role-btn" type="button" onclick="setRole(this,'staff@hospital.com','staff123')">
          <span class="role-ico">💁‍♀️</span><span class="role-nm">Reception</span>
        </button>
      </div>

      <div class="divider">or enter credentials manually</div>

      {{-- Laravel login form with CSRF --}}
      <form method="POST" action="{{ route('login.post') }}" id="form">
        @csrf

        <div class="form-grp">
          <label class="form-lbl" for="email">Email Address</label>
          <input class="form-inp {{ $errors->has('email') ? 'err' : '' }}"
                 type="email" id="email" name="email"
                 value="{{ old('email','admin@hospital.com') }}"
                 placeholder="you@hospital.com" required autofocus>
          @error('email')<span class="err-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-grp">
          <label class="form-lbl" for="password">Password</label>
          <div class="pwd-wrap">
            <input class="form-inp {{ $errors->has('password') ? 'err' : '' }}"
                   type="password" id="password" name="password"
                   value="admin123"
                   placeholder="••••••••" required>
            <button type="button" class="eye-btn" id="eye" onclick="toggleEye()">👁</button>
          </div>
          @error('password')<span class="err-msg">{{ $message }}</span>@enderror
        </div>

        <div class="form-opts">
          <label class="chk-wrap">
            <input type="checkbox" name="remember" checked> Remember me
          </label>
          <a href="#" class="forgot">Forgot password?</a>
        </div>

        <button type="submit" class="btn-submit" id="sbtn">
          <span id="btxt">Sign In to Dashboard</span>
          <div class="spin" id="spin"></div>
          <span id="barr" style="font-size:16px">→</span>
        </button>
      </form>

      <div class="card-footer">
        Protected by CSRF Token &nbsp;·&nbsp; Laravel Session Auth &nbsp;·&nbsp;
        <a href="#">Privacy Policy</a>
      </div>
    </div>
  </div>

</div>

<script>
// Floating crosses in background
(function(){
  const c = document.getElementById('crosses');
  const symbols = ['✚','⚕','✙','⊕'];
  for(let i=0;i<18;i++){
    const el = document.createElement('div');
    el.className = 'cross';
    el.textContent = symbols[Math.floor(Math.random()*symbols.length)];
    el.style.cssText = `
      left:${Math.random()*100}%;
      animation-duration:${12+Math.random()*18}s;
      animation-delay:${-Math.random()*20}s;
      font-size:${14+Math.random()*16}px;
      opacity:${0.03+Math.random()*0.05};
    `;
    c.appendChild(el);
  }
})();

// Role selector — fills email/password fields automatically
function setRole(btn, email, pass){
  document.querySelectorAll('.role-btn').forEach(b=>b.classList.remove('sel'));
  btn.classList.add('sel');
  document.getElementById('email').value    = email;
  document.getElementById('password').value = pass;
}

// Password toggle
function toggleEye(){
  const p = document.getElementById('password');
  const e = document.getElementById('eye');
  p.type = p.type==='password' ? 'text' : 'password';
  e.textContent = p.type==='password' ? '👁' : '🙈';
}

// Loading state on submit
document.getElementById('form').addEventListener('submit', function(){
  const btn = document.getElementById('sbtn');
  document.getElementById('btxt').style.display='none';
  document.getElementById('barr').style.display='none';
  document.getElementById('spin').style.display='block';
  btn.disabled = true;
});

// Keyboard shortcuts
document.getElementById('email').addEventListener('keydown',e=>{if(e.key==='Enter')document.getElementById('password').focus()});
document.getElementById('password').addEventListener('keydown',e=>{if(e.key==='Enter')document.getElementById('form').submit()});
</script>
</body>
</html>