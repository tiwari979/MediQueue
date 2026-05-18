@extends('layouts.app')
@section('title','Edit Patient')
@section('page-title','Edit Patient Record')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Patient Module</div>
    <div class="sh-title">Edit — {{ $patient->name }}</div>
    <div class="sh-desc">{{ $patient->patient_id }}</div>
  </div>
  <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-ghost">← Back to Profile</a>
</div>

<div style="max-width:720px">
  <div class="card">
    <div class="card-head"><span class="card-title">Update Patient Information</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('patients.update', $patient->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Full Name *</label>
            <input name="name" class="form-ctrl" value="{{ old('name', $patient->name) }}" required>
            @error('name')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Patient ID</label>
            <input class="form-ctrl" value="{{ $patient->patient_id }}" disabled style="opacity:.5">
            <div class="form-hint">Auto-generated. Cannot be changed.</div>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Phone Number *</label>
            <input name="phone" class="form-ctrl" value="{{ old('phone', $patient->phone) }}" required>
            @error('phone')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Blood Group *</label>
            <select name="blood_group" class="form-ctrl" required>
              @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                <option value="{{ $bg }}" {{ old('blood_group', $patient->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
              @endforeach
            </select>
            @error('blood_group')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-grp">
          <label class="form-lbl">Address</label>
          <textarea name="address" class="form-ctrl">{{ old('address', $patient->address) }}</textarea>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:1.1rem;margin-top:.5rem">
          <div style="font-size:11px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:1rem">Emergency Contact</div>
          <div class="form-grid">
            <div class="form-grp">
              <label class="form-lbl">Contact Name</label>
              <input name="emergency_contact_name" class="form-ctrl"
                     value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                     placeholder="Name">
            </div>
            <div class="form-grp">
              <label class="form-lbl">Contact Phone</label>
              <input name="emergency_contact_phone" class="form-ctrl"
                     value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                     placeholder="Phone number">
            </div>
          </div>
        </div>

        <div style="display:flex;gap:8px;margin-top:.5rem">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection