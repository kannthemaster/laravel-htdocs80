<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>{{ $title ?? config('app.name') }}</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  @stack('styles')
</head>
<body class="tw-bg-slate-50 tw-text-slate-800">
  <header class="tw-sticky tw-top-0 tw-z-30 tw-bg-white tw-shadow-sm">
    <div class="tw-max-w-7xl tw-mx-auto tw-flex tw-items-center tw-justify-between tw-px-4 tw-py-3">
      <div class="tw-font-semibold tw-text-lg">{{ config('app.name') }}</div>
      <div id="health-badge" class="tw-badge-ok">
        <span class="tw-w-2 tw-h-2 tw-rounded-full tw-bg-emerald-500"></span> healthy
      </div>
    </div>
  </header>

  <div class="tw-max-w-7xl tw-mx-auto tw-grid md:tw-grid-cols-[260px_1fr] tw-gap-4 tw-p-4">
    {{-- Sidebar --}}
    <aside class="tw-hidden md:tw-block">
      <nav class="tw-card tw-sticky tw-top-20">
        <ul class="tw-space-y-1">
          <li><a href="{{ url('/modern') }}" class="tw-block tw-rounded-lg tw-px-3 tw-py-2 hover:tw-bg-slate-100">Dashboard</a></li>
          <li><a href="{{ route('patient.index') }}" class="tw-block tw-rounded-lg tw-px-3 tw-py-2 hover:tw-bg-slate-100">Patients</a></li>
          <li><a href="{{ route('visit.index') }}" class="tw-block tw-rounded-lg tw-px-3 tw-py-2 hover:tw-bg-slate-100">Visits</a></li>
          <li><a href="{{ route('lab.index') }}" class="tw-block tw-rounded-lg tw-px-3 tw-py-2 hover:tw-bg-slate-100">Labs</a></li>
          <li><a href="{{ route('report.dashboard') }}" class="tw-block tw-rounded-lg tw-px-3 tw-py-2 hover:tw-bg-slate-100">Reports</a></li>
        </ul>
      </nav>
    </aside>

    {{-- Content --}}
    <main>
      @yield('content')
    </main>
  </div>

  <script>
    (async () => {
      try {
        const res = await fetch('{{ url('/healthz') }}', { cache: 'no-store' });
        const data = await res.json();
        const el = document.getElementById('health-badge');
        if (!data || data.database?.startsWith('error') || data.storage?.startsWith('error')) {
          el.className = 'tw-badge-bad';
          el.innerHTML = '<span class="tw-w-2 tw-h-2 tw-rounded-full tw-bg-rose-500"></span> degraded';
        }
      } catch (e) {
        const el = document.getElementById('health-badge');
        el.className = 'tw-badge-bad';
        el.innerHTML = '<span class="tw-w-2 tw-h-2 tw-rounded-full tw-bg-rose-500"></span> unknown';
      }
    })();
  </script>

  @stack('scripts')
</body>
</html>
