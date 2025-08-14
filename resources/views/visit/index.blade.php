@extends('layouts.app')

@section('content')
<script type="text/javascript">
$('#box2').dataTable( {
  "pageLength": 1000
} );
    $(document).ready(function() {
    $('#box2').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'print'
        ]
    } );
} );
</script>
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
              


                    <div class="card-header">Visit</div>
                    <div class="card-body">
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

                        @include('visit.searchForm', ['visit' => ""]) 
                        <br/>
                        
                        
                        
                            <table class="uk-table uk-table-hover uk-table-striped" height="100%" width="100%" id="box2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NH</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>เพศ</th>
                                        <th>ประเภท</th>
                                        <th>อายุ</th>
                                        <th>วันที่มา</th>
                                        <th>วินิจฉัย / สถานะโรค</th>
                                        <th>วันที่นัด</th>
                                        <th>เหตุผลนัด</th>
                                        <th>Tel</th>
                                        <th>วันที่มาครั้งแรก</th>
                                    </tr>
                                    <!-- <tr>
                                        <th>#</th><th>วันที่มา</th><th>NH</th><th>ชื่อ-นามสกุล</th><th>เพศ</th><th>อายุ</th><th>status</th><th>วินิจฉัย</th><th nowrap="1">สถานะของโรค</th><th nowrap="1">นัดครั้งถัดไป</th>
                                    </tr> -->
                                </thead>
                                <tbody>

                                @foreach($visits as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->patient2->code }}</td>
        <td align="left" nowrap="">{{ $item->patient2->name .' '.$item->patient2->surname }}</td>
        <td>{{ App\Models\Patient::$sexOption[$item->patient2->sex] }}</td>
        <td>{{ App\Models\Patient::$statusOption[$item->patient2->status] }}</td>
        <td nowrap="1">{{ $item->patient2->age() }}</td>
        <td nowrap="1">{{ $item->date }}</td>
        <td align="left" nowrap="1">: {{ $item->diagnosiAllString() }}<br>: {{ $item->diagnosiStatusAllString() }}</td>
        <td nowrap="1">{{ $item->appointment }}</td>
        <td nowrap="1">{{ $item->appointment_reason }}</td>
        <td>{{ $item->patient2->tel }}</td>
        <td nowrap="1">{{ $item->patient2->first_visit }}</td>
    </tr>
@endforeach

                                </tbody>
                                <tr>
    <td>รวม</td>
    <td>{{ count($visits) }} ราย</td>
</tr>
                            </table>
                       
                            <div class="pagination-wrapper"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('sweetalert::alert')
@endsection

