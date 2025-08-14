<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportController extends Controller
{
    public function dashboard(Request $request)
{
    $month = $request->input('month') ?? date('n');
    $year = $request->input('year') ?? (date('Y') + 543);

    $reportData = $this->getReportData($month, $year);
    $activityRows = $this->getMonthlyActivityReport($month, $year); // ✅ เพิ่มบรรทัดนี้

    return view('report.dashboard', compact('reportData', 'month', 'year', 'activityRows')); // ✅ เพิ่ม 'activityRows'
}

   private function getReportData($month, $year)
{

    $yearAD = $year - 543;

    // สร้าง WHERE เงื่อนไขแบบยืดหยุ่น
    $where = "YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) = $yearAD";
    if ($month) {
        $where .= " AND MONTH(DATE_SUB(v.date, INTERVAL 543 YEAR)) = $month";
    }

    // เตรียม SQL Statement
    $sql = "
        SELECT
            COUNT(DISTINCT v.patient_id) AS total_patients,
            COUNT(DISTINCT v.id) AS total_visits,

            -- จำนวน visit แยกเพศ
COUNT(DISTINCT CASE WHEN p.sex = 1 THEN v.id END) AS visit_male,
COUNT(DISTINCT CASE WHEN p.sex = 2 THEN v.id END) AS visit_female,


            -- กลุ่มเสี่ยง
            SUM(p.status = 2 AND p.occupation = 10) AS fsw,
            SUM(p.status = 1 AND p.occupation = 10) AS msw,
            SUM(p.status = 1 AND p.occupation != 10) AS msm,
            SUM(p.status = 3) AS labor,
            SUM(p.status = 5) AS young,
            SUM(p.status = 7) AS drug_user,
            SUM(p.status NOT IN (1,2,3,5,7)) AS other,

            -- อายุรวม
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) <= 14) AS age_0_14,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 15 AND 24) AS age_15_24,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 25 AND 34) AS age_25_34,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 35 AND 44) AS age_35_44,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 45 AND 54) AS age_45_54,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 55 AND 64) AS age_55_64,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 65 AND 74) AS age_65_74,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) >= 75) AS age_75_up,

            -- อายุแยกเพศ
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) <= 14 AND p.sex = 1) AS age_0_14_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) <= 14 AND p.sex = 2) AS age_0_14_female,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 15 AND 24 AND p.sex = 1) AS age_15_24_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 15 AND 24 AND p.sex = 2) AS age_15_24_female,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 25 AND 34 AND p.sex = 1) AS age_25_34_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 25 AND 34 AND p.sex = 2) AS age_25_34_female,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 35 AND 44 AND p.sex = 1) AS age_35_44_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 35 AND 44 AND p.sex = 2) AS age_35_44_female,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 45 AND 54 AND p.sex = 1) AS age_45_54_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 45 AND 54 AND p.sex = 2) AS age_45_54_female,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 55 AND 64 AND p.sex = 1) AS age_55_64_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 55 AND 64 AND p.sex = 2) AS age_55_64_female,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 65 AND 74 AND p.sex = 1) AS age_65_74_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) BETWEEN 65 AND 74 AND p.sex = 2) AS age_65_74_female,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) >= 75 AND p.sex = 1) AS age_75_up_male,
            SUM(YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) - YEAR(DATE_SUB(p.birth_date, INTERVAL 543 YEAR)) >= 75 AND p.sex = 2) AS age_75_up_female,

            -- สัญชาติ
            COUNT(DISTINCT CASE WHEN p.nationality IN ('THA', 'Thai', 'ไทย') THEN v.patient_id END) AS thai,
            COUNT(DISTINCT CASE WHEN p.nationality IN ('MMR', 'พม่า', 'Myanmar') THEN v.patient_id END) AS burmese,
            COUNT(DISTINCT CASE WHEN p.nationality NOT IN ('THA', 'Thai', 'ไทย', 'MMR', 'พม่า', 'Myanmar') THEN v.patient_id END) AS other_nationality,

            -- โรคแยกชายหญิง
            " . $this->getDiseaseBySexCounts($month, $yearAD) . ",

            -- โรคหลัก (3 โรค): ผู้ป่วยใหม่/เกิดซ้ำ
            " . implode(",\n", $this->getDiseaseSubqueries($month, $yearAD)) . ",

            -- โรคอื่นรวมทั้งหมด
            " . $this->getOtherDiseaseCounts($month, $yearAD) . ",

            -- ดึงทุกรายการโรคตามเพศ (สำหรับ yearly dashboard ใช้ร่วมกันได้)
            " . $this->getDiseaseYearlyCounts() . "

        FROM visits v
        LEFT JOIN patients p ON p.id = v.patient_id
        LEFT JOIN diagnoses d ON d.visit_id = v.id
        WHERE $where
    ";

    return DB::select($sql)[0] ?? null;
}

