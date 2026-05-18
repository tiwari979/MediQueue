@extends('layouts.app')
@section('title','Reports')
@section('page-title','Reports & Analytics')

@section('content')
<div class="sh">
  <div>
    <div class="sh-eye">Analytics</div>
    <div class="sh-title">System Reports</div>
    <div class="sh-desc">Hospital-wide performance metrics and operational analytics</div>
  </div>
</div>

{{-- KPI Row --}}
<div class="stats-row">
  <div class="stat-card sc-teal">
    <span class="sc-ico">👥</span>
    <div class="sc-lbl">Total Patients</div>
    <div class="sc-val">{{ number_format($totalPatients) }}</div>
    <div class="sc-sub">All time registrations</div>
  </div>
  <div class="stat-card sc-blue">
    <span class="sc-ico">🛏️</span>
    <div class="sc-lbl">Total Admissions</div>
    <div class="sc-val">{{ number_format($totalAdmissions) }}</div>
    <div class="sc-sub">Avg stay: {{ number_format($avgStayDays, 1) }} days</div>
  </div>
  <div class="stat-card sc-amber">
    <span class="sc-ico">📊</span>
    <div class="sc-lbl">Bed Utilization</div>
    <div class="sc-val">{{ $bedUtilization }}<span style="font-size:1rem">%</span></div>
    <div class="sc-sub">Current occupancy rate</div>
  </div>
  <div class="stat-card sc-red">
    <span class="sc-ico">💊</span>
    <div class="sc-lbl">Dispensed Today</div>
    <div class="sc-val">{{ number_format($dispensedToday) }}</div>
    <div class="sc-sub">{{ $lowStockItems }} items low on stock</div>
  </div>
</div>

<div class="three-col">
  <a href="{{ route('reports.opd') }}" class="card" style="margin-bottom:0;text-decoration:none;display:block;transition:border-color .2s" onmouseover="this.style.borderColor='var(--teal)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="padding:1.5rem;text-align:center">
      <div style="font-size:36px;margin-bottom:1rem">🔄</div>
      <div style="font-family:'Crimson Pro',serif;font-size:1.2rem;font-weight:600;color:#fff;margin-bottom:6px">OPD Queue Report</div>
      <div style="font-size:12.5px;color:var(--text2);line-height:1.5">Daily token analysis, department-wise footfall, average wait times and queue efficiency metrics.</div>
      <div style="margin-top:1.25rem;font-size:12px;color:var(--teal2);font-weight:500">View Report →</div>
    </div>
  </a>
  <a href="{{ route('reports.beds') }}" class="card" style="margin-bottom:0;text-decoration:none;display:block;transition:border-color .2s" onmouseover="this.style.borderColor='var(--teal)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="padding:1.5rem;text-align:center">
      <div style="font-size:36px;margin-bottom:1rem">🛏️</div>
      <div style="font-family:'Crimson Pro',serif;font-size:1.2rem;font-weight:600;color:#fff;margin-bottom:6px">Bed & Admission Report</div>
      <div style="font-size:12.5px;color:var(--text2);line-height:1.5">Ward occupancy rates, admission history, length of stay analysis, and bed turnover statistics.</div>
      <div style="margin-top:1.25rem;font-size:12px;color:var(--teal2);font-weight:500">View Report →</div>
    </div>
  </a>
  <a href="{{ route('reports.inventory') }}" class="card" style="margin-bottom:0;text-decoration:none;display:block;transition:border-color .2s" onmouseover="this.style.borderColor='var(--teal)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="padding:1.5rem;text-align:center">
      <div style="font-size:36px;margin-bottom:1rem">💊</div>
      <div style="font-family:'Crimson Pro',serif;font-size:1.2rem;font-weight:600;color:#fff;margin-bottom:6px">Inventory Report</div>
      <div style="font-size:12.5px;color:var(--text2);line-height:1.5">Dispensing logs, category-wise usage analysis, low stock alerts, and expiry tracking.</div>
      <div style="margin-top:1.25rem;font-size:12px;color:var(--teal2);font-weight:500">View Report →</div>
    </div>
  </a>
</div>
@endsection