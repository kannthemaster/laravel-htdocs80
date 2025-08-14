<div class="row">
    <div class="form-group col-2 {{ $errors->has('date') ? 'has-error' : '' }}">
        <label for="date" class="control-label">{{ 'Date' }}</label>
        <input class="form-control" name="date" type="text" id="date"
            value="{{ isset($contactperson->date) ? $contactperson->date : '' }}" autocomplete="off">
        {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group col-6 {{ $errors->has('name_surname') ? 'has-error' : '' }}">
        <label for="name_surname" class="control-label">{{ 'Name - Surname' }}</label>
        <input class="form-control" name="name_surname" type="text" id="name_surname"
            value="{{ isset($contactperson->name_surname) ? $contactperson->name_surname : '' }}">
        {!! $errors->first('name_surname', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group col-2 {{ $errors->has('cpsex') ? 'has-error' : '' }}">
        <label for="cpsex" class="control-label">{{ 'Sex' }}</label>
        {{-- <input class="form-control" name="cpsex" type="number" id="cpsex" value="{{ isset($contactperson->cpsex) ? $contactperson->cpsex : '' }}"> --}}
            <select class="form-control" name="cpsex" id="cpsex">
                @foreach (App\Models\ContactPerson::$cpsexOption as $key => $value)
                    <option value="{{ $key }}" @if (isset($contactperson->cpsex) && $key == $contactperson->cpsex) selected="selected" @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        {!! $errors->first('cpsex', '<p class="help-block">:message</p>') !!}
    </div>
    

    <div class="col-8 form-group {{ $errors->has('trush_tracks') ? 'has-error' : '' }}">
        <label for="trush_tracks" class="control-label">{{ 'การติดตามคู่สัมผัส' }}</label>

        <select class="form-control" name="trush_tracks" id="trush_tracks">
            @foreach (App\Models\ContactPerson::$touchTracingOption as $key => $value)
                <option value="{{ $key }}" name=" {{ $value }}">
                    {{ $value }}
                </option>
            @endforeach
        </select>
        {!! $errors->first('trush_tracks', '<p class="help-block">:message</div>') !!}
    </div>

    <div class="form-group col-2 {{ $errors->has('type') ? 'has-error' : '' }}">
        <label for="type" class="control-label">{{ 'type' }}</label>
        {{-- <input class="form-control" name="type" type="number" id="type"
            value="{{ isset($contactperson->type) ? $contactperson->type : '' }}"> --}}
            <select class="form-control" name="type" id="type">
                @foreach (App\Models\ContactPerson::$typeOption as $key => $value)
                    <option value="{{ $key }}" @if (isset($contactperson->type) && $key == $contactperson->type) selected="selected" @endif>
                        {{ $value }}</option>
                @endforeach
            </select>
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>

</div>
<hr>
<div id="touchTrackDiv">
    <table class="table table-bordered table-hover text-center">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th class="" colspan="4">ช่องทางการมีเภทสัมพันธ์</th>
                <th colspan="4">การใช้ถุงยาง</th>
                <th rowspan="2"></th>
            </tr>
            <tr>
                <th>Vagina</th>
                <th>Mouth</th>
                <th>Penis</th>
                <th>Anus</th>


                <th>ใช้</th>
                <th>ไม่ใช้</th>
                <th>แตก/หลุด</th>
                <th>ไม่แตก/หลุด</th>


            </tr>
        </thead>
        <tbody>
            @if(isset($contactperson))
            <?php $count = 0; ?>

            @foreach ($contactperson->sex_condom() as $key => $item)
            <tr>
                <td>{{ ++$count }}</td>
                <td>{{ $item['vagina_mt'] }}</td>
                <td>{{ $item['mouth_mt'] }}</td>
                <td>{{ $item['penis_mt'] }}</td>
                <td>{{ $item['anus_mt'] }}</td>

                <td>{{ $item['use_cd'] }}</td>
                <td>{{ $item['unuse_cd'] }}</td>
                <td>{{ $item['brea_slip_cd'] }}</td>
                <td>{{ $item['unbrea_slip_cd'] }}</td>

                <td>
                    <button class="btn btn-danger btn-sm" type="button" onclick='removeRow(this)'><i class="fa fa-trash"
                            aria-hidden="true"></i></button>

                </td>

            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
    <hr>

    <h5>ช่องทางการมีเพศสัมพันธ์</h5>
    <div class="row" style="margin-left: 0px;">

        <div class="form-check  col-3  {{ $errors->has('vagina_mt') ? 'has-error' : '' }}">
            <label for="vagina_mt" class="form-check-label">{{ 'Vagina' }}</label>
            <input class="form-check-input" name="vagina_mt" type="checkbox" id="vagina_mt" value="1"
                @if (isset($contactperson->vagina_mt)) checked @endif>
            {!! $errors->first('vagina_mt', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-check  col-3 {{ $errors->has('mouth_mt') ? 'has-error' : '' }}">
            <label for="mouth_mt" class="form-check-label">{{ 'Mouth' }}</label>
            <input class="form-check-input" name="mouth_mt" type="checkbox" id="mouth_mt" value="1"
                @if (isset($contactperson->mouth_mt)) checked @endif>
            {!! $errors->first('mouth_mt', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-check  col-3 {{ $errors->has('penis_mt') ? 'has-error' : '' }}">
            <label for="penis_mt" class="form-check-label">{{ 'Penis' }}</label>
            <input class="form-check-input" name="penis_mt" type="checkbox" id="penis_mt" value="1"
                @if (isset($contactperson->penis_mt)) checked @endif>
            {!! $errors->first('penis_mt', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-check col-3  {{ $errors->has('anus_mt') ? 'has-error' : '' }}">
            <label for="anus_mt" class="form-check-label">{{ 'Anus' }}</label>
            <input class="form-check-input" name="anus_mt" type="checkbox" id="anus_mt" value="1"
                @if (isset($contactperson->anus_mt)) checked @endif>
            {!! $errors->first('anus_mt', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <h5>การใช้ถุงยาง</h5>
    <div class="row" style="margin-left: 0px;">
        <div class="form-check  col-3 {{ $errors->has('use_cd') ? 'has-error' : '' }} ">
            <label for="use_cd" class="control-label">{{ 'ใช้' }}</label>
            <input class="form-check-input" name="use_cd" type="checkbox" id="use_cd" value="1"
                @if (isset($contactperson->use_cd)) checked @endif>
            {!! $errors->first('use_cd', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-check  col-3 {{ $errors->has('unuse_cd') ? 'has-error' : '' }}">
            <label for="unuse_cd" class="control-label">{{ 'ไม่ใช้' }}</label>
            <input class="form-check-input" name="unuse_cd" type="checkbox" id="unuse_cd" value="1"
                @if (isset($contactperson->unuse_cd)) checked @endif>
            {!! $errors->first('unuse_cd', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-check  col-3 {{ $errors->has('brea_slip_cd') ? 'has-error' : '' }}">
            <label for="brea_slip_cd" class="control-label">{{ 'แตก/หลุด' }}</label>
            <input class="form-check-input" name="brea_slip_cd" type="checkbox" id="brea_slip_cd" value="1"
                @if (isset($contactperson->brea_slip_cd)) checked @endif>
            {!! $errors->first('brea_slip_cd', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-check  col-3 {{ $errors->has('unbrea_slip_cd') ? 'has-error' : '' }}">
            <label for="unbrea_slip_cd" class="control-label">{{ 'ไม่แตก/หลุด' }}</label>
            <input class="form-check-input" name="unbrea_slip_cd" type="checkbox" id="unbrea_slip_cd"
                value="1" @if (isset($contactperson->unbrea_slip_cd)) checked @endif>
            {!! $errors->first('unbrea_slip_cd', '<p class="help-block">:message</p>') !!}
        </div>

    </div>

    <div class="form-group">
        <input class="btn btn-info" type="button" value="Add">
    </div>
</div>

<hr>

<div class="form-group">
    <input class="btn btn-primary float-right" type="submit"
        value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}" style="float: right">
</div>

<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/bootstrap-datepicker-thai.js"></script>
<script src="/js/bootstrap-datepicker.th.js"></script>

<script type="text/javascript">
    function removeRow(element) {
        console.log("removeRow")
        $(element).closest('tr').remove()
    }

    function get_sex_condom() {

        result = []
        for (i = 0; i < $("#touchTrackDiv tbody tr").length; i++) {
            tr = $("#touchTrackDiv tbody tr")[i]
            vagina_mt = $(tr).find("td:nth-child(2)").text().trim(),
            mouth_mt = $(tr).find("td:nth-child(3)").text().trim()
            penis_mt = $(tr).find("td:nth-child(4)").text().trim(),
            anus_mt = $(tr).find("td:nth-child(5)").text().trim()
            use_cd = $(tr).find("td:nth-child(6)").text().trim(),
            unuse_cd = $(tr).find("td:nth-child(7)").text().trim()
            brea_slip_cd = $(tr).find("td:nth-child(8)").text().trim(),
            unbrea_slip_cd = $(tr).find("td:nth-child(9)").text().trim()
                
            result.push({
                "vagina_mt": vagina_mt,
                "mouth_mt": mouth_mt,
                "penis_mt": penis_mt,
                "anus_mt": anus_mt,
                "use_cd": use_cd,
                "unuse_cd": unuse_cd,
                "brea_slip_cd": brea_slip_cd,
                "unbrea_slip_cd": unbrea_slip_cd,



            })
        }

        return result;
    }

    $(function() {
        @if(isset($contactperson))
        (function(){
            $("#trush_tracks").val({{$contactperson->trush_tracks}})
            
        })()
        @endif
        $('.date').datepicker({
            language: 'th-th',
        });


        $("#touchTrackDiv .btn-danger").click(removeRow)

        $("#touchTrackDiv .btn-info").click(function() {
            // console.log("click")
            $("#touchTrackDiv tbody").append(`
                <tr>
                    <td></td>

                    <td> ${$('#vagina_mt').is(':checked') ? '1' : '' } </td>
                    <td> ${$('#mouth_mt').is(':checked') ? '1' : '' } </td>
                    <td> ${$('#penis_mt').is(':checked') ? '1' : '' } </td>
                    <td> ${$('#anus_mt').is(':checked') ? '1' : '' } </td>

                    <td> ${$('#use_cd').is(':checked') ? '1' : '' } </td>
                    <td> ${$('#unuse_cd').is(':checked') ? '1' : '' } </td>
                    <td> ${$('#brea_slip_cd').is(':checked') ? '1' : '' } </td>
                    <td> ${$('#unbrea_slip_cd').is(':checked') ? '1' : '' } </td> 
                    
                    <td>
                        <button class="btn btn-danger btn-sm" type="button" onclick='removeRow(this)'><i class="fa fa-trash"
                                            aria-hidden="true"></i></button>    
                    </td> 
                </tr>
            `)

            // $("#touchTrackDiv input").removeAttr('checked');
            $("#touchTrackDiv input").prop('checked', false);
            // $("#touchTrackTable .btn-danger").click(removeRow)
            // $("#touch_tracing_name").val("");
            // $("#touch_tracing_id").val(0)
        })
        // $("#touchTrackDiv body").click(function() {
        //     $(this).closest('tr').remove()
        // })


        $("form").submit(function() {
            console.log("visit_edit_form")
            sex_condom = get_sex_condom()

            $("<input />").attr("type", "hidden")
                .attr("name", "sex_condom")
                .attr("value", JSON.stringify(sex_condom))
                .appendTo("form")
            return true
        })

    });
</script>
