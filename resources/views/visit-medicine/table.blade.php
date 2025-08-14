
<?php
if(!isset($editable)){
    $editable = 0;
}
   

?>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>ชื่อยา</th>
                <th>dose </th>
                <th>route </th>
                <th>จำนวน</th>
                @if($editable)
                <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($visitMedicine as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                
                <td>{{ $item->medicine_id ?  App\Models\Medicine::find($item->medicine_id)->name : ""}}</td>
                <td>{{ $item->dose }}</td>
                <td>{{ $item->route }}</td>
                <td>{{ $item->amount }}</td>
                @if($editable)
                <td>
                    <!-- <a href="{{ url('/backend/visit-medicine/' . $item->id) }}" title="View VisitMedicine"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> -->
                    <a href="{{ route('visit-medicine.edit',['visit_medicine'=>$item->id,'page'=>$_GET["page"]]) }}" title="Edit VisitMedicine" class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                    <form method="POST" action="{{ route('visit-medicine.destroy',['visit_medicine'=>$item->id,'page'=>$_GET["page"]])  }}" accept-charset="UTF-8" style="display:inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete VisitMedicine" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

</div>