@extends('layouts.modern')

@section('content')
  <div class="tw-grid sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
    <div class="tw-card">
      <div class="tw-text-sm tw-text-slate-500">Today Visits</div>
      <div class="tw-text-2xl tw-font-semibold">34</div>
    </div>
    <div class="tw-card">
      <div class="tw-text-sm tw-text-slate-500">Patients</div>
      <div class="tw-text-2xl tw-font-semibold">1,294</div>
    </div>
    <div class="tw-card">
      <div class="tw-text-sm tw-text-slate-500">Pending Labs</div>
      <div class="tw-text-2xl tw-font-semibold">7</div>
    </div>
    <div class="tw-card">
      <div class="tw-text-sm tw-text-slate-500">Revenue (M)</div>
      <div class="tw-text-2xl tw-font-semibold">1.2</div>
    </div>
  </div>

  <div class="tw-card tw-mt-4">
    <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
      <h2 class="tw-text-lg tw-font-semibold">Latest Visits</h2>
      <a href="{{ route('visit.index') }}" class="tw-btn-primary">View all</a>
    </div>
    <div class="tw-overflow-x-auto">
      <table class="tw-w-full tw-text-sm">
        <thead>
          <tr class="tw-text-left tw-text-slate-500">
            <th class="tw-py-2">#</th>
            <th class="tw-py-2">Patient</th>
            <th class="tw-py-2">Date</th>
            <th class="tw-py-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:tw-bg-slate-50">
            <td class="tw-py-2">V-10231</td>
            <td class="tw-py-2">Somchai T.</td>
            <td class="tw-py-2">2025-08-15</td>
            <td class="tw-py-2"><span class="tw-badge-ok">done</span></td>
          </tr>
          <tr class="hover:tw-bg-slate-50">
            <td class="tw-py-2">V-10230</td>
            <td class="tw-py-2">Suda R.</td>
            <td class="tw-py-2">2025-08-15</td>
            <td class="tw-py-2"><span class="tw-badge-bad">pending</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection
