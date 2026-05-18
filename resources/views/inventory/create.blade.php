@extends('layouts.app')
@section('title','Add Inventory Item')
@section('page-title','Inventory Management')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Inventory Module</div>
    <div class="sh-title">Add New Item</div>
  </div>
  <a href="{{ route('inventory.index') }}" class="btn btn-ghost">← Back</a>
</div>

<div style="max-width:680px">
  <div class="card">
    <div class="card-head"><span class="card-title">Item Details</span></div>
    <div class="card-body">
      <form method="POST" action="{{ route('inventory.store') }}">
        @csrf

        <div class="form-grp">
          <label class="form-lbl">Item Name *</label>
          <input name="name" class="form-ctrl" value="{{ old('name') }}"
                 placeholder="e.g. Paracetamol 500mg, Surgical Gloves (L)" required>
          @error('name')<div class="form-err">{{ $message }}</div>@enderror
        </div>

        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Category *</label>
            <select name="category" class="form-ctrl" required>
              <option value="">— Select Category —</option>
              @foreach($categories as $c)
                <option value="{{ $c }}" {{ old('category') === $c ? 'selected' : '' }}>{{ $c }}</option>
              @endforeach
            </select>
            @error('category')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Unit *</label>
            <select name="unit" class="form-ctrl" required>
              <option value="">— Select Unit —</option>
              @foreach($units as $u)
                <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
              @endforeach
            </select>
            @error('unit')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Current Stock *</label>
            <input name="current_stock" type="number" min="0" class="form-ctrl"
                   value="{{ old('current_stock', 0) }}" required>
            @error('current_stock')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Reorder Level *</label>
            <input name="reorder_level" type="number" min="0" class="form-ctrl"
                   value="{{ old('reorder_level', 50) }}" required>
            @error('reorder_level')<div class="form-err">{{ $message }}</div>@enderror
            <div class="form-hint">Alert triggered when stock falls to or below this.</div>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Unit Price (₹) *</label>
            <input name="unit_price" type="number" step="0.01" min="0" class="form-ctrl"
                   value="{{ old('unit_price', '0.00') }}" required>
            @error('unit_price')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Expiry Date</label>
            <input name="expiry_date" type="date" class="form-ctrl" value="{{ old('expiry_date') }}">
            @error('expiry_date')<div class="form-err">{{ $message }}</div>@enderror
            <div class="form-hint">Leave blank for non-expirable items (PPE, equipment).</div>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Supplier</label>
            <input name="supplier" class="form-ctrl" value="{{ old('supplier') }}"
                   placeholder="Supplier / Manufacturer name">
          </div>
          <div class="form-grp">
            <label class="form-lbl">Batch Number</label>
            <input name="batch_number" class="form-ctrl" value="{{ old('batch_number') }}"
                   placeholder="e.g. BT-2024-001">
          </div>
        </div>

        <div style="display:flex;gap:8px;margin-top:.5rem">
          <button type="submit" class="btn btn-primary">Add to Inventory →</button>
          <a href="{{ route('inventory.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection