<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>โรค</th>
                <th>สถานะของโรค</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($diagnosis as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->disease() }}</td>
                <td>{{ $item->diseaseState() }}</td>
                <td>
                    <!-- <a href="{{ url('/backend/diagnosis/' . $item->id) }}" title="View Diagnosi"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> -->
                    <a href="{{ route('diagnosis.edit',['diagnosi' => $item->id, 'page'=>request('page')]) }}" 
   title="Edit Diagnosi" 
   class="btn btn-primary btn-sm">
   <i class="fa fa-pencil" aria-hidden="true"></i>
</a>

<form method="POST" action="{{ route('diagnosis.destroy', ['diagnosi' => $item->id, 'page'=>request('page')]) }}" 
      style="display:inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" title="Delete Diagnosi" 
            onclick="return confirm('Confirm delete?')">
        <i class="fa fa-trash" aria-hidden="true"></i>
    </button>
</form>

                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>