<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>MediQueue - Staff Login</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--ink:#101828;--muted:#667085;--line:#D9E2E7;--green:#0CA678;--green-dark:#087F5B;--blue:#228BE6;--aqua:#E8F8F5;--peach:#FDE9E2;--cream:#FFF9E8;--red:#E03131}
body{font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;color:var(--ink);background:#fff;min-height:100vh;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
button,input{font:inherit}
.brand-mark{width:38px;height:38px;border-radius:8px;background:linear-gradient(145deg,var(--green),#31C48D);position:relative;box-shadow:0 10px 20px rgba(12,166,120,.2);flex-shrink:0}
.brand-mark::before,.brand-mark::after{content:"";position:absolute;background:#fff}
.brand-mark::before{width:8px;height:24px;left:15px;top:7px;border-radius:5px}
.brand-mark::after{width:24px;height:8px;left:7px;top:15px;border-radius:5px}
.shell{min-height:100vh;display:grid;grid-template-columns:1.05fr .95fr}
.left{background:linear-gradient(90deg,rgba(255,255,255,.96),rgba(255,255,255,.8)),radial-gradient(circle at 80% 20%,rgba(34,139,230,.18),transparent 28%),radial-gradient(circle at 85% 78%,rgba(12,166,120,.2),transparent 28%),linear-gradient(135deg,#EAF6FF,#F3FBF8 46%,#FFF5EC);padding:2rem 3rem;display:flex;flex-direction:column;border-right:1px solid var(--line)}
.brand{display:flex;align-items:center;gap:12px}
.brand-name{font-size:1.85rem;font-weight:800;color:var(--green-dark);line-height:1}
.brand-tag{font-size:10px;font-weight:800;letter-spacing:.22em;text-transform:uppercase;color:var(--muted);margin-top:5px}
.hero{margin:auto 0;max-width:720px}
.pill{display:inline-flex;border:1px solid #BDEFE3;background:rgba(255,255,255,.75);border-radius:999px;padding:8px 14px;font-size:13px;font-weight:800;color:var(--green-dark);margin-bottom:1.4rem}
h1{font-size:clamp(2.6rem,5vw,5.2rem);line-height:1.02;letter-spacing:-.04em;font-weight:800;color:#0B1220;max-width:650px}
.desc{font-size:18px;line-height:1.75;color:#475467;margin-top:1.4rem;max-width:570px}
.quick{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:2rem;max-width:650px}
.quick div{border:1px solid var(--line);background:#fff;border-radius:8px;padding:16px;box-shadow:0 10px 28px rgba(16,24,40,.06)}
.quick b{display:block;font-size:1.6rem;color:var(--green-dark);line-height:1}
.quick span{display:block;font-size:12px;font-weight:700;color:var(--muted);margin-top:8px}
.foot{border-top:1px solid rgba(12,166,120,.14);padding-top:1.2rem;color:var(--muted);font-size:13px;font-weight:600}
.right{display:flex;align-items:center;justify-content:center;padding:2rem;background:#F8FAFC}
.card{width:100%;max-width:440px;background:#fff;border:1px solid var(--line);border-radius:8px;padding:2rem;box-shadow:0 18px 45px rgba(16,24,40,.12)}
.card-title{font-size:2rem;font-weight:800;letter-spacing:-.02em}
.card-sub{font-size:14px;color:var(--muted);line-height:1.6;margin-top:6px;margin-bottom:1.5rem;font-weight:600}
.alert{border:1px solid #FFD3D0;background:#FFF1F0;color:var(--red);border-radius:8px;padding:11px 13px;font-size:13px;font-weight:700;margin-bottom:1rem}
.success{border-color:#BDEFE3;background:var(--aqua);color:var(--green-dark)}
.role-label{display:block;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);margin-bottom:8px}
.role-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:1.2rem}
.role-btn{border:1px solid var(--line);background:#fff;border-radius:8px;padding:10px 8px;cursor:pointer;font-weight:800;color:#344054;transition:.16s}
.role-btn:hover,.role-btn.sel{border-color:#BDEFE3;background:var(--aqua);color:var(--green-dark)}
.divider{display:flex;align-items:center;gap:10px;color:#98A2B3;font-size:12px;font-weight:800;margin:1rem 0}
.divider::before,.divider::after{content:"";height:1px;background:#EEF2F4;flex:1}
.form-grp{margin-bottom:1rem}
.form-lbl{display:block;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:7px}
.form-inp{width:100%;border:1px solid var(--line);border-radius:8px;padding:12px 13px;font-size:14px;color:var(--ink);outline:none;transition:.18s;background:#fff}
.form-inp:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(12,166,120,.12)}
.form-inp.err{border-color:var(--red)}
.err-msg{display:block;font-size:12px;font-weight:700;color:var(--red);margin-top:5px}
.pwd-wrap{position:relative}
.pwd-wrap .form-inp{padding-right:74px}
.eye-btn{position:absolute;right:8px;top:50%;transform:translateY(-50%);border:0;background:#F2F4F7;border-radius:999px;padding:6px 10px;cursor:pointer;font-size:12px;font-weight:800;color:#475467}
.form-opts{display:flex;align-items:center;justify-content:space-between;font-size:13px;color:var(--muted);font-weight:700;margin-bottom:1.3rem}
.chk-wrap{display:flex;align-items:center;gap:8px;cursor:pointer}
.chk-wrap input{accent-color:var(--green)}
.forgot{color:var(--green-dark)}
.btn-submit{width:100%;border:0;border-radius:8px;background:var(--green);color:#fff;padding:13px;font-weight:800;cursor:pointer;transition:.18s;display:flex;justify-content:center;gap:10px}
.btn-submit:hover{background:var(--green-dark);box-shadow:0 10px 24px rgba(12,166,120,.18)}
.btn-submit:disabled{opacity:.7;cursor:not-allowed}
.spin{display:none;width:17px;height:17px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:sp .7s linear infinite}
@keyframes sp{to{transform:rotate(360deg)}}
.card-footer{font-size:12px;color:var(--muted);text-align:center;margin-top:1.2rem;line-height:1.6}
@media(max-width:900px){.shell{grid-template-columns:1fr}.left{padding:1.5rem}.hero{margin:2.5rem 0}.quick{grid-template-columns:1fr}.right{padding:1rem}.foot{display:none}}
</style>
</head>
<body>
<div class="shell">
  <section class="left">
    <a href="/" class="brand">
      <span class="brand-mark"></span>
      <span>
        <span class="brand-name">MediQueue</span>
        <span class="brand-tag">Hospital System</span>
      </span>
    </a>
    <div class="hero">
      <div class="pill">Secure access for hospital teams</div>
      <h1>One workspace for every daily hospital handoff.</h1>
      <p class="desc">Sign in to manage OPD tokens, patient records, bed occupancy, medicine inventory, and operational reports from the same clean dashboard.</p>
      <div class="quick">
        <div><b>OPD</b><span>Queue and token flow</span></div>
        <div><b>Beds</b><span>Ward availability</span></div>
        <div><b>Stock</b><span>Pharmacy alerts</span></div>
      </div>
    </div>
    <div class="foot">Protected by Laravel session authentication and CSRF validation.</div>
  </section>

  <section class="right">
    <div class="card" id="card">
      <div class="card-title">Staff Login</div>
      <div class="card-sub">Use a demo role or enter your assigned credentials.</div>

      @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
      @endif
      @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
      @endif

      <span class="role-label">Demo role</span>
      <div class="role-grid">
        <button class="role-btn sel" type="button" onclick="setRole(this,'admin@hospital.com','admin123')">Admin</button>
        <button class="role-btn" type="button" onclick="setRole(this,'doctor@hospital.com','doc123')">Doctor</button>
        <button class="role-btn" type="button" onclick="setRole(this,'staff@hospital.com','staff123')">Reception</button>
      </div>

      <div class="divider">or sign in manually</div>

      <form method="POST" action="{{ route('login.post') }}" id="form">
        @csrf
        <div class="form-grp">
          <label class="form-lbl" for="email">Email Address</label>
          <input class="form-inp {{ $errors->has('email') ? 'err' : '' }}" type="email" id="email" name="email" value="{{ old('email','admin@hospital.com') }}" placeholder="you@hospital.com" required autofocus>
          @error('email')<span class="err-msg">{{ $message }}</span>@enderror
        </div>
        <div class="form-grp">
          <label class="form-lbl" for="password">Password</label>
          <div class="pwd-wrap">
            <input class="form-inp {{ $errors->has('password') ? 'err' : '' }}" type="password" id="password" name="password" value="admin123" placeholder="Enter password" required>
            <button type="button" class="eye-btn" id="eye" onclick="toggleEye()">Show</button>
          </div>
          @error('password')<span class="err-msg">{{ $message }}</span>@enderror
        </div>
        <div class="form-opts">
          <label class="chk-wrap"><input type="checkbox" name="remember" checked> Remember me</label>
          <a href="/" class="forgot">Back home</a>
        </div>
        <button type="submit" class="btn-submit" id="sbtn">
          <span id="btxt">Sign In to Dashboard</span>
          <div class="spin" id="spin"></div>
        </button>
      </form>

      <div class="card-footer">MediQueue Hospital Management System</div>
    </div>
  </section>
</div>

<script>
function setRole(btn,email,pass){
  document.querySelectorAll('.role-btn').forEach(b=>b.classList.remove('sel'));
  btn.classList.add('sel');
  document.getElementById('email').value=email;
  document.getElementById('password').value=pass;
}
function toggleEye(){
  const p=document.getElementById('password');
  const e=document.getElementById('eye');
  p.type=p.type==='password'?'text':'password';
  e.textContent=p.type==='password'?'Show':'Hide';
}
document.getElementById('form').addEventListener('submit',function(){
  const btn=document.getElementById('sbtn');
  document.getElementById('btxt').style.display='none';
  document.getElementById('spin').style.display='block';
  btn.disabled=true;
});
</script>
</body>
</html>
