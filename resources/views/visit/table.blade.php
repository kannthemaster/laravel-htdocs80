<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>วินิจฉัย</th>
                <th>สถานะ</th>
                <th>นัดครั้งถัดไป</th>
                <th>เหตุผล</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visit as $item)
            <tr data-method="{{ $item->method }}"> <!-- เพิ่ม data-method -->
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->diagnosiAllString() }}</td>
                <td>{{ $item->diagnosiStatusAllString() }}</td>
                <td>{{ $item->appointment }}</td>
                <td>{{ $item->appointment_reason }}</td>
                <td>
                    <a href="{{ route('visit.edit',['visit'=>$item->id,'page'=>1])}}" title="Edit Visit">
                        <button class="btn btn-primary btn-sm">
                        <i class="fa fa-pencil" aria-hidden="true"></i> แก้ไข</button></a>
                    <form method="POST" action="{{ route('visit.destroy',['visit'=>$item->id,'page'=>1]) }}" accept-charset="UTF-8" style="display:inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Visit" onclick="return confirm(&quot;Confirm delete?&quot;)">
                        <i class="fa fa-trash" aria-hidden="true"></i> ลบ Visit </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@php
    $latestVisit = $visit->sortByDesc('date')->first(); // หาวันที่ visit ล่าสุด
@endphp

@if ($latestHBsAgDate || $latestAntiHCVDate || $latestVisit || (isset($patient) && $patient->phone_changed))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let message = '';

            // ตรวจสอบการเปลี่ยนเบอร์โทร
            @if (isset($patient) && $patient->phone_changed)
                message += '<span style="color: red; font-size: 18px; font-weight: bold;">⚠️ แจ้งเตือน: ผู้ป่วยมีการเปลี่ยนเบอร์โทร ⚠️</span><br>';
            @endif

            // แสดงวันที่ของ HBs Ag
            @if ($latestHBsAgDate)
                message += 'ตรวจ HBs Ag วันที่: {{ $latestHBsAgDate }}<br>';
            @endif

            // แสดงวันที่ของ Anti-HCV
            @if ($latestAntiHCVDate)
                message += 'ตรวจ Anti HCV วันที่: {{ $latestAntiHCVDate }}<br>';
            @endif

            // ตรวจสอบการนัดหมาย
            @if ($latestVisit && $latestVisit->appointment)
                message += 'ผู้ป่วยมีนัดวันที่: <b>{{ $latestVisit->appointment }}</b><br>เหตุผล: <b>{{ $latestVisit->appointment_reason ?? "ไม่ระบุ" }}</b>';
            @else
                message += 'ผู้ป่วยไม่มีนัดหมาย';
            @endif

            // แสดงการแจ้งเตือนถ้ามีข้อความ
            if (message) {
                Swal.fire({
                    icon: 'info',
                    title: 'แจ้งเตือนสำคัญ',
                    html: message,
                    confirmButtonText: 'ตกลง'
                });
            }
        });
    </script>
@endif


