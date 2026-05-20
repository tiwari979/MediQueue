@extends('layouts.app')
@section('title','Bed Management')
@section('page-title','Bed Management')

@push('head')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
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
.allocation-form{display:grid;grid-template-columns:minmax(250px,1.1fr) minmax(320px,1.5fr) minmax(220px,1fr) minmax(190px,1fr) auto auto;gap:10px;align-items:end;width:100%}
.allocation-field label{display:block;font-size:10px;font-weight:800;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px}
.allocation-field .form-ctrl{height:38px}
.allocation-field select.form-ctrl{margin-top:6px}
.allocation-field select.form-ctrl[size]{height:142px}
.select-search{width:100%;height:36px;border:1px solid var(--line);border-radius:8px;padding:8px 10px;font-size:12px;color:var(--ink);outline:none;background:#fff}
.select-search:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(12,166,120,.12)}
@media(max-width:720px){.bed-grid{grid-template-columns:repeat(auto-fill,minmax(62px,1fr))}.allocation-panel.open{align-items:flex-start;flex-direction:column}}
@media(max-width:1200px){.allocation-form{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Module 2 - Real-time Tracking</div>
    <div class="sh-title">Bed Availability</div>
    <div class="sh-desc">Ward-wise bed status with instant allocation and discharge workflows</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('beds.create') }}" class="btn btn-ghost">+ Add Bed</a>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">+ Admit Patient</a>
  </div>
</div>

