@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold text-primary">📊 แดชบอร์ดรายปี</h1>
  </div>

  {{-- Year selector --}}
  <form method="GET" action="{{ route('report.yearly') }}" class="mb-4">
    <div class="row g-2 align-items-center">
      <div class="col-auto">
        <label for="year" class="form-label fw-semibold">เลือกปี (พ.ศ.)</label>
      </div>
      <div class="col-auto">
        <select name="year" id="year" class="form-select shadow-sm" style="min-width: 120px;">
          @for ($y = 2560; $y <= date('Y') + 543; $y++)
            <option value="{{ $y }}" @if($y == $year) selected @endif>{{ $y }}</option>
          @endfor
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-success shadow-sm px-4">ดูรายงาน</button>
      </div>
    </div>
  </form>
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card text-white text-center shadow" style="background: #1976d2;">
      <div class="card-body py-4">
        <div class="display-3 fw-bold" style="text-shadow: 1px 2px 7px #3332;">{{ $totalPatients }}</div>
        <div class="fw-light fs-5 mt-1">จำนวนผู้รับบริการ</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-white text-center shadow" style="background: #388e3c;">
      <div class="card-body py-4">
        <div class="display-3 fw-bold" style="text-shadow: 1px 2px 7px #3332;">{{ $totalVisits }}</div>
        <div class="fw-light fs-5 mt-1">จำนวนครั้งที่รับบริการ</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-white text-center shadow" style="background: #26c6da;">
      <div class="card-body py-4">
        <div class="display-3 fw-bold" style="text-shadow: 1px 2px 7px #3332;">{{ $malePatients }}</div>
        <div class="fw-light fs-5 mt-1">ชาย</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card text-white text-center shadow" style="background: #ffc107;">
      <div class="card-body py-4">
        <div class="display-3 fw-bold" style="text-shadow: 1px 2px 7px #3332;">{{ $femalePatients }}</div>
        <div class="fw-light fs-5 mt-1">หญิง</div>
      </div>
    </div>
  </div>
</div>
@if(isset($diseaseNewRepeatRows))
<div class="row g-3 mb-4">
  @php
    $diseaseColors = [
      'Syphilis' => [
        'bg' => '#e3f0ff', 'head' => '#1976d2', 
        'new_male' => '#1976d2', 'new_female' => '#f06292', 
        'repeat_male' => '#00bcd4', 'repeat_female' => '#ffd600'
      ],
      'Gonorrhea' => [
        'bg' => '#e5fbe5', 'head' => '#388e3c',
        'new_male' => '#388e3c', 'new_female' => '#f06292',
        'repeat_male' => '#00bcd4', 'repeat_female' => '#ffd600'
      ],
      'Non-Gonococcal urethritis' => [
        'bg' => '#fffbe4', 'head' => '#ffd600',
        'new_male' => '#ffd600', 'new_female' => '#ffb300',
        'repeat_male' => '#00bcd4', 'repeat_female' => '#1976d2'
      ]
    ];
  @endphp
  @foreach ($diseaseNewRepeatRows as $disease => $row)
    @php
      $color = $diseaseColors[$disease] ?? $diseaseColors['Syphilis'];
    @endphp
    <div class="col-12 col-md-4">
      <div class="card shadow border-0"
        style="background:{{ $color['bg'] }}; border-radius:1.4rem;">
        <div class="card-header fs-5 fw-bold text-center border-0"
          style="background:{{ $color['head'] }}; color:white; border-top-left-radius:1.4rem; border-top-right-radius:1.4rem; letter-spacing:1px;">
          {{ $disease }}
        </div>
        <div class="card-body py-3">
          <div class="row">
            <div class="col-6">
              <div class="mb-1 text-secondary" style="font-weight:600;">ผู้ป่วยใหม่</div>
              <div class="d-flex justify-content-center align-items-center gap-2 mb-1">
                <span class="fw-bold display-5" style="color:{{ $color['new_male'] }};">{{ $row['new_male'] }}</span>
                <span class="fw-bold display-5" style="color: #999; font-size:2.3rem;">/</span>
                <span class="fw-bold display-5" style="color:{{ $color['new_female'] }};">{{ $row['new_female'] }}</span>
              </div>
              <div class="text-muted" style="font-size:13px;">ชาย / หญิง</div>
            </div>
            <div class="col-6">
              <div class="mb-1 text-secondary" style="font-weight:600;">โรคซ้ำ</div>
              <div class="d-flex justify-content-center align-items-center gap-2 mb-1">
                <span class="fw-bold display-5" style="color:{{ $color['repeat_male'] }};">{{ $row['repeat_male'] }}</span>
                <span class="fw-bold display-5" style="color: #999; font-size:2.3rem;">/</span>
                <span class="fw-bold display-5" style="color:{{ $color['repeat_female'] }};">{{ $row['repeat_female'] }}</span>
              </div>
              <div class="text-muted" style="font-size:13px;">ชาย / หญิง</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endif


