@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <!-- Edit Lab #{{ $lab->id }} -->
                    <h3>{{ $patient->name }} {{ $patient->surname }} | {{ $patient->code }}</h3></div>

                    <div class="card-body">
                        @if ($backUrl)
    <a href="{{ $backUrl }}" class="btn btn-primary">กลับหน้า Visit</a>
@else
    <span class="text-muted">No back link available</span>
@endif

                        {{-- <a href="{{ route('visit.edit',$lab->visit_id) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i>กลับหน้า Visit</button></a> --}}
                      <a href="https://192.168.1.250/lab" class="btn btn-success">กลับหน้า LAB</a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ route('lab.update', ['lab' => $lab->id, 'page' => $_GET['page']]) }}"
                            accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('lab.form', ['formMode' => 'edit'])

                        </form>
                        <div class="border p-1">
                            
                            <form method="POST" action="{{ route('lab-sub.updateAllSub', ['id' => $lab->id, 'page' => $_GET['page']]) }}"
                                accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" autocomplete="off" class="border">
                                {{ csrf_field() }}
    
                                @include ('lab-sub.table', ['addResult' => 1, 'labsub' => $lab->labSub()])
    
    
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" value="Save">
                                </div>
    
                            </form>
                        </div>
                        
                        <!-- @include ('lab-item.table', ['labitem' => $lab->labItem()]) -->

                    </div>
                </div>
            </div>
        </div>

        <br>
        @if (false)
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Blood Test for Lab #{{ $lab->id }}</div>
                        <div class="card-body">
                            <?php $labBlood = $lab->labBlood(); ?>

                            @if (!$labBlood)
                                <a href="{{ route('lab-blood.create', ['lab' => $lab->id]) }}" class="btn btn-success btn-sm"
                                    title="Add New Lab Blood">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                </a>
                            @else
                                @include ('lab-blood.table', ['labBlood' => $labBlood, 'edit' => 1])
                            @endif


                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
    @include('sweetalert::alert')
@endsection

