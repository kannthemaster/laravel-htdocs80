<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
<!-- DataTable -->
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Libre+Barcode+128&family=Noto+Sans+SC:wght@100..900&family=Noto+Sans+Thai:wght@100..900&family=Sriracha&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <!-- <link href="/css/jquery.datetimepicker.min.css" rel="stylesheet"> -->
    <link href="/css/datepicker.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css" rel="stylesheet">
     <link href="https://cdn.datatables.net/1.13.7/css/dataTables.uikit.min.css" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    <ul class="navbar-nav me-auto fixed-top navbar-light bg-light">
                        @auth
                            @hasrole('Registrar')
                                <li class="flex-sm-fill text-sm-center nav-link active">
                                    <font size="4"><a class="nav-link active" aria-current="page"
                                        href="{{ route('patient.index') }}">ลงทะเบียน #1</a></font>
                                </li>
                            @endhasrole
                            @hasrole('Doctor')
                                <li class="flex-sm-fill text-sm-center nav-link active btn btn-primary">
                                    <a class="nav-link active" aria-current="page"
                                        href="{{ route('visit.room', ['status' => 2]) }}"><font size="4" color="white">ห้องตรวจ #2 (<font color="yellow" size="5"><b>{{ App\Models\Visit::count(2) }}</b></font>) ราย</font></a>
                                </li>
                            @endhasrole
                            @hasrole('Doctor')
                                <li class="flex-sm-fill text-sm-center nav-link active btn" style="background-color: #F7CCFF; border-color: #F7CCFF;">
                                    <a class="nav-link active" aria-current="page"
                                        href="{{ route('visit.room', ['status' => 8]) }}"><font size="4" color="black">ห้องตรวจ #8 (<font color="red" size="5"><b>{{ App\Models\Visit::count(8) }}</b></font>) ราย</font></a>
                                </li>
                            @endhasrole
                            @hasrole('Nurse')
                                <li class="flex-sm-fill text-sm-center nav-link active btn btn-info">
                                    <a class="nav-link active" aria-current="page"
                                        href="{{ route('visit.room', ['status' => 3]) }}"><font size="4"color="black">ห้องเจาะเลือด/ฉีดยา #4 (<font color="yellow" size="5"><b>{{ App\Models\Visit::count(3) }}</b></font>) ราย</font></a>
                                </li>
                            @endhasrole
                            @hasrole('Lab')
                                <li class="flex-sm-fill text-sm-center nav-link active btn">
                                    <a class="nav-link active" aria-current="page"
                                        href="{{ route('lab.index') }}"><font size="4"color="black">ห้อง Lab</font></a>
                                </li>
                            @endhasrole
                            <!-- @hasrole('Pharmacy')
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page"
                                        href="{{ route('visit.room', ['status' => 5]) }}">ห้องยา</a>
                                </li>
                            @endhasrole -->


                            <li class="flex-sm-fill text-sm-center nav-link active">
                                <font size="4"><a class="nav-link active" aria-current="page"
                                    href="{{ route('visit.appointment') }}">นัดหมาย</a></font>
                            </li>

                            <li class="flex-sm-fill text-sm-center nav-link active">
                                <font size="4"><a class="nav-link active" aria-current="page"
                                    href="{{ route('visit.index') }}">ค้นหา</a></font>
                            </li>

                            <li class="flex-sm-fill text-sm-center nav-link active">
                                <font size="4"><a class="nav-link active" aria-current="page"
                                    href="{{ route('medicine.index', ['status' => 5]) }}">ยา</a></font>
                            </li>
                            <li class="flex-sm-fill text-sm-center nav-link active">
    <font size="4">
        <a class="nav-link active" aria-current="page" href="{{ route('report.dashboard') }}">รายงาน</a>
    </font>
</li>
                        <li class="flex-sm-fill text-sm-center nav-link active">
                            <font size="4">
    <a class="nav-link" href="{{ route('report.yearly') }}">แดชบอร์ดรายปี</a>
    </font>
</li>


                        @endauth
                        <li class="flex-sm-fill text-sm-center nav-link active">
                                <font size="4"><a class="nav-link active" aria-current="page"
                                    href="#" style="color:red;"><div id="time"></div></a></font>
                            </li>
                            @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                    @role('Admin')
                                        <li><a class="dropdown-item" href="/backend">Backend</a></li>
                                    @endrole
                                    <li><a class="dropdown-item" href="/home">home</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                    @role('Admin')
                                        <li><a class="dropdown-item" href="/backend">Backend</a></li>
                                    @endrole
                                    <li><a class="dropdown-item" href="/home">home</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
            
        </nav>

        <main class="py-4">
            <div class="container-fluid">
                <div id="flash-msg">
                    @include('flash::message')
                </div>
                @yield('content')
                @stack('scripts')
        </main>
    </div>

</body>
<script type="text/javascript">
  function showTime() {
    var date = new Date(),
        utc = new Date(Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate(),
          date.getHours(),
          date.getMinutes(),
          date.getSeconds()
        ));

    document.getElementById('time').innerHTML = utc.toLocaleTimeString();
  }

  setInterval(showTime, 1000);
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<style>
body, .chartjs-render-monitor {
  font-family: "Chakra Petch", sans-serif;
  font-weight: 400;
  font-style: normal;
}
</style>
</html>
