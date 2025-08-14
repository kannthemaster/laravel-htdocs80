<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Contact Person for Visit #{{ $visit->id }}</div>
            <div class="card-body">
                <a href="{{ route('contact-person.create',['visit' => $visit->id,'page'=>$_GET["page"]]) }}" class="btn btn-success btn-sm" title="Add New ContactPerson">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </a>
                <br />
                <br />
                @include ('visit.table_contact_person', ['contactperson' => $visit->contactPerson()])

            </div>
        </div>
    </div>
</div>