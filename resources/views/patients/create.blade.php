@extends('layouts.app')
@section('title','Register Patient')
@section('page-title','Register New Patient')
 
@section('content')
<div class="sh">
  <div><div class="sh-eye">Patient Module</div><div class="sh-title">New Patient Registration</div></div>
  <a href="{{ route('patients.index') }}" class="btn btn-ghost">← Back</a>
</div>
<div style="max-width:720px">
  <div class="card">
    <div class="card-head"><span class="card-title">Personal Information</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('patients.store') }}">
        @csrf
        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Full Name *</label>
            <input name="name" class="form-ctrl {{ $errors->has('name') ? 'border-red' : '' }}" value="{{ old('name') }}" placeholder="Patient's full name" required>
            @error('name')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Date of Birth *</label>
            <input name="dob" type="date" class="form-ctrl" value="{{ old('dob') }}" required>
            @error('dob')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Gender *</label>
            <select name="gender" class="form-ctrl" required>
              <option value="">— Select —</option>
              <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
              <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Blood Group *</label>
            <select name="blood_group" class="form-ctrl" required>
              <option value="">— Select —</option>
              @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
              @endforeach
            </select>
            @error('blood_group')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Phone Number *</label>
            <input name="phone" class="form-ctrl" value="{{ old('phone') }}" placeholder="10-digit mobile number" required>
            @error('phone')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Email Address</label>
            <input name="email" type="email" class="form-ctrl" value="{{ old('email') }}" placeholder="Optional">
            @error('email')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="form-grp">
          <label class="form-lbl">Address</label>
          <textarea name="address" class="form-ctrl" placeholder="Full residential address">{{ old('address') }}</textarea>
        </div>
        <div style="border-top:1px solid var(--border);margin:1rem 0;padding-top:1rem">
          <div style="font-size:12px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:1rem">Emergency Contact</div>
          <div class="form-grid">
            <div class="form-grp">
              <label class="form-lbl">Contact Name</label>
              <input name="emergency_contact_name" class="form-ctrl" value="{{ old('emergency_contact_name') }}" placeholder="Name">
            </div>
            <div class="form-grp">
              <label class="form-lbl">Contact Phone</label>
              <input name="emergency_contact_phone" class="form-ctrl" value="{{ old('emergency_contact_phone') }}" placeholder="Phone number">
            </div>
          </div>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary">Register Patient →</button>
          <a href="{{ route('patients.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection