@extends('layouts.app')
@section('title','Patients')
@section('page-title','Patient Registry')
 
@section('content')
<div class="sh">
  <div><div class="sh-eye">Module 3</div><div class="sh-title">All Patients</div></div>
  <div style="display:flex;gap:8px">
    <form method="GET">
      <div class="search-wrap" style="min-width:240px">
        <span>Search</span>
        <input name="search" value="{{ $search }}" placeholder="Search name, ID, phone...">
      </div>
    </form>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">+ Register Patient</a>
  </div>
</div>
 
{{-- Filter Tabs --}}
<div style="display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:1.5rem">
  @foreach([''=>'All Patients','admitted'=>'Currently Admitted','opd'=>'In OPD Queue'] as $val => $label)
    <a href="?status={{ $val }}" style="padding:10px 20px;font-size:13px;font-weight:{{ $status === $val ? '500' : '400' }};color:{{ $status === $val ? 'var(--teal2)' : 'var(--text2)' }};border-bottom:2px solid {{ $status === $val ? 'var(--teal2)' : 'transparent' }};text-decoration:none;transition:all .18s">{{ $label }}</a>
  @endforeach
</div>
 
<div class="card">
  <table class="tbl">
    <thead>
      <tr><th>Patient ID</th><th>Name</th><th>Age / Gender</th><th>Blood</th><th>Phone</th><th>Registered</th><th>Status</th><th></th></tr>
    </thead>
    <tbody>
      @forelse($patients as $p)
        @php
          $adm = $p->latestAdmission;
          $statusBadge = $adm && $adm->status === 'admitted'
            ? '<span class="badge b-green">Admitted</span>'
            : '<span class="badge b-blue">OPD</span>';
        @endphp
        <tr>
          <td class="td-m">{{ $p->patient_id }}</td>
          <td class="td-b">{{ $p->name }}</td>
          <td>{{ $p->age }}y / {{ ucfirst($p->gender) }}</td>
          <td><span class="badge b-red" style="font-size:11px">{{ $p->blood_group }}</span></td>
          <td class="td-m">{{ $p->phone }}</td>
          <td class="td-m">{{ $p->created_at->format('d M Y') }}</td>
          <td>{!! $statusBadge !!}</td>
          <td><a href="{{ route('patients.show', $p->id) }}" class="btn btn-ghost btn-sm">View</a></td>
        </tr>
      @empty
        <tr><td colspan="8"><div class="empty"><div class="empty-ico"></div><div class="empty-txt">No patients found</div></div></td></tr>
      @endforelse
    </tbody>
  </table>
  <div style="padding:1rem 1.5rem">{{ $patients->links() }}</div>
</div>
@endsection
