@extends('layouts.app')
@section('title','OPD Report')
@section('page-title','OPD Queue Report')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Reports / OPD</div>
    <div class="sh-title">OPD Queue Analytics</div>
  </div>
  <div style="display:flex;gap:8px;align-items:center">
    <form method="GET" style="display:flex;gap:8px">
      <div>
        <label style="font-size:11px;color:var(--text2);display:block;margin-bottom:4px">From</label>
        <input name="from" type="date" class="form-ctrl" style="padding:7px 11px;font-size:12px" value="{{ $from }}">
      </div>
      <div>
        <label style="font-size:11px;color:var(--text2);display:block;margin-bottom:4px">To</label>
        <input name="to" type="date" class="form-ctrl" style="padding:7px 11px;font-size:12px" value="{{ $to }}">
      </div>
      <div style="display:flex;align-items:flex-end">
        <button class="btn btn-primary btn-sm" type="submit">Apply</button>
      </div>
    </form>
    <div style="display:flex;align-items:flex-end">
      <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-sm">Back</a>
    </div>
  </div>
</div>

{{-- Summary --}}
@php
  $totalTokens  = $daily->sum('total');
  $totalServed  = $daily->sum('served');
  $avgWait      = $daily->avg('avg_wait');
  $servRate     = $totalTokens > 0 ? round(($totalServed / $totalTokens) * 100) : 0;
@endphp
<div class="stats-row">
  <div class="stat-card sc-teal">
    <div class="sc-lbl">Total Tokens</div>
    <div class="sc-val">{{ number_format($totalTokens) }}</div>
    <div class="sc-sub">In selected period</div>
  </div>
  <div class="stat-card sc-blue">
    <div class="sc-lbl">Patients Served</div>
    <div class="sc-val">{{ number_format($totalServed) }}</div>
    <div class="sc-sub">{{ $servRate }}% service rate</div>
  </div>
  <div class="stat-card sc-amber">
    <div class="sc-lbl">Avg Wait Time</div>
    <div class="sc-val">{{ number_format($avgWait) }}<span style="font-size:1rem">m</span></div>
    <div class="sc-sub">Across all departments</div>
  </div>
  <div class="stat-card sc-red">
    <div class="sc-lbl">Departments</div>
    <div class="sc-val">{{ $byDept->count() }}</div>
    <div class="sc-sub">Active in period</div>
  </div>
</div>

<div class="two-col">
  {{-- Daily Breakdown --}}
  <div class="card">
    <div class="card-head"><span class="card-title">Daily Token Summary</span></div>
    <table class="tbl">
      <thead>
        <tr><th>Date</th><th>Total</th><th>Served</th><th>Pending</th><th>Avg Wait</th></tr>
      </thead>
      <tbody>
        @forelse($daily as $row)
          @php $pending = $row->total - $row->served; @endphp
          <tr>
            <td class="td-b">{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
            <td>{{ $row->total }}</td>
            <td style="color:var(--green);font-weight:500">{{ $row->served }}</td>
            <td>
              @if($pending > 0)
                <span style="color:var(--amber);font-weight:500">{{ $pending }}</span>
              @else
                <span style="color:var(--text3)">0</span>
              @endif
            </td>
            <td class="td-m">{{ number_format($row->avg_wait) }} min</td>
          </tr>
        @empty
          <tr><td colspan="5"><div class="empty"><div class="empty-txt">No data for selected range</div></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Department Breakdown --}}
  <div class="card">
    <div class="card-head"><span class="card-title">Department-wise Load</span></div>
    <div style="padding:1.25rem">
      @forelse($byDept as $row)
        @php $maxCount = $byDept->max('total'); $pct = $maxCount > 0 ? round(($row->total / $maxCount) * 100) : 0; @endphp
        <div style="margin-bottom:1rem">
          <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:6px">
            <span style="color:#fff;font-weight:500">{{ $row->department }}</span>
            <div style="display:flex;gap:10px;align-items:center">
              @if($row->emergency > 0)
                <span class="badge b-red" style="font-size:10px">{{ $row->emergency }} emerg.</span>
              @endif
              <span style="color:var(--teal2);font-weight:600">{{ $row->total }}</span>
            </div>
          </div>
          <div class="prog">
            <div class="prog-fill" style="width:{{ $pct }}%;background:var(--teal)"></div>
          </div>
        </div>
      @empty
        <div class="empty"><div class="empty-txt">No data for selected range</div></div>
      @endforelse
    </div>
  </div>
</div>
@endsection
