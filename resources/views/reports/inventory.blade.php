@extends('layouts.app')
@section('title','Inventory Report')
@section('page-title','Inventory Report')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Reports / Inventory</div>
    <div class="sh-title">Stock Usage & Dispensing Log</div>
  </div>
  <a href="{{ route('reports.index') }}" class="btn btn-ghost">Back</a>
</div>

<div class="two-col">
  {{-- Category-wise Dispensing --}}
  <div class="card">
    <div class="card-head"><span class="card-title">Dispensed by Category</span></div>
    <div style="padding:1.25rem">
      @forelse($dispensedByCategory as $row)
        @php $max = $dispensedByCategory->max('total'); $pct = $max > 0 ? round(($row->total / $max) * 100) : 0; @endphp
        <div style="margin-bottom:1rem">
          <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:5px">
            <span style="color:#fff;font-weight:500">{{ $row->category }}</span>
            <span style="color:var(--teal2);font-weight:600">{{ number_format($row->total) }} units</span>
          </div>
          <div class="prog">
            <div class="prog-fill" style="width:{{ $pct }}%;background:var(--teal)"></div>
          </div>
        </div>
      @empty
        <div class="empty"><div class="empty-txt">No dispensing records</div></div>
      @endforelse
    </div>
  </div>

  {{-- Low Stock Alert Table --}}
  <div class="card">
    <div class="card-head"><span class="card-title">Low Stock Alert</span></div>
    <table class="tbl">
      <thead><tr><th>Item</th><th>Stock</th><th>Reorder At</th><th>Status</th></tr></thead>
      <tbody>
        @forelse(\App\Models\Inventory::whereRaw('current_stock <= reorder_level')->get() as $item)
          <tr>
            <td class="td-b">{{ $item->name }}<div class="td-m">{{ $item->category }}</div></td>
            <td style="color:var(--red);font-weight:600">{{ number_format($item->current_stock) }}</td>
            <td class="td-m">{{ $item->reorder_level }}</td>
            <td>
              @if($item->current_stock === 0)
                <span class="badge b-red">Out</span>
              @else
                <span class="badge b-amber">Low</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="4"><div class="empty" style="padding:1.5rem"><div class="empty-txt">All items above reorder level</div></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Full Dispensing Log --}}
<div class="card">
  <div class="card-head"><span class="card-title">Dispensing & Activity Log</span></div>
  <table class="tbl">
    <thead>
      <tr><th>Time</th><th>Item</th><th>Action</th><th>Qty</th><th>Notes</th><th>Done By</th></tr>
    </thead>
    <tbody>
      @forelse($logs as $log)
        <tr>
          <td class="td-m">{{ $log->created_at->format('d M, h:i A') }}</td>
          <td class="td-b">{{ $log->inventory->name ?? '—' }}</td>
          <td>
            @php
              $actionBadge = match($log->action) {
                'dispensed'  => '<span class="badge b-amber">Dispensed</span>',
                'restocked'  => '<span class="badge b-green">Restocked</span>',
                'added'      => '<span class="badge b-blue">Added</span>',
                'adjusted'   => '<span class="badge b-teal">Adjusted</span>',
                'expired'    => '<span class="badge b-red">Expired</span>',
                default      => '<span class="badge b-blue">' . ucfirst($log->action) . '</span>',
              };
            @endphp
            {!! $actionBadge !!}
          </td>
          <td style="font-weight:600">{{ $log->quantity }}</td>
          <td class="td-m">{{ Str::limit($log->notes, 35) ?? '—' }}</td>
          <td class="td-m">{{ $log->user?->name ?? 'System' }}</td>
        </tr>
      @empty
        <tr><td colspan="6"><div class="empty"><div class="empty-txt">No activity logs found</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div style="padding:1rem 1.5rem">{{ $logs->links() }}</div>
</div>
@endsection
