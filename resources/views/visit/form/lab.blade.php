<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-header">Lab for Visit #{{ $visit->id }}</div>
        <div class="card-body">
            <?php $lab =$visit->lab() ?>

            @if(!$lab)
            <a href="{{ route('lab.create',['visit' => $visit->id]) }}" class="btn btn-success btn-sm" title="Add New Lab">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New
            </a>  
            @else
               
            
                @include ('lab.table', ['lab' => $visit->lab(),'page'=>$_GET["page"]])
                @if(false)
                    <a href="{{ route('lab-item.create',['visit' => $visit->id]) }}" class="btn btn-success btn-sm" title="Add New Lab">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a> 
                    <br><br>
                    @include ('lab-item.table', ['labitem' => $visit->labItem(),'page'=>$_GET["page"]])
                    
                    <?php $labBlood =$lab->labBlood() ?>
                    @if($labBlood)
                    <h3>Blood Test</h3>
                        @include ('lab-blood.table', ['labBlood' => $labBlood,'edit' => 0])
                    @endif

                @endif

                {{-- <a href="{{ route('lab-sub.add',['visit' => $visit->id,'page'=>$_GET["page"]]) }}" class="btn btn-success btn-sm" title="Add New Lab">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </a>  --}}

                <a href="{{ route('lab-sub.config',['visit' => $visit->id,'page'=>$_GET["page"]]) }}" class="btn btn-success btn-sm" title="Add New Lab">
                    <i class="fa fa-pencil" aria-hidden="true"></i> Add Lab
                </a> 

                @include ('lab-sub.table', ['labsub' => $visit->labSub()])
            @endif


        </div>
    </div>
</div>
</div>