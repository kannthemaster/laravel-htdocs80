<div class="row g-4">
    <div class="form-group">
    <label for="photo">รูปผู้ป่วย</label><br>

    {{-- ปุ่มเลือกไฟล์จากเครื่อง --}}
    <input type="file" accept="image/*" capture="environment" name="photo" id="photoInput" class="form-control mb-2">

    {{-- แสดงภาพตัวอย่าง --}}
    <img id="preview" src="#" alt="Preview" style="display:none; max-height: 200px;" class="img-thumbnail">

    {{-- พื้นที่วางรูป (Paste Image) --}}
    <div id="pasteArea" contenteditable="true" 
         style="border: 2px dashed #ccc; padding: 10px; min-height: 80px; margin-bottom: 10px; cursor: text;">
        คลิกที่นี่แล้วเสียบบัตรประชาชน เพื่อกรอกข้อมูล
    </div>

    {{-- ปุ่มเปิดกล้อง --}}
    <button type="button" class="btn btn-secondary mt-2" onclick="openCamera()">📷 ถ่ายด้วยกล้อง</button>

    {{-- วิดีโอกล้อง --}}
    <video id="camera" autoplay style="display: none; max-height: 240px;"></video>

    {{-- ปุ่มถ่ายรูป --}}
    <button type="button" class="btn btn-success mt-2" id="snap" style="display: none;" onclick="takeSnapshot()">📸 ถ่ายภาพ</button>

    {{-- canvas เก็บภาพกล้อง --}}
    <canvas id="snapshot" name="canvas" style="display: none;"></canvas>
</div>

<script>
    const video = document.getElementById('camera');
    const canvas = document.getElementById('snapshot');
    const photoInput = document.getElementById('photoInput');
    const preview = document.getElementById('preview');
    const pasteArea = document.getElementById('pasteArea');

    function openCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.style.display = 'block';
                document.getElementById('snap').style.display = 'inline-block';
            })
            .catch(err => alert("ไม่สามารถเข้าถึงกล้องได้: " + err));
    }

    function resizeImage(image, maxWidth, callback) {
        const canvasResize = document.createElement('canvas');
        const ctx = canvasResize.getContext('2d');

        let width = image.width;
        let height = image.height;

        if (width > maxWidth) {
            height = height * (maxWidth / width);
            width = maxWidth;
        }

        canvasResize.width = width;
        canvasResize.height = height;

        ctx.drawImage(image, 0, 0, width, height);

        canvasResize.toBlob(blob => {
            callback(blob);
        }, 'image/jpeg', 0.8); // คุณภาพไฟล์ jpeg 80%
    }

    function takeSnapshot() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        const img = new Image();
        img.onload = () => {
            resizeImage(img, 800, (blob) => {
                const file = new File([blob], "photo.jpg", { type: "image/jpeg" });
                const dt = new DataTransfer();
                dt.items.add(file);
                photoInput.files = dt.files;

                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';

                video.style.display = 'none';
                document.getElementById('snap').style.display = 'none';
            });
        };
        img.src = canvas.toDataURL('image/jpeg');
    }

    // แก้ไข event change ของ input ไฟล์ เพื่อ resize ก่อนแสดง preview
    photoInput.addEventListener("change", function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const img = new Image();
            const url = URL.createObjectURL(file);

            img.onload = () => {
                resizeImage(img, 800, (blob) => {
                    const resizedFile = new File([blob], file.name, { type: "image/jpeg" });
                    const dt = new DataTransfer();
                    dt.items.add(resizedFile);
                    photoInput.files = dt.files;

                    preview.src = URL.createObjectURL(resizedFile);
                    preview.style.display = 'block';

                    URL.revokeObjectURL(url);
                });
            };

            img.src = url;
        }
    });

    // เพิ่ม event listener สำหรับ paste image ลงใน pasteArea
    pasteArea.addEventListener('paste', function (e) {
        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            if (item.kind === 'file') {
                const blob = item.getAsFile();

                const reader = new FileReader();
                reader.onload = function (event) {
                    const base64data = event.target.result;

                    const img = new Image();
                    img.onload = () => {
                        resizeImage(img, 800, (blobResized) => {
                            const file = new File([blobResized], "pasted_image.jpg", { type: "image/jpeg" });
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            photoInput.files = dt.files;

                            preview.src = URL.createObjectURL(file);
                            preview.style.display = 'block';

                            // ล้างข้อความใน pasteArea (optional)
                            pasteArea.innerHTML = '';
                        });
                    };
                    img.src = base64data;
                };
                reader.readAsDataURL(blob);

                e.preventDefault();
                break; // รับแค่ภาพแรกจาก clipboard
            }
        }
    });
</script>


    <div class="col-2 form-group {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code" class="control-label">{{ 'หมายเลขประจำตัว(HN.)' }}</label>
        <input class="form-control" name="code" type="text" id="code" value="{{ isset($patient->code) ? $patient->code : App\Models\Patient::calNH() }}">
        {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
    </div>
    <!-- <div class="col-2  form-group {{ $errors->has('prefix') ? 'has-error' : '' }}">
        <label for="prefix" class="control-label">{{ 'คำนำหน้าชื่อ' }}</label>
        {{-- <input class="form-control" name="prefix" type="number" id="prefix" value="{{ isset($patient->prefix) ? $patient->prefix : ''}}" required> --}}
        <select id="prefix" name="prefix" class="form-control">
    @foreach(\App\Models\Patient::$prefixOption as $key => $label)
        <option value="{{ $key }}">{{ $label }}</option>
    @endforeach
</select>
        {!! $errors->first('prefix', '<p class="help-block">:message</p>') !!}
    </div> -->
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
    <div class="col-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <label for="name" class="control-label">{{ 'ชื่อ' }}</label>
        <input class="form-control" name="name" type="text" id="name" value="{{ isset($patient->name) ? $patient->name : '' }}" required>
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-4 form-group {{ $errors->has('surname') ? 'has-error' : '' }}">
        <label for="surname" class="control-label">{{ 'นามสกุล' }}</label>
        <input class="form-control" name="surname" type="text" id="surname" value="{{ isset($patient->surname) ? $patient->surname : '' }}" required>
        {!! $errors->first('surname', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<!-- <div class="row g-4">
    <div class="col-2  form-group {{ $errors->has('sex') ? 'has-error' : '' }}">
        <label for="sex" class="control-label">{{ 'เพศ' }}</label>
        {{-- <input class="form-control" name="sex" type="number" id="sex" value="{{ isset($patient->sex) ? $patient->sex : ''}}" > --}}
        <select id="sex" name="sex" class="form-control">
    @foreach(\App\Models\Patient::$sexOption as $key => $label)
        <option value="{{ $key }}">{{ $label }}</option>
    @endforeach
</select>
        {!! $errors->first('sex', '<p class="help-block">:message</p>') !!}
    </div> -->
<div class="row g-4">
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
    <div class="col-2  form-group {{ $errors->has('first_visit') ? 'has-error' : '' }}">
        <label for="first_visit" class="control-label">{{ 'วันที่มาครั้งแรก' }}</label>
        {{-- <input class="form-control" name="first_visit" type="date" id="first_visit" value="{{ isset($patient->first_visit) ? $patient->first_visit : ''}}" > --}}
        <input class="form-control date" name="first_visit" type="text" id="first_visit" value="{{ isset($patient->first_visit) ? $patient->first_visit : date('d').'/'.date('m').'/'.(date('Y')+543) }}">

        {!! $errors->first('first_visit', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-3 form-group {{ $errors->has('id_card_number') ? 'has-error' : '' }}">
    <label for="id_card_number" class="control-label">เลขบัตรประชาชน / Passport</label>
    <input class="form-control" name="id_card_number" type="text" id="id_card_number"
        value="{{ old('id_card_number', $patient->id_card_number ?? '') }}"
        required>
    {!! $errors->first('id_card_number', '<p class="help-block">:message</p>') !!}
    <small id="id_card_feedback" style="color: red;"></small>
</div>

      
    <div class="col-3 form-group {{ $errors->has('birth_date') ? 'has-error' : '' }}">
        <label for="birth_date" class="control-label">{{ 'วันเกิด' }}</label>
        <input class="form-control date" name="birth_date" type="text" id="birth_date" value="{{ isset($patient->birth_date) ? $patient->birth_date : '' }}" required>
        {!! $errors->first('birth_date', '<p class="help-block">:message</p>') !!}
    </div>
<div class="col-2 form-group {{ $errors->has('tel') ? 'has-error' : '' }} {{ isset($patient) && $patient->phone_changed ? 'border-red' : '' }}">
    <label for="tel" class="control-label">{{ 'เบอร์โทร' }}</label>
    <input class="form-control" name="tel" type="text" id="tel" value="{{ isset($patient->tel) ? $patient->tel : '' }}" required>
    {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
    
    <div class="row">
        <!-- ช่องติ๊กแจ้งเปลี่ยนเบอร์โทร -->
        <div class="col-12 form-group">
            <input type="hidden" name="phone_changed" value="0"> <!-- ค่าพื้นฐานเป็น 0 -->
            <input type="checkbox" name="phone_changed" id="phone_changed" value="1" 
                   @if (isset($patient) && $patient->phone_changed) checked @endif>
            <label for="phone_changed" class="control-label">แจ้งเปลี่ยนเบอร์โทร
            <small class="text-success">ติ๊กออกเมื่อมีการเปลี่ยนเบอร์โทรแล้ว</small>
            </label>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('id_card_number');
    const feedback = document.getElementById('id_card_feedback');

    input.addEventListener('blur', function () {
        const idCard = input.value.trim();
        if (idCard.length > 0) {
            fetch("{{ route('check.id_card') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id_card_number: idCard })
            })
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    feedback.textContent = "หมายเลขนี้ถูกใช้ไปแล้ว";
                    input.classList.add('is-invalid');
                } else {
                    feedback.textContent = "";
                    input.classList.remove('is-invalid');
                }
            });
        }
    });
});
</script>


<!-- เพิ่ม CSS สำหรับ border สีแดง -->
<style>
    .border-red {
        border: 2px solid red;
    }

    #phone_changed:checked + label {
        color: red; /* เปลี่ยนสี label เป็นสีแดงเมื่อ checkbox ถูกติ๊ก */
    }
</style>

</div>
    </div>

</div>

<div class="row">
  <div class="col-3 form-group {{ $errors->has('nationality') ? 'has-error' : '' }}">
    <label for="nationality" class="control-label">{{ 'สัญชาติ' }}</label>
    <input type="text" class="form-control" name="nationality" id="nationality" placeholder="ค้นหา Country ID" value="{{ isset($patient->nationality) ? $patient->nationality : '' }}" required>
    {!! $errors->first('nationality', '<p class="help-block">:message</p>') !!}
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefixToSex = {
        1: 1, // นาย -> ชาย
        2: 2, // นาง -> หญิง
        3: 2, // นางสาว -> หญิง
        4: 1, // MR. -> ชาย
        5: 2, // MRS. -> หญิง
        6: 2, // MISS -> หญิง
        7: 2, // MS. -> หญิง
    };

    const prefixSelect = document.getElementById('prefix');
    const sexSelect = document.getElementById('sex');

    prefixSelect.addEventListener('change', function () {
        const selectedPrefix = parseInt(this.value);
        const mappedSex = prefixToSex[selectedPrefix] ?? '';

        sexSelect.value = mappedSex;
    });
});
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script type="text/javascript">
    $(function() {
        var countries = @json($countries);
        
        $("#nationality").autocomplete({
            source: countries.map(country => ({
                label: country.country_id + " - " + (country.nation_name_th || 'ไม่มีข้อมูล'), // แสดงข้อมูลสัญชาติภาษาไทย
                value: country.country_id,
                nationality: country.nation_name_th
            })),
            select: function(event, ui) {
                $('#nationality').val(ui.item.value);
                return false;
            }
        });
    });
</script>

    <div class="col-3 form-group {{ $errors->has('education') ? 'has-error' : '' }}">
        <label for="education" class="control-label">{{ 'การศึกษาสูงสุด' }}</label>
        {{-- <input class="form-control" name="education" type="number" id="education"
            value="{{ isset($patient->education) ? $patient->education : '' }}"> --}}

        <select class="form-control" name="education" id="education" required>
            @foreach (App\Models\Patient::$educationOption as $key => $value)
            <option value="{{ $key }}" @if (isset($patient->education) && $key == $patient->education) selected="selected" @endif>
                {{ $value }}
            </option>
            @endforeach
        </select>
        {!! $errors->first('education', '<p class="help-block">:message</p>') !!}
    </div>



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

    <div class="col-3 form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status" class="control-label">{{ 'Status' }}</label>
        {{-- <input class="form-control" name="status" type="number" id="status"
            value="{{ isset($patient->status) ? $patient->status : '' }}"> --}}

        <select class="form-control" name="status" id="status">
            @foreach (App\Models\Patient::$statusOption as $key => $value)
            <option value="{{ $key }}" @if (isset($patient->status) && $key == $patient->status) selected="selected" @endif>
                {{ $value }}
            </option>
            @endforeach
        </select>
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row">
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
</div>




<div class="form-group {{ $errors->has('congenitaldisease') ? 'has-error' : '' }}">
    <label for="congenitaldisease" class="control-label">{{ 'โรคประจำตัว' }}</label>
    <input class="form-control" name="congenitaldisease" type="text" id="congenitaldisease" 
        value="{{ isset($patient->congenitaldisease) ? $patient->congenitaldisease : '' }}" 
        required onblur="autoFillDefault(this)">
    {!! $errors->first('congenitaldisease', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('drug_allergy') ? 'has-error' : '' }}">
    <label for="drug_allergy" class="control-label">{{ 'ประวัติแพ้ยา' }}</label>
    <input class="form-control" name="drug_allergy" type="text" id="drug_allergy" 
        value="{{ isset($patient->drug_allergy) ? $patient->drug_allergy : '' }}" 
        required onblur="autoFillDefault(this)">
    {!! $errors->first('drug_allergy', '<p class="help-block">:message</p>') !!}
</div>

<script>
function autoFillDefault(inputField) {
    if (inputField.value.trim() === "") {
        inputField.value = "ปฏิเสธ";
    }
}
</script>




<!-- ลบปุ่ม submit ที่นี่ -->
<input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
<!-- <script src="/js/jquery.js"></script>
<script src="/js/jquery.datetimepicker.full.min.js"></script> -->

<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/bootstrap-datepicker-thai.js"></script>
<script src="/js/bootstrap-datepicker.th.js"></script>


<script type="text/javascript">
    $(function() {


        $('.date').datepicker({
            language: 'th-th',
        });

        $('#occupation').change(function() {
            $('#organization_div').hide()
            $('#other_occupation_div').hide()
            $('#organization').val("")
            $('#other_occupation').val("")
            switch ($('#occupation').val()) {
                case '10':
                    $('#organization_div').show()
                    
                    
                    break;
                case '11':
                    $('#other_occupation_div').show()
                    
                    break;
            }
        })

    });
</script>