{{-- Visual Bed Allocation Map --}}
<div class="card">
  <div class="card-head">
    <div>
      <span class="card-title">Visual Bed Allocation</span>
      <div class="td-m" style="margin-top:4px">Choose a ward and scan availability like a seat map.</div>
    </div>
    <form method="GET" style="display:flex;gap:8px">
      <select name="ward" class="form-ctrl" style="width:180px;padding:7px 11px;font-size:12px" onchange="this.form.submit()">
        <option value="">All Wards</option>
        @foreach($wards as $w)
          <option value="{{ $w }}" {{ $ward === $w ? 'selected' : '' }}>{{ $w }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="bed-board" style="padding:16px">
    @forelse($bedMap as $wardName => $wardBeds)
      @php
        $availableCount = $wardBeds->where('status','available')->count();
        $occupiedCount = $wardBeds->where('status','occupied')->count();
        $maintenanceCount = $wardBeds->where('status','maintenance')->count();
      @endphp
      <section class="ward-map">
        <div class="ward-map-head">
          <div>
            <div class="ward-map-title">{{ $wardName }}</div>
            <div class="ward-map-meta">{{ $availableCount }} available · {{ $occupiedCount }} occupied · {{ $maintenanceCount }} maintenance</div>
          </div>
          <a href="?ward={{ urlencode($wardName) }}" class="btn btn-ghost btn-sm">Focus Ward</a>
        </div>
        <div class="bed-grid">
          @foreach($wardBeds as $mapBed)
            @php $mapAdmission = $mapBed->currentAdmission; @endphp
            <button
              type="button"
              class="bed-tile {{ $mapBed->status }}"
              data-bed-id="{{ $mapBed->id }}"
              data-bed-number="{{ $mapBed->bed_number }}"
              data-ward="{{ $mapBed->ward }}"
              data-type="{{ ucfirst($mapBed->bed_type) }}"
              data-status="{{ $mapBed->status }}"
              data-patient="{{ $mapAdmission?->patient?->name ?? '' }}"
              @if($mapBed->status !== 'available') disabled @endif
              aria-label="Bed {{ $mapBed->bed_number }} is {{ $mapBed->status }}"
            >
              <span class="bed-no">{{ $mapBed->bed_number }}</span>
              <span class="bed-type">{{ $mapBed->bed_type }}</span>
              @if($mapAdmission)
                <span class="bed-patient">{{ $mapAdmission->patient->name }}</span>
              @endif
            </button>
          @endforeach
        </div>
        <div class="bed-legend">
          <span class="legend-item"><span class="legend-dot available"></span>Available</span>
          <span class="legend-item"><span class="legend-dot occupied"></span>Occupied</span>
          <span class="legend-item"><span class="legend-dot maintenance"></span>Maintenance</span>
          <span class="legend-item"><span class="legend-dot selected"></span>Selected</span>
        </div>
        <div class="allocation-panel">
          <form method="POST" action="#" class="allocation-form existing-patient-admit-form">
            @csrf
            <input type="hidden" name="bed_id" class="selected-bed-id">
            <div class="allocation-copy">
              <strong class="selected-bed-title">No bed selected</strong>
              <span class="selected-bed-meta">Select an available bed to start allocation.</span>
            </div>
            <div class="allocation-field">
              <label>Registered Patient</label>
              <input type="search" class="select-search patient-search" placeholder="Search name, DOB, reg date, ID">
              <select class="form-ctrl selected-patient-id searchable-select" required size="5">
                <option value="">Select patient</option>
                @foreach($existingPatients as $patient)
                  <option value="{{ $patient->id }}" data-search="{{ strtolower($patient->name.' '.$patient->dob->format('d M Y').' '.$patient->dob->format('Y-m-d').' '.$patient->created_at->format('d M Y').' '.$patient->created_at->format('Y-m-d').' '.$patient->patient_id) }}">
                    {{ $patient->name }} | DOB: {{ $patient->dob->format('d M Y') }} | Reg: {{ $patient->created_at->format('d M Y') }} | {{ $patient->patient_id }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="allocation-field">
              <label>Doctor</label>
              <input type="search" class="select-search doctor-search" placeholder="Search doctor or department">
              <select name="doctor" class="form-ctrl doctor-select searchable-select" required size="5">
                <option value="">Select doctor</option>
                @foreach($doctors as $doctor)
                  <option value="{{ $doctor->name }}" data-search="{{ strtolower($doctor->name.' '.$doctor->department) }}">{{ $doctor->name }} ({{ $doctor->department }})</option>
                @endforeach
              </select>
            </div>
            <div class="allocation-field">
              <label>Diagnosis</label>
              <input name="diagnosis" class="form-ctrl" placeholder="Reason for admission" required>
            </div>
            <button type="submit" class="btn btn-primary">Admit Selected</button>
            <a href="{{ route('patients.create') }}" class="btn btn-ghost selected-bed-action">Register New</a>
          </form>
        </div>
      </section>
    @empty
      <div class="empty"><div class="empty-ico"></div><div class="empty-txt">No beds have been added yet</div></div>
    @endforelse
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
              <div class="empty-ico"></div>
              <div class="empty-txt">No beds found for the selected filters</div>
            </div>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div style="padding:1rem 1.5rem">{{ $beds->links() }}</div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.bed-tile.available').forEach((tile) => {
  tile.addEventListener('click', () => {
    document.querySelectorAll('.bed-tile.selected').forEach((selected) => selected.classList.remove('selected'));
    tile.classList.add('selected');

    const wardMap = tile.closest('.ward-map');
    const panel = wardMap.querySelector('.allocation-panel');
    const title = wardMap.querySelector('.selected-bed-title');
    const meta = wardMap.querySelector('.selected-bed-meta');
    const action = wardMap.querySelector('.selected-bed-action');
    const bedInput = wardMap.querySelector('.selected-bed-id');

    title.textContent = `${tile.dataset.ward} · Bed ${tile.dataset.bedNumber}`;
    meta.textContent = `${tile.dataset.type} bed is available for allocation.`;
    action.href = `{{ route('patients.create') }}?bed=${tile.dataset.bedId}`;
    bedInput.value = tile.dataset.bedId;
    panel.classList.add('open');
  });
});

document.querySelectorAll('.existing-patient-admit-form').forEach((form) => {
  form.addEventListener('submit', (event) => {
    const patientId = form.querySelector('.selected-patient-id').value;
    if (!patientId) {
      event.preventDefault();
      toast('Please select a registered patient first.', 'error');
      return;
    }

    form.action = `{{ url('/patients') }}/${patientId}/admit`;
  });
});

function wireSelectSearch(inputSelector, selectSelector) {
  document.querySelectorAll(inputSelector).forEach((input) => {
    const form = input.closest('.existing-patient-admit-form');
    const select = form.querySelector(selectSelector);

    input.addEventListener('input', () => {
      const query = input.value.trim().toLowerCase();
      let firstVisibleValue = '';

      Array.from(select.options).forEach((option) => {
        if (!option.value) {
          option.hidden = false;
          return;
        }

        const haystack = option.dataset.search || option.textContent.toLowerCase();
        const visible = haystack.includes(query);
        option.hidden = !visible;
        if (visible && !firstVisibleValue) firstVisibleValue = option.value;
      });

      if (select.value && select.selectedOptions[0]?.hidden) {
        select.value = '';
      }
    });
  });
}

wireSelectSearch('.patient-search', '.selected-patient-id');
wireSelectSearch('.doctor-search', '.doctor-select');
</script>
@endpush
@endsection
