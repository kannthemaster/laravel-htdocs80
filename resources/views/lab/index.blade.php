@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Lab</div>
                    <div class="card-body">
                    
<script>
    // เมื่อหน้าโหลด ให้แปลงวันที่จาก พ.ศ. เป็น ค.ศ.
    window.onload = function() {
        let startDateInput = document.getElementById('start_date');
        let endDateInput = document.getElementById('end_date');

        if (startDateInput && startDateInput.value) {
            startDateInput.value = convertToAD(startDateInput.value);  // แปลงเป็น ค.ศ. เมื่อหน้าโหลด
        }
        
        if (endDateInput && endDateInput.value) {
            endDateInput.value = convertToAD(endDateInput.value);  // แปลงเป็น ค.ศ. เมื่อหน้าโหลด
        }
    };

    // ฟังก์ชันแปลงจาก พ.ศ. ไปเป็น ค.ศ.
    function convertToAD(dateStr) {
        let parts = dateStr.split('-');
        if (parts.length === 3) {
            parts[0] = parseInt(parts[0]) - 543; // แปลงปี พ.ศ. ไปเป็น ค.ศ.
        }
        return parts.join('-');
    }

    // ฟังก์ชันแปลงจาก ค.ศ. ไปเป็น พ.ศ. เมื่อผู้ใช้เลือกวันที่
    function convertToBE(dateStr) {
        let parts = dateStr.split('-');
        if (parts.length === 3) {
            parts[0] = parseInt(parts[0]) + 543; // แปลงปี ค.ศ. ไปเป็น พ.ศ.
        }
        return parts.join('-');
    }

    // เมื่อกดปุ่มค้นหา แปลงวันที่ก่อนส่งฟอร์ม
    function convertDates() {
        let startDateInput = document.getElementById('start_date');
        let endDateInput = document.getElementById('end_date');

        // ถ้ามีการเลือกวันที่ จะแปลงเป็น พ.ศ.
        if (startDateInput && startDateInput.value) {
            startDateInput.value = convertToBE(startDateInput.value);
        }

        if (endDateInput && endDateInput.value) {
            endDateInput.value = convertToBE(endDateInput.value);
        }
    }
</script>

<form method="GET" action="{{ route('lab.index') }}" class="form-inline my-2 my-lg-0 float-end">
    <div class="d-flex align-items-center">
        <!-- กล่องค้นหาด้วย HN, LN, ชื่อ หรือ นามสกุล -->
        <div class="form-group mr-2">
            <label for="search" class="d-block">&nbsp<br>&nbsp;</label>
            <input type="text" class="form-control" name="search" id="search" placeholder="ค้นหา HN, LN, ชื่อ หรือ นามสกุล..." value="{{ request('search') }}">
        </div>
 
        <!-- วันที่เริ่มต้น -->
        <div class="form-group mr-2">
            <label for="start_date" class="d-block">วันที่เริ่มต้น<br>Collected Date</label>
            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
        </div>
 
        <!-- วันที่สิ้นสุด -->
        <div class="form-group mr-2">
            <label for="end_date" class="d-block">วันที่สิ้นสุด<br>Collected Date</label>
            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
        </div>
 
        <!-- สถานะ -->
        <div class="form-group mr-2">
            <label for="status" class="d-block">&nbsp;<br>สถานะ</label>
            <select name="status" class="form-control" id="status">
                <option value="">เลือกสถานะ</option>
                <option value="all" {{ request('status') == "all" ? 'selected' : '' }}>ทั้งหมด</option>
                @foreach(\App\Models\Lab::$statusOption as $statusKey => $statusValue)
                <option value="{{ $statusKey }}" {{ request('status') == $statusKey ? 'selected' : '' }}>{{ $statusValue }}</option>
                @endforeach
            </select>
        </div>

 

        <!-- ปุ่มค้นหา -->
       <div class="form-group mr-2">
        <label for="status" class="d-block"> <br> </label>
            <button class="btn btn-secondary" type="submit" onclick="convertDates()">
                <i class="fa fa-search"></i> ค้นหา
            </button>
        </div>
    </div>
</form>


                </div>
                    <div class="card-body">
<a href="{{ route('lab.export', request()->query()) }}" class="btn btn-success mb-3">
    <i class="fa fa-download"></i> Export to Excel
</a>

            <!-- @include ('status') -->
                        
                        <!-- <a href="{{ route('lab.create') }}" class="btn btn-success btn-sm" title="Add New Lab">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a> -->
                        


                        <div class="table-responsive">
    <table class="table" id="labTable">
        <thead>
            <tr>
                <th>#</th>
                <th>HN</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>LN</th>
                <th>Result</th> <!-- เดิม -->
                <th>Remark</th> <!-- เพิ่ม -->
                <th>Collected Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($lab as $index => $item)
            <tr>
                <td>{{ $index + 1 + (($lab->currentPage() - 1) * $lab->perPage()) }}</td>
                <td>{{ optional($item->patient)->code }}</td>
                <td>{{ optional($item->patient)->name }}</td>
                <td>{{ optional($item->patient)->surname }}</td>                
                <td>{{ $item->LN }}</td>
                <td>
                    @if($item->labSub2->isNotEmpty())
                        @foreach($item->labSub2 as $sub)
                            <div>
                                <strong>Method:</strong> {{ \App\Models\LabSub::$methodOption[$sub->method] ?? 'ไม่ระบุ' }}  
                                <strong>Result:</strong> {{ !empty($sub->result) ? $sub->result : 'รอลงผล' }}
                            </div>
                        @endforeach
                    @else
                        รอลงผล
                    @endif
                </td>
                <td>
                    {{ $item->remark ?? '-' }}
                </td>
                <td>{{ $item->collected_date }}</td>
                <td><strong>{{ \App\Models\Lab::$statusOption[$item->status] ?? 'ไม่ระบุ' }}</strong></td>
                <td>
                    <a href="{{ route('lab.edit' , ['lab'=>$item->id,'page'=>4]) }}" title="Edit Lab">
                        <button class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
    <div class="pagination-wrapper">
    {!! $lab->appends(request()->query())->links('pagination::bootstrap-4') !!}
    </div>


                    </div>
                </div>
                @include('sweetalert::alert')
            </div>
        </div>
    </div>
  <script>
    document.getElementById('exportExcel').addEventListener('click', function() {
        // ดึงข้อมูลทั้งหมดจากตาราง
        let table = document.getElementById('labTable');
        let rows = table.querySelectorAll('tr');
        let data = [];

        rows.forEach(function(row, index) {
            let cells = row.querySelectorAll('td, th');
            let rowData = [];
            cells.forEach(function(cell) {
                rowData.push(cell.innerText);
            });
            if (rowData.length > 0) {
                data.push(rowData);
            }
        });

        // ส่งข้อมูลไปยัง Route export
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('lab.export') }}'; // ใช้ POST ที่แก้ไขใน Route
        form.target = '_blank';

        let csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // ส่งข้อมูลที่ดึงมาจากตารางไปในฟอร์ม
        data.forEach(function(rowData, index) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'data[]';
            input.value = JSON.stringify(rowData); // แปลงเป็น JSON
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });
</script>

@endsection
