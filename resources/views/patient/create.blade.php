@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create New Patient</div>
                    <div class="card-body">
                        <a href="{{ route('patient.index') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ route('patient.store') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="card mb-4">
                              <div class="card-header">ข้อมูลผู้ป่วย</div>
                              <div class="card-body">
                                @include ('patient.form', ['formMode' => 'create'])
                                <div class="card-header">ที่อยู่</div>
                                @include('address.form', ['formMode' => 'create'])
                              </div>
                            </div>
                         
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
