<?php
if (isset($visit)) {
    $patient = $visit->patient();
} else {
    $patient = App\Models\Patient::find($_GET['patient']);
}

?>
<div class="row">
@if($patient->photo)
    <img src="{{ route('patient.showPhoto', $patient->id) }}" alt="รูปผู้ป่วย" style="max-width:250px;" />
@else
    <p>ไม่มีรูปผู้ป่วย</p>
@endif    
<div class="col-2 form-group {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code" class="control-label">{{ 'หมายเลขประจำตัว(HN.)' }}</label>
        <input class="form-control" name="code" type="text" id="code"
            value="{{ isset($patient->code) ? $patient->code : '' }}" readonly>
    </div>
<div class="col-2  form-group {{ $errors->has('prefix') ? 'has-error' : '' }}">
        <label for="prefix" class="control-label">{{ 'คำนำหน้าชื่อ' }}</label>
        {{-- <input class="form-control" name="prefix" type="number" id="prefix" value="{{ isset($patient->prefix) ? $patient->prefix : ''}}" required> --}}
        <select class="form-control" name="prefix" id="prefix" required>
            @foreach (App\Models\Patient::$prefixOption as $key => $value)
            <option value="{{ $key }}" @if (isset($patient->prefix) && $key == $patient->prefix) selected="selected" @endif>
                {{ $value }}
            </option>
            @endforeach
        </select>
        {!! $errors->first('prefix', '<p class="help-block">:message</p>') !!}
    </div>
<div class="col-2 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="control-label">{{ 'ชื่่อ' }}</label>
    @php
        $nameColor = isset($patient->name) && (str_contains($patient->name, '(L') || str_contains($patient->name, '(T')) ? 'blue' : 'black';
    @endphp
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($patient->name) ? $patient->name : '' }}" style="color: {{ $nameColor }};" readonly>
</div>

<div class="col-2 form-group {{ $errors->has('surname') ? 'has-error' : '' }}">
    <label for="surname" class="control-label">{{ 'นามสกุล' }}</label>
    @php
        $surnameColor = isset($patient->name) && (str_contains($patient->name, '(L') || str_contains($patient->name, '(T')) ? 'blue' : 'black';
    @endphp
    <input class="form-control" name="surname" type="text" id="surname" value="{{ isset($patient->surname) ? $patient->surname : '' }}" style="color: {{ $surnameColor }};" readonly>
</div>

    <div class="col-2 form-group {{ $errors->has('birth_date') ? 'has-error' : '' }}">
        <label for="birth_date" class="control-label">{{ 'วันเกิด' }}</label>
        <input class="form-control" name="birth_date" type="text" id="birth_date"
            value="{{ isset($patient->birth_date) ? $patient->birth_date : '' }}" readonly>
    </div>
    <div class="col-2  form-group {{ $errors->has('sex') ? 'has-error' : '' }}">
        <label for="sex" class="control-label">{{ 'เพศ' }}</label>
        {{-- <input class="form-control" name="sex" type="number" id="sex" value="{{ isset($patient->sex) ? $patient->sex : ''}}" > --}}
        <select class="form-control" name="sex" id="sex" required>
            @foreach (App\Models\Patient::$sexOption as $key => $value)
            <option value="{{ $key }}" @if (isset($patient->sex) && $key == $patient->sex) selected="selected" @endif>
                {{ $value }}
            </option>
            @endforeach
        </select>
        {!! $errors->first('sex', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-2 form-group">
        <label for="age" class="control-label">{{ 'อายุ' }}</label>
        <input class="form-control" name="age" type="text" id="age"
            value="{{ isset($patient->birth_date) ? $patient->age() : '' }}" readonly>
    </div>
    <div class="col-2 form-group {{ $errors->has('tel') ? 'has-error' : '' }}">
        <label for="tel" class="control-label">{{ 'เบอร์โทร' }} <font color="red">(แก้ไขได้)</font></label>
        <input class="form-control" name="tel" type="text" id="tel" value="{{ isset($patient->tel) ? $patient->tel : '' }}">
   </div>
      <div class="col-3 form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" class="control-label">{{ 'Status' }} <font color="red">(แก้ไขได้)</font></label>
                {{-- <input class="form-control" name="status" type="number" id="status" value="{{ isset($patient->status) ? $patient->status : '' }}"> --}}
                
                <select class="form-control" name="patient_status" id="patient_status">
    @foreach (App\Models\Patient::$statusOption as $key => $value)
        <option value="{{ $key }}" @if (isset($patient->status) && $key == $patient->status) selected="selected" @endif>
            {{ $value }}
        </option>
    @endforeach
</select>

                {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
            </div>

<div class="col-4 form-group {{ $errors->has('occupation') ? 'has-error' : '' }}">
        <label for="occupation" class="control-label">{{ 'อาชีพ' }}</label>
        {{-- <input class="form-control" name="occupation" type="number" id="occupation"
            value="{{ isset($patient->occupation) ? $patient->occupation : '' }}"> --}}
        <select class="form-control" name="occupation" id="occupation">
            @foreach (App\Models\Patient::$occupationOption as $key => $value)
            <option value="{{ $key }}" @if (isset($patient->occupation) && $key == $patient->occupation) selected="selected" @endif>
                {{ $value }}
            </option>
            @endforeach
        </select>
        {!! $errors->first('occupation', '<p class="help-block">:message</p>') !!}
    </div>

    <div id="other_occupation_div" class="col-8 form-group {{ $errors->has('other_occupation') ? 'has-error' : '' }}" style="display:none" >
        <label for="other_occupation" class="control-label">{{ 'อาชีพ' }}</label>
        <input class="form-control" name="other_occupation" type="text" id="other_occupation" value="{{ isset($patient->other_occupation) ? $patient->other_occupation : '' }}" >
        {!! $errors->first('other_occupation', '<p class="help-block">:message</p>') !!}
    </div>

    <div id="organization_div" class="col-8 orm-group {{ $errors->has('organization') ? 'has-error' : '' }}" style="{{ isset($patient->occupation)  && $patient->occupation ==10  ? '' : 'display:none' }}">
        <label for="organization" class="control-label">{{ 'ชื่อสถานประกอบการ' }}</label>
        <input class="form-control" name="organization" type="text" id="organization" value="{{ isset($patient->organization) ? $patient->organization : '' }}">
        {!! $errors->first('organization', '<p class="help-block">:message</p>') !!}
    </div>



   <div class="col-3 form-group {{ $errors->has('congenitaldisease') ? 'has-error' : '' }}">
    <label for="congenitaldisease" class="control-label" style="color: red;">{{ 'โรคประจำตัว' }}</label>
    <input class="form-control" name="congenitaldisease" type="text" id="congenitaldisease" style="color: red;"
        value="{{ isset($patient->congenitaldisease) ? $patient->congenitaldisease : '' }}">
</div>

<div class="col-3 form-group {{ $errors->has('drug_allergy') ? 'has-error' : '' }}">
    <label for="drug_allergy" class="control-label" style="color: red;">{{ 'ประวัติแพ้ยา' }}</label>
    <input class="form-control" name="drug_allergy" type="text" id="drug_allergy" style="color: red;"
        value="{{ isset($patient->drug_allergy) ? $patient->drug_allergy : '' }}">
</div>


<!-- เรียกใช้ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* ปรับตำแหน่งให้เป็น "กลางซ้าย" จริงๆ */
    .swal2-left-center {
        position: fixed !important;
        left: 10px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: fit-content !important;
        z-index: 1050 !important; /* ไม่ให้ทับ Alert หลัก */
    }
</style>


    <div class="col-3 form-group {{ $errors->has('marital_status') ? 'has-error' : '' }}">
        <label for="marital_status" class="control-label">{{ 'สถานภาพ' }}</label>
        {{-- <input class="form-control" name="marital_status" type="number" id="marital_status"
        value="{{ isset($patient->marital_status) ? $patient->marital_status : '' }}"> --}}
        <select class="form-control" name="marital_status" id="marital_status">
            @foreach (App\Models\Patient::$maritalStatusOption as $key => $value)
            <option value="{{ $key }}" @if (isset($patient->marital_status) && $key == $patient->marital_status) selected="selected" @endif>
                {{ $value }}
            </option>
            @endforeach
        </select>
        {!! $errors->first('marital_status', '<p class="help-block">:message</p>') !!}
    </div>
    <div><input class="btn btn-primary" type="submit" value="บันทึก/แก้ไขข้อมูลผู้ป่วย"></div>
</div>
<hr>
<div class="row">
    <div class="col-3 form-group {{ $errors->has('date') ? 'has-error' : '' }}">
        <label for="date" class="control-label">{{ 'วันที่' }}</label>
        <input class="form-control date" name="date" type="text" id="date"
            value="{{ isset($visit->date) ? $visit->date : date('d') . '/' . date('m') . '/' . (date('Y') + 543) }}">
        {!! $errors->first('date', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="col-3 form-group {{ $errors->has('know_from') ? 'has-error' : '' }}">
        <label for="know_from" class="control-label">{{ 'ช่องทางการเข้าถึง' }}</label>
        {{-- <input class="form-control" name="know_from" type="number" id="know_from" value="{{ isset($visit->know_from) ? $visit->know_from : ''}}" > --}}
        <select class="form-control" name="know_from" id="know_from">
            @foreach (App\Models\Visit::$knowFromOption as $key => $value)
                <option value="{{ $key }}" @if (isset($visit->know_from) && $key == $visit->know_from) selected="selected" @endif>
                    {{ $value }}
                </option>
            @endforeach
        </select>

        {!! $errors->first('know_from', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="col-3 form-group {{ $errors->has('send_from') ? 'has-error' : '' }}">
        <label for="send_from" class="control-label click" data-bs-toggle="modal" data-bs-target="#send_from_model">{{ 'ส่งต่อมาจาก' }}</label>
        <input class="form-control" name="send_from" type="text" id="send_from" value="{{ isset($visit->send_from) ? $visit->send_from : '' }}">
        {!! $errors->first('send_from', '<p class="help-block">:message</p>') !!}
    </div>

            <div class="modal fade" id="send_from_model" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Refer Option</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach (App\Models\Visit::$referOption as $item)
                            <p class='send_from_item' style="display: list-item; margin-left : 1em;" onmouseover="this.style.color='red'" onmouseout ="this.style.color='black'"> {{ $item }}</p>
                        @endforeach             
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('.send_from_item').click(function() {
                console.log($(this).text())
                $('#send_from').val($('#send_from').val() + $(this).text() + '');

            })
            document.querySelectorAll('.send_from_item').forEach(item => {
            item.style.cursor = 'pointer';
            });
        </script>

    <div class="col-3 form-group {{ $errors->has('other_from') ? 'has-error' : '' }}">
        <label for="other_from" class="control-label click" data-bs-toggle="modal" data-bs-target="#other_from_model"><font color="red">{{ 'ลงเลขคิว (คลิกเลือกข้อความแล้วกด Enter)' }}</font></label>
        <input class="form-control" name="other_from" type="text" id="other_from" value="{{ isset($visit->other_from) ? $visit->other_from : '' }}">
        {!! $errors->first('other_from', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="modal fade" id="other_from_model" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Queue Option</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach (App\Models\Visit::$QueueOption as $item)
                            <p class='other_from_item' style="display: list-item; margin-left : 1em;" onmouseover="this.style.color='red'" onmouseout ="this.style.color='black'"> {{ $item }}</p>
                        @endforeach             
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('.other_from_item').click(function() {
                console.log($(this).text())
                $('#other_from').val($('#other_from').val() + $(this).text() + '');

            })
            document.querySelectorAll('.other_from_item').forEach(item => {
            item.style.cursor = 'pointer';
            });            
        </script>

</div>
<br>
<hr>
<style type="text/css"></style>
 <div class="btn-group text-center" role="group" aria-label="Toggle Collapses" style="width: 100%">
   <button style="text-center" class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target=".collapse" aria-expanded="false" aria-controls="visitReason visitSymptom visitContact bodyCheck visitLab visitDiagnosis visitTreatment visitOther"><font size="4">คลิกย่อแท็บทั้งหมด</font></button>
  </div> 

<!-- <div class="btn-group text-center" role="group" aria-label="Toggle Collapses" style="width: 100%">
   <button id="collapseButton" style="text-center" class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target=".collapse" aria-expanded="false" aria-controls="visitReason visitSymptom visitContact bodyCheck visitLab visitDiagnosis visitTreatment visitOther">
       <font size="4">คลิกย่อแท็บทั้งหมด</font>
   </button>
</div> -->

<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        // หน่วงเวลาเล็กน้อยเพื่อให้ Bootstrap โหลดเสร็จสมบูรณ์ก่อนคลิกปุ่ม
        setTimeout(function() {
            document.getElementById("collapseButton").click();
        }, 500); // รอ 500 มิลลิวินาที
    });
</script> -->

<hr>
@include('visit.form.reason')

<br>

<div class="d-grid">
    <div class="row">
        <div class="col-11">
            <a class="btn btn-light" data-bs-toggle="collapse" href="#visitSymptom" role="button" aria-expanded="false" style="width: 100%" aria-controls="visitSymptom">
                <font size="4">ประวัติการเจ็บป่วยปัจจุบัน**</font>
            </a>
            
        </div>
        <div class="col-1">
                <div class="col-auto form-group">
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </div>
    </div>


</div>
<div class="collapse" id="visitSymptom" data-bs-parent="#accordionExample">
    <div class="card card-body">
        <div class="form-group {{ $errors->has('symptom') ? 'has-error' : '' }}">
            <label for="symptom" class="control-label click" data-bs-toggle="modal" data-bs-target="#symptom_model">{{ 'อาการเจ็บป่วยครั้งปัจจุบัน' }}</label>
            <textarea class="form-control" rows="5" name="symptom" type="textarea" id="symptom">{{ isset($visit->symptom) ? $visit->symptom : '' }}</textarea>
            {!! $errors->first('symptom', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="modal fade" id="symptom_model" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Symptom Option</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach (App\Models\Visit::$symptomOption as $item)
                            <p class='symptom_item' style="display: list-item; margin-left : 1em;" onmouseover="this.style.color='red'" onmouseout ="this.style.color='black'"> {{ $item }}</p>
                        @endforeach             
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('.symptom_item').click(function() {
                console.log($(this).text())
                $('#symptom').val($('#symptom').val() + $(this).text() + ',\n');

            })
            document.querySelectorAll('.symptom_item').forEach(item => {
            item.style.cursor = 'pointer';
            });             
        </script>

    </div>
</div>
<br>

@if (isset($visit))
    <div class="d-grid">

        <div class="row">
            <div>
                <a class="btn btn-warning" data-bs-toggle="collapse" href="#visitContact" role="button"
                    style="width: 100%" aria-expanded="false" aria-controls="visitContact">
                    <font size="4">คู่นอน คู่สัมผัส (contact)</font>
                </a>
            </div>
            <!-- <div class="col-1">
                <div class="col-auto form-group">
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </div> -->
        </div>

    </div>
    <div class="collapse" id="visitContact" data-bs-parent="#accordionExample">
        <div class="card card-body">
            @include('visit.contact', ['visit' => $visit, 'page' => $_GET['page']])
        </div>
    </div>
    <br>
@endif

@if (isset($visit))
    <div class="d-grid">


        <div class="row">
            <div>
                <a class="btn btn-success" data-bs-toggle="collapse" href="#bodyCheck" role="button"
                    style="width: 100%" aria-expanded="false" aria-controls="bodyCheck">
                    <font size="4">การตรวจร่างกาย</font>
                </a>
            </div>

        </div>

    </div>

    <div class="collapse " id="bodyCheck">
        <div class="card card-body">
            <div class="form-group {{ $errors->has('body_check') ? 'has-error' : '' }}">
                <label for="body_check" class="control-label">{{ 'การตรวจร่างกาย' }}</label>
                <textarea class="form-control" rows="5" name="body_check" type="textarea" id="body_check">{{ isset($visit->body_check) ? $visit->body_check : '' }}</textarea>
                {!! $errors->first('body_check', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="mb-3">
    <div class="mb-3">
    <label class="form-label">การมาร์คร่างกาย</label>
    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
        <label>เลือกสี:</label>
        <input type="color" id="markColorPicker" value="#e63946">

        <label>ขนาด:</label>
        <input type="range" id="markSize" min="3" max="20" value="3">

        <label>โหมด:</label>
        <select id="drawMode">
            <option value="point">มาร์คจุด</option>
            <option value="free">วาดเส้น</option>
        </select>

        <button type="button" onclick="undoLast()" class="btn btn-warning btn-sm">Undo</button>
        <button type="button" onclick="clearAll()" class="btn btn-danger btn-sm">Clear ทั้งหมด</button>
        <button type="button" onclick="zoom(1.2)" class="btn btn-secondary btn-sm">Zoom In</button>
        <button type="button" onclick="zoom(0.8)" class="btn btn-secondary btn-sm">Zoom Out</button>
        <button type="button" onclick="panMode = !panMode" class="btn btn-info btn-sm">เลื่อนภาพ: <span id="panToggle">OFF</span></button>
    </div>

    <canvas id="visitCanvas" width="1200" height="200" style="border: 1px solid #ccc; cursor: crosshair;"></canvas>
    <input type="hidden" name="body_check_marks" id="body_check_marks">
</div>

<script>
const canvas = document.getElementById('visitCanvas');
const ctx = canvas.getContext('2d');
const coords = [];
const colorPicker = document.getElementById('markColorPicker');
const sizePicker = document.getElementById('markSize');
const drawMode = document.getElementById('drawMode');
const panToggle = document.getElementById('panToggle');

const image = new Image();
image.src = 'https://192.168.1.250/public/images/visit_cards/card.png';

let scale = 1;
let offsetX = 0;
let offsetY = 0;
let isDrawing = false;
let isPanning = false;
let startPan = {};
let panMode = false;
let currentPath = [];

image.onload = () => {
    drawAll();
    loadExistingMarks();
};

function zoom(factor) {
    scale *= factor;
    drawAll();
}

canvas.addEventListener('mousedown', function(e) {
    const pos = getPos(e);
    if (panMode) {
        isPanning = true;
        startPan = { x: e.clientX, y: e.clientY };
    } else if (drawMode.value === 'free') {
        isDrawing = true;
        currentPath = [];
        currentPath.push([pos.x, pos.y]);
    }
});

canvas.addEventListener('mousemove', function(e) {
    if (isPanning) {
        const dx = (e.clientX - startPan.x) / scale;
        const dy = (e.clientY - startPan.y) / scale;
        offsetX += dx;
        offsetY += dy;
        startPan = { x: e.clientX, y: e.clientY };
        drawAll();
        return;
    }

    if (isDrawing && drawMode.value === 'free') {
        const pos = getPos(e);
        currentPath.push([pos.x, pos.y]);

        drawAll();

        ctx.beginPath();
        const start = currentPath[0];
        ctx.moveTo(start[0] * canvas.width, start[1] * canvas.height);
        for (let i = 1; i < currentPath.length; i++) {
            ctx.lineTo(currentPath[i][0] * canvas.width, currentPath[i][1] * canvas.height);
        }
        ctx.strokeStyle = colorPicker.value;
        ctx.lineWidth = parseInt(sizePicker.value);
        ctx.lineCap = "round";
        ctx.stroke();
    }
});

canvas.addEventListener('mouseup', function() {
    if (isDrawing && drawMode.value === 'free') {
        isDrawing = false;
        if (currentPath.length > 1) {
            coords.push({
                type: 'path',
                points: currentPath,
                color: colorPicker.value,
                size: parseInt(sizePicker.value)
            });
            saveMarks();
        }
    }
    isPanning = false;
});

canvas.addEventListener('click', function(e) {
    if (drawMode.value !== 'point' || panMode) return;
    const pos = getPos(e);
    const comment = prompt("ใส่ข้อความกำกับจุดนี้ (หรือปล่อยว่าง)");
    if (comment === null) return;
    const mark = {
        type: 'point',
        x: pos.x.toFixed(4),
        y: pos.y.toFixed(4),
        color: colorPicker.value,
        note: comment,
        size: parseInt(sizePicker.value)
    };
    coords.push(mark);
    drawAll();
    saveMarks();
});

canvas.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    const pos = getPos(e);
    const radius = 0.02;
    const index = coords.findIndex(p => {
        if (p.type === 'point') {
            return Math.abs(p.x - pos.x) < radius && Math.abs(p.y - pos.y) < radius;
        } else if (p.type === 'path') {
            return p.points.some(pt => Math.abs(pt[0] - pos.x) < radius && Math.abs(pt[1] - pos.y) < radius);
        }
        return false;
    });
    if (index !== -1 && confirm('ต้องการลบมาร์คนี้ใช่หรือไม่?')) {
        coords.splice(index, 1);
        drawAll();
        saveMarks();
    }
});

function getPos(e) {
    const rect = canvas.getBoundingClientRect();
    const x = (e.clientX - rect.left - offsetX * scale) / (canvas.width * scale);
    const y = (e.clientY - rect.top - offsetY * scale) / (canvas.height * scale);
    return { x, y };
}


function undoLast() {
    coords.pop();
    drawAll();
    saveMarks();
}

function clearAll() {
    if (confirm('ลบทั้งหมด?')) {
        coords.length = 0;
        drawAll();
        saveMarks();
    }
}

function drawAll() {
    ctx.setTransform(1, 0, 0, 1, 0, 0); // รีเซ็ต transform ก่อน
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.setTransform(scale, 0, 0, scale, offsetX * scale, offsetY * scale);
    ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
    coords.forEach(drawMark);
}

function drawMark(mark) {
    if (mark.type === 'point') {
        const cx = mark.x * canvas.width;
        const cy = mark.y * canvas.height;
        ctx.beginPath();
        ctx.arc(cx, cy, mark.size || 7, 0, 2 * Math.PI);
        ctx.fillStyle = mark.color || '#e63946';
        ctx.fill();
        if (mark.note) {
            ctx.font = '13px Kanit, sans-serif';
            ctx.fillStyle = '#ad0505';
            ctx.fillText(mark.note, cx + 9, cy - 9);
        }
    } else if (mark.type === 'path') {
        ctx.beginPath();
        ctx.moveTo(mark.points[0][0] * canvas.width, mark.points[0][1] * canvas.height);
        for (let i = 1; i < mark.points.length; i++) {
            ctx.lineTo(mark.points[i][0] * canvas.width, mark.points[i][1] * canvas.height);
        }
        ctx.strokeStyle = mark.color;
        ctx.lineWidth = mark.size || 4;
        ctx.lineCap = "round";
        ctx.stroke();
    }
}

function saveMarks() {
    document.getElementById('body_check_marks').value = JSON.stringify(coords);
}

function loadExistingMarks() {
    const existing = @json($visit->body_check_marks ?? []);
    existing.forEach(m => coords.push(m));
    drawAll();
    saveMarks();
}

setInterval(() => {
    panToggle.innerText = panMode ? 'ON' : 'OFF';
}, 300);
</script>



    <br>

@endif

@if (isset($visit))
    <div class="d-grid">

        <div class="row">
            <div class="col-11">
                <a class="btn btn-primary" data-bs-toggle="collapse" href="#visitLab" role="button"
                    style="width: 100%" aria-expanded="false" aria-controls="visitLab">
                    <font size="4">Lab</font>
                </a>

            </div>
            <div class="col-1">
                <div class="col-auto form-group">
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </div>
        </div>

    </div>
    <div class="collapse " id="visitLab" data-bs-parent="#accordionExample">
        <div class="card card-body">
            @include('visit.form.lab', ['visit' => $visit, 'page' => 2])
        </div>
    </div>
    <br>
@endif

@if (isset($visit))
    <div class="d-grid">


        <div class="row">
            <div>
                <a class="btn btn-light" data-bs-toggle="collapse" href="#visitDiagnosis" role="button"
                    style="width: 100%" aria-expanded="false" aria-controls="visitDiagnosis">
                    <font size="4">วินิจฉัย**</font>
                </a>
            </div>
            <!-- <div class="col-1">
                <div class="col-auto form-group">
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </div> -->
        </div>


    </div>
    <div class="collapse  pb-2" id="visitDiagnosis" data-bs-parent="#accordionExample">
        <div class="card card-body">
            @include('visit.form.diagnosis', ['visit' => $visit])
        </div>
    </div>
    <br>
@endif

<div class="d-grid">


    <div class="row">
        <div class="col-11">
            <a class="btn btn-warning" data-bs-toggle="collapse" href="#visitTreatment" role="button"
                aria-expanded="false" style="width: 100%" aria-controls="visitTreatment">
                <font size="4">การรักษา + ให้คำแนะนำ</font>
            </a>
        </div>
<div class="col-1">
                <div class="col-auto form-group">
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </div>
    </div>

</div>
<div class="collapse " id="visitTreatment" data-bs-parent="#accordionExample">
    <div class="card card-body">


        @include('visit.form.medicine', ['visit' => $visit])
        <br>


        <div class="form-group {{ $errors->has('treatment') ? 'has-error' : '' }}">
            <label for="treatment" class="control-label click" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">{{ 'การให้คำแนะนำ' }}</label>

            <textarea class="form-control" rows="5" name="treatment" type="textarea" id="treatment">{{ isset($visit->treatment) ? $visit->treatment : '' }}</textarea>
            {!! $errors->first('treatment', '<p class="help-block">:message</p>') !!}
        </div>


        <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">การให้คำแนะนำ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach (App\Models\Visit::$treatmentOption as $item)
                            <!-- <p class='treatment_item' style="display: list-item; margin-left : 1em; ">&nbsp;{{ $item }}</p> -->
                            <p class='treatment_item' style="display: list-item; margin-left : 1em;" onmouseover="this.style.color='red'" onmouseout ="this.style.color='black'"> {{ $item }}</p>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('.treatment_item').click(function() {
                console.log($(this).text())
                $('#treatment').append($(this).text() + ', ');

            })
            document.querySelectorAll('.treatment_item').forEach(item => {
                item.style.cursor = 'pointer';
            });

        </script>

        <div class="form-group {{ $errors->has('consultation') ? 'has-error' : '' }}">
            <label for="consultation"
                class="control-label">{{ 'การให้สุขศึกษา/การปรึกษาเรื่อง Safe sex, Condom, ความรู้เรื่องโรค ยาและผลข้างเคียง' }}</label>
            {{-- <input class="form-control" name="consultation" type="number" id="consultation"
        value="{{ isset($visit->consultation) ? $visit->consultation : '' }}"> --}}
            <select class="form-control" name="consultation" id="consultation">
                @foreach (App\Models\Visit::$consultationOption as $key => $value)
                    <option value="{{ $key }}" @if (isset($visit->consultation) && $key == $visit->consultation) selected="selected" @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('consultation', '<p class="help-block">:message</p>') !!}
        </div>

        <div class="row">
            <div class="col-6 form-group {{ $errors->has('hiv_sti_test') ? 'has-error' : '' }}">
                <label for="hiv_sti_test"
                    class="control-label">{{ 'การให้คำปรึกษาเพื่อตรวจหาเชื้อ HIV/Syphilis' }}</label>
                {{-- <input class="form-control" name="hiv_sti_test" type="number" id="hiv_sti_test"
            value="{{ isset($visit->hiv_sti_test) ? $visit->hiv_sti_test : '' }}"> --}}

                <select class="form-control" name="hiv_sti_test" id="hiv_sti_test" data-bs-parent="#accordionExample">
                    @foreach (App\Models\Visit::$hivStiTestResuleOption as $key => $value)
                        <option value="{{ $key }}"
                            @if (isset($visit->hiv_sti_test) && $key == $visit->hiv_sti_test) selected="selected" @endif>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('hiv_sti_test', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-6 form-group {{ $errors->has('last_hiv_syphilis') ? 'has-error' : '' }}">
                <label for="last_hiv_syphilis" class="control-label">{{ 'ประวัติการตรวจ Sti ล่าสุด / On PrEP ล่าสุด' }}</label>
                <input class="form-control" name="last_hiv_syphilis" type="text" id="last_hiv_syphilis"
                    value="{{ isset($visit->last_hiv_syphilis) ? $visit->last_hiv_syphilis : '' }}">
                {!! $errors->first('last_hiv_syphilis', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-6 form-group {{ $errors->has('hiv_sti_test_date') ? 'has-error' : '' }}">
                <label for="hiv_sti_test_date" class="control-label">{{ 'Hiv Test Date' }}</label>
                <input class="form-control" name="hiv_sti_test_date" type="text" id="hiv_sti_test_date"
                    value="{{ isset($visit->hiv_sti_test_date) ? $visit->hiv_sti_test_date : '' }}">
                {!! $errors->first('hiv_sti_test_date', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-6 form-group {{ $errors->has('hiv_sti_test_resule') ? 'has-error' : '' }}">
                <label for="hiv_sti_test_resule" class="control-label">{{ 'Hiv Test Result' }}</label>
                {{-- <input class="form-control" name="hiv_sti_test_resule" type="text" id="hiv_sti_test_resule"
        value="{{ isset($visit->hiv_sti_test_resule) ? $visit->hiv_sti_test_resule : '' }}"> --}}
            <select class="form-control" name="hiv_sti_test_resule" id="hiv_sti_test_resule">
                @foreach (App\Models\Visit::$hivtestresultOption as $key => $value)
                    <option value="{{ $key }}" @if (isset($visit->hiv_sti_test_resule) && $key == $visit->hiv_sti_test_resule) selected="selected" @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('hiv_sti_test_resule', '<p class="help-block">:message</p>') !!}
            </div>
            
            <!-- {{-- <input class="form-control" name="hiv_sti_test_resule" type="text" id="hiv_sti_test_resule"
        value="{{ isset($visit->hiv_sti_test_resule) ? $visit->hiv_sti_test_resule : '' }}"> --}}
            <select class="form-control" name="hiv_sti_test_resule" id="hiv_sti_test_resule">
                @foreach (App\Models\Visit::$hivtestresultOption as $key => $value)
                    <option value="{{ $value }}" @if (isset($visit->hiv_sti_test_resule) && $key == $visit->hiv_sti_test_resule) selected="selected" @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('hiv_sti_test_resule', '<p class="help-block">:message</p>') !!} -->



        </div>


        <div class="row">
            <div class="col-4 form-group {{ $errors->has('provide_condom_site') ? 'has-error' : '' }}">
                <label for="provide_condom_site" class="control-label">{{ 'แจกถุงยางอนามัย ขนาด' }}</label>
                <input class="form-control" name="provide_condom_site" type="text" id="provide_condom_site"
                    value="{{ isset($visit->provide_condom_site) ? $visit->provide_condom_site : '' }}">
                {!! $errors->first('provide_condom_site', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-4 form-group {{ $errors->has('provide_condom_quantity') ? 'has-error' : '' }}">
                <label for="provide_condom_quantity"
                    class="control-label">{{ 'แจกถุงยางอนามัย จำนวน (ชิ้น)' }}</label>
                <input class="form-control" name="provide_condom_quantity" type="number"
                    id="provide_condom_quantity"
                    value="{{ isset($visit->provide_condom_quantity) ? $visit->provide_condom_quantity : '' }}">
                {!! $errors->first('provide_condom_quantity', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-4 form-group {{ $errors->has('provide_lubricant_quantity') ? 'has-error' : '' }}">
                <label for="provide_lubricant_quantity"
                    class="control-label">{{ 'แจกสารหล่อลื่น จำนวน (ชิ้น)' }}</label>
                <input class="form-control" name="provide_lubricant_quantity" type="number"
                    id="provide_lubricant_quantity"
                    value="{{ isset($visit->provide_lubricant_quantity) ? $visit->provide_lubricant_quantity : '' }}">
                {!! $errors->first('provide_lubricant_quantity', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        @if (0)
            <hr>
            <div id="touchTrackDiv">
                <h4 class="text-center">การติดตามคู่สัมผัส</h4>

                <div class="table-responsive">
                    <table id="touchTrackTable" class="table">
                        <thead>
                            <tr>
                                <th>ชื่อ-นามสกุล </th>
                                <th>การติดตามคู่สัมผัส </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
    @foreach ($visit->trush_tracks() as $key => $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td data-touch-tracing="{{ $item->touchTracing }}">
                {{ App\Models\Visit::$touchTracingOption[$item->touchTracing] }}
            </td>
            <td>
                <button class="btn btn-danger btn-sm" type="button">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
    @endforeach
</tbody>

                    </table>
                </div>
                <div class="row">
                    <div class="col-6 form-group {{ $errors->has('touch_tracing_name') ? 'has-error' : '' }}">
                        <label for="touch_tracing_name" class="control-label">{{ 'ชื่อ-นามสกุล' }}</label>
                        <input class="form-control" name="touch_tracing_name" type="text" id="touch_tracing_name"
                            value="">
                    </div>

                    <div class="col-5 form-group {{ $errors->has('touch_tracing') ? 'has-error' : '' }}">
                        <label for="touch_tracing" class="control-label">{{ 'การติดตามคู่สัมผัส' }}</label>

                        <select class="form-control" name="touch_tracing_id" id="touch_tracing_id">
                            @foreach (App\Models\Visit::$touchTracingOption as $key => $value)
                                <option value="{{ $key }}" name=" {{ $value }}">
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        {!! $errors->first('touch_tracing', '<p class="help-block">:message</div>') !!}
                    </div>

                    <div class="col-1">
                        <div style="padding-top: 24px">
                            <button type="button" class="btn btn-info" style="width: 100%"><i class="fa fa-plus"
                                    aria-hidden="true"></i> </button>
                        </div>
                    </div>

                </div>

                <script>
                    $(document).ready(function() {
                        function removeRow() {
                            $(this).closest('tr').remove()
                        }
                        $("#touchTrackTable .btn-danger").click(removeRow)

                        $("#touchTrackDiv .btn-info").click(function() {
                            console.log("click")
                            $("#touchTrackTable tbody").append(`
                        <tr>
                                <td>${$("#touch_tracing_name").val()}</td>
                                <td value="${$("#touch_tracing_id").val()}">${$("#touch_tracing_id").find('option:selected').attr("name")}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash"
                                            aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        `)

                            $("#touchTrackTable .btn-danger").unbind("click");
                            $("#touchTrackTable .btn-danger").click(removeRow)
                            $("#touch_tracing_name").val("");
                            $("#touch_tracing_id").val(0)
                        })
                        $("#touchTrackTable body").click(function() {
                            $(this).closest('tr').remove()
                        })
                    });
                </script>
            </div>
        @endif

    </div>
</div>
<br>
<div class="d-grid">
<div class="row">
            <div>
                <a class="btn btn-light" data-bs-toggle="collapse" href="#visitFollowup" role="button"
                    style="width: 100%" aria-expanded="false" aria-controls="visitFollowup">
                    <font size="4">**การติดตามผู้ป่วย**</font>
                </a>
            </div>
            <!-- <div class="col-1">
                <div class="col-auto form-group">
                    <input class="btn btn-primary" type="submit" value="Save">
                </div>
            </div> -->
        </div>
         <div class="collapse  pb-2" id="visitFollowup" data-bs-parent="#accordionExample">
                    <div class="card card-body">
{{-- ส่วนติดตามผู้ป่วย (อยู่นอกฟอร์มหลัก) --}}
@include('visit.form.followup', ['visit' => $visit, 'page' => request()->get('page')])
</div>
</div>
<br>
<div class="d-grid">


    <div class="row">
        <div>
            <a class="btn btn-light" data-bs-toggle="collapse" href="#visitOther" role="button"
                aria-expanded="false" style="width: 100%" aria-controls="visitOther">
                <font size="4">วันนัด</font>
            </a>
        </div>
       <!--  <div class="col-1">
            <div class="col-auto form-group">
                <input class="btn btn-primary" type="submit" value="Save">
            </div>
        </div> -->
    </div>
</div>
<div class="collapse " id="visitOther" data-bs-parent="#accordionExample">
    <div class="card card-body">



    <div class="row">
            <div class="col-2 form-group {{ $errors->has('appointment') ? 'has-error' : '' }}">
                <label for="appointment" class="control-label">{{ 'Appointment' }}</label>
                <input class="form-control date" name="appointment" type="text" id="appointment"
                    value="{{ isset($visit->appointment) ? $visit->appointment : '' }}">
                {!! $errors->first('appointment', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-10 form-group {{ $errors->has('appointment_reason') ? 'has-error' : '' }}">
                <label for="appointment_reason" class="control-label click" data-bs-toggle="modal"
                    data-bs-target="#appointment_reason_model">{{ 'Appointment Reason' }}</label>
                <input class="form-control " name="appointment_reason" type="text" id="appointment_reason"
                    value="{{ isset($visit->appointment_reason) ? $visit->appointment_reason : '' }}">
                {!! $errors->first('appointment_reason', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="modal fade" id="appointment_reason_model" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Appointment Reason Option</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach (App\Models\Visit::$reasonAppointmentOption as $item)
                            <p class="appointment_reason_item" 
                               style="display: list-item; margin-left: 1em;"
                               onmouseover="this.style.color='red'" 
                               onmouseout="this.style.color='black'">
                                {{ $item }}
                            </p>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.querySelectorAll('.appointment_reason_item').forEach(item => {
    item.style.cursor = 'pointer';  // ใช้ pointer ในการเปลี่ยน cursor ให้เป็นนิ้ว
});
            $('.appointment_reason_item').click(function() {
    var currentValue = $('#appointment_reason').val();
    if (currentValue) {
        $('#appointment_reason').val(currentValue + $(this).text().trim() + ', '); // เพิ่ม .trim() เพื่อลบช่องว่างก่อนหลังคำ
    } else {
        $('#appointment_reason').val($(this).text().trim() + ', ');
    }
});


        </script>

    </div>
</div>
<br>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- ติดตั้ง jQuery และ jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/bootstrap-datepicker-thai.js"></script>
<script src="/js/bootstrap-datepicker.th.js"></script>

<script type="text/javascript">
    function get_trush_tracks() {
        let trush_tracks = [];

        $("#touchTrackTable tbody tr").each(function () {
            let name = $(this).find("td:nth-child(1)").text();
            let touchTracing = $(this).find("td:nth-child(2)").data("touch-tracing");

            trush_tracks.push({
                name: name.trim(),
                touchTracing: touchTracing
            });
        });

        return trush_tracks;
    }

    $(function () {
        // ตั้งค่า Datepicker
        $('.date').datepicker({
            language: 'th-th',
        });

        // กำหนดค่า status ของ visit
        $('#status').val({{ $visit->status }});

        // เปิด collapse ทุกรายการ (ถ้ามี)
        $('.collapse').collapse('show');

        // จัดการการส่งฟอร์ม
        $("#visit_edit_form").submit(function () {
            console.log("visit_edit_form");

            let trush_tracks = get_trush_tracks();
            let $form = $(this);

            let $existingInput = $form.find("input[name='trush_tracks']");
            if ($existingInput.length === 0) {
                $("<input />")
                    .attr("type", "hidden")
                    .attr("name", "trush_tracks")
                    .val(JSON.stringify(trush_tracks))
                    .appendTo($form);
            } else {
                $existingInput.val(JSON.stringify(trush_tracks));
            }

            return true;
        });

        // ซ่อน/แสดง input ตามอาชีพ
        $('#occupation').change(function () {
            $('#organization_div').hide();
            $('#other_occupation_div').hide();
            $('#organization').val("");
            $('#other_occupation').val("");

            switch ($(this).val()) {
                case '10':
                    $('#organization_div').show();
                    break;
                case '11':
                    $('#other_occupation_div').show();
                    break;
            }
        });
    });
</script>

@php
$visitStatus = "";
$currentVisitDate = date('Y-m-d', strtotime(str_replace('/', '-', $visit->date ?? date('d/m/Y'))));

// ดึง visit ทั้งหมดของผู้ป่วยเรียงจากใหม่ → เก่า
$allVisits = $visit->patient2->visits2()->orderBy('date', 'desc')->get();

// หา visit ก่อนหน้าจากตำแหน่งใน array
$currentIndex = $allVisits->search(fn($v) => $v->id === $visit->id);
$previousVisit = $currentIndex !== false && $currentIndex + 1 < $allVisits->count()
    ? $allVisits[$currentIndex + 1]
    : null;

if ($previousVisit && !empty($previousVisit->appointment)) {
    $appointmentDate = date('Y-m-d', strtotime($previousVisit->appointment));
    $formattedVisitDate = date('d/m/Y', strtotime($currentVisitDate));
    $formattedAppointmentDate = date('d/m/Y', strtotime($appointmentDate));
    $reason = $previousVisit->appointment_reason ?? "ไม่ระบุ";

    if ($currentVisitDate > $appointmentDate) {
        // มาหลังวันนัด
        $visitStatus = "<span style='color: red;'>ผู้ป่วยมาหลังวันนัด</span><br>
            วันที่นัด: <b style='color: red;'>{$formattedAppointmentDate}</b><br>
            เหตุผล: <span style='color: blue; font-size: 18px;'>{$reason}</span>";
    } elseif ($currentVisitDate == $appointmentDate) {
        // มาตรงวันนัด
        $visitStatus = "<span style='color: green;'>ผู้ป่วยมาตรงวันนัด</span><br>
            วันที่นัด: {$formattedAppointmentDate}<br>
            เหตุผล: <span style='color: blue; font-size: 18px;'>{$reason}</span>";
    } else {
        // มาหาก่อนวันนัด
        $visitStatus = "<span style='color: dodgerblue;'>ผู้ป่วยมาหาก่อนวันนัด</span><br>
            วันที่นัด: {$formattedAppointmentDate}<br>
            เหตุผล: <span style='color: blue; font-size: 18px;'>{$reason}</span>";
    }
}
@endphp




<script>
document.addEventListener("DOMContentLoaded", function () {
    let visitStatus = @json($visitStatus);

    let drugAllergy = document.getElementById("drug_allergy").value.trim();
    let congenitalDisease = document.getElementById("congenitaldisease").value.trim();

    let hasDrugAllergy = drugAllergy && drugAllergy !== "ปฏิเสธ" && drugAllergy !== "-";
    let hasCongenitalDisease = congenitalDisease && congenitalDisease !== "ปฏิเสธ" && congenitalDisease !== "-";

    let patientInfoMessage = "";
    if (hasDrugAllergy) {
        patientInfoMessage += `<b>แพ้ยา:</b> <span style="color: red; font-size: 18px;">${drugAllergy}</span><br>`;
    }
    if (hasCongenitalDisease) {
        patientInfoMessage += `<b>โรคประจำตัว:</b> <span style="color: red; font-size: 18px;">${congenitalDisease}</span>`;
    }

    if ((visitStatus && visitStatus.trim() !== "") || patientInfoMessage) {
        let fullMessage = "";

        if (visitStatus && visitStatus.trim() !== "") {
            fullMessage += `<div style="margin-bottom: 10px;">${visitStatus}</div>`;
        }
        if (patientInfoMessage) {
            fullMessage += `<div>${patientInfoMessage}</div>`;
        }

        // 🔴 แสดงไอคอน "warning" ถ้ามีแพ้ยา หรือมาหลังนัด
        let showIcon = "info";
        if (visitStatus.includes("หลังวันนัด") || hasDrugAllergy) {
            showIcon = "warning";
        }

        Swal.fire({
    toast: true,
    icon: showIcon,
    title: "แจ้งเตือน",
    html: `<div style="max-width: 250px; white-space: normal;">${fullMessage}</div>`,
    showConfirmButton: false,
    timer: 8000,
    timerProgressBar: true,
    position: "bottom-start", // ซ้ายล่าง
    customClass: {
        popup: 'swal2-small swal-blue',
        title: 'swal-title-small swal-blue-title',
        htmlContainer: 'swal-html-small swal-blue-text'
    }
});

    }

    // ตรวจจับเมื่อเปลี่ยนค่า
    ["congenitaldisease", "drug_allergy"].forEach(id => {
        document.getElementById(id).addEventListener("change", function () {
            let drugAllergy = document.getElementById("drug_allergy").value.trim();
            let congenitalDisease = document.getElementById("congenitaldisease").value.trim();

            let hasDrugAllergy = drugAllergy && drugAllergy !== "ปฏิเสธ" && drugAllergy !== "-";
            let hasCongenitalDisease = congenitalDisease && congenitalDisease !== "ปฏิเสธ" && congenitalDisease !== "-";

            let patientInfoMessage = "";
            if (hasDrugAllergy) {
                patientInfoMessage += `<b>แพ้ยา:</b> <span style="color: red; font-size: 18px;">${drugAllergy}</span><br>`;
            }
            if (hasCongenitalDisease) {
                patientInfoMessage += `<b>โรคประจำตัว:</b> <span style="color: red; font-size: 18px;">${congenitalDisease}</span>`;
            }

            if (patientInfoMessage) {
                Swal.fire({
    toast: true,
    icon: showIcon,
    title: "แจ้งเตือน",
    html: `<div style="max-width: 250px; white-space: normal;">${fullMessage}</div>`,
    showConfirmButton: false,
    timer: 8000,
    timerProgressBar: true,
    position: "bottom-start", // ซ้ายล่าง
    customClass: {
        popup: 'swal2-small swal-blue',
        title: 'swal-title-small swal-blue-title',
        htmlContainer: 'swal-html-small swal-blue-text'
    }
});

            }
        });
    });
});

</script>
<style>
.swal2-small {
    width: 260px !important;
    font-size: 14px !important;
    border-radius: 10px !important;
    padding: 8px !important;
}

.swal-title-small {
    font-size: 16px !important;
    margin-bottom: 5px;
}

.swal-html-small {
    font-size: 14px !important;
}

.swal-blue {
    background-color: #e8f4fd !important;
    color: #084298 !important;
}

.swal-blue-title {
    color: #084298 !important;
}

.swal-blue-text {
    color: #084298 !important;
}
</style>


<style>
    /* กำหนดขนาดข้อความใน title และ html */
    /* เปลี่ยนสีข้อความ title ให้เป็นสีแดง */
.swal-red-title {
    color: red !important;  /* ทำให้ title เป็นสีแดง */
}

/* เปลี่ยนสีข้อความ html ของ SweetAlert ให้เป็นสีแดง */
.swal-red .swal2-popup {
    background-color: #fff3f3 !important;  /* เปลี่ยนสีพื้นหลังเป็นสีอ่อน */
    border: 1px solid red !important; /* เพิ่มเส้นขอบสีแดง */
}

.swal-red-text .swal2-html-container {
    color: red !important; /* เปลี่ยนสีข้อความใน html ให้เป็นสีแดง */
}

.swal-title-large {
    font-size: 24px !important;
}

.swal-html-large {
    font-size: 18px !important;
}

</style>