private function getOtherDiseaseCounts($month, $yearAD)
{
    // กำหนด diseases ให้ตรงกับชื่อ field ใน Blade
    $diseases = [
        4  => 'lgv',                    // LGV
        5  => 'chancroid',              // Chancroid
        6  => 'bacterial_vaginosis',    // Bacterial vaginosis
        7  => 'vaginal_candidiasis',    // Vaginal candidiasis
        8  => 'trichomoniasis',         // Trichomoniasis
        9  => 'molluseum_contagiosum',  // Molluseum contagiosum
        10 => 'anogenital_warts',       // Anogenital warts
        11 => 'other',                  // Other
        12 => 'herpes_simplex'          // Herpes simplex
    ];

    $queries = [];
    foreach ($diseases as $id => $alias) {
        $queries[] = "(
            SELECT COUNT(DISTINCT v.patient_id)
            FROM diagnoses d
            JOIN visits v ON v.id = d.visit_id
            WHERE d.disease = {$id}
              AND MONTH(DATE_SUB(v.date, INTERVAL 543 YEAR)) = {$month}
              AND YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) = {$yearAD}
        ) AS {$alias}";
    }

    return implode(",\n", $queries);
}

private function getDiseaseSubqueries($month, $yearAD)
{
    return [
        "(SELECT COUNT(DISTINCT v1.patient_id) FROM diagnoses d1 JOIN visits v1 ON v1.id = d1.visit_id WHERE d1.disease = 1 AND d1.disease_state = 1 AND MONTH(DATE_SUB(v1.date, INTERVAL 543 YEAR)) = $month AND YEAR(DATE_SUB(v1.date, INTERVAL 543 YEAR)) = $yearAD) AS syphilis_new",
        "(SELECT COUNT(DISTINCT v1.patient_id) FROM diagnoses d1 JOIN visits v1 ON v1.id = d1.visit_id WHERE d1.disease = 1 AND d1.disease_state = 2 AND MONTH(DATE_SUB(v1.date, INTERVAL 543 YEAR)) = $month AND YEAR(DATE_SUB(v1.date, INTERVAL 543 YEAR)) = $yearAD) AS syphilis_repeat",
        "(SELECT COUNT(DISTINCT v2.patient_id) FROM diagnoses d2 JOIN visits v2 ON v2.id = d2.visit_id WHERE d2.disease = 2 AND d2.disease_state = 1 AND MONTH(DATE_SUB(v2.date, INTERVAL 543 YEAR)) = $month AND YEAR(DATE_SUB(v2.date, INTERVAL 543 YEAR)) = $yearAD) AS gonorrhea_new",
        "(SELECT COUNT(DISTINCT v2.patient_id) FROM diagnoses d2 JOIN visits v2 ON v2.id = d2.visit_id WHERE d2.disease = 2 AND d2.disease_state = 2 AND MONTH(DATE_SUB(v2.date, INTERVAL 543 YEAR)) = $month AND YEAR(DATE_SUB(v2.date, INTERVAL 543 YEAR)) = $yearAD) AS gonorrhea_repeat",
        "(SELECT COUNT(DISTINCT v3.patient_id) FROM diagnoses d3 JOIN visits v3 ON v3.id = d3.visit_id WHERE d3.disease = 3 AND d3.disease_state = 1 AND MONTH(DATE_SUB(v3.date, INTERVAL 543 YEAR)) = $month AND YEAR(DATE_SUB(v3.date, INTERVAL 543 YEAR)) = $yearAD) AS chlamydia_new",
        "(SELECT COUNT(DISTINCT v3.patient_id) FROM diagnoses d3 JOIN visits v3 ON v3.id = d3.visit_id WHERE d3.disease = 3 AND d3.disease_state = 2 AND MONTH(DATE_SUB(v3.date, INTERVAL 543 YEAR)) = $month AND YEAR(DATE_SUB(v3.date, INTERVAL 543 YEAR)) = $yearAD) AS chlamydia_repeat",
        "(SELECT COUNT(DISTINCT v10.patient_id) FROM diagnoses d10 JOIN visits v10 ON v10.id = d10.visit_id WHERE d10.disease = 10 AND d10.disease_state = 2 AND MONTH(DATE_SUB(v10.date, INTERVAL 543 YEAR)) = $month AND YEAR(DATE_SUB(v10.date, INTERVAL 543 YEAR)) = $yearAD) AS warts_repeat"
    ];
}

