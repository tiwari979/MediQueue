@extends('layouts.app')
@section('title','Bed Management')
@section('page-title','Bed Management')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Module 2 — Real-time Tracking</div>
    <div class="sh-title">Bed Availability</div>
    <div class="sh-desc">Ward-wise bed status with instant allocation and discharge workflows</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('beds.create') }}" class="btn btn-ghost">+ Add Bed</a>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">+ Admit Patient</a>
  </div>
</div>

{{-- Ward Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:1.75rem">
  @foreach($wards as $ward)
    @php
      $s       = $wardSummary[$ward] ?? null;
      $total   = $s?->total   ?? 0;
      $avail   = $s?->available ?? 0;
      $occ     = $s?->occupied  ?? 0;
      $pct     = $total > 0 ? round(($occ / $total) * 100) : 0;
      $color   = $pct >= 90 ? 'var(--red)' : ($pct >= 70 ? 'var(--amber)' : 'var(--teal)');
      $txColor = $pct >= 90 ? '#F58585' : ($pct >= 70 ? '#FFCA5A' : 'var(--teal2)');
    @endphp
    <div class="card" style="margin-bottom:0">
      <div style="padding:1.2rem 1.4rem">
        <div style="font-size:10px;color:var(--text2);text-transform:uppercase;letter-spacing:.1em;font-weight:600;margin-bottom:10px">{{ $ward }}</div>
        <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:8px">
          <span style="font-family:'Crimson Pro',serif;font-size:2.2rem;font-weight:600;color:{{ $txColor }}">{{ $avail }}</span>
          <span style="font-size:14px;color:var(--text2)">/ {{ $total }} beds</span>
        </div>
        <div class="prog" style="margin-bottom:8px">
          <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text3)">
          <span>{{ $occ }} occupied</span>
          <span>{{ $s?->reserved ?? 0 }} reserved</span>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- Filter + Table --}}
<div class="card">
  <div class="card-head">
    <span class="card-title">Bed Register</span>
    <form method="GET" style="display:flex;gap:8px">
      <select name="ward" class="form-ctrl" style="width:160px;padding:6px 10px;font-size:12px" onchange="this.form.submit()">
        <option value="">All Wards</option>
        @foreach($wards as $w)
          <option value="{{ $w }}" {{ $ward === $w ? 'selected' : '' }}>{{ $w }}</option>
        @endforeach
      </select>
      <select name="status" class="form-ctrl" style="width:150px;padding:6px 10px;font-size:12px" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="available"   {{ $status === 'available'   ? 'selected' : '' }}>Available</option>
        <option value="occupied"    {{ $status === 'occupied'    ? 'selected' : '' }}>Occupied</option>
        <option value="maintenance" {{ $status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
      </select>
    </form>
  </div>

  <table class="tbl">
    <thead>
      <tr>
        <th>Bed No.</th><th>Ward</th><th>Type</th>
        <th>Patient</th><th>Doctor</th><th>Admitted</th>
        <th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($beds as $bed)
        @php $adm = $bed->currentAdmission; @endphp
        <tr>
          <td><span style="font-family:'Crimson Pro',serif;font-size:1.1rem;font-weight:600;color:var(--teal2)">{{ $bed->bed_number }}</span></td>
          <td>{{ $bed->ward }}</td>
          <td><span style="font-size:11px;color:var(--text2);text-transform:capitalize">{{ $bed->bed_type }}</span></td>
          <td>
            @if($adm)
              <a href="{{ route('patients.show', $adm->patient_id) }}" style="color:#fff;font-weight:600">{{ $adm->patient->name }}</a>
              <div style="font-size:11px;color:var(--text2)">{{ $adm->patient->patient_id }}</div>
            @else
              <span style="color:var(--text3)">—</span>
            @endif
          </td>
          <td class="td-m">{{ $adm?->doctor ?? '—' }}</td>
          <td class="td-m">{{ $adm?->admitted_at?->format('d M, h:i A') ?? '—' }}</td>
          <td>{!! $bed->status_badge !!}</td>
          <td>
            <div style="display:flex;gap:6px">
              @if($bed->status === 'occupied' && $adm)
                <form method="POST" action="{{ route('beds.release', $bed->id) }}"
                      onsubmit="return confirm('Discharge patient and release bed {{ $bed->bed_number }}?')">
                  @csrf
                  <button class="btn btn-danger btn-sm" type="submit">Discharge</button>
                </form>
              @elseif($bed->status === 'available')
                <a href="{{ route('patients.create') }}?bed={{ $bed->id }}" class="btn btn-primary btn-sm">Assign</a>
              @endif
              <a href="{{ route('beds.edit', $bed->id) }}" class="btn btn-ghost btn-sm">Edit</a>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8">
            <div class="empty">
              <div class="empty-ico">🛏️</div>
              <div class="empty-txt">No beds found for the selected filters</div>
            </div>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div style="padding:1rem 1.5rem">{{ $beds->links() }}</div>
</div>
@endsection