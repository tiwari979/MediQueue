@extends('layouts.app')
@section('title', $patient->name)
@section('page-title','Patient Details')

@push('head')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Patient Record</div>
    <div class="sh-title">{{ $patient->name }}</div>
    <div class="sh-desc">{{ $patient->patient_id }} · Registered {{ $patient->created_at->format('d M Y') }}</div>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-ghost">Edit Record</a>
    @if(!$patient->isAdmitted())
      <button class="btn btn-primary" onclick="openModal('modal-admit')">+ Admit Patient</button>
    @else
      <button class="btn btn-danger" onclick="openModal('modal-discharge')">Discharge Patient</button>
    @endif
  </div>
</div>

<div class="two-col" style="margin-bottom:1.5rem">
  {{-- Personal Info --}}
  <div class="card" style="margin-bottom:0">
    <div class="card-head"><span class="card-title">Personal Information</span></div>
    <div style="padding:1.25rem;display:flex;flex-direction:column;gap:.75rem">
      @foreach([
        ['Name',        $patient->name],
        ['Age / DOB',   $patient->age . ' years · ' . $patient->dob->format('d M Y')],
        ['Gender',       ucfirst($patient->gender)],
        ['Blood Group',  $patient->blood_group],
        ['Phone',        $patient->phone],
        ['Email',        $patient->email ?? 'Not provided'],
        ['Address',      $patient->address ?? 'Not provided'],
      ] as [$label, $value])
        <div style="display:flex;gap:12px;font-size:13px">
          <span style="color:var(--text2);width:130px;flex-shrink:0">{{ $label }}</span>
          <span style="color:#fff;font-weight:500">{{ $value }}</span>
        </div>
      @endforeach
      @if($patient->emergency_contact_name)
        <div style="border-top:1px solid var(--border);padding-top:.75rem;font-size:12px;color:var(--text2)">
          <strong style="color:var(--text)">Emergency Contact:</strong> {{ $patient->emergency_contact_name }} · {{ $patient->emergency_contact_phone }}
        </div>
      @endif
    </div>
  </div>

  {{-- Current Status --}}
  <div class="card" style="margin-bottom:0">
    <div class="card-head"><span class="card-title">Current Status</span></div>
    <div style="padding:1.25rem">
      @php $activeAdm = $patient->admissions->where('status','admitted')->first(); @endphp
      @if($activeAdm)
        <div style="background:var(--green-bg);border:1px solid rgba(34,197,94,.2);border-radius:var(--radius);padding:1rem;margin-bottom:1rem">
          <div style="font-size:11px;color:var(--green);font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">Currently Admitted</div>
          <div style="font-size:13px;color:#fff;font-weight:600;margin-bottom:4px">{{ $activeAdm->bed->ward }} - Bed {{ $activeAdm->bed->bed_number }}</div>
          <div style="font-size:12px;color:var(--text2)">{{ $activeAdm->diagnosis }}</div>
          <div style="font-size:12px;color:var(--text2);margin-top:4px">Under: {{ $activeAdm->doctor }}</div>
          <div style="font-size:11px;color:var(--text3);margin-top:4px">Admitted {{ $activeAdm->admitted_at->format('d M Y, h:i A') }} · {{ $activeAdm->length_of_stay }} day(s)</div>
        </div>
      @else
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;margin-bottom:1rem;text-align:center;color:var(--text2);font-size:13px">
          Not currently admitted
        </div>
      @endif

      {{-- OPD History --}}
      <div style="font-size:11px;color:var(--text2);font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px">Recent OPD Visits</div>
      @forelse($patient->opdTokens->take(3) as $tok)
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:12px">
          <span style="color:#fff">{{ $tok->department }}</span>
          <span style="color:var(--text2)">{{ $tok->created_at->format('d M Y') }}</span>
          {!! $tok->status_badge !!}
        </div>
      @empty
        <div style="font-size:12px;color:var(--text3)">No OPD visits recorded</div>
      @endforelse
    </div>
  </div>
</div>

{{-- Admission History --}}
<div class="card">
  <div class="card-head"><span class="card-title">Admission History</span></div>
  <table class="tbl">
    <thead>
      <tr><th>Admission</th><th>Ward / Bed</th><th>Diagnosis</th><th>Doctor</th><th>Admitted</th><th>Discharged</th><th>Days</th><th>Status</th></tr>
    </thead>
    <tbody>
      @forelse($patient->admissions as $adm)
        <tr>
          <td class="td-m">#{{ $adm->id }}</td>
          <td>{{ $adm->bed->ward }} / <strong>{{ $adm->bed->bed_number }}</strong></td>
          <td>{{ Str::limit($adm->diagnosis, 30) }}</td>
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
        <tr><td colspan="8"><div class="empty" style="padding:1.5rem"><div class="empty-txt">No admission records</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ── Admit Modal ───────────────────────────────────────────────────────── --}}