private function getDiseaseBySexCounts($month, $yearAD)
    {
        $diseases = [
            1 => 'syphilis',
            2 => 'gonorrhea',
            3 => 'chlamydia',
            4 => 'lgv',
            5 => 'chancroid',
            6 => 'bacterial_vaginosis',
            7 => 'vaginal_candidiasis',
            8 => 'trichomoniasis',
            9 => 'molluscum_contagiosum',
            10 => 'anogenital_warts',
            11 => 'other',
            12 => 'herpes_simplex',
        ];

        $counts = [];
        foreach ($diseases as $id => $name) {
            $counts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$id} AND p.sex = 1 THEN v.patient_id END) AS {$name}_male";
            $counts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$id} AND p.sex = 2 THEN v.patient_id END) AS {$name}_female";
        }

        return implode(",\n", $counts);
    }


  public function yearlyDashboard(Request $request)
{
    // รับปี พ.ศ. จาก Blade หรือค่า default
    $year = $request->input('year', date('Y') + 543);
    $activityRows = $this->getActivityByRiskGroupForYear($year);
    // Option กลุ่มเสี่ยง/โรค
    $statusOption = [
        1 => 'MSM/MSW/TG', 2 => 'พนักงานบริการ', 3 => 'แรงงานข้ามชาติ',
        4 => 'ผู้ป่วยเรือนจำ', 5 => 'เยาวชน', 6 => 'ประชาชนทั่วไป', 7 => 'ผู้ใช้สารเสพติด'
    ];
    $diseaseOption = [
        1 => 'Syphilis', 2 => 'Gonorrhea', 3 => 'Non-Gonococcal urethritis',
        4 => 'LGV', 5 => 'Chancroid', 6 => 'Bacterial vaginosis',
        7 => 'Vaginal candidiasis', 8 => 'Trichomoniasis', 9 => 'Molluseum contagiosum',
        10 => 'Anogenital warts', 11 => 'Other', 12 => 'Herpes simplex'
    ];
    $diseaseMainIds = [
    1 => 'Syphilis',
    2 => 'Gonorrhea',
    3 => 'Non-Gonococcal urethritis',
];
$diseaseNewRepeatRows = [];
foreach ($diseaseMainIds as $diseaseId => $diseaseName) {
    $new_male = DB::table('diagnoses')
        ->join('visits', 'visits.id', '=', 'diagnoses.visit_id')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->where('diagnoses.disease', $diseaseId)
        ->whereYear('visits.date', $year) // ใช้ $year
        ->where('patients.sex', 1)
        ->where('diagnoses.disease_state', 1)
        ->distinct('patients.id')->count('patients.id');

    $new_female = DB::table('diagnoses')
        ->join('visits', 'visits.id', '=', 'diagnoses.visit_id')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->where('diagnoses.disease', $diseaseId)
        ->whereYear('visits.date', $year)
        ->where('patients.sex', 2)
        ->where('diagnoses.disease_state', 1)
        ->distinct('patients.id')->count('patients.id');

    $repeat_male = DB::table('diagnoses')
        ->join('visits', 'visits.id', '=', 'diagnoses.visit_id')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->where('diagnoses.disease', $diseaseId)
        ->whereYear('visits.date', $year)
        ->where('patients.sex', 1)
        ->where('diagnoses.disease_state', 2)
        ->distinct('patients.id')->count('patients.id');

    $repeat_female = DB::table('diagnoses')
        ->join('visits', 'visits.id', '=', 'diagnoses.visit_id')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->where('diagnoses.disease', $diseaseId)
        ->whereYear('visits.date', $year)
        ->where('patients.sex', 2)
        ->where('diagnoses.disease_state', 2)
        ->distinct('patients.id')->count('patients.id');

    $diseaseNewRepeatRows[$diseaseName] = [
        'new_male' => $new_male,
        'new_female' => $new_female,
        'repeat_male' => $repeat_male,
        'repeat_female' => $repeat_female,
    ];
}
    // (1) Visits (ปี พ.ศ.)
    $visitBase = DB::table('visits')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->whereYear('visits.date', $year);

    $totalVisits = (clone $visitBase)->count('visits.id');
    $maleVisits = (clone $visitBase)->where('patients.sex', 1)->count('visits.id');
    $femaleVisits = (clone $visitBase)->where('patients.sex', 2)->count('visits.id');

    // (2) Patients (นับไม่ซ้ำรายคน)
    // Patient summary (distinct รายปี)
$patientBase = DB::table('visits')
    ->join('patients', 'patients.id', '=', 'visits.patient_id')
    ->whereYear('visits.date', $year);

// เพิ่มสัญชาติ
$thai = (clone $patientBase)->whereIn('patients.nationality', ['THA', 'Thai', 'ไทย'])->distinct('patients.id')->count('patients.id');
$burmese = (clone $patientBase)->whereIn('patients.nationality', ['MMR', 'Myanmar', 'พม่า'])->distinct('patients.id')->count('patients.id');
$other_nationality = (clone $patientBase)
    ->whereNotIn('patients.nationality', ['THA', 'Thai', 'ไทย', 'MMR', 'Myanmar', 'พม่า'])
    ->distinct('patients.id')->count('patients.id');


    $totalPatients = (clone $patientBase)->distinct('patients.id')->count('patients.id');
    $malePatients = (clone $patientBase)->where('patients.sex', 1)->distinct('patients.id')->count('patients.id');
    $femalePatients = (clone $patientBase)->where('patients.sex', 2)->distinct('patients.id')->count('patients.id');

    // (3) Risk group (รายคน)
    $patientsInYear = DB::table('visits')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->whereYear('visits.date', $year)
        ->select('patients.id', 'patients.sex', 'patients.status')
        ->distinct('patients.id')
        ->get();

    $riskSummary = [];
    foreach ($statusOption as $k => $groupName) {
        $riskSummary[$groupName] = [
            'male' => $patientsInYear->where('sex', 1)->where('status', $k)->unique('id')->count(),
            'female' => $patientsInYear->where('sex', 2)->where('status', $k)->unique('id')->count(),
        ];
        $riskSummary[$groupName]['total'] = $riskSummary[$groupName]['male'] + $riskSummary[$groupName]['female'];
    }

    // (4) Risk group (visit)
    $visitsInYear = DB::table('visits')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->whereYear('visits.date', $year)
        ->select('visits.id', 'patients.sex', 'patients.status')
        ->get();

    $riskVisitSummary = [];
    foreach ($statusOption as $k => $groupName) {
        $riskVisitSummary[$groupName] = [
            'male' => $visitsInYear->where('sex', 1)->where('status', $k)->count(),
            'female' => $visitsInYear->where('sex', 2)->where('status', $k)->count(),
        ];
        $riskVisitSummary[$groupName]['total'] = $riskVisitSummary[$groupName]['male'] + $riskVisitSummary[$groupName]['female'];
    }

    // (5) โรคหลัก (รายคน)
    $diseaseRows = [];
    foreach ($diseaseOption as $code => $diseaseName) {
    $male = DB::table('diagnoses')
        ->join('visits', 'visits.id', '=', 'diagnoses.visit_id')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->where('diagnoses.disease', $code)  // ใช้ $code ที่เป็นเลข!
        ->whereYear('visits.date', $year)
        ->where('patients.sex', 1)
        ->whereIn('diagnoses.disease_state', [1, 2])
        ->distinct('patients.id')
        ->count('patients.id');

    $female = DB::table('diagnoses')
        ->join('visits', 'visits.id', '=', 'diagnoses.visit_id')
        ->join('patients', 'patients.id', '=', 'visits.patient_id')
        ->where('diagnoses.disease', $code)  // ใช้ $code ที่เป็นเลข!
        ->whereYear('visits.date', $year)
        ->where('patients.sex', 2)
        ->whereIn('diagnoses.disease_state', [1, 2])
        ->distinct('patients.id')
        ->count('patients.id');

    $diseaseRows[$diseaseName] = [
        'male' => $male,
        'female' => $female,
        'total' => $male + $female,
    ];
}

    // ส่งข้อมูลไปยัง Blade
    return view('report.yearly_dashboard', compact(
        'year', 'totalVisits', 'maleVisits', 'femaleVisits',
        'totalPatients', 'malePatients', 'femalePatients',
        'riskSummary', 'riskVisitSummary', 'diseaseRows', 'activityRows', 'thai',
        'burmese', 'other_nationality','diseaseNewRepeatRows'
    ));
}



