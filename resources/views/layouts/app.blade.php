<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') — MediQueue HMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Crimson+Pro:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">
<style>
/* ─── Reset & Variables ───────────────────────────────────────────────────── */
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --c1:#051923;--c2:#003554;--c3:#006494;--c4:#0582CA;--c5:#00A6FB;
  --teal:#00B4A0;--teal2:#00D4BD;--teal-bg:rgba(0,180,160,0.08);
  --amber:#F4A300;--amber-bg:rgba(244,163,0,0.08);
  --red:#E83B3B;--red-bg:rgba(232,59,59,0.08);
  --green:#22C55E;--green-bg:rgba(34,197,94,0.08);
  --text:#E2EAF0;--text2:#8BA3B5;--text3:#4A6070;
  --border:rgba(255,255,255,0.07);--border2:rgba(255,255,255,0.13);
  --surface:rgba(255,255,255,0.03);--surface2:rgba(255,255,255,0.06);
  --radius:10px;--radius-lg:16px;
}
html{scroll-behavior:smooth}
body{font-family:'Sora',sans-serif;background:var(--c1);color:var(--text);min-height:100vh;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.1);border-radius:3px}
a{text-decoration:none;color:inherit}

/* ─── Layout ────────────────────────────────────────────────────────────────  */
.shell{display:flex;min-height:100vh}
.sidebar{width:248px;flex-shrink:0;background:var(--c2);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:50}
.main{margin-left:248px;flex:1;display:flex;flex-direction:column;min-height:100vh}
.topbar{background:rgba(5,25,35,0.95);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);padding:0 2rem;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40}
.page-body{padding:1.75rem 2rem;flex:1}

/* ─── Sidebar ───────────────────────────────────────────────────────────────  */
.sb-top{padding:1.25rem;border-bottom:1px solid var(--border)}
.sb-brand{display:flex;align-items:center;gap:11px}
.sb-logo-box{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--teal),var(--c4));display:flex;align-items:center;justify-content:center;font-size:19px;flex-shrink:0;box-shadow:0 4px 14px rgba(0,180,160,0.28)}
.sb-name{font-size:.95rem;font-weight:700;color:#fff;line-height:1.1}
.sb-tag{font-size:8.5px;color:var(--text2);letter-spacing:.12em;text-transform:uppercase;margin-top:2px}
.sb-sec{padding:1.1rem 1rem .2rem;font-size:9px;color:var(--text3);letter-spacing:.15em;text-transform:uppercase;font-weight:600}
.sb-link{display:flex;align-items:center;gap:10px;padding:8px 11px;margin:1px 7px;border-radius:var(--radius);color:var(--text2);font-size:12.5px;font-weight:400;cursor:pointer;border:none;background:none;width:calc(100% - 14px);text-align:left;font-family:'Sora',sans-serif;transition:all .15s}
.sb-link:hover{background:var(--surface2);color:var(--text)}
.sb-link.active{background:rgba(0,180,160,.14);color:var(--teal2);font-weight:500}
.sb-link .ico{width:18px;text-align:center;font-size:14px;flex-shrink:0}
.sb-badge{margin-left:auto;font-size:10px;padding:2px 6px;border-radius:20px;font-weight:600}
.badge-r{background:var(--red-bg);color:var(--red)}
.badge-g{background:var(--teal-bg);color:var(--teal2)}
.sb-bottom{margin-top:auto;padding:1rem 1.25rem;border-top:1px solid var(--border)}
.sb-user{display:flex;align-items:center;gap:9px}
.sb-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--c4),var(--teal));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#fff;flex-shrink:0}
.sb-uname{font-size:11.5px;font-weight:500;color:var(--text);line-height:1.2}
.sb-urole{font-size:10px;color:var(--text2)}

