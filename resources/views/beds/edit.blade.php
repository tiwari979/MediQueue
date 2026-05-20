@extends('layouts.app')
@section('title','Edit Bed')
@section('page-title','Bed Management')

@section('content')
<div class="sh">
  <div><div class="sh-eye">Bed Module</div><div class="sh-title">Edit Bed - {{ $bed->bed_number }}</div></div>
  <a href="{{ route('beds.index') }}" class="btn btn-ghost">Back</a>
</div>

<div style="max-width:520px">
  <div class="card">
    <div class="card-head">
      <span class="card-title">Bed Details</span>
      {!! $bed->status_badge !!}
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('beds.update', $bed->id) }}">
        @csrf
        @method('PUT')
        <div class="form-grp">
          <label class="form-lbl">Bed Number</label>
          <input class="form-ctrl" value="{{ $bed->bed_number }}" disabled style="opacity:.5">
          <div class="form-hint">Bed number cannot be changed after creation.</div>
        </div>
        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Ward *</label>
            <select name="ward" class="form-ctrl" required>
              @foreach($wards as $w)
                <option value="{{ $w }}" {{ $bed->ward === $w ? 'selected' : '' }}>{{ $w }}</option>
              @endforeach
            </select>
            @error('ward')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Bed Type *</label>
            <select name="bed_type" class="form-ctrl" required>
              <option value="general" {{ $bed->bed_type === 'general' ? 'selected' : '' }}>General</option>
              <option value="icu"     {{ $bed->bed_type === 'icu'     ? 'selected' : '' }}>ICU</option>
              <option value="special" {{ $bed->bed_type === 'special' ? 'selected' : '' }}>Special / Isolation</option>
            </select>
          </div>
        </div>
        <div class="form-grp">
          <label class="form-lbl">Status *</label>
          <select name="status" class="form-ctrl" required {{ $bed->status === 'occupied' ? 'disabled' : '' }}>
            <option value="available"   {{ $bed->status === 'available'   ? 'selected' : '' }}>Available</option>
            <option value="maintenance" {{ $bed->status === 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
          </select>
          @if($bed->status === 'occupied')
            <input type="hidden" name="status" value="occupied">
            <div class="form-hint" style="color:var(--amber)">Cannot change status of an occupied bed. Discharge patient first.</div>
          @endif
          @error('status')<div class="form-err">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex;gap:8px;margin-top:.5rem">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <a href="{{ route('beds.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
