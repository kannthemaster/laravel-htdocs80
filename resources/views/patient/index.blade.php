@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Patient</div>
                    <div class="card-body">
                        <a href="{{ route('patient.create') }}" class="btn btn-success btn-sm" title="Add New Patient">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        <!-- ปุ่มสำหรับลิงค์ไปยังหน้าที่ระบุ -->
    <a href="https://192.168.1.250/public/queue/index.php" class="btn btn-primary btn-sm" title="Go to Queue" target="_blank">
        <i class="fa fa-list" aria-hidden="true"></i>เปิดโปรแกรมเรียกคิว
    </a>
                        <form method="GET" action="{{ url('/patient') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-end" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        {{-- @include('patient.searchForm', ['visit' => ""]) --}}

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table" id="example">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>วันที่มาล่าสุด</th><th>HN</th><th>ชื่อ-นามสกุล</th><th>เลขที่บัตร</th><th>เพศ</th><th>อายุ</th><th>status</th>
                                        
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($patient as $item)

                                    <tr>
                                        <!-- <td>{{ $loop->iteration }}</td> -->
                                        <td>{{ $item->lastVisitDate() }}</td>
                                        <td>{{ $item->code }}</td>
                                        <!-- <td>
                                            
                                            {{ $item->name .' '. $item->surname }}

                                        </td> -->
                                        <td>
                                        <style type="text/css">
                                            .text-blue {
                                            color: blue;
                                        }

                                        .text-black {
                                            color: black;
                                        }
                                        </style>
                                        @php
                                            $fullName = $item->name .' '. $item->surname;
                                            $color = (str_contains($fullName, '(L') || str_contains($fullName, '(T')) ? 'blue' : 'black';
                                        @endphp
                                        <span style="color: {{ $color }};">{{ $fullName }}</span>
                                        </td>

                                        <td>{{ $item->id_card_number }}</td>
                                        <td>{{ App\Models\Patient::$sexOption[$item->sex] }}</td>
                                        <td>{{ $item->age() }}</td>
                                        <td>{{ App\Models\Patient::$statusOption[$item->status] }}</td>
                                        
                                        

                                        <td>
                                            <!-- <a href="{{ url('/patient/' . $item->id) }}" title="View Patient"></a> -->
                                            <!-- <button class="btn btn-info btn-sm" ><i class="fa fa-eye" aria-hidden="true" ></i> </button> -->
                                            <a href="{{ url('/patient/' . $item->id . '/edit') }}" title="Edit Patient"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> Visit </button></a>
<!--                                             <form method="POST" action="{{ url('/patient' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Patient" onclick="return confirm(&quot;คุณจะลบ {{ $item->name .' '. $item->surname }} หรือไม่ delete?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> ลบผู้ป่วย </button>
                                            </form> -->
                                            <form method="POST" action="{{ url('/patient' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->name }} {{ $item->surname }}')">
                                                <i class="fa fa-trash" aria-hidden="true"></i> ลบผู้ป่วย
                                                </button>
                                            </form>

                                            {{-- <a href="{{ route('visit.create',['patient' => $item->id]) }}" title="Back"><button class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Visit</button></a> --}}
                                            <button class="btn btn-info btn-sm" id="show-details-{{ $item->id }}">Print Sticker</button>
                                            <button
    class="btn btn-success btn-print-opd btn-sm"
    data-code="{{ $item->code }}"
    data-prefix="{{ App\Models\Patient::$prefixOption[$item->prefix] }}"
    data-name="{{ $item->name }}"
    data-surname="{{ $item->surname }}"
    data-sex="{{ App\Models\Patient::$sexOption[$item->sex] }}"
    data-idcard="{{ $item->id_card_number }}"
    data-birth="{{ $item->birth_date }}"
    data-age="{{ $item->age() }}"
    data-tel="{{ $item->tel }}"
    data-email="{{ $item->email }}"
    data-address="{{ optional($item->address2)->fullAddress() }}"
    data-nationality="{{ $item->nationality }}"
    data-education="{{ App\Models\Patient::$educationOption[$item->education] ?? '-' }}"
    data-marital="{{ App\Models\Patient::$maritalStatusOption[$item->marital_status] ?? '-' }}"
    data-statusid="{{ $item->status }}"
    data-occupationid="{{ $item->occupation }}"
    data-otheroccupation="{{ $item->other_occupation }}"
    data-disease="{{ $item->congenitaldisease }}"
    data-allergy="{{ $item->drug_allergy }}"
    data-organization="{{ $item->organization }}"
