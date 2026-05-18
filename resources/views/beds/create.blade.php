@extends('layouts.app')
@section('title','Add Bed')
@section('page-title','Bed Management')

@section('content')
<div class="sh">
  <div><div class="sh-eye">Bed Module</div><div class="sh-title">Add New Bed</div></div>
  <a href="{{ route('beds.index') }}" class="btn btn-ghost">← Back</a>
</div>

<div style="max-width:520px">
  <div class="card">
    <div class="card-head"><span class="card-title">Bed Details</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('beds.store') }}">
        @csrf
        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Bed Number *</label>
            <input name="bed_number" class="form-ctrl" value="{{ old('bed_number') }}"
                   placeholder="e.g. A-07, ICU-03" required>
            @error('bed_number')<div class="form-err">{{ $message }}</div>@enderror
            <div class="form-hint">Must be unique across all wards.</div>
          </div>
          <div class="form-grp">
            <label class="form-lbl">Ward *</label>
            <select name="ward" class="form-ctrl" required>
              <option value="">— Select Ward —</option>
              @foreach($wards as $w)
                <option value="{{ $w }}" {{ old('ward') === $w ? 'selected' : '' }}>{{ $w }}</option>
              @endforeach
            </select>
            @error('ward')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="form-grp">
          <label class="form-lbl">Bed Type *</label>
          <select name="bed_type" class="form-ctrl" required>
            <option value="general" {{ old('bed_type','general') === 'general' ? 'selected' : '' }}>General</option>
            <option value="icu"     {{ old('bed_type') === 'icu'     ? 'selected' : '' }}>ICU</option>
            <option value="special" {{ old('bed_type') === 'special' ? 'selected' : '' }}>Special / Isolation</option>
          </select>
          @error('bed_type')<div class="form-err">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex;gap:8px;margin-top:.5rem">
          <button type="submit" class="btn btn-primary">Add Bed →</button>
          <a href="{{ route('beds.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection