@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
<div class="progress-container">
    <div id="progress-bar"></div>
  </div>
  
</div>
<style>
  /* ตั้งค่าเฉพาะส่วน card-header */
  .card-header {
    text-align: center;
    font-family: Arial, sans-serif;
    margin: 20px auto;
    padding: 10px;
    width: 100%; /* ขยายความกว้างเต็มหน้าจอ */
    background: linear-gradient(135deg, #f8f9fa, #e9ecef); /* สีไล่ระดับ */
    border-radius: 5px;
    color: #000;
  }

  .progress-container {
    width: 80%; /* แถบพลัง 80% ของความกว้าง */
    height: 15px;
    background-color: #ddd;
    border-radius: 10px;
    overflow: hidden;
    margin: 10px auto;
    border: 1px solid #bbb;
    position: relative;
  }

  #progress-bar {
    height: 100%;
    width: 100%;
    background: linear-gradient(90deg, #ff0000, #ff9900); /* สีไล่ระดับจากแดงไปส้ม */
    transition: width 1s linear;
  }

  #timer-text {
    font-size: 14px;
    font-weight: bold;
    color: red;
  }
</style>
<div class="card-body">
  <!-- ปุ่มเปิดหน้าเรียกคิวในแท็บใหม่ -->
  <a href="https://192.168.1.250/public/queue/index.php" class="btn btn-primary btn-lg" title="เปิดโปรแกรมเรียกคิว" target="_blank">
    <i class="fa fa-list" aria-hidden="true"></i> เปิดโปรแกรมเรียกคิว
  </a>
</div>
 
<div class="card-body">
<div class="spinner-border text-info"></div><i id="timer-text">20 seconds remaining</i>
<script>
  var timeLeft = 20; // เวลาที่เริ่มต้น
  var totalTime = 20; // เวลารวมทั้งหมด
  var progressBar = document.getElementById("progress-bar");
  var timerText = document.getElementById("timer-text");

  function updateProgress() {
    var percentage = (timeLeft / totalTime) * 100;
    progressBar.style.width = percentage + "%"; // ลดความกว้างตามเวลาที่ลดลง
    timerText.innerHTML = timeLeft + " seconds to refresh page"; // แสดงข้อความเวลาที่เหลือ

    if (timeLeft <= 0) {
      clearInterval(timerId);
      timerText.innerHTML = "Refreshing..."; // เปลี่ยนข้อความเมื่อหมดเวลา
      setTimeout(() => location.reload(), 1000); // รีเฟรชหน้าเว็บ
    }

    timeLeft--;
  }

  var timerId = setInterval(updateProgress, 1000); // เรียกใช้ฟังก์ชันทุก 1 วินาที
</script>

                        @include ('status')

                        <!-- <a href="{{ route('visit.create') }}" class="btn btn-success btn-sm" title="Add New Visit">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a> -->

                        {{-- <form method="GET" action="{{ route('visit.index') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-end" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form> --}}

                        {{-- <br/>
                        <br/> --}}
                        
                        <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>NH</th><th>ชื่อ</th><th>นามสกุล</th><th>เพศ</th><th>คิวตรวจ</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($visit as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->patient()->code }}</td><td>{{ $item->patient()->name }}</td><td>{{ $item->patient()->surname }}</td><td>{{ App\Models\Patient::$sexOption[$item->patient()->sex] }}</td>
                                        <td><b><font color="red">คิว {{ $item->other_from }}</font></b></td>
                                        <td>
                                            {{-- <a href="{{ url('/visit/' . $item->id) }}" title="View Visit"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> </button></a> --}}
                                            <a href="{{ $item->roomLink()}}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> </button></a>

                                            {{-- <form method="POST" action="{{ url('/visit' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Visit" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> </button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $visit->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
