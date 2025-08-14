@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Patient #{{ $patient->id }}</div>
                    <div class="card-body">

                        <div>
                            <a href="{{ route('patient.index') }}" title="Back"><button class="btn btn-warning btn-sm"><i
                                        class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>


                        </div>
                        

                       

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">
    <title>Bootstrap Example</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
<?php

// กำหนดค่าตัวแปรวันเกิด
$patient_birthdate = $patient->birth_date;

// แยกวัน เดือน ปี เกิด
$birth_date_parts = explode("/", $patient_birthdate);
$birth_day = $birth_date_parts[0];
$birth_month = $birth_date_parts[1];
$birth_year_be = $birth_date_parts[2];

// แปลงปี พ.ศ. เป็น ค.ศ.
$birth_year_ad = $birth_year_be - 543;

// หาอายุ
$age_year = date("Y") - $birth_year_ad;
$age_month = date("m") - $birth_month;
$age_day = date("d") - $birth_day;

// ตรวจสอบว่าอายุเป็นลบหรือไม่
if ($age_month < 0) {
  $age_year--;
  $age_month += 12;
}

if ($age_day < 0) {
  $age_month--;
  $age_day += 31;
}
// แสดงผลลัพธ์
//echo "อายุ: $age_year ปี $age_month เดือน $age_day วัน";
?>                        
    <div class="toast text-bg-primary border-0 fade show position-absolute end-0 p-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body sticky-top">
          อายุ {{ $age_year }} ปี
        </div>
        <button type="button" class="btn-close btn-close-white me-1 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div><br>
    @if($patient->photo)
    <img src="{{ route('patient.showPhoto', $patient->id) }}" alt="รูปผู้ป่วย" style="max-height:200px;" />

@else
    <p>ไม่มีรูปผู้ป่วย</p>
@endif
@if($patient->photo)
    <div class="mb-2">
        <form action="{{ route('patient.deletePhoto', $patient->id) }}" method="POST" style="display:inline; max-height: 200px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('ลบรูปผู้ป่วยนี้หรือไม่?')">🗑 ลบรูป</button>
        </form>
    </div>
@endif

                        <form method="POST" action="{{ route('patient.update', $patient->id) }}" accept-charset="UTF-8"
                            class="form-horizontal" enctype="multipart/form-data" autocomplete="off">                 
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include('patient.form', ['formMode' => 'edit'])

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Address for Patient #{{ $patient->id }}</div>
                    <div class="card-body">

                        @if (!$patient->address())
                        <div class="p-3 mb-2 bg-warning">
                            <a href="{{ route('address.create', ['patient' => $patient->id]) }}"
                                class="btn btn-danger btn-sm " title="Add New Address">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a></div>
                        @else
                            @include('address.table', ['address' => $patient->address()])
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Visit for Patient #{{ $patient->id }}</div>
                    <div class="card-body">

                        <form method="POST" class="row gy-2 gx-3 align-items-center"  action="{{ route('patient.createVisit')  }}" >
                            {{ csrf_field() }}
                            <input type="hidden" name="patient_id" value="{{$patient->id}}">
                            <div class="col-auto">
                                <label class="visually-hidden" for="clinic">Clinic</label>
                                <select class="form-select" id="clinic" name="clinic">
                                    <option value="1">STI Clinic</option>
                                    <option value="2" disabled>TB Clinic</option>
                                    <option value="3" disabled>TMC Clinic</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="visually-hidden" for="status">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                     <option value="">เลือกห้องที่จะส่ง</option>
                                    <option value="2">ห้องตรวจ 2</option>
                                    <option value="8">ห้องตรวจ 8</option>
                                    <option value="3">ห้องเจาะเลือด/ฉีดยา</option>
                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Visit</button>
                            </div>
                        </form>

{{-- 
                        <a href="{{ route('visit.create', ['patient' => $patient->id]) }}" class="btn btn-success btn-sm"
                            title="Add New vISIT">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a> --}}
                        <br>
                        @include('visit.table', ['visit' => $patient->visits(), 'hasMethod' => $hasMethod ?? false])

                    </div>
                </div>
            </div>
        </div>
        @include('sweetalert::alert')
    </div>
@endsection
