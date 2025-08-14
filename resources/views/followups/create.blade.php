@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h5 class="mb-4">เพิ่มการติดตามผู้ป่วย</h5>

        <form action="{{ route('visit_followups.store') }}" method="POST">
            @csrf
            <input type="hidden" name="visit_id" value="{{ $visit->id }}">
            <input type="hidden" name="patient_id" value="{{ $visit->patient_id }}">
            <div class="mb-3">
                <label for="method" class="form-label">ช่องทาง</label>
                <select name="method" id="method" class="form-control">
                    <option value="โทรศัพท์">โทรศัพท์</option>
                    <option value="ไลน์">ไลน์</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="followup_count" class="form-label">จำนวนครั้ง</label>
                <input type="number" name="followup_count" id="followup_count" class="form-control" value="1" min="1">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select name="status" id="status" class="form-control">
                    <option value="สำเร็จ">สำเร็จ</option>
                    <option value="ไม่สำเร็จ">ไม่สำเร็จ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="remark" class="form-label">หมายเหตุ</label>
                <textarea name="remark" id="remark" class="form-control" rows="3" placeholder="กรอกหมายเหตุเพิ่มเติม (ถ้ามี)"></textarea>
            </div>

            <!-- เพิ่มปุ่มเช็คช่องแจ้งเวชระเบียนให้เปลี่ยนเบอร์โทร -->
           <div class="form-check mb-3">
    <input type="hidden" name="phone_changed" value="0">
    <input type="checkbox" name="phone_changed" id="phone_changed" value="1" class="form-check-input">
    <label for="phone_changed" class="form-check-label text-danger fw-bold">
        เช็คช่องนี้เพื่อแจ้งเวชระเบียนให้เปลี่ยนเบอร์โทรศัพท์
    </label>
</div>


            <button type="submit" class="btn btn-primary">บันทึกการติดตาม</button>
            <a href="{{ route('visit.edit', ['visit' => $visit->id, 'page' => request()->get('page', 1)]) }}" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
@endsection