>
    OPD CARD
</button>



                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $patient->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>
<!-- -------------------------------- Modal 1 -------------------------------- -->

<div class="modal fade" id="myModal" role="dialog" >
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    
<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Patient Details</h4>
    </div>
    <div class="modal-body" id="box" media="print">
        <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
<style>
    .vertical{
     transform: rotate(180deg);
     writing-mode: vertical-lr;
     text-align: center;
}

</style> 
<table height="100%" width="100%" >
 <tr>
    <td nowrap="1">
        <font class="hn" size="5"><b>  S-<span></span> | </b></font><font class="status" size="2"><b><span></span></b></font>     
    </td>
<tr>
    <td nowrap="1">
    <font class="name-surname" size="4.5" style="line-height: 0.1;"> <span></span></font>
    </td>

</tr>
<tr>
    <td nowrap="1">
    <font class="age" size="2" style="line-height: 0.1;" > Age: <span></span></font><font class="sex" size="2"> | Sex: <span></span></font> <font class="hn" size="3"> | visit: [{{ date('d-m-') }}{{ date('Y')+543 }}]</font>
    </td>
</tr>
<tr>
    <td nowrap="1">
      <font style="font-family: 'Libre Barcode 39', sans-serif; line-height: 0.5;" class="hn" size="6">*<span></span>*</font>
    </td>
</tr>
</table>
<!-- //////////////////////////////////////////////////////////////    -->    
    </div>
    <div class="modal-footer">
      <a href="#"><button class="btn btn-success" onclick="printSticker('box')">Print</button></a>
      <button type="button" class="btn btn-primary no-print" onclick="closeModal1()">Close</button>

<script>
function closeModal1() {
    document.activeElement.blur(); // เคลื่อน focus ออกก่อน
    $('#myModal').modal('hide');   // ใช้ Bootstrap จัดการซ่อน modal
}
</script>


    </div>
  </div>
  </div>
</div>
<!-- -------------------------------- Modal 1 -------------------------------- -->
<!-- -------------------------------- Modal 2 -------------------------------- -->
<div class="modal fade" id="myModal2" role="dialog">
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>

    <div class="modal-dialog modal-a5">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">OPD CARD</h4>
            </div>
            <div class="modal-body" id="box2" media="print">
                <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
                <style>
                    .a5-paper {
                        width: 210mm;
                        height: 148mm;
                        padding: 10mm;
/*                        border: 1px solid black;*/
                        box-sizing: border-box;
                    }

                    /* ขนาดของ modal สำหรับ A5 แนวนอน */
                    .modal-a5 {
                        width: 793px;  /* กว้าง A5 แนวนอน */
                        height: 561px; /* สูง A5 แนวนอน */
                        max-width: 100%;
                        max-height: 100%;
                    }

                    .table-border {
                        width: 100%;
                        border-collapse: collapse;
                        text-align: left;
                    }

                    .table-border td, .table-border th {
                        border: 1px solid #ccc; /* สีเทาอ่อน */
                        padding: 5px;
                    }

                    .no-border {
                        border: none;
                    }

                    .checkbox-label {
                        margin-right: 20px;
                    }

                    .full-width {
                        width: 100%;
                    }

     @media print {
        #category-box {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    /* แสดง checkbox ที่เลือกแล้ว (checked) */
    input[type="checkbox"]:checked {
        -webkit-appearance: checkbox !important;
        appearance: checkbox !important;
        background-color: #000 !important; /* ให้เครื่องหมายถูกแสดง */
    }

    /* แสดง checkbox ทุกตัวในหน้าพิมพ์ */
    input[type="checkbox"] {
        -webkit-appearance: checkbox !important;
        appearance: checkbox !important;
        visibility: visible !important; /* ทำให้ checkbox แสดง */
    }

    /* แสดง modal ในขณะพิมพ์ */
    .modal, .modal-content {
        display: block !important;
        visibility: visible !important;
        position: absolute !important;
        z-index: 9999 !important;
    }

    /* ซ่อน backdrop ของ modal */
    .modal-backdrop {
        display: none !important;
    }

    /* ซ่อนปุ่มพิมพ์ */
    .btn-print-opd {
        display: none !important;
    }
    .no-print {
            display: none !important;
        }
}

                </style>

                <!-- เริ่มต้นตาราง -->
                <div class="a5-paper">
    <table class="table-border" style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td colspan="2" style="text-align: center;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c9/Thai_government_Garuda_emblem_%28Version_2%29.svg"
                     alt="Thai Emblem" style="width: 60px; vertical-align: middle;">
            </td>
            <td colspan="4" style="text-align: center; font-size: 18px; font-weight: bold;">
                Confidential<br>
                Service Registration Card
            </td>
            <td style="text-align: right;">
    <svg id="category-box" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
         style="display: inline-block; margin-right: 5px;">
      <rect id="category-box-rect" width="20" height="20" fill="#ffffff" stroke="#ccc"/>
    </svg>
    <div style="font-size: 20px; font-weight: bold; display: inline-block;">
        <span id="opd-code"></span>
    </div>
    <div style="font-family: 'Libre Barcode 39', sans-serif; font-size: 32px; line-height: 1;">
        <span id="opd-barcode"></span>
    </div>
</td>

        </tr>
        <tr>
            <td colspan="7" style="text-align: center; padding: 6px;">
                Preventive Medicine Service Center, Office of Disease Prevention and Control 1, Chiang Mai, Thailand
            </td>
        </tr>
        <tr>
            <td colspan="3"><span id="opd-title-name"></span></td>
            <td colspan="2"><span id="opd-surname"></span></td>
            <td colspan="2"><span id="opd-sex"></span></td>
        </tr>
        <tr>
            <td colspan="3"><span id="opd-idcard"></span></td>
            <td colspan="3"><span id="opd-birth"></span></td>
            <td colspan="1"><span id="opd-age"></span></td>
        </tr>
        <tr>
            <td colspan="7"><span id="opd-address"></span></td>
        </tr>
        <tr>
            <td colspan="3"><span id="opd-tel"></span></td>
            <td colspan="4"><span id="opd-email"></span></td>
        </tr>
        <tr>
            <td colspan="2"><span id="opd-nationality"></span></td>
            <td colspan="2"><span id="opd-education"></span></td>
            <td colspan="3"><span id="opd-marital"></span></td>
        </tr>
        <tr>
            <td colspan="7">
                Category:<br>
                <label><input type="checkbox" id="status-1"> MSM/MSW/TG</label>
                <label><input type="checkbox" id="status-2"> P</label>
                <label><input type="checkbox" id="status-3"> Migrant Worker</label>
                <label><input type="checkbox" id="status-4"> Prisoner</label>
                <label><input type="checkbox" id="status-5"> Youth</label>
                <label><input type="checkbox" id="status-6"> General Public</label>
                <label><input type="checkbox" id="status-7"> Drug User</label>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Occupation:<br>
                <label><input type="checkbox" id="occupation-1"> Agriculture</label>
                <label><input type="checkbox" id="occupation-2"> Government/State Enterprise</label>
                <label><input type="checkbox" id="occupation-3"> Laborer</label>
                <label><input type="checkbox" id="occupation-4"> Business/Trade</label>
                <label><input type="checkbox" id="occupation-5"> Housework</label>
                <label><input type="checkbox" id="occupation-6"> Student</label>
                <label><input type="checkbox" id="occupation-7"> Military/Police</label>
                <label><input type="checkbox" id="occupation-8"> Business/Trade</label>
                <label><input type="checkbox" id="occupation-9"> Public Health Personnel</label>
                <label><input type="checkbox" id="occupation-10"> Special Profession</label>
                <label><input type="checkbox" id="occupation-11"> Other: <span id="opd-otheroccupation"></span></label>
            </td>
        </tr>
        <tr>
            <td colspan="3"><span id="opd-disease"></span></td>
            <td colspan="4"><span id="opd-allergy"></span></td>
        </tr>
        <tr>
            <td colspan="7"><span id="opd-organization"></span></td>
        </tr>
    </table>
