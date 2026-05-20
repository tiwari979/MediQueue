@extends('layouts.app')
@section('title','Bed Report')
@section('page-title','Bed & Admission Report')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Reports / Beds</div>
    <div class="sh-title">Bed Occupancy & Admissions</div>
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

{{-- Ward Occupancy --}}
<div class="card">
  <div class="card-head"><span class="card-title">Ward Occupancy Rates</span></div>
  <div style="padding:1.5rem;display:grid;grid-template-columns:repeat(3,1fr);gap:1rem">
    @foreach($wardStats as $ward)
      @php
        $pct     = $ward->total > 0 ? round(($ward->occupied / $ward->total) * 100) : 0;
        $color   = $pct >= 90 ? 'var(--red)' : ($pct >= 70 ? 'var(--amber)' : 'var(--teal)');
        $txColor = $pct >= 90 ? '#F58585'    : ($pct >= 70 ? '#FFCA5A'      : 'var(--teal2)');
      @endphp
      <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:1.1rem">
        <div style="font-size:11px;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px">{{ $ward->ward }}</div>
        <div style="font-family:'Crimson Pro',serif;font-size:1.8rem;font-weight:600;color:{{ $txColor }};line-height:1;margin-bottom:6px">{{ $pct }}%</div>
        <div class="prog" style="margin-bottom:6px">
          <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
        </div>
        <div style="font-size:11px;color:var(--text3)">{{ $ward->occupied }} / {{ $ward->total }} beds occupied</div>
      </div>
    @endforeach
  </div>
</div>

{{-- Admission History --}}
<div class="card">
  <div class="card-head">
    <span class="card-title">Admission History</span>
    <span style="font-size:12px;color:var(--text2)">{{ $from }} to {{ $to }}</span>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>#</th><th>Patient</th><th>Ward / Bed</th><th>Diagnosis</th><th>Doctor</th><th>Admitted</th><th>Discharged</th><th>Days</th><th>Status</th></tr>
    </thead>
    <tbody>
      @forelse($admissions as $adm)
        <tr>
          <td class="td-m">{{ $adm->id }}</td>
          <td class="td-b">
            <a href="{{ route('patients.show', $adm->patient_id) }}" style="color:#fff">{{ $adm->patient->name }}</a>
            <div class="td-m">{{ $adm->patient->patient_id }}</div>
          </td>
          <td>{{ $adm->bed->ward }} / <strong>{{ $adm->bed->bed_number }}</strong></td>
          <td>{{ Str::limit($adm->diagnosis, 28) }}</td>
          <td class="td-m">{{ $adm->doctor }}</td>
          <td class="td-m">{{ $adm->admitted_at->format('d M Y') }}</td>
          <td class="td-m">{{ $adm->discharged_at?->format('d M Y') ?? '—' }}</td>
          <td class="td-m">{{ $adm->length_of_stay }}d</td>
          <td>
            @if($adm->status === 'admitted')
              <span class="badge b-green">Active</span>
            @else
              <span class="badge b-blue">Discharged</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="9"><div class="empty"><div class="empty-txt">No admissions in selected range</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div style="padding:1rem 1.5rem">{{ $admissions->links() }}</div>
</div>
@endsection
