<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') - MediQueue HMS</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --ink:#101828;--ink2:#344054;--text:#101828;--text2:#667085;--text3:#98A2B3;
  --line:#D9E2E7;--line2:#E8EEF2;--surface:#FFFFFF;--surface2:#F8FAFC;
  --green:#0CA678;--green-dark:#087F5B;--green-soft:#E8F8F5;
  --blue:#228BE6;--blue-soft:#EAF6FF;--amber:#F59F00;--amber-soft:#FFF4D6;
  --red:#E03131;--red-soft:#FFF1F0;--violet:#7950F2;--violet-soft:#F1EDFF;
  --peach:#FDE9E2;--cream:#FFF9E8;--shadow:0 16px 35px rgba(16,24,40,.08);
  --teal:var(--green);--teal2:var(--green-dark);--amber-bg:var(--amber-soft);
  --red-bg:var(--red-soft);--green-bg:var(--green-soft);--border:var(--line);
  --radius:8px;--radius-lg:8px;
}
html{scroll-behavior:smooth}
body{font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;background:#F6FAF8;color:var(--ink);min-height:100vh;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
button,input,select,textarea{font:inherit}
::-webkit-scrollbar{width:8px;height:8px}
::-webkit-scrollbar-track{background:#F2F4F7}
::-webkit-scrollbar-thumb{background:#C7D7D1;border-radius:999px}

.shell{display:flex;min-height:100vh}
.sidebar{width:282px;flex-shrink:0;background:#fff;border-right:1px solid var(--line);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:50}
.main{margin-left:282px;flex:1;display:flex;flex-direction:column;min-height:100vh}
.topbar{height:68px;background:rgba(255,255,255,.94);backdrop-filter:blur(16px);border-bottom:1px solid var(--line);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40}
.page-body{padding:2rem;flex:1}

.brand-mark{width:36px;height:36px;border-radius:8px;background:linear-gradient(145deg,var(--green),#31C48D);position:relative;box-shadow:0 10px 20px rgba(12,166,120,.18);flex-shrink:0}
.brand-mark::before,.brand-mark::after{content:"";position:absolute;background:#fff}
.brand-mark::before{width:8px;height:23px;left:14px;top:6px;border-radius:5px}
.brand-mark::after{width:23px;height:8px;left:6px;top:14px;border-radius:5px}

.sb-top{padding:1.25rem 1.2rem;border-bottom:1px solid var(--line2)}
.sb-brand{display:flex;align-items:center;gap:12px}
.sb-name{font-size:1.25rem;font-weight:800;color:var(--green-dark);line-height:1}
.sb-tag{font-size:10px;color:var(--text2);letter-spacing:.18em;text-transform:uppercase;margin-top:5px;font-weight:700}
.sb-sec{padding:1.1rem 1rem .4rem;font-size:10px;color:var(--text3);letter-spacing:.16em;text-transform:uppercase;font-weight:800}
.sb-link{display:flex;align-items:center;gap:11px;padding:10px 12px;margin:2px 9px;border-radius:999px;color:var(--ink2);font-size:13px;font-weight:700;cursor:pointer;border:1px solid transparent;background:none;width:calc(100% - 18px);text-align:left;transition:all .16s}
.sb-link:hover{background:var(--green-soft);color:var(--green-dark)}
.sb-link.active{background:var(--green);border-color:var(--green);color:#fff;box-shadow:0 10px 24px rgba(12,166,120,.18)}
.sb-link .ico{width:22px;height:22px;border-radius:999px;background:#F2F4F7;display:flex;align-items:center;justify-content:center;color:var(--green-dark);font-size:0;flex-shrink:0}
.sb-link.active .ico{background:rgba(255,255,255,.24);color:#fff}
.sb-link .ico::before{font-size:12px;font-weight:900}
.sb-link[href*="dashboard"] .ico::before{content:"D"}
.sb-link[href*="opd"] .ico::before{content:"Q"}
.sb-link[href*="beds"] .ico::before{content:"B"}
.sb-link[href*="patients"] .ico::before{content:"P"}
.sb-link[href*="inventory"] .ico::before{content:"I"}
.sb-link[href*="reports"] .ico::before{content:"R"}
.sb-badge{margin-left:auto;font-size:10px;padding:2px 7px;border-radius:999px;font-weight:800}
.badge-r{background:var(--red-soft);color:var(--red)}
.badge-g{background:var(--green-soft);color:var(--green-dark)}
.sb-bottom{margin-top:auto;padding:1rem 1.15rem;border-top:1px solid var(--line2);background:#FBFDFB}
.sb-user{display:flex;align-items:center;gap:10px}
.sb-av{width:38px;height:38px;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;flex-shrink:0}
.sb-uname{font-size:13px;font-weight:800;color:var(--ink);line-height:1.2}
.sb-urole{font-size:11px;color:var(--text2);font-weight:700;margin-top:2px}

.tb-title{font-size:1.25rem;font-weight:800;color:var(--ink)}
.tb-right{display:flex;align-items:center;gap:12px}
.chip-live{display:flex;align-items:center;gap:7px;padding:7px 13px;border-radius:999px;font-size:12px;font-weight:800;background:var(--green-soft);border:1px solid #BDEFE3;color:var(--green-dark)}
.live-dot{width:7px;height:7px;border-radius:50%;background:var(--green);animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}
.tb-clock{font-size:13px;color:var(--text2);font-weight:700}

.flash{padding:13px 1.2rem;font-size:13px;margin-bottom:1.25rem;border-radius:var(--radius);display:flex;align-items:center;gap:10px;border:1px solid;font-weight:700;background:#fff}
.flash-success{border-color:#BDEFE3;color:var(--green-dark)}
.flash-error{border-color:#FFD3D0;color:var(--red)}

.sh{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;gap:16px}
.sh-eye{font-size:11px;color:var(--green-dark);text-transform:uppercase;letter-spacing:.16em;font-weight:800;margin-bottom:4px}
.sh-title{font-size:2rem;font-weight:800;color:var(--ink);line-height:1.15}
.sh-desc{font-size:14px;color:var(--text2);margin-top:6px;font-weight:600}

.stats-row{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:1.75rem}
.stat-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-lg);padding:1.35rem;position:relative;overflow:hidden;transition:transform .18s,box-shadow .18s,border-color .18s;box-shadow:0 1px 2px rgba(16,24,40,.03)}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow);border-color:#BDEFE3}
.stat-card::after{content:"";position:absolute;top:0;left:0;right:0;height:4px}
.sc-teal::after{background:var(--green)}.sc-blue::after{background:var(--blue)}.sc-amber::after{background:var(--amber)}.sc-red::after{background:var(--red)}
.sc-ico{display:none}
.sc-lbl{font-size:11px;color:var(--text2);text-transform:uppercase;letter-spacing:.1em;font-weight:800;margin-bottom:8px}
.sc-val{font-size:2.35rem;font-weight:800;line-height:1;color:var(--ink)}
.sc-teal .sc-val{color:var(--green-dark)}.sc-blue .sc-val{color:#1864AB}.sc-amber .sc-val{color:#C77700}.sc-red .sc-val{color:var(--red)}
.sc-sub{font-size:12px;color:var(--text2);margin-top:8px;font-weight:600}

.card{background:#fff;border:1px solid var(--line);border-radius:var(--radius-lg);overflow:hidden;margin-bottom:1.5rem;box-shadow:0 1px 2px rgba(16,24,40,.03)}
.card-head{padding:1rem 1.35rem;border-bottom:1px solid var(--line2);display:flex;align-items:center;justify-content:space-between;gap:12px;background:#FBFDFB}
.card-title{font-size:14px;font-weight:800;color:var(--ink)}
.card-body{padding:1.35rem}

.tbl{width:100%;border-collapse:collapse;background:#fff}
.tbl th{padding:11px 1.35rem;text-align:left;font-size:11px;font-weight:800;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;border-bottom:1px solid var(--line2);background:#F8FAFC;white-space:nowrap}
.tbl td{padding:13px 1.35rem;font-size:13px;color:var(--ink2);border-bottom:1px solid #EEF2F4;vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:#FBFDFB}
.td-b{font-weight:800;color:var(--ink)}
.td-m{color:var(--text2);font-size:12px;font-weight:600}

.badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800}
.badge::before{content:"";width:6px;height:6px;border-radius:50%;flex-shrink:0}
.b-green{background:var(--green-soft);color:var(--green-dark)}.b-green::before{background:var(--green)}
.b-red{background:var(--red-soft);color:var(--red)}.b-red::before{background:var(--red)}
.b-amber{background:var(--amber-soft);color:#B26B00}.b-amber::before{background:var(--amber)}
.b-blue{background:var(--blue-soft);color:#1864AB}.b-blue::before{background:var(--blue)}
.b-teal{background:var(--green-soft);color:var(--green-dark)}.b-teal::before{background:var(--green)}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:9px 16px;border-radius:999px;font-size:13px;font-weight:800;cursor:pointer;transition:all .18s;border:1px solid transparent;line-height:1;background:#fff;white-space:nowrap}
.btn-primary{background:var(--green);color:#fff;border-color:var(--green)}.btn-primary:hover{background:var(--green-dark);border-color:var(--green-dark);box-shadow:0 10px 22px rgba(12,166,120,.18)}
.btn-ghost{background:#fff;color:var(--ink2);border-color:var(--line)}.btn-ghost:hover{border-color:#BDEFE3;background:var(--green-soft);color:var(--green-dark)}
.btn-danger{background:var(--red-soft);color:var(--red);border-color:#FFD3D0}.btn-danger:hover{background:#FFE3E0}
.btn-sm{padding:7px 12px;font-size:12px}

.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem}
.form-grp{margin-bottom:1rem}
.form-lbl{display:block;font-size:11px;font-weight:800;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;margin-bottom:7px}
.form-ctrl{width:100%;background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:10px 12px;font-size:13px;color:var(--ink);outline:none;transition:border-color .2s,box-shadow .2s}
.form-ctrl:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(12,166,120,.12)}
.form-ctrl::placeholder{color:var(--text3)}
select.form-ctrl option{background:#fff;color:var(--ink)}
textarea.form-ctrl{resize:vertical;min-height:90px}
.form-err{font-size:12px;color:var(--red);margin-top:5px;font-weight:700}
.form-hint{font-size:12px;color:var(--text2);margin-top:5px}

.prog{height:7px;border-radius:999px;background:#EEF4F1;overflow:hidden}
.prog-fill{height:100%;border-radius:999px;transition:width .5s ease}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.three-col{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem}
.search-wrap{display:flex;align-items:center;gap:9px;background:#fff;border:1px solid var(--line);border-radius:999px;padding:8px 13px}
.search-wrap input{background:none;border:none;outline:none;color:var(--ink);font-size:13px;width:100%}
.search-wrap input::placeholder{color:var(--text3)}

.pagination{display:flex;gap:6px;margin-top:1rem;justify-content:center;flex-wrap:wrap}
.pagination a,.pagination span{padding:7px 12px;border-radius:999px;font-size:12px;border:1px solid var(--line);color:var(--text2);background:#fff;font-weight:800}
.pagination a:hover{border-color:var(--green);color:var(--green-dark);background:var(--green-soft)}
.pagination .active span{background:var(--green);border-color:var(--green);color:#fff}

.modal-bg{position:fixed;inset:0;background:rgba(16,24,40,.55);backdrop-filter:blur(5px);z-index:100;display:none;align-items:center;justify-content:center;padding:1rem}
.modal-bg.open{display:flex}
.modal-box{background:#fff;border:1px solid var(--line);border-radius:var(--radius-lg);width:540px;max-width:100%;animation:mup .22s ease;max-height:90vh;overflow-y:auto;box-shadow:0 30px 80px rgba(16,24,40,.2)}
@keyframes mup{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.modal-head{padding:1.35rem;border-bottom:1px solid var(--line2);background:#FBFDFB}
.modal-title{font-size:1.35rem;font-weight:800;color:var(--ink)}
.modal-sub{font-size:13px;color:var(--text2);margin-top:4px;font-weight:600}
.modal-body{padding:1.35rem}
.modal-foot{padding:1rem 1.35rem;border-top:1px solid var(--line2);display:flex;justify-content:flex-end;gap:8px;background:#FBFDFB}

#toast{position:fixed;bottom:1.5rem;right:1.5rem;z-index:300;display:none;align-items:center;gap:12px;padding:13px 18px;border-radius:var(--radius-lg);font-size:13px;background:#fff;border:1px solid var(--green);color:var(--ink);box-shadow:var(--shadow);min-width:260px;animation:mup .3s ease;font-weight:700}
#toast.show{display:flex}
.empty{padding:3rem;text-align:center;color:var(--text2)}
.empty-ico{font-size:0;margin-bottom:10px}
.empty-ico::before{content:"";display:inline-block;width:42px;height:42px;border-radius:50%;background:var(--green-soft)}
.empty-txt{font-size:14px;font-weight:700}

.bed-board{display:grid;gap:18px}
.ward-map{border:1px solid var(--line);border-radius:8px;background:#fff;overflow:hidden}
.ward-map-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 16px;background:#FBFDFB;border-bottom:1px solid var(--line2)}
.ward-map-title{font-size:15px;font-weight:800;color:var(--ink)}
.ward-map-meta{font-size:12px;font-weight:800;color:var(--text2)}
.bed-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(72px,1fr));gap:12px;padding:16px}
.bed-tile{position:relative;min-height:66px;border:2px solid var(--line);border-radius:8px;background:#fff;color:var(--ink);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;text-align:center;transition:transform .16s,box-shadow .16s,border-color .16s,background .16s}
.bed-tile:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
.bed-tile.available{border-color:#8CE0C8;background:var(--green-soft);cursor:pointer}
.bed-tile.occupied{border-color:#FFB4AD;background:var(--red-soft);cursor:not-allowed}
.bed-tile.maintenance{border-color:#FFE08A;background:var(--amber-soft);cursor:not-allowed}
.bed-tile.selected{border-color:var(--green);background:var(--green);color:#fff;box-shadow:0 12px 26px rgba(12,166,120,.24)}
.bed-no{font-size:14px;font-weight:900;line-height:1}
.bed-type{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;opacity:.72}
.bed-patient{font-size:10px;font-weight:800;max-width:64px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:inherit;opacity:.85}
.bed-legend{display:flex;flex-wrap:wrap;gap:10px;padding:0 16px 16px}
.legend-item{display:inline-flex;align-items:center;gap:7px;font-size:12px;font-weight:800;color:var(--text2)}
.legend-dot{width:14px;height:14px;border-radius:4px;border:2px solid var(--line);background:#fff}
.legend-dot.available{border-color:#8CE0C8;background:var(--green-soft)}
.legend-dot.occupied{border-color:#FFB4AD;background:var(--red-soft)}
.legend-dot.maintenance{border-color:#FFE08A;background:var(--amber-soft)}
.legend-dot.selected{border-color:var(--green);background:var(--green)}
.allocation-panel{margin-top:12px;border-top:1px solid var(--line2);padding:14px 16px;background:#FBFDFB;display:none;align-items:center;justify-content:space-between;gap:14px}
.allocation-panel.open{display:flex}
.allocation-copy{font-size:13px;color:var(--text2);font-weight:700}
.allocation-copy strong{display:block;color:var(--ink);font-size:15px;margin-bottom:2px}

[style*="color:#fff"],[style*="color: #fff"]{color:var(--ink)!important}
[style*="color:var(--text2)"],[style*="color: var(--text2)"]{color:var(--text2)!important}
[style*="color:var(--text3)"],[style*="color: var(--text3)"]{color:var(--text2)!important}
[style*="font-family:'Crimson Pro'"],[style*='font-family:"Crimson Pro"']{font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif!important}
[style*="background:rgba(255,255,255,.015)"],[style*="background:rgba(255,255,255,.07)"]{background:#F8FAFC!important}
[style*="border-bottom:1px solid rgba(255,255,255,.03)"]{border-bottom:1px solid #EEF2F4!important}
.card[style*="cursor:pointer"]:hover{transform:translateY(-2px);box-shadow:var(--shadow);border-color:#BDEFE3}

@media (max-width:1100px){
  .sidebar{position:static;width:100%;height:auto}
  .shell{display:block}
  .main{margin-left:0}
  .sidebar nav{display:grid;grid-template-columns:repeat(3,1fr);gap:4px;padding:.5rem}
  .sb-sec,.sb-bottom{display:none}
  .stats-row,.two-col,.three-col{grid-template-columns:1fr 1fr}
}
@media (max-width:720px){
  .topbar{padding:0 1rem}.page-body{padding:1rem}.sh{align-items:flex-start;flex-direction:column}
  .stats-row,.two-col,.three-col,.form-grid,.form-grid-3{grid-template-columns:1fr}
  .card-head{align-items:flex-start;flex-direction:column}
  .tbl{display:block;overflow-x:auto;white-space:nowrap}
  .sidebar nav{grid-template-columns:1fr}
}
</style>
@stack('head')
</head>
<body>
<div class="shell">
  <aside class="sidebar">
    <div class="sb-top">
      <a class="sb-brand" href="{{ route('dashboard') }}">
        <span class="brand-mark"></span>
        <span>
          <span class="sb-name">MediQueue</span>
          <span class="sb-tag">Hospital System</span>
        </span>
      </a>
    </div>

    <nav style="padding:.5rem 0;overflow-y:auto;flex:1">
      <div class="sb-sec">Main</div>
      <a class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <span class="ico"></span> Dashboard
      </a>

      <div class="sb-sec">Clinical Modules</div>
      <a class="sb-link {{ request()->routeIs('opd.*') ? 'active' : '' }}" href="{{ route('opd.index') }}">
        <span class="ico"></span> OPD Queue
        @php $waiting = \App\Models\OpdToken::where('status','waiting')->count() @endphp
        @if($waiting > 0)<span class="sb-badge badge-r">{{ $waiting }}</span>@endif
      </a>
      <a class="sb-link {{ request()->routeIs('beds.*') ? 'active' : '' }}" href="{{ route('beds.index') }}">
        <span class="ico"></span> Bed Management
      </a>
      <a class="sb-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
        <span class="ico"></span> Patients
      </a>
      <a class="sb-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
        <span class="ico"></span> Inventory
        @php $low = \App\Models\Inventory::whereRaw('current_stock <= reorder_level')->count() @endphp
        @if($low > 0)<span class="sb-badge badge-r">{{ $low }}</span>@endif
      </a>

      <div class="sb-sec">Analytics</div>
      <a class="sb-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
        <span class="ico"></span> Reports
      </a>
    </nav>

    <div class="sb-bottom">
      <div class="sb-user">
        <div class="sb-av">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        <div style="flex:1;min-width:0">
          <div class="sb-uname" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ Auth::user()->name }}</div>
          <div class="sb-urole">{{ ucfirst(Auth::user()->role) }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" title="Logout" class="btn btn-ghost btn-sm">Logout</button>
        </form>
      </div>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="tb-title">@yield('page-title', 'Dashboard')</div>
      <div class="tb-right">
        <div class="chip-live"><div class="live-dot"></div>Live System</div>
        <div class="tb-clock" id="clock">--:-- --</div>
      </div>
    </header>

    <main class="page-body">
      @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="flash flash-error">Please fix this: {{ $errors->first() }}</div>
      @endif

      @yield('content')
    </main>
  </div>
</div>

<div id="toast"><span id="toast-ico"></span><span id="toast-msg"></span></div>

<script>
function tick(){
  const n=new Date();
  document.getElementById('clock').textContent=n.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit',hour12:true});
}
tick(); setInterval(tick,1000);
function toast(msg,type='success'){
  const t=document.getElementById('toast');
  document.getElementById('toast-ico').textContent=type==='success'?'Done':'Alert';
  document.getElementById('toast-msg').textContent=msg;
  t.style.borderColor=type==='success'?'var(--green)':'var(--red)';
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),3500);
}
function openModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }
</script>
@stack('scripts')
</body>
</html>
