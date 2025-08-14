@extends('layouts.app')

@section('content')
<style>
    .btn-light,.btn-light:focus{
        background-color: lightblue;
    }
</style>
    <div class="container">

        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Visit #{{ $visit->id }}</div>
                    <div class="card-body">
                        <a href="{{ route('patient.edit', $visit->patient_id) }}" title="Back"><button
                                class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                                Back</button></a>
                        {{-- <a href="javascript:history.back()" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a> --}}
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
<div class="accordion" id="accordionExample">
    
                        <form autocomplete="off" method="POST" id="visit_edit_form"
                            action="{{ route('visit.update', ['visit' => $visit->id, 'page' => $_GET['page']]) }}"
                            accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            
                            {{ csrf_field() }}

                            @include('visit.form.index', ['formMode' => 'edit'])

                            {{ method_field('PATCH') }}
                            {{-- @include('visit.form.save') --}}

                            <div class="row">
    <div class="col-auto">
        <label class="visually-hidden" for="status">Status</label>
        <select class="form-select" id="status" name="status">
            <option value="2" {{ $visit->status == 2 ? 'selected' : '' }}>ห้องตรวจ 2</option>
            <option value="8" {{ $visit->status == 8 ? 'selected' : '' }}>ห้องตรวจ 8</option>
            <option value="3" {{ $visit->status == 3 ? 'selected' : '' }}>ห้องเจาะเลือด/ฉีดยา</option>
            <option value="5" {{ $visit->status == 5 ? 'selected' : '' }}>ห้องยา</option>
            <option value="6" {{ $visit->status == 6 ? 'selected' : '' }}>กลับบ้าน</option>
        </select>
    </div>

    <div class="col-auto form-group">
        <input class="btn btn-primary" type="submit" value="Save">
    </div>
</div>

                            </div>

                        </form>

</div>
                    </div>
                </div>

            </div>
        </div>

{{-- Visit History --}}
<?php
$visits = App\Models\Visit::where('patient_id', $visit->patient_id)
    ->orderBy('date', 'asc') // เรียงลำดับตามวันที่
    ->get();

?>
<style type="text/css">
    .current-visit {
    background-color: #ffd700 !important; /* สีทอง */
    font-weight: bold;
}
</style>

        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Visit History</div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>วันที่</th><th>วินิจฉัย</th><th>สถานะโรค</th><th>RPR</th><th>วันนัด</th><th>เหตุผล</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($visits as $item)
                                    <tr>
                                        <tr @if($item->id === $visit->id) class="current-visit" @endif>
                                        <td>{{ $loop->iteration }}</td>
                                        <td nowrap="1">{{ $item->date }}</td>
                                        <td>{{ $item->diagnosiAllString() }}</td>
                                        <td>{{ $item->diseaseStateString() }}</td>
                                        <td>{{ $item->RPR() }}</td>
                                        <td nowrap="1">{{ $item->appointment }}</td>
                                        <td>{{ $item->appointment_reason }}</td>
                                        <td>
                                            <td>
                    <!-- <a href="{{ url('/visit/' . $item->id) }}" title="View Visit"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> -->
                    <a href="{{ route('visit.edit',['visit'=>$item->id,'page'=>1])}}" title="Edit Visit"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></button></a>

                    
                </td>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('sweetalert::alert')
    </div>
@endsection
