@extends('layouts.app')
@section('title','Inventory')
@section('page-title','Inventory Management')
 
@section('content')
<div class="sh">
  <div><div class="sh-eye">Module 4</div><div class="sh-title">Medicines & Consumables</div></div>
  <a href="{{ route('inventory.create') }}" class="btn btn-primary">+ Add Item</a>
</div>
 
<div class="stats-row">
  <div class="stat-card sc-teal"><div class="sc-lbl">Total Items</div><div class="sc-val">{{ \App\Models\Inventory::count() }}</div><div class="sc-sub">Active stock items</div></div>
  <div class="stat-card sc-red"><div class="sc-lbl">Low Stock</div><div class="sc-val">{{ $lowCount }}</div><div class="sc-sub">Needs reorder</div></div>
  <div class="stat-card sc-amber"><div class="sc-lbl">Expiring Soon</div><div class="sc-val">{{ $expCount }}</div><div class="sc-sub">Within 30 days</div></div>
  <div class="stat-card sc-blue"><div class="sc-lbl">Categories</div><div class="sc-val">{{ count($categories) }}</div><div class="sc-sub">Item categories</div></div>
</div>
 
<div class="card">
  <div class="card-head">
    <span class="card-title">Stock Register</span>
    <form method="GET" style="display:flex;gap:8px">
      <div class="search-wrap" style="min-width:200px">
        <span>🔍</span><input name="search" value="{{ $search }}" placeholder="Search item...">
      </div>
      <select name="category" class="form-ctrl" style="width:160px;padding:6px 10px;font-size:12px" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $c)
          <option value="{{ $c }}" {{ $category === $c ? 'selected' : '' }}>{{ $c }}</option>
        @endforeach
      </select>
      <select name="alert" class="form-ctrl" style="width:140px;padding:6px 10px;font-size:12px" onchange="this.form.submit()">
        <option value="">All Stock</option>
        <option value="low" {{ $alert === 'low' ? 'selected' : '' }}>Low Stock</option>
        <option value="expiring" {{ $alert === 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
      </select>
      <button class="btn btn-ghost btn-sm" type="submit">Filter</button>
    </form>
  </div>
 
  {{-- Header --}}
  <div style="display:grid;grid-template-columns:2fr 90px 130px 80px 90px;padding:9px 1.5rem;border-bottom:1px solid var(--border);background:rgba(255,255,255,.015)">
    <div style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-weight:600">Item</div>
    <div style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-weight:600">Stock</div>
    <div style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-weight:600">Level</div>
    <div style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-weight:600">Expiry</div>
    <div style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;font-weight:600">Status</div>
  </div>
 
  @forelse($items as $item)
    @php
      $pct = $item->stock_pct;
      $color = match($item->stock_status) {
        'out'   => 'var(--red)',
        'low'   => 'var(--red)',
        'watch' => 'var(--amber)',
        default => 'var(--teal)',
      };
      $badge = match($item->stock_status) {
        'out'   => '<span class="badge b-red">Out</span>',
        'low'   => '<span class="badge b-red">Low</span>',
        'watch' => '<span class="badge b-amber">Watch</span>',
        default => '<span class="badge b-green">OK</span>',
      };
    @endphp
    <div style="display:grid;grid-template-columns:2fr 90px 130px 80px 90px;padding:12px 1.5rem;border-bottom:1px solid rgba(255,255,255,.03);align-items:center;gap:12px;font-size:12.5px" class="inv-hover">
      <div>
        <div style="font-weight:600;color:#fff">{{ $item->name }}</div>
        <div style="font-size:11px;color:var(--text2)">{{ $item->category }} · {{ $item->unit }}</div>
      </div>
      <div style="font-weight:600;color:{{ $color }}">{{ number_format($item->current_stock) }}</div>
      <div>
        <div style="height:5px;border-radius:3px;background:rgba(255,255,255,.07);overflow:hidden;margin-bottom:4px">
          <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:3px"></div>
        </div>
        <div style="font-size:10px;color:var(--text3)">Reorder at {{ $item->reorder_level }}</div>
      </div>
      <div style="font-size:12px;color:var(--text2)">{{ $item->expiry_date ? $item->expiry_date->format('M Y') : 'N/A' }}</div>
      <div style="display:flex;gap:6px;align-items:center">
        {!! $badge !!}
        <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-ghost btn-sm">Edit</a>
      </div>
    </div>
  @empty
    <div class="empty"><div class="empty-ico">💊</div><div class="empty-txt">No inventory items found</div></div>
  @endforelse
 
  <div style="padding:1rem 1.5rem">{{ $items->links() }}</div>
</div>
@endsection