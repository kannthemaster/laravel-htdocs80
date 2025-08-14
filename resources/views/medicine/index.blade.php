@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- @include('admin.sidebar') --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Medicine</div>
                    <div class="card-body">
                        <a href="{{ route('medicine.create') }}" class="btn btn-success btn-sm" title="Add New Medicine">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/backend/medicine') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-end" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Medicine</th><th>Dose</th><th>Route</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($medicine as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->dose }}</td><td>{{ $item->route }}</td>
                                        <td>
                                            <a href="{{ url('/medicine/' . $item->id) }}" title="View Medicine"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> </button></a>
                                            <a href="{{ url('/medicine/' . $item->id . '/edit') }}" title="Edit Medicine"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> </button></a>

                                            <form method="POST" action="{{ url('/medicine' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Medicine" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{-- <div class="pagination-wrapper"> {!! $medicine->appends(['search' => Request::get('search')])->render() !!} </div> --}}
                            {!! $medicine->links() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
