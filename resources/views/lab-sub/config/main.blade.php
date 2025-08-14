@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- @include('admin.sidebar') -->

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">New Add LabSub</div>
                <div class="card-body">
                    {{-- <a href="{{ route('visit.edit',$_GET['visit']) }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a> --}}
                    <a href="javascript:history.back()" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
 
                    <br />
                    <br />


                    <?php $visit = App\Models\Visit::find($_GET['visit']) ?>
                    <?php $labSubConfig = $visit->lab()->labSubConfig() ?>

     
                 <form method="POST" action="{{ route('lab-sub.save-config',['page'=>$_GET["page"]]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="lab_id" value="{{$visit->lab()->id}}">


                        <h3>Gram stain</h3>
                        @include ('lab-sub.config.specimenFrom', ['method' => '1','labSubConfig' => $labSubConfig])
                        <hr>

                        <h3>Wet smear</h3>
                        @include ('lab-sub.config.specimenFrom', ['method' => '2','labSubConfig' => $labSubConfig])
                        <hr>
                        <?php 
                        // <h3>PAP Smear</h3>
                        // @include ('lab-sub.config.specimenFrom', ['method' => '3','labSubConfig' => $labSubConfig])
                        // <hr>
                        ?>
                        <h3>Culture</h3>
                        @include ('lab-sub.config.specimenFrom', ['method' => '4','labSubConfig' => $labSubConfig])
                        <hr>
 
                        <h3>PCR for CT/NG</h3>
                        @include ('lab-sub.config.specimenFromPcr', ['method' => '5','labSubConfig' => $labSubConfig])
                        <hr>

                        <h3>Blood test</h3>
                        @include ('lab-sub.config.blood', ['labSubConfig' => $labSubConfig])
                        <hr>
                        <h3>Pap Smear</h3>
                        @include ('lab-sub.config.specimenFrom', ['method' => '13','labSubConfig' => $labSubConfig])
                        <hr>
                        <div  style="display: flex;">
                            <label class="control-label pe-2" for="other">
                                Other
                            </label>
                            <input class="form-control" type="text"  id="other" name="other" value="{{ isset($labSubConfig[10]) ? $labSubConfig[10]['key'] : '' }}">
                            <input  type="hidden"  id="othervalue" name="othervalue" value="{{ isset($labSubConfig[10]) ? $labSubConfig[10]['value'] : '' }}">
                        </div>
                        <hr>
                        <div class="form-group">
    <label for="collected_date" class="control-label">Collected Date</label>
    <input class="form-control date" name="collected_date" type="text" id="collected_date" 
       value="{{ old('collected_date', isset($lab->collected_date) ? $lab->collected_date : \Carbon\Carbon::now()->addYears(543)->format('Y-m-d')) }}">
</div>
<hr>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <select class="form-control" name="status" id="status" required>
        <option value="" disabled selected>เลือก *ส่ง Lab* นะจ๊ะ</option>
        @foreach (App\Models\Lab::$statusOption as $key => $value)
            <option value="{{ $key }}" 
                @if (isset($lab->status) && $key == $lab->status) selected="selected" @endif>
                {{ $value }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>

                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="save">
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection