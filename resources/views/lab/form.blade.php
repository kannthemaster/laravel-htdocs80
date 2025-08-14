{{-- <div class="form-group {{ $errors->has('patient_id') ? 'has-error' : ''}}">
    <label for="patient_id" class="control-label">{{ 'Patient Id' }}</label>
    <input class="form-control" name="patient_id" type="number" id="patient_id" value="{{ isset($lab->patient_id) ? $lab->patient_id : ''}}" >
    {!! $errors->first('patient_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('visit_id') ? 'has-error' : ''}}">
    <label for="visit_id" class="control-label">{{ 'Visit Id' }}</label>
    <input class="form-control" name="visit_id" type="number" id="visit_id" value="{{ isset($lab->visit_id) ? $lab->visit_id : ''}}" >
    {!! $errors->first('visit_id', '<p class="help-block">:message</p>') !!}
</div> --}}

<?php
$users = App\Models\User::pluck('name', 'id');
use Carbon\Carbon;
?>


<div class="form-group {{ $errors->has('report_by') ? 'has-error' : ''}}">
    <label for="report_by" class="control-label">{{ 'Report By' }}</label>
    {{-- <input class="form-control" name="report_by" type="number" id="report_by" value="{{ isset($lab->report_by) ? $lab->report_by : ''}}" > --}}
    <select class="form-control" name="report_by" id="report_by">
        <option value=""></option>
        @foreach ($users as $key => $value)
            <option value="{{ $key }}" @if (isset($lab->report_by) && $key == $lab->report_by) selected="selected" @endif>
                {{ $value }}</option>
        @endforeach
    </select>
    
    {!! $errors->first('report_by', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('approve_by') ? 'has-error' : ''}}">
    <label for="approve_by" class="control-label">{{ 'Approve By' }}</label>
    {{-- <input class="form-control" name="approve_by" type="number" id="approve_by" value="{{ isset($lab->approve_by) ? $lab->approve_by : ''}}" > --}}
    <select class="form-control" name="approve_by" id="approve_by">
        <option value=""></option>
        @foreach ($users as $key => $value)
            <option value="{{ $key }}" @if (isset($lab->approve_by) && $key == $lab->approve_by) selected="selected" @endif>
                {{ $value }}</option>
        @endforeach
    </select>


    {!! $errors->first('approve_by', '<p class="help-block">:message</p>') !!}
</div>
<!-- <div class="form-group {{ $errors->has('collected_date') ? 'has-error' : ''}}">
    <label for="collected_date" class="control-label">{{ 'Collected Date' }}</label>
    <input class="form-control date" name="collected_date" type="text" id="collected_date" 
       value="{{ old('collected_date', isset($lab->collected_date) ? $lab->collected_date : \Carbon\Carbon::now()->addYears(543)->format('d/m/Y')) }}">

    {!! $errors->first('collected_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('report_date') ? 'has-error' : ''}}">
    <label for="report_date" class="control-label">{{ 'Report Date' }}</label>
    <input class="form-control date" name="report_date" type="text" id="report_date" value="{{ isset($lab->report_date) ? $lab->report_date : ''}}" >
    {!! $errors->first('report_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('LN') ? 'has-error' : ''}}">
    <label for="LN" class="control-label">{{ 'Ln' }}</label>
    <input class="form-control" name="LN" type="text" id="LN" value="{{ isset($lab->LN) ? $lab->LN : ''}}" >
    {!! $errors->first('LN', '<p class="help-block">:message</p>') !!}
</div> -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('collected_date') ? 'has-error' : ''}}">
            <label for="collected_date" class="control-label">Collected Date</label>
            <input class="form-control date" name="collected_date" type="text" id="collected_date" 
                value="{{ old('collected_date', isset($lab->collected_date) ? $lab->collected_date : \Carbon\Carbon::now()->addYears(543)->format('d/m/Y')) }}">
            {!! $errors->first('collected_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- <div class="form-group {{ $errors->has('collected_at') ? 'has-error' : ''}}">
    <label for="collected_at" class="control-label">Collected Time</label>
    <input class="form-control" name="collected_at" type="time" id="collected_at"
        value="{{ old('collected_at', isset($lab->collected_at) ? \Carbon\Carbon::parse($lab->collected_at)->format('H:i') : '') }}">
    {!! $errors->first('collected_at', '<p class="help-block">:message</p>') !!}
</div> -->

    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('report_date') ? 'has-error' : ''}}">
            <label for="report_date" class="control-label">Report Date</label>
            <input class="form-control date" name="report_date" type="text" id="report_date" 
                value="{{ old('report_date', isset($lab->report_date) ? $lab->report_date : '') }}">
            {!! $errors->first('report_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('report_at') ? 'has-error' : ''}}">
            <label for="report_at" class="control-label">Report Time</label>
            <input class="form-control" name="report_at" type="time" id="report_at" 
                value="{{ old('report_at', isset($lab->report_at) ? $lab->report_at : '') }}">
            {!! $errors->first('report_at', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('approve_date') ? 'has-error' : ''}}">
            <label for="approve_date" class="control-label">Approve Date</label>
            <input class="form-control date" name="approve_date" type="text" id="approve_date" 
                value="{{ old('approve_date', isset($lab->approve_date) ? $lab->approve_date : '') }}">
            {!! $errors->first('approve_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('approve_at') ? 'has-error' : ''}}">
            <label for="approve_at" class="control-label">Approve Time</label>
            <input class="form-control" name="approve_at" type="time" id="approve_at" 
                value="{{ old('approve_at', isset($lab->approve_at) ? $lab->approve_at : '') }}">
            {!! $errors->first('approve_at', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="form-group {{ $errors->has('remark') ? 'has-error' : ''}}">
    <label for="remark" class="control-label">Remark</label>
    <textarea class="form-control" name="remark" id="remark" rows="3">{{ old('remark', isset($lab->remark) ? $lab->remark : '') }}</textarea>
    {!! $errors->first('remark', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    {{-- <input class="form-control" name="status" type="number" id="status" value="{{ isset($lab->status) ? $lab->status : ''}}" > --}}
    <select class="form-control" name="status" id="status">
        <option value=""></option>
        @foreach (App\Models\Lab::$statusOption  as $key => $value)
            <option value="{{ $key }}" @if (isset($lab->status) && $key == $lab->status) selected="selected" @endif>
                {{ $value }}</option>
        @endforeach
    </select>


    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>

<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/bootstrap-datepicker-thai.js"></script>
<script src="/js/bootstrap-datepicker.th.js"></script>

<script type="text/javascript">
    $(function() {
        $('.date').datepicker({
            language: 'th-th',
        });
    });
</script>
