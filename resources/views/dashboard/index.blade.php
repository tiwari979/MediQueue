@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Stats ─────────────────────────────────────────────────────────────── --}}
<div class="stats-row">
  <div class="stat-card sc-teal">
    <span class="sc-ico">👥</span>
    <div class="sc-lbl">Patients Today</div>
    <div class="sc-val">{{ $patientsToday }}</div>
    <div class="sc-sub">{{ $admittedToday }} admitted · {{ $dischargedToday }} discharged</div>
  </div>
  <div class="stat-card sc-red">
    <span class="sc-ico">⏱️</span>
    <div class="sc-lbl">Avg OPD Wait</div>
    <div class="sc-val">{{ number_format($avgWaitMinutes) }}<span style="font-size:1rem">m</span></div>
    <div class="sc-sub">{{ $totalWaiting }} patients currently waiting</div>
  </div>
  <div class="stat-card sc-blue">
    <span class="sc-ico">🛏️</span>
    <div class="sc-lbl">Beds Available</div>
    <div class="sc-val">{{ $availableBeds }}</div>
    <div class="sc-sub">of {{ $totalBeds }} total · {{ $occupiedBeds }} occupied</div>
  </div>
  <div class="stat-card sc-amber">
    <span class="sc-ico">⚠️</span>
    <div class="sc-lbl">Stock Alerts</div>
    <div class="sc-val">{{ $lowStockCount }}</div>
    <div class="sc-sub">{{ $expiringCount }} expiring within 30 days</div>
  </div>
</div>

{{-- ── Two Column: OPD + Beds ───────────────────────────────────────────── --}}
<div class="two-col">

  {{-- OPD Department Load --}}
  <div class="card">
    <div class="card-head">
      <span class="card-title">🔄 OPD Department Load</span>
      <a href="{{ route('opd.index') }}" class="btn btn-ghost btn-sm">View Queue →</a>
    </div>
    <div>
      @forelse($opdByDept as $row)
        @php
          $max = 25;
          $pct = min(100, round(($row->count / $max) * 100));
          $color = $pct >= 80 ? 'var(--red)' : ($pct >= 50 ? 'var(--amber)' : 'var(--teal)');
        @endphp
        <div style="display:flex;align-items:center;gap:14px;padding:11px 1.5rem;border-bottom:1px solid rgba(255,255,255,.03)">
          <div style="font-size:13px;font-weight:500;width:150px;flex-shrink:0">{{ $row->department }}</div>
          <div style="flex:1;height:8px;border-radius:4px;background:rgba(255,255,255,.07);overflow:hidden">
            <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:4px"></div>
          </div>
          <div style="font-size:13px;font-weight:600;width:28px;text-align:right">{{ $row->count }}</div>
          <div style="font-size:11px;color:var(--text2);width:55px;text-align:right">~{{ number_format($row->avg_wait) }}m</div>
        </div>
      @empty
        <div class="empty"><div class="empty-ico">✅</div><div class="empty-txt">No patients currently waiting</div></div>
      @endforelse
    </div>
  </div>

  {{-- Bed Occupancy --}}
  <div class="card">
    <div class="card-head">
      <span class="card-title">🛏️ Ward Bed Occupancy</span>
      <a href="{{ route('beds.index') }}" class="btn btn-ghost btn-sm">View Beds →</a>
    </div>
    <div style="padding:1.25rem">
      @foreach($bedsByWard as $ward)
        @php
          $pct = $ward->total > 0 ? round(($ward->occupied / $ward->total) * 100) : 0;
          $color = $pct >= 90 ? 'var(--red)' : ($pct >= 70 ? 'var(--amber)' : 'var(--teal)');
          $txtColor = $pct >= 90 ? '#F58585' : ($pct >= 70 ? '#FFCA5A' : 'var(--teal2)');
        @endphp
        <div style="margin-bottom:.85rem">
          <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px">
            <span style="color:var(--text2)">{{ $ward->ward }}</span>
            <span style="font-weight:500;color:{{ $txtColor }}">{{ $ward->available }} free / {{ $ward->total }}</span>
          </div>
          <div class="prog"><div class="prog-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div></div>
        </div>
      @endforeach
    </div>
  </div>

</div>

{{-- ── Recent Admissions ────────────────────────────────────────────────── --}}
<div class="card">
  <div class="card-head">
    <span class="card-title">Recent Admissions</span>
    <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">+ Admit Patient</a>
  </div>
  <table class="tbl">
    <thead>
      <tr>
        <th>Patient ID</th><th>Name</th><th>Ward / Bed</th>
        <th>Doctor</th><th>Diagnosis</th><th>Admitted</th><th>Status</th><th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($recentAdmissions as $admission)
        <tr>
          <td class="td-m">{{ $admission->patient->patient_id }}</td>
          <td class="td-b">{{ $admission->patient->name }}</td>
          <td class="td-m">{{ $admission->bed->ward }} / {{ $admission->bed->bed_number }}</td>
          <td class="td-m">{{ $admission->doctor }}</td>
          <td>{{ Str::limit($admission->diagnosis, 25) }}</td>
          <td class="td-m">{{ $admission->admitted_at->format('d M, h:i A') }}</td>
          <td><span class="badge b-green">Admitted</span></td>
          <td>
            <a href="{{ route('patients.show', $admission->patient_id) }}" class="btn btn-ghost btn-sm">View</a>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="empty" style="padding:2rem;text-align:center;color:var(--text2)">No recent admissions</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection