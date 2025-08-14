<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-header">Medicine for Visit #{{ $visit->id }}</div>
        <div class="card-body">
            <?php $lab =$visit->lab() ?>



                <a href="{{ route('visit-medicine.add',['visit' => $visit->id,'page'=>$_GET['page']]) }}" class="btn btn-success btn-sm" title="Add New ContactPerson">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </a> 

                @include ('visit-medicine.table', ['visitMedicine' => $visit->visitMedicine(), 'editable'=>1])
         


        </div>
    </div>
</div>
</div>