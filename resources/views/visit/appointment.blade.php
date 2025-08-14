@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Visit</div>
                    <div class="card-body">

                        {{-- @include ('status') --}}

                        <!-- <a href="{{ route('visit.create') }}" class="btn btn-success btn-sm" title="Add New Visit">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a> -->
<form method="GET" action="{{ route('visit.appointment') }}" accept-charset="UTF-8" autocomplete="off" class="p-3 border rounded">
    <fieldset>
        <legend class="mb-3 font-weight-bold text-primary">ค้นหาการนัดหมาย</legend>
        
        <div class="row align-items-center">
            <!-- HN -->
            <div class="col-md-2">
                <label for="hn" class="form-label">HN</label>
                <input type="text" class="form-control" id="hn" name="hn" placeholder="HN" value="{{ request('hn') }}">
            </div>
            
            <!-- เหตุผลการนัดหมาย -->
            <div class="col-md-3">
                <label for="reason" class="form-label">เหตุผลการนัดหมาย</label>
                @php
                    $reasonOptions = \App\Models\Visit::$reasonAppointmentOption;
                @endphp
                <select class="form-control" id="reason" name="reason">
                    <option value="">-- เลือกเหตุผล --</option>
                    @foreach ($reasonOptions as $key => $label)
                        <option value="{{ $label }}" {{ request('reason') == $label ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- วันที่เริ่มต้น -->
            <div class="col-md-3">
                <label for="start" class="form-label">วันที่เริ่มต้น (วันนัด)</label>
                <input type="text" class="form-control date" id="start" name="start" placeholder="เลือกวันที่เริ่มต้น" value="{{ request('start') }}" required>
            </div>
            
            <!-- วันที่สิ้นสุด -->
            <div class="col-md-3">
                <label for="end" class="form-label">วันที่สิ้นสุด (วันนัด)</label>
                <input type="text" class="form-control date" id="end" name="end" placeholder="เลือกวันที่สิ้นสุด" value="{{ request('end') }}" required>
            </div>
            
            <!-- ปุ่มค้นหา -->
            <div class="col-md-1 text-right">
                <label class="form-label d-block">&nbsp;</label>
                <button class="btn btn-primary w-100" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </fieldset>
</form>
 <div class="card-body">
                        {{-- จำนวนผู้ป่วยที่ค้นพบ --}}
                        <p>จำนวนผู้ป่วยที่ค้นพบ: {{ $totalVisits }}</p>
                        <a href="{{ route('visit.export', request()->query()) }}" class="btn btn-success mb-3">
    <i class="fa fa-download"></i> Export to Excel
</a>

                        <div class="table-responsive">
                        <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>NH</th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>เพศ</th>
             <th>ประเภท</th>
            <th>วันที่มา</th>
            <th>diag</th>
            <th>วันนัด</th>
            <th class="col-4">เหตุผล</th>
            <th></th> <!-- แสดงผลวันที่ถัดไป -->
            <th>สถานะการติดตาม</th> 
            <th>โทรศัพท์</th> <!-- แสดงผลวันที่ถัดไป -->
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($visit as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->patient()->code }}</td>
            <td nowrap="1">{{ $item->patient()->name }}</td>
            <td>{{ $item->patient()->surname }}</td>
            <td>{{ App\Models\Patient::$sexOption[$item->patient()->sex] }}</td>
            <td>{{ App\Models\Patient::$statusOption[$item->patient()->status] }}</td>
            <td style="color: gray;" nowrap="1">{{ $item->date }}</td>
            <td>
    @if ($item->diagnoses->isNotEmpty())
        @foreach ($item->diagnoses as $diag)
            <!-- แสดง Disease -->
            @if (array_key_exists($diag->disease, \App\Models\Diagnosi::$diseaseOption))
                <span class="badge bg-info">
                    {{ \App\Models\Diagnosi::$diseaseOption[$diag->disease] }}
                </span><br>
            @else
                <span style="color: red;">ไม่ทราบโรค</span><br>
            @endif

            <!-- แสดง Term Syphilis -->
            @if (!empty($diag->term_syphilis) && array_key_exists($diag->term_syphilis, \App\Models\Diagnosi::$termSyphilisOption))
                <span class="text-primary">
                    Term Syphilis: {{ \App\Models\Diagnosi::$termSyphilisOption[$diag->term_syphilis] }}
                </span><br>
            @endif

            <!-- แสดง Disease State -->
            @if (!empty($diag->disease_state) && array_key_exists($diag->disease_state, \App\Models\Diagnosi::$diseaseStateOption))
                <span class="text-success">
                    State: {{ \App\Models\Diagnosi::$diseaseStateOption[$diag->disease_state] }}
                </span><br>
            @endif
        @endforeach
    @else
        <span style="color: red;">ไม่มีการวินิจฉัย</span>
    @endif
</td>

            <td style="color: green;" nowrap="1">{{ $item->appointment }}</td>
            <td>
     <a href="{{ url('/visit/' . $item->id . '/edit') . '?page=' . request('page') }}">
        {{ $item->appointment_reason }}
    </a>
    @php
        $remarks = $item->followups;
    @endphp
    @foreach ($remarks as $index => $remark) 
    @php
        $isSuccess = $remark->status === 'สำเร็จ'; // ตรวจสอบว่า status เป็น 'สำเร็จ' หรือไม่
    @endphp
    <br><medium class="text-muted">
        <span class="badge {{ $isSuccess ? 'bg-success' : 'bg-warning text-dark' }}">
            ติดตาม: {{ $index + 1 }}
        </span> 
        {{ $remark->remark }}
    </medium>
@endforeach
</td>

            <td style="color: green;" nowrap="1">
                @if ($item->next_visit)
                    @php
                        $nextVisitDate = \Carbon\Carbon::parse($item->next_visit);
                        $appointmentDate = \Carbon\Carbon::parse($item->appointment);
                    @endphp

                    @if ($nextVisitDate->lt($appointmentDate))
                        <span style="color: blue;">วันที่มา {{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</span><br> 
                        <a href="{{ url('/visit/' . $item->id . '/edit') . '?page=' . request('page') }}"><span style="color: red;">มาก่อนนัด</span><br>
                        <span style="color: red;">{{ $item->appointment }}</span><br>
                        <!-- <span style="color: blue;">วันที่มา {{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</span> -->
                    @else
                        {{ $nextVisitDate->format('Y-m-d') }}
                    @endif
                @else
                    <span style="color: red;">ยังไม่มา</span>
                @endif
            </a>
            </td>
            <td>
    @php
        $followupCount = $item->followups->count();
        $successCount = $item->followups->where('status', 'สำเร็จ')->count();
        
        // นับจำนวนการติดตามแต่ละช่องทาง
        $lineFollowups = $item->followups->where('method', 'ไลน์')->count();
        $phoneFollowups = $item->followups->where('method', 'โทรศัพท์')->count();
    @endphp

    <span class="badge bg-primary">ติดตาม {{ $followupCount }} ครั้ง</span><br>
    
    @if ($successCount > 0)
        <span class="badge bg-success">สำเร็จแล้ว {{ $successCount }} ครั้ง</span><br>
    @else
        <span class="badge bg-danger">ยังไม่ได้ตาม</span><br>
    @endif

    <!-- แสดงการติดตามตามช่องทาง -->
    <span class="badge bg-info">ไลน์: {{ $lineFollowups }} ครั้ง</span><br>
    <span class="badge bg-warning text-dark">โทรศัพท์: {{ $phoneFollowups }} ครั้ง</span><br>
</td>

            <td style="color: blue;" nowrap="1">{{ $item->patient()->tel }}</td>
            <td>
    <a href="{{ url('/visit/' . $item->id . '/edit') . '?page=' . request('page') }}">
        <button class="btn btn-primary btn-sm">
            <i class="fa fa-pencil" aria-hidden="true"></i> 
        </button>
    </a>
</td>

        </tr>
    @endforeach
</tbody>

</table>
                            <div class="pagination-wrapper"> <!-- เพิ่มการใช้งาน appends ใน pagination -->
{{ $visit->appends(['start' => request('start'), 'end' => request('end')])->links() }} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
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

@endsection