private function getYearlyReportData($year)
{
    $yearAD = $year;

    $sql = "
        SELECT
            COUNT(DISTINCT v.patient_id) AS total_patients,
            COUNT(v.id) AS total_visits,

            COUNT(DISTINCT CASE WHEN p.sex = 1 THEN p.id END) AS male_patients,
            COUNT(DISTINCT CASE WHEN p.sex = 2 THEN p.id END) AS female_patients,

            SUM(CASE WHEN p.sex = 1 THEN 1 ELSE 0 END) AS visit_male,
            SUM(CASE WHEN p.sex = 2 THEN 1 ELSE 0 END) AS visit_female,

            SUM(CASE WHEN p.sex = 1 THEN 1 ELSE 0 END) AS male,
            SUM(CASE WHEN p.sex = 2 THEN 1 ELSE 0 END) AS female,


            COUNT(DISTINCT CASE WHEN p.nationality IN ('THA', 'Thai', 'ไทย') THEN v.patient_id END) AS thai,
            COUNT(DISTINCT CASE WHEN p.nationality IN ('MMR', 'พม่า', 'Myanmar') THEN v.patient_id END) AS burmese,
            COUNT(DISTINCT CASE WHEN p.nationality NOT IN ('THA', 'Thai', 'ไทย', 'MMR', 'พม่า', 'Myanmar') THEN v.patient_id END) AS other_nationality,

            -- อายุแยกชายหญิง
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) <= 14 AND p.sex = 1) AS age_0_14_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) <= 14 AND p.sex = 2) AS age_0_14_female,

            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 15 AND 24 AND p.sex = 1) AS age_15_24_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 15 AND 24 AND p.sex = 2) AS age_15_24_female,

            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 25 AND 34 AND p.sex = 1) AS age_25_34_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 25 AND 34 AND p.sex = 2) AS age_25_34_female,

            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 35 AND 44 AND p.sex = 1) AS age_35_44_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 35 AND 44 AND p.sex = 2) AS age_35_44_female,

            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 45 AND 54 AND p.sex = 1) AS age_45_54_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 45 AND 54 AND p.sex = 2) AS age_45_54_female,

            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 55 AND 64 AND p.sex = 1) AS age_55_64_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 55 AND 64 AND p.sex = 2) AS age_55_64_female,

            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 65 AND 74 AND p.sex = 1) AS age_65_74_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) BETWEEN 65 AND 74 AND p.sex = 2) AS age_65_74_female,

            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) >= 75 AND p.sex = 1) AS age_75_up_male,
            SUM(TIMESTAMPDIFF(YEAR, DATE_SUB(p.birth_date, INTERVAL 543 YEAR), DATE_SUB(v.date, INTERVAL 543 YEAR)) >= 75 AND p.sex = 2) AS age_75_up_female,

            " . $this->getDiseaseYearlyCounts() . "
        FROM visits v
        LEFT JOIN patients p ON p.id = v.patient_id
        LEFT JOIN diagnoses d ON d.visit_id = v.id
        WHERE YEAR(DATE_SUB(v.date, INTERVAL 543 YEAR)) = ?
    ";

    $data = DB::select($sql, [$yearAD]);
    return $data[0] ?? null;
}