{{-- กราฟ --}}

<div class="row mb-4">
    <div class="col-6">
    <div class="card h-100 shadow-sm">
      <div class="card-header bg-success text-white fw-bold">ผู้ป่วยแยกตามกลุ่มเสี่ยง</div>
      <div class="card-body">
        <canvas id="riskGroupBar" style="min-height:240px"></canvas>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white fw-bold">โรคหลัก (ชาย/หญิง)</div>
      <div class="card-body">
        <canvas id="diseaseBar" style="min-height:300px"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card h-100 shadow-sm">
      <div class="card-header bg-info text-white fw-bold">สัดส่วนเพศ</div>
      <div class="card-body">
        <canvas id="genderPie" style="min-height:240px"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4">
   <div class="card h-100 shadow-sm">
  <div class="card-header bg-warning fw-bold">สัดส่วนสัญชาติ</div>
  <div class="card-body">
    <canvas id="nationPie" style="min-height:240px"></canvas>
  </div>
</div>
  </div>
</div>
<!-- {{-- (1)+(4) Visits + (2)+(3) Patients --}}
{{-- ตาราง visits/patient --}}
<table class="table table-bordered align-middle text-center mb-4">
  <thead class="bg-main">
    <tr>
      <th></th>
      <th class="bg-total">รวม</th>
      <th class="bg-male">ชาย</th>
      <th class="bg-female">หญิง</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="text-start">จำนวน Visit</td>
      <td class="bg-total fw-strong">{{ $totalVisits }}</td>
      <td class="bg-male">{{ $maleVisits }}</td>
      <td class="bg-female">{{ $femaleVisits }}</td>
    </tr>
    <tr>
      <td class="text-start">จำนวนผู้ป่วย (รายคน)</td>
      <td class="bg-total fw-strong">{{ $totalPatients }}</td>
      <td class="bg-male">{{ $malePatients }}</td>
      <td class="bg-female">{{ $femalePatients }}</td>
    </tr>
  </tbody>
</table> -->

  {{-- (3) Patient by risk group --}}
  <h5 class="mt-4 fw-bold">ผู้ป่วย (รายคน) แยกตามกลุ่มเสี่ยง</h5>
  <table class="table table-bordered text-center align-middle mb-4">
    <thead class="bg-main">
      <tr>
        <th class="text-start">กลุ่มเสี่ยง</th>
        <th class="bg-total">รวม</th>
        <th class="bg-male">ชาย</th>
        <th class="bg-female">หญิง</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($riskSummary as $name => $row)
        <tr>
          <td class="text-start">{{ $name }}</td>
          <td class="bg-total">{{ $row['total'] }}</td>
          <td class="bg-male">{{ $row['male'] }}</td>
          <td class="bg-female">{{ $row['female'] }}</td>
        </tr>
      @endforeach
      <tr class="fw-bold bg-light">
        <td class="text-start">รวมทั้งหมด</td>
        <td class="bg-total">{{ array_sum(array_column($riskSummary, 'total')) }}</td>
        <td class="bg-male">{{ array_sum(array_column($riskSummary, 'male')) }}</td>
        <td class="bg-female">{{ array_sum(array_column($riskSummary, 'female')) }}</td>
      </tr>
    </tbody>
  </table>

  {{-- (5) Visits by risk group --}}
  <h5 class="mt-4 fw-bold">Visit แยกตามกลุ่มเสี่ยง</h5>
  <table class="table table-bordered text-center align-middle mb-4">
    <thead>
      <tr>
        <th class="text-start">กลุ่มเสี่ยง</th>
        <th class="bg-total">รวม</th>
        <th class="bg-male">ชาย</th>
        <th class="bg-female">หญิง</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($riskVisitSummary as $name => $row)
        <tr>
          <td class="text-start">{{ $name }}</td>
          <td class="bg-total">{{ $row['total'] }}</td>
          <td class="bg-male">{{ $row['male'] }}</td>
          <td class="bg-female">{{ $row['female'] }}</td>
        </tr>
      @endforeach
      <tr class="fw-bold bg-light">
        <td class="text-start">รวมทั้งหมด</td>
        <td class="bg-total">{{ array_sum(array_column($riskVisitSummary, 'total')) }}</td>
        <td class="bg-male">{{ array_sum(array_column($riskVisitSummary, 'male')) }}</td>
        <td class="bg-female">{{ array_sum(array_column($riskVisitSummary, 'female')) }}</td>
      </tr>
    </tbody>
  </table>

