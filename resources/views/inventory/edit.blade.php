@extends('layouts.app')
@section('title','Edit Inventory Item')
@section('page-title','Inventory Management')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Inventory Module</div>
    <div class="sh-title">Edit - {{ $item->name }}</div>
    <div class="sh-desc">{{ $item->category }} · {{ $item->unit }}</div>
  </div>
  <div style="display:flex;gap:8px">
    <button class="btn btn-primary" onclick="openModal('modal-dispense')">Dispense</button>
    <a href="{{ route('inventory.index') }}" class="btn btn-ghost">Back</a>
  </div>
</div>

<div class="two-col">
  {{-- Edit Form --}}
  <div class="card" style="margin-bottom:0">
    <div class="card-head">
      <span class="card-title">Update Stock Details</span>
      @php
        $badge = match($item->stock_status) {
          'out'   => '<span class="badge b-red">Out of Stock</span>',
          'low'   => '<span class="badge b-red">Low Stock</span>',
          'watch' => '<span class="badge b-amber">Watch</span>',
          default => '<span class="badge b-green">OK</span>',
        };
      @endphp
      {!! $badge !!}
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('inventory.update', $item->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grp">
          <label class="form-lbl">Item Name</label>
          <input class="form-ctrl" value="{{ $item->name }}" disabled style="opacity:.5">
        </div>

        <div class="form-grid">
          <div class="form-grp">
            <label class="form-lbl">Current Stock *</label>
            <input name="current_stock" type="number" min="0" class="form-ctrl"
                   value="{{ old('current_stock', $item->current_stock) }}" required>
            @error('current_stock')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Reorder Level *</label>
            <input name="reorder_level" type="number" min="0" class="form-ctrl"
                   value="{{ old('reorder_level', $item->reorder_level) }}" required>
            @error('reorder_level')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-grid">
          <div class="form-grp">
          <label class="form-lbl">Unit Price (Rs.) *</label>
            <input name="unit_price" type="number" step="0.01" min="0" class="form-ctrl"
                   value="{{ old('unit_price', $item->unit_price) }}" required>
            @error('unit_price')<div class="form-err">{{ $message }}</div>@enderror
          </div>
          <div class="form-grp">
            <label class="form-lbl">Expiry Date</label>
            <input name="expiry_date" type="date" class="form-ctrl"
                   value="{{ old('expiry_date', $item->expiry_date?->format('Y-m-d')) }}">
            @error('expiry_date')<div class="form-err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-grp">
          <label class="form-lbl">Supplier</label>
          <input name="supplier" class="form-ctrl"
                 value="{{ old('supplier', $item->supplier) }}" placeholder="Supplier name">
        </div>

        <div class="form-grp">
          <label class="form-lbl">Update Notes</label>
          <input name="notes" class="form-ctrl" placeholder="Reason for stock adjustment (optional)">
        </div>

        <div style="display:flex;gap:8px;margin-top:.5rem">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <form method="POST" action="{{ route('inventory.destroy', $item->id) }}"
                style="display:inline" onsubmit="return confirm('Delete this item permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Item</button>
          </form>
        </div>
      </form>
    </div>
  </div>

  {{-- Info Panel --}}
  <div style="display:flex;flex-direction:column;gap:14px">
    <div class="card" style="margin-bottom:0">
      <div class="card-head"><span class="card-title">Stock Overview</span></div>
      <div style="padding:1.25rem">
        <div style="text-align:center;margin-bottom:1.25rem">
          <div style="font-family:'Crimson Pro',serif;font-size:3rem;font-weight:600;line-height:1;color:{{ $item->isLowStock() ? '#F58585' : 'var(--teal2)' }}">
            {{ number_format($item->current_stock) }}
          </div>
          <div style="font-size:12px;color:var(--text2);margin-top:4px">{{ $item->unit }}(s) in stock</div>
        </div>

        <div class="prog" style="height:8px;margin-bottom:8px">
          <div class="prog-fill" style="width:{{ $item->stock_pct }}%;background:{{ $item->isLowStock() ? 'var(--red)' : 'var(--teal)' }}"></div>
        </div>

        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text3);margin-bottom:1.25rem">
          <span>0</span>
          <span>Reorder: {{ $item->reorder_level }}</span>
          <span>{{ $item->reorder_level * 5 }}+</span>
        </div>

        @foreach([
          ['Category',     $item->category],
          ['Unit',         $item->unit],
          ['Unit Price',   'Rs. ' . number_format($item->unit_price, 2)],
          ['Batch No.',    $item->batch_number ?? '—'],
          ['Supplier',     $item->supplier ?? '—'],
          ['Expiry',       $item->expiry_date ? $item->expiry_date->format('d M Y') : 'N/A'],
        ] as [$label, $value])
          <div style="display:flex;justify-content:space-between;font-size:12.5px;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.04)">
            <span style="color:var(--text2)">{{ $label }}</span>
            <span style="color:#fff;font-weight:500">{{ $value }}</span>
          </div>
        @endforeach

        @if($item->isExpiringSoon())
          <div style="margin-top:1rem;padding:.75rem;background:var(--amber-bg);border:1px solid rgba(244,163,0,.2);border-radius:var(--radius);font-size:12px;color:var(--amber)">
            This item expires within 30 days. Plan for disposal or return.
          </div>
        @endif
        @if($item->isLowStock())
          <div style="margin-top:.5rem;padding:.75rem;background:var(--red-bg);border:1px solid rgba(232,59,59,.2);border-radius:var(--radius);font-size:12px;color:var(--red)">
            Stock below reorder level. Raise purchase order immediately.
          </div>
        @endif
      </div>
    </div>

    {{-- Recent Logs --}}
    <div class="card" style="margin-bottom:0">
      <div class="card-head"><span class="card-title">Recent Activity</span></div>
      @forelse($item->logs->take(5) as $log)
        <div style="display:flex;gap:10px;padding:10px 1.25rem;border-bottom:1px solid rgba(255,255,255,.03);font-size:12.5px">
          <span style="font-size:16px">
            {{ match($log->action) { 'dispensed'=>'Dispensed', 'restocked'=>'Restocked', 'added'=>'Added', default=>'Log' } }}
          </span>
          <div style="flex:1">
            <div style="color:#fff;font-weight:500;text-transform:capitalize">{{ $log->action }}</div>
            <div style="color:var(--text2)">{{ $log->quantity }} {{ $item->unit }}(s) · {{ $log->notes }}</div>
            <div style="font-size:11px;color:var(--text3);margin-top:2px">{{ $log->created_at->diffForHumans() }}</div>
          </div>
        </div>
      @empty
        <div class="empty" style="padding:1.5rem"><div class="empty-txt">No activity yet</div></div>
      @endforelse
    </div>
  </div>