<div class="modal-bg" id="modal-admit" onclick="if(event.target===this)closeModal('modal-admit')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title">Admit Patient</div>
      <div class="modal-sub">Assign a bed and record admission details for {{ $patient->name }}</div>
    </div>
    <form method="POST" action="{{ route('patients.admit', $patient->id) }}">
      @csrf
      <div class="modal-body">
        <div class="form-grp">
          <label class="form-lbl">Select Available Bed *</label>
          <input type="hidden" name="bed_id" id="admit-bed-id" value="{{ old('bed_id') }}" required>
          <div class="bed-board" style="max-height:360px;overflow:auto;border:1px solid var(--line);border-radius:8px;background:#fff">
            @foreach($bedMap as $wardName => $wardBeds)
              @php
                $availableCount = $wardBeds->where('status','available')->count();
                $occupiedCount = $wardBeds->where('status','occupied')->count();
              @endphp
              <section class="ward-map" style="border:0;border-bottom:1px solid var(--line2);border-radius:0">
                <div class="ward-map-head">
                  <div>
                    <div class="ward-map-title">{{ $wardName }}</div>
                    <div class="ward-map-meta">{{ $availableCount }} available · {{ $occupiedCount }} occupied</div>
                  </div>
                </div>
                <div class="bed-grid">
                  @foreach($wardBeds as $bed)
                    @php $bedAdmission = $bed->currentAdmission; @endphp
                    <button
                      type="button"
                      class="bed-tile admit-bed-tile {{ $bed->status }} {{ old('bed_id') == $bed->id ? 'selected' : '' }}"
                      data-bed-id="{{ $bed->id }}"
                      data-bed-number="{{ $bed->bed_number }}"
                      data-ward="{{ $bed->ward }}"
                      data-type="{{ ucfirst($bed->bed_type) }}"
                      @if($bed->status !== 'available') disabled @endif
                    >
                      <span class="bed-no">{{ $bed->bed_number }}</span>
                      <span class="bed-type">{{ $bed->bed_type }}</span>
                      @if($bedAdmission)
                        <span class="bed-patient">{{ $bedAdmission->patient->name }}</span>
                      @endif
                    </button>
                  @endforeach
                </div>
              </section>
            @endforeach
          </div>
          <div class="bed-legend" style="padding:12px 0 0">
            <span class="legend-item"><span class="legend-dot available"></span>Available</span>
            <span class="legend-item"><span class="legend-dot occupied"></span>Occupied</span>
            <span class="legend-item"><span class="legend-dot maintenance"></span>Maintenance</span>
            <span class="legend-item"><span class="legend-dot selected"></span>Selected</span>
          </div>
          <div class="form-hint" id="admit-bed-summary">Select an available bed from the visual map.</div>
          @error('bed_id')<div class="form-err">{{ $message }}</div>@enderror
        </div>
        <div class="form-grp">
          <label class="form-lbl">Attending Doctor *</label>
          <select name="doctor" class="form-ctrl searchable-select" data-placeholder="Select Doctor" required>
            <option value="">Select Doctor</option>
            @foreach($doctors as $doctor)
              <option value="{{ $doctor->name }}" {{ old('doctor') == $doctor->name ? 'selected' : '' }}>{{ $doctor->name }} ({{ $doctor->department }})</option>
            @endforeach
          </select>
          @error('doctor')<div class="form-err">{{ $message }}</div>@enderror
        </div>
        <div class="form-grp">
          <label class="form-lbl">Diagnosis / Chief Complaint *</label>
          <textarea name="diagnosis" class="form-ctrl" placeholder="Primary diagnosis..." required>{{ old('diagnosis') }}</textarea>
          @error('diagnosis')<div class="form-err">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-ghost" onclick="closeModal('modal-admit')">Cancel</button>
        <button type="submit" class="btn btn-primary">Confirm Admission</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Discharge Modal ───────────────────────────────────────────────────── --}}
<div class="modal-bg" id="modal-discharge" onclick="if(event.target===this)closeModal('modal-discharge')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title">Discharge Patient</div>
      <div class="modal-sub">Complete discharge for {{ $patient->name }} and release the bed</div>
    </div>
    <form method="POST" action="{{ route('patients.discharge', $patient->id) }}">
      @csrf
      <div class="modal-body">
        @if($activeAdm)
          <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;margin-bottom:1rem;font-size:13px">
            <div style="color:var(--text2);margin-bottom:4px">Releasing bed:</div>
            <div style="color:#fff;font-weight:600">{{ $activeAdm->bed->ward }} - {{ $activeAdm->bed->bed_number }}</div>
          </div>
        @endif
        <div class="form-grp">
          <label class="form-lbl">Discharge Summary</label>
          <textarea name="summary" class="form-ctrl" rows="4"
                    placeholder="Condition at discharge, medications prescribed, follow-up instructions..."></textarea>
          <div class="form-hint">This will be saved in the patient's permanent record.</div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-ghost" onclick="closeModal('modal-discharge')">Cancel</button>
        <button type="submit" class="btn btn-danger" onclick="return confirm('Confirm discharge of {{ $patient->name }}?')">Confirm Discharge</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.querySelectorAll('.searchable-select').forEach((el) => {
  new TomSelect(el, {
    create: false,
    sortField: {
      field: "text",
      direction: "asc"
    }
  });
});

document.querySelectorAll('.admit-bed-tile.available').forEach((tile) => {
  tile.addEventListener('click', () => {
    document.querySelectorAll('.admit-bed-tile.selected').forEach((selected) => selected.classList.remove('selected'));
    tile.classList.add('selected');
    document.getElementById('admit-bed-id').value = tile.dataset.bedId;
    document.getElementById('admit-bed-summary').textContent = `${tile.dataset.ward} · Bed ${tile.dataset.bedNumber} (${tile.dataset.type}) selected.`;
  });
});
</script>
@endpush