{{-- ตารางโรคหลัก --}}
@if(isset($diseaseRows))
  <h5 class="mt-4 fw-bold">โรคหลัก (นับผู้ป่วยรายคน)</h5>
  <table class="table table-bordered text-center align-middle mb-4">
    <thead class="bg-main">
      <tr>
        <th>โรค</th>
        <th class="bg-total">รวม</th>
        <th class="bg-male">ชาย</th>
        <th class="bg-female">หญิง</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($diseaseRows as $disease => $row)
        <tr>
          <td class="text-start">{{ $disease }}</td>
          <td class="bg-total fw-strong">{{ $row['total'] }}</td>
          <td class="bg-male">{{ $row['male'] }}</td>
          <td class="bg-female">{{ $row['female'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

{{-- ตารางกิจกรรม/โรคย่อย --}}
@if(isset($activityRows) && count($activityRows))
  <h5 class="mt-4 fw-bold">ตารางโรคกิจกรรม/โรคย่อย (แยกตามกลุ่มเสี่ยง)</h5>
  <table class="table table-bordered text-center align-middle mb-4">
    <thead class="bg-main align-middle">
      <tr>
        <th rowspan="2">กิจกรรม</th>
        <th colspan="3">รวม</th>
        <th colspan="7">กลุ่มเสี่ยง</th>
      </tr>
      <tr>
        <th class="bg-male">ชาย</th><th class="bg-female">หญิง</th><th class="bg-total">รวม</th>
        <th>FSW</th><th>MSW</th><th>MSM</th><th>แรงงานข้ามชาติ</th><th>เรือนจำ</th><th>เยาวชน</th><th>อื่น ๆ</th>
      </tr>
    </thead>
    <tbody>
      @foreach($activityRows as $row)
      <tr>
        <td class="text-start">{{ $row['activity'] }}</td>
        <td class="bg-male">{{ $row['male'] }}</td>
        <td class="bg-female">{{ $row['female'] }}</td>
        <td class="bg-total fw-strong">{{ $row['total'] }}</td>
        <td>{{ $row['fsw'] }}</td>
        <td>{{ $row['msw'] }}</td>
        <td>{{ $row['msm'] }}</td>
        <td>{{ $row['labor'] }}</td>
        <td>{{ $row['prisoner'] }}</td>
        <td>{{ $row['young'] }}</td>
        <td>{{ $row['other'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endif



@push('scripts')
<!-- Chart.js & Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

  // Palette Pastel (คุณจะเปลี่ยนได้ตามใจเลย)
  const pastelBlue = "rgba(100,181,246,0.7)";
  const pastelPink = "rgba(255,138,170,0.6)";
  const pastelYellow = "rgba(255,236,139,0.7)";
  const pastelGreen = "rgba(129,199,132,0.6)";
  const pastelGray = "rgba(158,158,158,0.25)";
  const pastelPurple = "rgba(186,104,200,0.5)";

  // 1. Pie เพศ
  new Chart(document.getElementById('genderPie'), {
    type: 'pie',
    data: {
      labels: ['ชาย', 'หญิง'],
      datasets: [{
        data: [{{ $malePatients }}, {{ $femalePatients }}],
        backgroundColor: [pastelBlue, pastelPink],
        borderWidth: 0
      }]
    },
    options: {
      plugins: {
        legend: { position: 'bottom', labels: { font: { family: 'Prompt, Nunito, sans-serif', size: 15 } } },
        datalabels: {
          color: '#555',
          font: { family: 'Prompt, Nunito, sans-serif', weight: '500', size: 17 },
          borderRadius: 10,
          backgroundColor: "rgba(255,255,255,0.72)",
          padding: 6,
          formatter: (value, ctx) => {
            let total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            let percent = total ? (value * 100 / total).toFixed(1) : 0;
            return value + ' (' + percent + '%)';
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });

  // 2. Pie สัญชาติ
  new Chart(document.getElementById('nationPie'), {
    type: 'pie',
    data: {
      labels: ['ไทย', 'พม่า', 'อื่นๆ'],
      datasets: [{
        data: [{{ $thai }}, {{ $burmese }}, {{ $other_nationality }}],
        backgroundColor: [pastelGreen, pastelYellow, pastelGray],
        borderWidth: 0
      }]
    },
    options: {
      plugins: {
        legend: { position: 'right', labels: { font: { family: 'Prompt, Nunito, sans-serif', size: 15 } } },
        datalabels: {
          color: '#555',
          font: { family: 'Prompt, Nunito, sans-serif', weight: '500', size: 16 },
          borderRadius: 12,
          backgroundColor: "rgba(255,255,255,0.82)",
          padding: 7,
          formatter: function(value, ctx) {
            let total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            let percent = total ? (value * 100 / total).toFixed(1) : 0;
            return value + ' (' + percent + '%)';
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });

  // 3. Bar กลุ่มเสี่ยง
  new Chart(document.getElementById('riskGroupBar'), {
    type: 'bar',
    data: {
      labels: {!! json_encode(array_keys($riskSummary)) !!},
      datasets: [
        {
          label: 'ชาย',
          data: {!! json_encode(array_column($riskSummary, 'male')) !!},
          backgroundColor: pastelBlue,
          borderRadius: 8,
        },
        {
          label: 'หญิง',
          data: {!! json_encode(array_column($riskSummary, 'female')) !!},
          backgroundColor: pastelPink,
          borderRadius: 8,
        },
        {
          label: 'รวม',
          data: {!! json_encode(array_column($riskSummary, 'total')) !!},
          backgroundColor: pastelYellow,
          borderRadius: 8,
        }
      ]
    },
    options: {
      plugins: {
        legend: { position: 'top', labels: { font: { family: 'Prompt, Nunito, sans-serif', size: 14 } } },
        datalabels: {
          anchor: 'end', align: 'top',
          color: '#666', font: { family: 'Prompt, Nunito, sans-serif', weight: '600', size: 14 },
          backgroundColor: "rgba(255,255,255,0.7)",
          borderRadius: 6, padding: 4,
          formatter: v => v > 0 ? v : ''
        }
      },
      scales: {
        x: { stacked: false, ticks: { font: { family: 'Prompt, Nunito, sans-serif', size: 13 } } },
        y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Prompt, Nunito, sans-serif', size: 13 } }, grid: { color: "rgba(200,200,200,0.08)" } }
      }
    },
    plugins: [ChartDataLabels]
  });

  // 4. Bar โรคหลัก
  new Chart(document.getElementById('diseaseBar'), {
    type: 'bar',
    data: {
      labels: {!! json_encode(array_keys($diseaseRows)) !!},
      datasets: [
        {
          label: 'ชาย',
          backgroundColor: pastelBlue,
          data: {!! json_encode(array_column($diseaseRows, 'male')) !!},
          borderRadius: 8,
        },
        {
          label: 'หญิง',
          backgroundColor: pastelPink,
          data: {!! json_encode(array_column($diseaseRows, 'female')) !!},
          borderRadius: 8,
        }
      ]
    },
    options: {
      plugins: {
        legend: { position: 'top', labels: { font: { family: 'Prompt, Nunito, sans-serif', size: 14 } } },
        datalabels: {
          anchor: 'end', align: 'top',
          color: '#666', font: { family: 'Prompt, Nunito, sans-serif', weight: '600', size: 14 },
          backgroundColor: "rgba(255,255,255,0.7)",
          borderRadius: 6, padding: 4,
          formatter: v => v > 0 ? v : ''
        }
      },
      scales: {
        x: { stacked: false, ticks: { font: { family: 'Prompt, Nunito, sans-serif', size: 13 } } },
        y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Prompt, Nunito, sans-serif', size: 13 } }, grid: { color: "rgba(200,200,200,0.08)" } }
      }
    },
    plugins: [ChartDataLabels]
  });

});
</script>
<link href="https://fonts.googleapis.com/css?family=Prompt:400,600&display=swap" rel="stylesheet">
<style>
body, .chartjs-render-monitor { font-family: 'Prompt', 'Nunito', sans-serif; }
</style>


@endpush
</div>
<style>
  .bg-male { background-color: #B3E5FC !important; }  /* ฟ้า */
  .bg-female { background-color: #F8BBD0 !important; } /* ชมพู */
  .bg-total { background-color: #FFF9C4 !important; }  /* เหลือง */
  .bg-main { background-color: #f1f8e9 !important; }
  .fw-strong { font-weight: 600; }
</style>
@endsection
