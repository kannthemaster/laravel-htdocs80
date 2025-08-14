<div>


    <a href="{{ route('diagnosis.create',['visit' => $visit->id, 'page'=>$_GET["page"]]) }}" class="btn btn-success btn-sm" title="Add New Diagnosis">
        <i class="fa fa-plus" aria-hidden="true"></i> Add New
    </a>

    @include ('diagnosis.table', ['diagnosis' => $visit->diagnosi()])



</div>