private function getDiseaseYearlyCounts()
{
    $diseases = [
        1 => 'syphilis',
        2 => 'gonorrhea',
        3 => 'chlamydia',
        4 => 'lgv',
        5 => 'chancroid',
        6 => 'bacterial_vaginosis',
        7 => 'vaginal_candidiasis',
        8 => 'trichomoniasis',
        9 => 'molluscum_contagiosum',
        10 => 'anogenital_warts',
        11 => 'other',
        12 => 'herpes_simplex'
    ];

    $sqlParts = [];

    foreach ($diseases as $code => $name) {
        $sqlParts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$code} AND p.sex = 1 THEN v.patient_id END) AS {$name}_male";
        $sqlParts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$code} AND p.sex = 2 THEN v.patient_id END) AS {$name}_female";

        if (in_array($code, [1, 2, 3])) {
            $sqlParts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$code} AND d.disease_state = 1 AND p.sex = 1 THEN v.patient_id END) AS {$name}_new_male";
            $sqlParts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$code} AND d.disease_state = 1 AND p.sex = 2 THEN v.patient_id END) AS {$name}_new_female";
            $sqlParts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$code} AND d.disease_state = 2 AND p.sex = 1 THEN v.patient_id END) AS {$name}_repeat_male";
            $sqlParts[] = "COUNT(DISTINCT CASE WHEN d.disease = {$code} AND d.disease_state = 2 AND p.sex = 2 THEN v.patient_id END) AS {$name}_repeat_female";
        }
    }

    return implode(",\n", $sqlParts);
}

    public function getMonthlyActivityReport($month, $year)
{
    $yearAD = $year - 543;

    // กลุ่มกิจกรรม (mapped จาก disease)
    $activityMap = [
        1 => 'Syphilis', 2 => 'Gonorrhea', 3 => 'Non-Gonococcal urethritis', 4 => 'LGV', 5 => 'Chancroid',
        6 => 'Bacterial vaginosis', 7 => 'Vaginal candidiasis', 8 => 'Trichomoniasis', 9 => 'Molluseum contagiosum',
        10 => 'Anogenital warts', 11 => 'Other', 12 => 'Herpes simplex', 13 => 'Pre-test', 14 => 'Post-test',
        15 => 'No STI', 16 => 'GF', 17 => 'Egasp 1', 18 => 'Egasp 2'
    ];

    $statusGroups = [
        'fsw' => [2], // พนักงานบริการหญิง
        'msw' => [1], // MSW ต้องกรองอาชีพพิเศษด้านล่าง
        'msm' => [1], // MSM อย่างเดียว 
        'labor' => [3],
        'prisoner' => [4],
        'young' => [5],
        'other' => [6, 7, 0],
    ];

    $rows = [];

    foreach ($activityMap as $diseaseId => $activityName) {
        $row = [
            'activity' => $activityName,
            'male' => 0,
            'female' => 0,
            'total' => 0,
            'fsw' => 0,
            'msw' => 0,
            'msm' => 0,
            'labor' => 0,
            'prisoner' => 0,
            'young' => 0,
            'other' => 0,
        ];

        $query = DB::table('visits as v')
            ->join('patients as p', 'p.id', '=', 'v.patient_id')
            ->join('diagnoses as d', 'd.visit_id', '=', 'v.id')
            ->whereYear(DB::raw("DATE_SUB(v.date, INTERVAL 543 YEAR)"), $yearAD)
            ->whereMonth(DB::raw("DATE_SUB(v.date, INTERVAL 543 YEAR)"), $month)
            ->where('d.disease', $diseaseId);

        $cloned = clone $query;

        // รวมเพศ
        $row['male'] = (clone $query)->where('p.sex', 1)->distinct('v.patient_id')->count('v.patient_id');
        $row['female'] = (clone $query)->where('p.sex', 2)->distinct('v.patient_id')->count('v.patient_id');
        $row['total'] = $row['male'] + $row['female'];

        // กลุ่มเสี่ยง
        foreach ($statusGroups as $group => $statusCodes) {
            $groupQuery = (clone $cloned)->whereIn('p.status', $statusCodes);

            // เงื่อนไขพิเศษ: MSM ต้องเป็นเพศชายและอาชีพพิเศษ (10)
            if ($group === 'msw') {
                $groupQuery = $groupQuery->where('p.sex', 1)->where('p.occupation', 10);
            } elseif ($group === 'msm') {
                $groupQuery = $groupQuery->where('p.sex', 1)->where('p.occupation', '!=', 10);
            }

            $row[$group] = $groupQuery->distinct('v.patient_id')->count('v.patient_id');
        }

        $rows[] = $row;
    }

    return $rows;
}
public function getYearlyActivityReport($year)
{
    $yearAD = $year - 543;  // แปลงปี พ.ศ. เป็น ค.ศ.
    
    // ซ้ำกับการดึงข้อมูลรายเดือน
    $activityRows = $this->getMonthlyActivityReport(1, $year);  // ใช้เดือน 1 เพื่อให้ได้ข้อมูลปีทั้งหมด

    $monthlyData = [];

    for ($m = 1; $m <= 12; $m++) {
        $monthlyData[] = $this->getMonthlyActivityReport($m, $year);
    }

    return view('report.yearly_dashboard', compact('activityRows', 'monthlyData'));
}
    public function getActivityByRiskGroupForYear($year)
{
    $yearAD = $year - 543;

    $activityMap = [
        1 => 'Syphilis', 2 => 'Gonorrhea', 3 => 'Non-Gonococcal urethritis', 4 => 'LGV', 5 => 'Chancroid',
        6 => 'Bacterial vaginosis', 7 => 'Vaginal candidiasis', 8 => 'Trichomoniasis', 9 => 'Molluseum contagiosum',
        10 => 'Anogenital warts', 11 => 'Other', 12 => 'Herpes simplex', 13 => 'Pre-test', 14 => 'Post-test',
        15 => 'No STI', 16 => 'GF', 17 => 'Egasp 1', 18 => 'Egasp 2'
    ];

    $rows = [];

    foreach ($activityMap as $diseaseId => $activityName) {
        // ผู้ป่วยไม่ซ้ำคน
        $patients = DB::table('diagnoses as d')
    ->join('visits as v', 'v.id', '=', 'd.visit_id')
    ->join('patients as p', 'p.id', '=', 'v.patient_id')
    ->where('d.disease', $diseaseId)
    ->whereIn('d.disease_state', [1, 2])
    ->whereYear(DB::raw("DATE_SUB(v.date, INTERVAL 543 YEAR)"), $yearAD)
    ->select('v.patient_id', 'p.sex', 'p.status', 'p.occupation')
    ->groupBy('v.patient_id', 'p.sex', 'p.status', 'p.occupation')
    ->get();

        $row = [
            'activity' => $activityName,
            'male' => 0,
            'female' => 0,
            'total' => 0,
            'fsw' => 0,
            'msw' => 0,
            'msm' => 0,
            'labor' => 0,
            'prisoner' => 0,
            'young' => 0,
            'other' => 0,
        ];

        foreach ($patients as $p) {
            if ($p->sex == 1) $row['male']++;
            elseif ($p->sex == 2) $row['female']++;

            if ($p->status == 2) {
                $row['fsw']++;
            } elseif ($p->status == 1 && $p->sex == 1 && $p->occupation == 10) {
                $row['msw']++;
            } elseif ($p->status == 1 && $p->sex == 1 && $p->occupation != 10) {
                $row['msm']++;
            } elseif ($p->status == 3) {
                $row['labor']++;
            } elseif ($p->status == 4) {
                $row['prisoner']++;
            } elseif ($p->status == 5) {
                $row['young']++;
            } elseif (in_array($p->status, [6, 7, 0])) {
                $row['other']++;
            }
        }

        $row['total'] = $row['male'] + $row['female'];
        $rows[] = $row;

    }

    return $rows;

}


}
