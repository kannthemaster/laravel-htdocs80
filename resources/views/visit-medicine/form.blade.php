<!-- <div class="form-group {{ $errors->has('visit_id') ? 'has-error' : ''}}">
    <label for="visit_id" class="control-label">{{ 'Visit Id' }}</label>
    <input class="form-control" name="visit_id" type="number" id="visit_id" value="{{ isset($visitmedicine->visit_id) ? $visitmedicine->visit_id : ''}}" >
    {!! $errors->first('visit_id', '<p class="help-block">:message</p>') !!}
</div> -->
<div class="form-group {{ $errors->has('medicine') ? 'has-error' : ''}}">
    <label for="medicine" class="control-label">{{ 'Medicine' }}</label>
    <!-- <input class="form-control" name="medicine" type="text" id="medicine" value="{{ isset($visitmedicine->medicine) ? $visitmedicine->medicine : ''}}" > -->
    <?php $items = App\Models\Medicine::orderBy('name')->get(); ?>
    <select name="medicine_id" id="medicine_id" class="form-control name" >
        <option value=""></option>
        @foreach($items as $item)
        <option value="{{$item->id}}" dose="{{$item->dose}}" route="{{$item->route}}" amount="{{$item->amount}}">{{$item->name}}</option>
        @endforeach
    </select>

    {!! $errors->first('medicine', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('dose') ? 'has-error' : ''}}">
    <label for="dose" class="control-label">{{ 'Dose' }}</label>
    <input class="form-control" name="dose" type="text" id="dose" value="{{ isset($visitmedicine->dose) ? $visitmedicine->dose : ''}}" >
    {!! $errors->first('dose', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('route') ? 'has-error' : ''}}">
    <label for="route" class="control-label">{{ 'route' }}</label>
    <input class="form-control" name="route" type="text" id="route" value="{{ isset($visitmedicine->route) ? $visitmedicine->route : ''}}" >
    {!! $errors->first('route', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
    <label for="amount" class="control-label">{{ 'Amount' }}</label>
    <input class="form-control" name="amount" type="text" id="amount" value="{{ isset($visitmedicine->amount) ? $visitmedicine->amount : ''}}" >
    {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.name').change(function(){
            
            // console.log($(this).attr('rowId'))
            // console.log($(this).val())
            $('#dose').val($('option:selected', this).attr('dose')) 
            $('#route').val($('option:selected', this).attr('route')) 
            $('#amount').val($('option:selected', this).attr('amount')) 


        });

        $("#medicine_id").val({{ isset($visitmedicine) ? $visitmedicine->medicine_id : ''}});
    });

    
</script>
