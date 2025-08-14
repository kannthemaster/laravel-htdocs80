<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>   
                <th>#</th>             
                <th>Method</th>
                <th>Specimen From</th>
                <th>Result</th>
                <th>Actions</th>
            </tr>
        </thead>
<button id="printButton" type="button" class="btn btn-secondary">Print</button>

        <tbody>

                @foreach($labsub as $item)
            <tr>
                <td>
        <input type="checkbox" class="lab-checkbox" value="{{ $item->id }}">
    </td>
                <td>{{ $loop->iteration }}</td>              
                <td>{{ $item->method() }}</td>
                <td>{{ $item->specimenFrom() }}</td>
                {{-- <td>{{ $item->result }}</td> --}}
                <td>
                    {{-- <input type="text" id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}"  value="{{ $item->result }}" class="form-control track border "> --}}
              
                    
                   @switch($item->method)
            @case(1)
            <?php 
                $case1id = $item->id; 
                $options = App\Models\LabSub::getGramOptions($item->specimenFrom());
            ?>
            <label for="result[{{$item->id}}]" class="control-label click" data-bs-toggle="modal" data-bs-target="#modal-{{$item->id}}">
                {{ 'เลือกลงผล Gram' }}
            </label>
            <textarea rows="1" id="result-{{$item->id}}" name="result[{{$item->id}}]" org="{{ $item->result }}" class="form-control track border" style="overflow:hidden; resize:true; min-height: 60px;">{{ $item->result }}</textarea>
            <!-- Modal -->
            <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel-{{ $item->id }}">เลือกลงผล Gram - {{ $item->specimenFrom() }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>I</th>
                            <th>E</th>
                            <th>PMN</th>
                            @if (in_array($item->specimenFrom(), ['Vagina', 'Cervix', 'Urethra']))
                            <th>Fungus</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Column I -->
                            <td>
                                <ul>
                                    <li class="gram_item" data-value="I : Not found">I : Not found</li>
                                    <li class="gram_item" data-value="I : GNDC 1+">I : GNDC 1+</li>
                                    <li class="gram_item" data-value="I : GNDC 2+">I : GNDC 2+</li>
                                    <li class="gram_item" data-value="I : GNDC 3+">I : GNDC 3+</li>
                                    <li class="gram_item" data-value="I : GNDC 4+">I : GNDC 4+</li>
                                    <li class="gram_item" data-value="I : Other">I : Other: </li>
                                </ul>
                            </td>
                            <!-- Column E -->
                            <td>
                                <ul>
                                    <li class="gram_item" data-value="E : Not found">E : Not found</li>
                                    <li class="gram_item" data-value="E : GNDC 1+">E : GNDC 1+</li>
                                    <li class="gram_item" data-value="E : GNDC 2+">E : GNDC 2+</li>
                                    <li class="gram_item" data-value="E : GNDC 3+">E : GNDC 3+</li>
                                    <li class="gram_item" data-value="E : GNDC 4+">E : GNDC 4+</li>
                                    <li class="gram_item" data-value="I : Other">I : Other: </li>
                                </ul>
                            </td>
                            <!-- Column PMN -->
                            <td>
                                <ul>
                                    <li class="gram_item" data-value="PMN : Negative finding">PMN : Negative finding</li>
                                    <li class="gram_item" data-value="PMN : 1+">PMN : 1+</li>
                                    <li class="gram_item" data-value="PMN : 2+">PMN : 2+</li>
                                    <li class="gram_item" data-value="PMN : 3+">PMN : 3+</li>
                                    <li class="gram_item" data-value="PMN : 4+">PMN : 4+</li>
                                    <li class="gram_item" data-value="PMN : Other">PMN : Other: </li>
                                </ul>
                            </td>
                            <!-- Column Fungus -->
                            @if (in_array($item->specimenFrom(), ['Vagina', 'Cervix', 'Urethra']))
                            <td>
                                <ul>
                                    <li class="gram_item" data-value="Fungus : Not found">Fungus : Not found</li>
                                    <li class="gram_item" data-value="Fungus : Growth">Fungus : Growth</li>
                                    <li class="gram_item" data-value="Fungus : Budding yeast cell Found">Fungus : Budding yeast cell Found</li>
                                    <li class="gram_item" data-value="Fungus : Budding yeast with pseudohyphae Found">Fungus : Budding yeast with pseudohyphae Found</li>
                                    <li class="gram_item" data-value="Fungus : Pseudohyphae Found">Fungus : Pseudohyphae Found</li>
                                </ul>
                            </td>
                        @endif
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("input", function (event) {
    if (event.target.tagName.toLowerCase() !== "textarea") return;
    
    event.target.style.height = "auto"; // รีเซ็ตความสูงก่อน
    event.target.style.height = (event.target.scrollHeight) + "px"; // ปรับตามเนื้อหา
}, false);
</script>
<style>
    .gram_item {
        cursor: pointer; /* เปลี่ยนเป็นรูปนิ้วชี้ */
        color: black;    /* สีปกติ */
    }

    .gram_item:hover {
        color: red; /* เปลี่ยนเป็นสีแดงเมื่อ hover */
    }
</style>


    <script>
    $(document).off('click', '.gram_item').on('click', '.gram_item', function () {
    const text = $(this).data('value'); // ดึงค่าจาก data-value
    const modalId = $(this).closest('.modal').attr('id'); // หา ID ของ Modal
    const textareaId = modalId.replace('modal-', 'result-'); // หา ID ของ Textarea
    const textarea = $('#' + textareaId); // หา Textarea

    // อ่านค่าปัจจุบัน และเพิ่มค่าใหม่
    const currentValue = textarea.val();
    textarea.val(currentValue + text + ', ');
});


</script>


            @break

                    @case(2)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$wetResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break

                    @case(3)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$kohResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break

                    @case(4)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$cultureResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break

                    @case(5)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$pcrResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break

                    @case(6)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$hivResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}                        </option>
                        @endforeach
                    </select>

                    @break

                    @case(7)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$tphrResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break

                    @case(8)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$rprResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break

                    @case(11)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$HBsAgResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break
                    @case(12)
                    <select class="form-control track border"  id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                        @foreach (App\Models\LabSub::$AntiHCVResultOption as $key => $value)
                        <option value="{{ $value }}" @if (isset($item->result) && $value == $item->result) selected="selected" @endif>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                    @break
                    @case(13)
                    @if ($item->specimen_from == 3)
                        <input type="text" class="form-control track border" id="result[{{$item->id}}]" name="result[{{$item->id}}]"
                            value="{{ $item->result }}" >
                    @else
                        <select class="form-control track border" id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}">
                            @foreach (App\Models\LabSub::$papResultOption as $key => $value)
                            <option value="{{ $value }}" @if ($item->result == $value) selected="selected" @endif>
                            {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    @endif
                    @break

                    @default
                    <input class="form-control track border" id="result[{{$item->id}}]" name="result[{{$item->id}}]" org="{{ $item->result }}"  value="{{ $item->result }}" type="text" >
                    @endswitch

                    
                
                </td>
                @if(isset($addResult) && $addResult)
                <td>
                    <!-- <a href="{{ url('/backend/lab-sub/' . $item->id) }}" title="View LabSub"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> -->
                    <a href="{{ route('lab-sub.result',['id'=>$item->id,'page'=>$_GET["page"]]) }}" title="Result LabSub" class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> </a>

                </td>
                @else
                <td>
                    <!-- <a href="{{ url('/backend/lab-sub/' . $item->id) }}" title="View LabSub"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a> -->
                    <a href="{{ route('lab-sub.edit',['lab_sub'=>$item->id,'page'=>$_GET["page"]]) }}" title="Edit LabSub" class="btn btn-primary btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i> </a>
                    <form method="POST" action="{{ route('lab-sub.destroy',['lab_sub'=>$item->id,'page'=>$_GET["page"]]) }}" accept-charset="UTF-8" style="display:inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete LabSub" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </form>
                </td>
                @endif

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
$( document ).ready(function() {
    $(".track").change(function(){
        console.log("track")
        if($(this).val()!=$(this).attr('org')){
            $(this).addClass('border-danger')
        }else{
            $(this).removeClass('border-danger')
        }
       
    })
});
</script>
<script>
    let selectedRows = "";
let rowIndex = 1; // เพิ่มตัวแปรนับลำดับ
    document.getElementById("printButton").addEventListener("click", function () {
    let selectedRows = "";
    document.querySelectorAll("input.lab-checkbox:checked").forEach(function (checkbox) {
        const row = checkbox.closest("tr");
        const sequence = row.children[1].textContent.trim(); // ลำดับ (#)
        const method = row.children[2].textContent.trim(); // Method
        const specimen = row.children[3].textContent.trim(); // Specimen From

        let result = "";
        const textarea = row.querySelector("textarea");
        const select = row.querySelector("select");
        const input = row.querySelector("input[type='text']");
        if (textarea) {
    result = textarea.value.trim();
} else if (select) {
    result = select.options[select.selectedIndex].text.trim();
} else if (input) {
    result = input.value.trim(); // ✅ เพิ่มส่วนนี้
}
          // ตรวจสอบคำว่า "positive" และเพิ่ม class "text-red"
        const isPositive = result.toLowerCase().includes("positive");
        const resultClass = isPositive ? "text-red" : "";
        // 👇 ใส่ลำดับ (rowIndex) หน้า method
    selectedRows += `
        <tr>
            <td>${rowIndex}</td>
            <td>${method}</td>
            <td>${specimen}</td>
            <td class="${resultClass}">${result || "-"}</td>
        </tr>
    `;

    rowIndex++; // เพิ่มลำดับถัดไป
});

    if (!selectedRows) {
        alert("กรุณาเลือกข้อมูลอย่างน้อย 1 แถวก่อนพิมพ์");
        return;
    }

    const currentDate = new Date().toLocaleDateString("th-TH", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });

    const printContent = `
        <html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Selected Rows</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Prompt:400,600&display=swap" rel="stylesheet">
<style>
body, .chartjs-render-monitor { font-family: 'Prompt', 'Nunito', sans-serif; }
</style>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap');    
        .text-red {
        color: red !important;
        font-weight: bold;
    }
        @media print {
            @page {
                size: A4; /* กำหนดขนาดหน้ากระดาษเป็น A4 */
                margin: 10mm; /* กำหนดระยะขอบ */
            }

        body {
            font-family: 'Prompt', 'Nunito', sans-serif;
            line-height: 1.6;
        font-size: 14px; /* 🔽 ลดขนาดตัวอักษร */
        }

        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-table td {
            border: none !important;
        }

        .header-table img {
            max-height: 120px;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
        }

        .header-subtitle {
            font-size: 11px;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        font-size: 14px;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }
        h3 {
        font-size: 18px;
        }

        thead {
            background-color: #f1f1f1;
        }
        .text-red {
        color: red;
        font-weight: bold;
    }
    </style>
</head>
<body class="container">
    <table class="header-table">
        <tr>
            <td class="text-center" style="width: 12%;">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
            </td>
            <td class="text-center" style="width: 76%;">
                <div class="header-title"><h3>Laboratory Test Report</h3></div>
                <div class="header-subtitle">Medical Laboratory for Disease Control, Office of Disease Prevention and Control 1 Chiang Mai</div>
                <div class="header-subtitle">
                    143 Sri Don Chai Road, Chang Klan Subdistrict, Mueang District, Chiang Mai 50000<br>
                    Telephone: 053-140 774 to 6
                </div>
            </td>
            <td class="text-center" style="width: 12%;">
                <img src="{{ asset('images/S__21831716.jpg') }}" alt="Logo Right">
            </td>
        </tr>
    </table>

    <div class="row mb-4">
        <div class="col-md-6">
            <div><strong>Patient Number(HN):</strong> {{ $patient->code ?? '' }}</div>
            <div><strong>Patient Name:</strong> {{ $patient->name ?? '' }} {{ $patient->surname ?? '' }}</div>
        </div>
        <div class="col-md-6">
            <div><strong>Sex:</strong> 
    {{ $patient->sex == '1' ? 'M' : ($patient->sex == '2' ? 'F' : '') }}
</div>

            <div><strong>Age:</strong> {{ $patient->age() ?? '' }}</div>
        </div>
    </div>

    <h3>Result</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Method</th>
                <th>Specimen From</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            ${selectedRows}
        </tbody>
    </table>
        <br><br>
<style>
  table, th, td {
    border: none !important; /* ลบขอบของตารางทั้งหมด */
  }

  input {
    border: none !important; /* ลบขอบของ input */
    background-color: white; /* กำหนดพื้นหลังเป็นสีขาว */
  }
</style>

<table class="table table-sm table-borderless">
  <tbody>
       
    <tr>
      <th scope="row" class="align-middle"></th>
      <td class="text-center" style="font-size: 13px;">Reported By</td>
      <td class="text-center">
        <td style="font-size: 14px;">{{ App\Models\User::getName($lab->report_by) }}</td>
      </td>
      <td></td>
      <td class="text-center" style="font-size: 13px;">Reported Date</td>
      <td class="text-center">
        <td style="font-size: 14px;">{{ $lab->report_date }} {{ $lab->report_at }}</td>
      </td>
      <td></td>
    </tr>
    <tr>
      <th scope="row" class="align-middle"></th>
      <td class="text-center" style="font-size: 13px;">Approved By</td>
      <td class="text-center">
        <td style="font-size: 14px;">{{ App\Models\User::getName($lab->approve_by) }}</td>
      </td>
      <td></td>
      <td class="text-center" style="font-size: 13px;">Approved Date</td>
      <td class="text-center">
        <td style="font-size: 14px;">{{ $lab->approve_date }} {{ $lab->approve_at }}</td>
      </td>
      <td></td>
    </tr>
  </tbody>
</table>




 
</body>
</html>
    `;

    const printWindow = window.open("", "_blank");
    printWindow.document.write(printContent);
    printWindow.document.close();
    // printWindow.print();
});

</script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
document.addEventListener('DOMContentLoaded', function () {
    const textareas = document.querySelectorAll('textarea.track');

    textareas.forEach(textarea => {
        // ฟังก์ชันปรับขนาด
        const resize = () => {
            textarea.style.height = 'auto'; // รีเซ็ตก่อน
            textarea.style.height = textarea.scrollHeight + 'px'; // ตั้งความสูงเท่ากับเนื้อหา
        };

        // เรียก resize ครั้งแรกเมื่อโหลด
        resize();

        // เรียกทุกครั้งเมื่อพิมพ์
        textarea.addEventListener('input', resize);
    });
});
</script>
