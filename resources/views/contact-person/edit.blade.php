@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit ContactPerson #{{ $contactperson->id }}</div>
                    <div class="card-body">
                        <a href="javascript:history.back()" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ route('contact-person.update',['contact_person'=>$contactperson->id,'page'=>$_GET["page"]])  }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <input type="hidden" name='visit_id' value="{{$contactperson->visit_id}}">
                            @include ('contact-person.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
        @include('sweetalert::alert')
    </div>
@endsection
