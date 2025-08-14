<?php
require_once('Connections/pdomysql.php');
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ERROR | E_PARSE);
$mysql = new pdomysql();

$cid = $_REQUEST['cid_send'];
//$cid = 3510100222005;

$data['cid'] = $cid;

function ThaiEachDate($vardate = "")
{
    $_month_name = array("01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม",
        "04" => "เมษายน", "05" => "พฤษภาคม", "06" => "มิถุนายน",
        "07" => "กรกฎาคม", "08" => "สิงหาคม", "09" => "กันยายน",
        "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");
    $yy = substr($vardate, 0, 4);
    $mm = substr($vardate, 5, 2);
    $dd = substr($vardate, 8, 2);
    $yy += 543;
    if ($yy == 543) {
        $dateT = "-";
    } else {
        $dateT = $dd . " " . $_month_name[$mm] . " " . $yy;
    }
    return $dateT;
}

$sql_1 = "SELECT
            concat(p.code) AS HN,
            concat(p.name, ' ', p.surname) AS PTname,
            p.birth_date,
            p.id,
            p.id_card_number AS CID,
            concat('อายุ ', TIMESTAMPDIFF(YEAR, p.birth_date, CURDATE()) + 544, ' ปี ') AS ageYEARs,
            v.date,
            v.id,
            MAX(v.date) AS LastVisitDate
        FROM patients p
            LEFT OUTER JOIN visits v ON v.patient_id = p.id
        WHERE
            p.id_card_number = :cid OR p.code = :cid";

try {
    $row = $mysql->selectOne($sql_1, $data);

echo "HYGGE=GAP";

    if ($row) {
        echo "&PatientName=" . $row['PTname'] . 
        "&PatientHn=" . $row['HN'] . 
        "&PatientAge=" . $row['ageYEARs'] .
        "&VSTDATE=".$row['LastVisitDate']. 
        "&PatientCID=" . $row['CID'].
        "&GAP=POOK";
        $cidis = $row['CID'];
        $hnis  = $row['HN'];
    } else {
        echo "&Patientname=ไม่พบในฐานข้อมูล";
    }
    $data2['hnis'] = isset($hnis) ? $hnis : "";
    $data3['hnis'] = isset($hnis) ? $hnis : "";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

exit();
?>
