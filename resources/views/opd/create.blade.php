@extends('layouts.app')
@section('title','Issue Token')
@section('page-title','Issue OPD Token')
 
@section('content')
<div class="sh">
  <div><div class="sh-eye">OPD Module</div><div class="sh-title">Issue New Token</div></div>
  <a href="{{ route('opd.index') }}" class="btn btn-ghost">Back</a>
</div>
 
<div style="max-width:640px">
  <div class="card">
    <div class="card-head"><span class="card-title">Token Details</span><span class="td-m">Next Token: <strong style="color:var(--teal2)">T-{{ str_pad($nextToken,3,'0',STR_PAD_LEFT) }}</strong></span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('opd.store') }}">
        @csrf
        <div class="form-grp">
          <label class="form-lbl">Patient *</label>
          <select name="patient_id" class="form-ctrl" required>
            <option value="">Select Patient</option>
            @foreach($patients as $p)
              <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                {{ $p->name }} ({{ $p->patient_id }})
              </option>
            @endforeach
          </select>
          @error('patient_id')<div class="form-err">{{ $message }}</div>@enderror
          <div class="form-hint">Patient must be registered first. <a href="{{ route('patients.create') }}" style="color:var(--teal2)">Register new</a></div>
        </div>
 
        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Department *</label>
            <select name="department" class="form-ctrl" required>
              <option value="">Select</option>
              @foreach($departments as $d)
                <option value="{{ $d }}" {{ old('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
              @endforeach
            </select>
            @error('department')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Priority *</label>
            <select name="priority" class="form-ctrl" required>
              <option value="normal" {{ old('priority','normal') === 'normal' ? 'selected' : '' }}>Regular</option>
              <option value="senior" {{ old('priority') === 'senior' ? 'selected' : '' }}>Senior Citizen</option>
              <option value="emergency" {{ old('priority') === 'emergency' ? 'selected' : '' }}>Emergency</option>
            </select>
            @error('priority')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>
 
        <div class="form-grp">
          <label class="form-lbl">Symptoms / Chief Complaint</label>
          <textarea name="symptoms" class="form-ctrl" placeholder="Describe main symptoms or reason for visit...">{{ old('symptoms') }}</textarea>
        </div>
 
        <div style="padding:.85rem 1rem;background:var(--teal-bg);border:1px solid rgba(0,180,160,.2);border-radius:var(--radius);font-size:12.5px;color:var(--text2);margin-bottom:1.25rem">
          <strong style="color:var(--teal2)">Queue Logic (M/M/c Model):</strong> Estimated wait time is calculated based on current queue length, number of doctors on duty, and patient priority.
        </div>
 
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary">Issue Token</button>
          <a href="{{ route('opd.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