</div>


            <div class="modal-footer no-print">
    <button class="btn btn-success" onclick="printOpdCard('box2')">Print</button>
   <button type="button" class="btn btn-primary no-print" onclick="closeModal2()">Close</button>

<script>
function closeModal2() {
    document.activeElement.blur(); // ลบ focus ก่อน
    $('#myModal2').modal('hide'); // ให้ Bootstrap จัดการ
}

</script>
</div>

        </div>
    </div>
</div>
<!-- -------------------------------- Modal 2 -------------------------------- -->

<!-- Sctipt -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<style>
    .btn-purple {
    color: #fff;
    background-color: #fff;
    border-color: #643ab0;
}
.modal-body div{float:left; width: 100%}
.modal-body div p{float:left; width: 20%; font-weight: 600;}
.modal-body div span{float:left; width: 80%}

.vertical{
     transform: rotate(180deg);
     writing-mode: vertical-lr;
     text-align: center;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const printButtons = document.querySelectorAll('.btn-print-opd');

    printButtons.forEach(function (button) {
        button.addEventListener('click', function () {

            // ✅ แปลงข้อมูลจากปุ่ม
            let statusId = parseInt(button.dataset.statusid);
            let occupationId = parseInt(button.dataset.occupationid);

            // ✅ ใส่ข้อมูลใน span
            document.getElementById('opd-code').innerText = '(HN): ' + button.dataset.code;
            document.getElementById('opd-barcode').innerText = '*' + button.dataset.code + '*';
            document.getElementById('opd-title-name').innerText = 'Title/Name: ' + button.dataset.prefix + ' ' + button.dataset.name;
            document.getElementById('opd-surname').innerText = 'Surname: ' + button.dataset.surname;
            document.getElementById('opd-sex').innerText = 'Sex: ' + button.dataset.sex;
            document.getElementById('opd-idcard').innerText = 'ID/Passport Number: ' + button.dataset.idcard;
            document.getElementById('opd-birth').innerText = 'Date of Birth: ' + button.dataset.birth;
            document.getElementById('opd-age').innerText = 'Age: ' + button.dataset.age;
            document.getElementById('opd-tel').innerText = 'Phone Number: ' + button.dataset.tel;
            document.getElementById('opd-email').innerText = 'Email: ' + button.dataset.email;
            document.getElementById('opd-address').innerText = 'Address in Thailand: ' + button.dataset.address;
            document.getElementById('opd-nationality').innerText = 'Nationality: ' + button.dataset.nationality;
            document.getElementById('opd-education').innerText = 'Education Level: ' + button.dataset.education;
            document.getElementById('opd-marital').innerText = 'Marital Status: ' + button.dataset.marital;
            document.getElementById('opd-disease').innerText = 'Underlying Disease: ' + button.dataset.disease;
            document.getElementById('opd-allergy').innerText = 'Drug/Food Allergy: ' + button.dataset.allergy;
            document.getElementById('opd-organization').innerText = 'Organization: ' + button.dataset.organization;
            document.getElementById('opd-otheroccupation').innerText = button.dataset.otheroccupation;

            // ✅ รีเซ็ต checkbox ทั้งหมด
            for (let i = 1; i <= 7; i++) {
                const el = document.getElementById(`status-${i}`);
                if (el) el.checked = false;
            }
            for (let i = 1; i <= 11; i++) {
                const el = document.getElementById(`occupation-${i}`);
                if (el) el.checked = false;
            }

            // ✅ ติ๊ก checkbox ตามค่าที่ได้
            if (statusId) {
                const el = document.getElementById(`status-${statusId}`);
                if (el) {
                    el.checked = true;
                    el.setAttribute('checked', 'checked');
                }
            }
            if (occupationId) {
                const el = document.getElementById(`occupation-${occupationId}`);
                if (el) {
                    el.checked = true;
                    el.setAttribute('checked', 'checked');
                }
            }

             // ✅ กล่องสีตาม category
            const categoryBox = document.getElementById('category-box');
           const categoryColors = {
    1: '#c8f7c5', // MSM/MSW/TG - เขียว
    2: '#f9d5e5', // Sex Worker - ชมพู
    3: '#cce5ff', // Migrant Worker - ฟ้า
    4: '#ffe0b3', // Prisoner - ส้ม
    5: '#fff9b1', // Youth - เหลือง
    6: '#ffffff', // General Public - ขาว
    7: '#e0ccff'  // Drug User - ม่วง
};

const boxRect = document.getElementById('category-box-rect');
if (boxRect && statusId) {
    boxRect.setAttribute('fill', categoryColors[statusId]);
}

            // ✅ เปิด modal
            $('#myModal2').modal('show');
        });
    });
});



    jQuery(document).ready(function($) {
    $('#example').DataTable({
        searching: false,
        responsive: true,
        "autoWidth": false,
        order: [[1, 'desc']],
        "pageLength": 50,
    });


var table = $('#example').DataTable();

const statusAbbreviations = {
  "MSM/MSW/TG": "MS",
  "พนักงานบริการ": "P",
  "แรงงานข้ามชาติ": "MG",
  "ผู้ป่วยเรือนจำ": "PR",
  "เยาวชน": "YT",
  "ประชาชนทั่วไป": "G",
  "ผู้ใช้สารเสพติด": "DU"
};

$('#example tbody').on('click', '.btn-info', function () {
  var buttonId = $(this).attr('id');
  var patientId = buttonId.split('-')[2];
  var row = $(this).closest('tr');
  var data = table.row(row).data();

  $(".modal-body div span").text("");

  $(".hn span").html(data[1]);
  $(".name-surname span").html(data[2]);
  $(".age span").text(data[5]);
  $(".sex span").text(data[4]);
  $(".date span").text(data[0]);
  
  // เปลี่ยนสถานะเป็นตัวย่อ
  $(".status span").text(statusAbbreviations[data[6]] || data[6]);

  $("#myModal").modal("show");
});

$('#example tbody').on('click', '.btn-outline-success', function() {
  var buttonId = $(this).attr('id');
  var patientId = buttonId.split('-')[2];
  var row = $(this).closest('tr');
  var data = table.row(row).data();

  $(".modal-body div span").text("");

  $(".myModal2-hn span").text(data[1]);
  
  var nameSurname = data[2].replace(/<span[^>]*>(.*?)<\/span>/g, '$1');
  $(".name-surname span").text(nameSurname);

  $(".myModal2-age span").text(data[5]);
  $(".myModal2-sex span").text(data[4]);
  $(".myModal2-date span").text(data[0]);
  
  // เปลี่ยนสถานะเป็นตัวย่อ
  $(".myModal2-status span").text(statusAbbreviations[data[6]] || data[6]);

  $("#myModal2").modal("show");
});

} );


function printSticker(box){
    var printContent = document.getElementById(box).innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;

}

window.onafterprint = function() {
    window.location.reload(true);
};

$('#close-modal').on('click', function() {
$('#myModal').modal('hide');
})

// ฟังก์ชันสำหรับพิมพ์
function printOpdCard(boxId) {
  var printContent = document.getElementById(boxId).innerHTML;
  var originalContent = document.body.innerHTML;
  document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
}
$('#close-modal2').click(function() {
$('#myModal2').modal('hide');
});
// Event listener สำหรับปุ่ม Print ใน Modal 2
$('#printOpdCardBtn').click(function() {
  printOpdCard('box2');
});
window.onafterprint = function() {
    window.location.reload(true);
};
// Event listener สำหรับปุ่ม Close ใน Modal 2

function confirmDelete(patientName) {
    let reason = prompt("กรุณาพิมพ์ \"ยืนยันลบข้อมูล\" เพื่อยืนยันการลบผู้ป่วย " + patientName + ":");
    if (reason.toLowerCase() === "ยืนยันลบข้อมูล") {
        // ส่งฟอร์มไปยังเซิร์ฟเวอร์
        event.target.form.submit();
    } else {
        alert("คำยืนยันไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง");
    }
}

</script>


                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