</div>

{{-- ── Dispense Modal ────────────────────────────────────────────────────── --}}
<div class="modal-bg" id="modal-dispense" onclick="if(event.target===this)closeModal('modal-dispense')">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title">Dispense Item</div>
      <div class="modal-sub">Issue {{ $item->name }} from inventory</div>
    </div>
    <form method="POST" action="{{ route('inventory.dispense', $item->id) }}">
      @csrf
      <div class="modal-body">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;margin-bottom:1.25rem;display:flex;justify-content:space-between;align-items:center">
          <span style="font-size:13px;color:var(--text2)">Available stock</span>
          <span style="font-family:'Crimson Pro',serif;font-size:1.6rem;font-weight:600;color:var(--teal2)">{{ number_format($item->current_stock) }} <span style="font-size:.9rem;font-weight:400">{{ $item->unit }}(s)</span></span>
        </div>
        <div class="form-grp">
          <label class="form-lbl">Quantity to Dispense *</label>
          <input name="quantity" type="number" min="1" max="{{ $item->current_stock }}"
                 class="form-ctrl" placeholder="Enter quantity" required>
          @error('quantity')<div class="form-err">{{ $message }}</div>@enderror
        </div>
        <div class="form-grp">
          <label class="form-lbl">Notes / Reason</label>
          <input name="notes" class="form-ctrl" placeholder="Patient name, ward, purpose...">
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-ghost" onclick="closeModal('modal-dispense')">Cancel</button>
        <button type="submit" class="btn btn-primary">Confirm Dispense</button>
      </div>
    </form>
  </div>
</div>
@endsection
