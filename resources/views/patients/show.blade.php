@extends('layouts.app')
@section('title', $patient->name)
@section('page-title','Patient Details')

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
        ['👤 Name',        $patient->name],
        ['🎂 Age / DOB',   $patient->age . ' years · ' . $patient->dob->format('d M Y')],
        ['⚧ Gender',       ucfirst($patient->gender)],
        ['🩸 Blood Group',  $patient->blood_group],
        ['📞 Phone',        $patient->phone],
        ['📧 Email',        $patient->email ?? 'Not provided'],
        ['🏠 Address',      $patient->address ?? 'Not provided'],
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
          <div style="font-size:13px;color:#fff;font-weight:600;margin-bottom:4px">{{ $activeAdm->bed->ward }} — Bed {{ $activeAdm->bed->bed_number }}</div>
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
          <select name="bed_id" class="form-ctrl" required>
            <option value="">— Select Bed —</option>
            @foreach($availBeds->groupBy('ward') as $wardName => $wardBeds)
              <optgroup label="{{ $wardName }}">
                @foreach($wardBeds as $bed)
                  <option value="{{ $bed->id }}">{{ $bed->bed_number }} ({{ $bed->bed_type }})</option>
                @endforeach
              </optgroup>
            @endforeach
          </select>
          @error('bed_id')<div class="form-err">{{ $message }}</div>@enderror
        </div>
        <div class="form-grp">
          <label class="form-lbl">Attending Doctor *</label>
          <input name="doctor" class="form-ctrl" value="{{ old('doctor') }}"
                 placeholder="Dr. Full Name" required>
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
        <button type="submit" class="btn btn-primary">Confirm Admission →</button>
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
            <div style="color:#fff;font-weight:600">{{ $activeAdm->bed->ward }} — {{ $activeAdm->bed->bed_number }}</div>
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