/* ─── Topbar ────────────────────────────────────────────────────────────────  */
.tb-title{font-family:'Crimson Pro',serif;font-size:1.25rem;font-weight:600;color:#fff}
.tb-right{display:flex;align-items:center;gap:12px}
.chip-live{display:flex;align-items:center;gap:6px;padding:5px 13px;border-radius:20px;font-size:11px;font-weight:500;background:var(--teal-bg);border:1px solid rgba(0,180,160,.25);color:var(--teal2)}
.live-dot{width:6px;height:6px;border-radius:50%;background:var(--teal);animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
.tb-clock{font-size:12px;color:var(--text2)}

/* ─── Flash Messages ────────────────────────────────────────────────────────  */
.flash{padding:13px 1.5rem;font-size:13px;margin-bottom:1.25rem;border-radius:var(--radius);display:flex;align-items:center;gap:10px;border:1px solid}
.flash-success{background:var(--green-bg);border-color:rgba(34,197,94,.25);color:#4ADE80}
.flash-error  {background:var(--red-bg);border-color:rgba(232,59,59,.25);color:#F87171}

/* ─── Stats Grid ─────────────────────────────────────────────────────────────  */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:1.75rem}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.35rem;position:relative;overflow:hidden;transition:border-color .2s,transform .2s}
.stat-card:hover{border-color:var(--border2);transform:translateY(-1px)}
.stat-card::after{content:'';position:absolute;top:0;left:0;right:0;height:2px}
.sc-teal::after{background:linear-gradient(90deg,var(--teal),transparent)}
.sc-blue::after{background:linear-gradient(90deg,var(--c4),transparent)}
.sc-amber::after{background:linear-gradient(90deg,var(--amber),transparent)}
.sc-red::after{background:linear-gradient(90deg,var(--red),transparent)}
.sc-ico{font-size:22px;margin-bottom:11px;display:block}
.sc-lbl{font-size:10px;color:var(--text2);text-transform:uppercase;letter-spacing:.1em;font-weight:600;margin-bottom:5px}
.sc-val{font-family:'Crimson Pro',serif;font-size:2.2rem;font-weight:600;line-height:1}
.sc-teal .sc-val{color:var(--teal2)}.sc-blue .sc-val{color:#7EC8F5}.sc-amber .sc-val{color:#FFCA5A}.sc-red .sc-val{color:#F58585}
.sc-sub{font-size:11px;color:var(--text3);margin-top:5px}

/* ─── Cards ──────────────────────────────────────────────────────────────────  */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;margin-bottom:1.5rem}
.card-head{padding:.9rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px}
.card-title{font-size:13px;font-weight:600;color:#fff}
.card-body{padding:1.5rem}

/* ─── Table ──────────────────────────────────────────────────────────────────  */
.tbl{width:100%;border-collapse:collapse}
.tbl th{padding:9px 1.5rem;text-align:left;font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;border-bottom:1px solid var(--border);background:rgba(255,255,255,.015)}
.tbl td{padding:12px 1.5rem;font-size:12.5px;color:var(--text);border-bottom:1px solid rgba(255,255,255,.03)}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:rgba(255,255,255,.02)}
.td-b{font-weight:600;color:#fff}
.td-m{color:var(--text2);font-size:12px}

/* ─── Badges ─────────────────────────────────────────────────────────────────  */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
.badge::before{content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0}
.b-green{background:var(--green-bg);color:var(--green)}.b-green::before{background:var(--green)}
.b-red{background:var(--red-bg);color:var(--red)}.b-red::before{background:var(--red)}
.b-amber{background:var(--amber-bg);color:var(--amber)}.b-amber::before{background:var(--amber)}
.b-blue{background:rgba(0,166,251,.1);color:var(--c5)}.b-blue::before{background:var(--c5)}
.b-teal{background:var(--teal-bg);color:var(--teal2)}.b-teal::before{background:var(--teal)}

/* ─── Buttons ────────────────────────────────────────────────────────────────  */
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--radius);font-size:12.5px;font-weight:500;cursor:pointer;transition:all .18s;border:1px solid transparent;font-family:'Sora',sans-serif;line-height:1}
.btn-primary{background:var(--teal);color:#fff;border-color:var(--teal)}.btn-primary:hover{background:var(--teal2)}
.btn-ghost{background:var(--surface2);color:var(--text);border-color:var(--border2)}.btn-ghost:hover{border-color:var(--text2)}
.btn-danger{background:var(--red-bg);color:var(--red);border-color:rgba(232,59,59,.25)}.btn-danger:hover{background:rgba(232,59,59,.18)}
.btn-sm{padding:5px 12px;font-size:11.5px}

/* ─── Forms ──────────────────────────────────────────────────────────────────  */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem}
.form-grp{margin-bottom:1rem}
.form-lbl{display:block;font-size:10.5px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;margin-bottom:6px}
.form-ctrl{width:100%;background:rgba(255,255,255,.05);border:1px solid var(--border2);border-radius:var(--radius);padding:10px 13px;font-size:13px;color:var(--text);font-family:'Sora',sans-serif;outline:none;transition:border-color .2s}
.form-ctrl:focus{border-color:var(--teal);background:rgba(0,180,160,.04)}
.form-ctrl::placeholder{color:var(--text3)}
select.form-ctrl option{background:#003554;color:var(--text)}
textarea.form-ctrl{resize:vertical;min-height:80px}
.form-err{font-size:11px;color:var(--red);margin-top:4px}
.form-hint{font-size:11px;color:var(--text3);margin-top:4px}

/* ─── Progress ───────────────────────────────────────────────────────────────  */
.prog{height:6px;border-radius:3px;background:rgba(255,255,255,.07);overflow:hidden}
.prog-fill{height:100%;border-radius:3px;transition:width .5s ease}

/* ─── Section Header ─────────────────────────────────────────────────────────  */
.sh{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;gap:12px}
.sh-eye{font-size:10px;color:var(--teal2);text-transform:uppercase;letter-spacing:.12em;font-weight:600;margin-bottom:3px}
.sh-title{font-family:'Crimson Pro',serif;font-size:1.45rem;font-weight:600;color:#fff}
.sh-desc{font-size:12.5px;color:var(--text2);margin-top:3px}

/* ─── Two/Three Col ──────────────────────────────────────────────────────────  */
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.three-col{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem}

/* ─── Pagination ─────────────────────────────────────────────────────────────  */
.pagination{display:flex;gap:5px;margin-top:1rem;justify-content:center;flex-wrap:wrap}
.pagination a,.pagination span{padding:6px 12px;border-radius:8px;font-size:12px;border:1px solid var(--border2);color:var(--text2);background:var(--surface)}
.pagination a:hover{border-color:var(--teal);color:var(--teal2)}
.pagination .active span{background:var(--teal);border-color:var(--teal);color:#fff}

/* ─── Search ─────────────────────────────────────────────────────────────────  */
.search-wrap{display:flex;align-items:center;gap:8px;background:var(--surface);border:1px solid var(--border2);border-radius:var(--radius);padding:7px 13px}
.search-wrap input{background:none;border:none;outline:none;color:var(--text);font-size:13px;width:100%;font-family:'Sora',sans-serif}
.search-wrap input::placeholder{color:var(--text3)}

/* ─── Modal ──────────────────────────────────────────────────────────────────  */
.modal-bg{position:fixed;inset:0;background:rgba(0,10,20,.88);backdrop-filter:blur(5px);z-index:100;display:none;align-items:center;justify-content:center;padding:1rem}
.modal-bg.open{display:flex}
.modal-box{background:#003554;border:1px solid var(--border2);border-radius:var(--radius-lg);width:520px;max-width:100%;animation:mup .22s ease;max-height:90vh;overflow-y:auto}
@keyframes mup{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.modal-head{padding:1.35rem 1.5rem;border-bottom:1px solid var(--border)}
.modal-title{font-family:'Crimson Pro',serif;font-size:1.25rem;font-weight:600;color:#fff}
.modal-sub{font-size:12px;color:var(--text2);margin-top:3px}
.modal-body{padding:1.5rem}
.modal-foot{padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:8px}

/* ─── Toast ──────────────────────────────────────────────────────────────────  */
#toast{position:fixed;bottom:1.5rem;right:1.5rem;z-index:300;display:none;align-items:center;gap:12px;padding:13px 18px;border-radius:var(--radius-lg);font-size:13px;background:#003554;border:1px solid var(--teal);color:var(--text);box-shadow:0 8px 30px rgba(0,0,0,.4);min-width:260px;animation:mup .3s ease}
#toast.show{display:flex}

/* ─── Empty ──────────────────────────────────────────────────────────────────  */
.empty{padding:3rem;text-align:center;color:var(--text2)}
.empty-ico{font-size:36px;opacity:.35;margin-bottom:12px}
.empty-txt{font-size:13px}
</style>
@stack('head')
</head>
<body>
<div class="shell">

  {{-- ─── Sidebar ─────────────────────────────────────────────────────────── --}}
  <aside class="sidebar">
    <div class="sb-top">
      <div class="sb-brand">
        <div class="sb-logo-box">🏥</div>
        <div>
          <div class="sb-name">MediQueue HMS</div>
          <!-- <div class="sb-tag">Laravel MVC · INT221</div> -->
        </div>
      </div>
    </div>

    <nav style="padding:.5rem 0;overflow-y:auto;flex:1">
      <div class="sb-sec">Main</div>
      <a class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <span class="ico">📊</span> Dashboard
      </a>

      <div class="sb-sec">Clinical Modules</div>
      <a class="sb-link {{ request()->routeIs('opd.*') ? 'active' : '' }}" href="{{ route('opd.index') }}">
        <span class="ico">🔄</span> OPD Queue
        @php $waiting = \App\Models\OpdToken::where('status','waiting')->count() @endphp
        @if($waiting > 0)<span class="sb-badge badge-r">{{ $waiting }}</span>@endif
      </a>
      <a class="sb-link {{ request()->routeIs('beds.*') ? 'active' : '' }}" href="{{ route('beds.index') }}">
        <span class="ico">🛏️</span> Bed Management
      </a>
      <a class="sb-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
        <span class="ico">👤</span> Patients
      </a>
      <a class="sb-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
        <span class="ico">💊</span> Inventory
        @php $low = \App\Models\Inventory::whereRaw('current_stock <= reorder_level')->count() @endphp
        @if($low > 0)<span class="sb-badge badge-r">{{ $low }}</span>@endif
      </a>

      <div class="sb-sec">Analytics</div>
      <a class="sb-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
        <span class="ico">📈</span> Reports
      </a>
    </nav>

    <div class="sb-bottom">
      <div class="sb-user">
        <div class="sb-av">{{ substr(Auth::user()->name, 0, 2) }}</div>
        <div style="flex:1;min-width:0">
          <div class="sb-uname" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ Auth::user()->name }}</div>
          <div class="sb-urole">{{ ucfirst(Auth::user()->role) }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" title="Logout" style="background:none;border:none;cursor:pointer;color:var(--text2);font-size:16px;opacity:.5;transition:opacity .2s;padding:4px" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.5">⎋</button>
        </form>
      </div>
    </div>
  </aside>

  {{-- ─── Main Area ───────────────────────────────────────────────────────── --}}
  <div class="main">
    <header class="topbar">
      <div class="tb-title">@yield('page-title', 'Dashboard')</div>
      <div class="tb-right">
        <div class="chip-live"><div class="live-dot"></div>Live System</div>
        <div class="tb-clock" id="clock">--:-- --</div>
      </div>
    </header>

    <main class="page-body">
      {{-- Flash messages --}}
      @if(session('success'))
        <div class="flash flash-success">✅ {{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="flash flash-error">❌ {{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="flash flash-error">
          ❌ Please fix the following: {{ $errors->first() }}
        </div>
      @endif

      @yield('content')
    </main>
  </div>

</div>

{{-- Toast --}}
<div id="toast"><span id="toast-ico">✅</span><span id="toast-msg"></span></div>

<script>
// Clock
function tick(){
  const n=new Date();
  document.getElementById('clock').textContent=n.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit',hour12:true});
}
tick(); setInterval(tick,1000);

// Toast helper
function toast(msg,type='success'){
  const t=document.getElementById('toast');
  document.getElementById('toast-ico').textContent=type==='success'?'✅':'❌';
  document.getElementById('toast-msg').textContent=msg;
  t.style.borderColor=type==='success'?'var(--teal)':'var(--red)';
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),3500);
}

// Modal helpers
function openModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }
</script>
@stack('scripts')
</body>
</html>