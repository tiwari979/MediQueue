{{-- ============================================================ --}}
{{-- resources/views/opd/index.blade.php                         --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title','OPD Queue')
@section('page-title','OPD Queue Management')

@section('content')

<div class="sh">
  <div>
    <div class="sh-eye">M/M/c Queuing Model</div>
    <div class="sh-title">Outpatient Department Queue</div>
    <div class="sh-desc">Real-time token management across all departments</div>
  </div>
  <a href="{{ route('opd.create') }}" class="btn btn-primary">+ Issue Token</a>
</div>

{{-- Department Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:1.75rem">
  @foreach($departments as $d)
    @php $summary = $deptSummary[$d] ?? null; $count = $summary?->count ?? 0; @endphp
    <div class="card" style="margin-bottom:0;cursor:pointer" onclick="window.location='?dept={{ urlencode($d) }}'">
      <div style="padding:1rem 1.25rem">
        <div style="font-size:11px;color:var(--text2);margin-bottom:6px">{{ $d }}</div>
        <div style="display:flex;align-items:baseline;gap:6px;margin-bottom:8px">
          <span style="font-family:'Crimson Pro',serif;font-size:1.8rem;font-weight:600;color:{{ $count >= 15 ? '#F58585' : ($count >= 8 ? '#FFCA5A' : 'var(--teal2)') }}">{{ $count }}</span>
          <span style="font-size:12px;color:var(--text2)">waiting</span>
        </div>
        @if($summary)
          <div class="prog"><div class="prog-fill" style="width:{{ min(100,($count/20)*100) }}%;background:{{ $count >= 15 ? 'var(--red)' : ($count >= 8 ? 'var(--amber)' : 'var(--teal)') }}"></div></div>
            <div style="font-size:11px;color:var(--text3);margin-top:5px">~{{ number_format($summary->avg_wait) }} min avg</div>
        @else
          <div style="font-size:11px;color:var(--text3)">No patients waiting</div>
        @endif
      </div>
    </div>
  @endforeach
</div>

{{-- Filter Bar --}}
<div class="card">
  <div class="card-head">
    <span class="card-title">Token List</span>
    <form method="GET" style="display:flex;gap:8px;align-items:center">
      <select name="dept" class="form-ctrl" style="width:180px;padding:6px 10px;font-size:12px" onchange="this.form.submit()">
        <option value="">All Departments</option>
        @foreach($departments as $d)
          <option value="{{ $d }}" {{ $dept === $d ? 'selected' : '' }}>{{ $d }}</option>
        @endforeach
      </select>
      <select name="status" class="form-ctrl" style="width:150px;padding:6px 10px;font-size:12px" onchange="this.form.submit()">
        <option value="waiting" {{ $status === 'waiting' ? 'selected' : '' }}>Waiting</option>
        <option value="in_consultation" {{ $status === 'in_consultation' ? 'selected' : '' }}>In Consultation</option>
        <option value="served" {{ $status === 'served' ? 'selected' : '' }}>Served</option>
      </select>
    </form>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>Token</th><th>Patient</th><th>Department</th><th>Priority</th><th>Est. Wait</th><th>Status</th><th>Issued</th><th>Actions</th></tr>
    </thead>
    <tbody>
      @forelse($tokens as $t)
        <tr>
          <td><span style="font-family:'Crimson Pro',serif;font-size:1.2rem;font-weight:600;color:var(--teal2)">T-{{ str_pad($t->token_number,3,'0',STR_PAD_LEFT) }}</span></td>
          <td class="td-b">{{ $t->patient->name }}<div class="td-m">{{ $t->patient->patient_id }}</div></td>
          <td>{{ $t->department }}</td>
          <td>{!! $t->priority_badge !!}</td>
          <td>{{ $t->estimated_wait > 0 ? $t->estimated_wait.' min' : '—' }}</td>
          <td>{!! $t->status_badge !!}</td>
          <td class="td-m">{{ $t->created_at->format('h:i A') }}</td>
          <td>
            <div style="display:flex;gap:6px">
              @if($t->status === 'waiting')
                <form method="POST" action="{{ route('opd.callnext', $t->id) }}">
                  @csrf <button class="btn btn-ghost btn-sm" type="submit">Call</button>
                </form>
              @elseif($t->status === 'in_consultation')
                <form method="POST" action="{{ route('opd.complete', $t->id) }}">
                  @csrf <button class="btn btn-primary btn-sm" type="submit">Done</button>
                </form>
              @endif
              <form method="POST" action="{{ route('opd.destroy', $t->id) }}">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Cancel this token?')">Cancel</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="8"><div class="empty"><div class="empty-ico"></div><div class="empty-txt">No tokens found</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div style="padding:1rem 1.5rem">{{ $tokens->links() }}</div>
</div>
@endsection
