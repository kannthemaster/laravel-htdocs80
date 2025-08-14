<?php
require_once('Connections/pdomysql.php');
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ERROR | E_PARSE);
$mysql = new pdomysql();

$cid = $_REQUEST['cid_send'];
//$cid = '0486355';
//$cid = '0344370';
//$cid = '1344342';


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

?>


<?php
$sql_1 = "SELECT
 			concat( p.hn ) AS HN,
 			concat( p.pname, p.fname, ' ', p.lname ) AS PTname,
 			p.birthday,
 			t.NAME AS PTTYPEname,
 			o.NAME AS occupName,
 			p.informaddr AS InformName,
 			p.cid AS CID,
 			concat('อายุ ',TIMESTAMPDIFF(YEAR,p.birthday,CURDATE()),'  ปี ',
			TIMESTAMPDIFF(MONTH,p.birthday,CURDATE())% 12,' เดือน ',FLOOR( TIMESTAMPDIFF( DAY, p.birthday, CURDATE())% 30.4375 ),' วัน' ) AS ageYEARs,
 			v.vstdate,
 			v.vn,
 			v.spclty AS SPCLTY,
 			max(v.vstdate) AS LastVisitDate,
 			concat( 'แผนก : ', s.NAME ) AS SPCLTYname ,

 				IFNULL((SELECT ov.oqueue from ovst ov
    			where ov.hn = v.hn AND ov.vstdate = CURRENT_DATE
    			ORDER BY ov.vn desc Limit 1),'No') QN

		FROM patient p
 			
 			LEFT OUTER JOIN pttype t ON t.pttype = p.pttype
			LEFT OUTER JOIN occupation o ON o.occupation = p.occupation
			LEFT OUTER JOIN vn_stat v ON v.hn = p.hn 
			LEFT OUTER JOIN spclty s ON s.spclty = v.spclty 
		
		WHERE
		p.cid = :cid OR p.hn = :cid
		and v.vn = (select  max( vv.vn ) from vn_stat vv where vv.hn = p.hn) 
 		and v.vstdate = (select  max( vv.vstdate ) from vn_stat vv where vv.hn = p.hn)";



$row = $mysql->selectOne($sql_1, $data);


echo "HYGGE=GAP";

if ($row) {
    echo "&PatientName=" . $row['PTname'] . "&PatientHn=" . $row['HN'] . "&PatientAge=" . $row['ageYEARs'] . "&PttypeName=" . $row['PTTYPEname'] . "&PtLastVisit=" . ThaiEachDate($row['LastVisitDate']) . "&SpcltyName=" . $row['SPCLTYname'] . "&PatientCID=" . $row['CID']."&VSTDATE=".$row['LastVisitDate']."&PatientQN=".$row['QN']."&GAP=POOK";
    $cidis = $row['CID'];
    $hnis  = $row['HN'];
} else {
    echo "&Patientname=ไม่พบในฐานข้อมูล";
}
$data2['hnis'] = $hnis;
$data3['hnis'] = $hnis;
?>


<?php
$sql_2 = "SELECT
	lh.order_date AS OrderLabDate,
	lh.receive_time AS OrderLabTime,
	lh.report_date AS ReportLabDate,
	lh.report_time AS ReportLabTime,
	lh.confirm_report AS ConfirmResult,
	lh.lab_order_number AS LabNumber
    FROM lab_head lh

    WHERE lh.hn = :hnis AND lh.order_date between (date(now())-10)  and date(now())
    order by lh.order_date DESC
    Limit 1";

$row_2 = $mysql->selectOne($sql_2, $data2);

if ($row_2) {
    echo
        "&LabOrderDate=" . ThaiEachDate($row_2['OrderLabDate']) .
        "&LabOrderTime=" . $row_2['OrderLabTime'] .
        "&LabConfirm=Date" . ThaiEachDate($row_2['ReportLabDate']) .
        "&LabReportTime=" . $row_2['OrderLabTime'] .
        "&LabResult=" . $row_2['ConfirmResult'] .
        "&LabNumber=" . $row_2['LabNumber'];
} else {
    echo "&LabOrderDate=ไม่พบในฐานข้อมูล&LabOrderTime=ไม่พบในฐานข้อมูล&LabResult=ไม่พบในฐานข้อมูล";
}
?>


<?php
$sql_3 = "SELECT
o1.hn as 'hn'
,o1.nextdate as 'app_date'

,o1.nexttime as Oactime1
,c.name as Oaclinic1
,d.name as Oadoctor1
,o1.clinic as OaclinicCode1

,a.clinic as Oaclinic2,a.nexttime  as Oactime2,a.doctor as Oadoctor2,a.clinic as OaclinicCode2
,b.clinic as Oaclinic3,b.nexttime  as Oactime3,b.doctor as Oadoctor3,b.clinic as OaclinicCode3
from oapp o1 
left outer join 
(select o2.hn,o2.oapp_id,o2.nextdate,o2.nexttime,c.name as Clinic,d.name as doctor 
from oapp o2
LEFT OUTER JOIN clinic c on c.clinic=o2.clinic
LEFT OUTER JOIN doctor d on d.code=o2.doctor
)a on a.hn=o1.hn and a.nextdate=o1.nextdate and a.oapp_id!=o1.oapp_id

left outer join 
(select o3.hn,o3.oapp_id,o3.nextdate,o3.nexttime,c.name as Clinic,d.name as doctor  from oapp o3
LEFT OUTER JOIN clinic c on c.clinic=o3.clinic
LEFT OUTER JOIN doctor d on d.code=o3.doctor

)b on b.hn=o1.hn and b.nextdate=o1.nextdate and b.oapp_id!=o1.oapp_id and b.oapp_id!=a.oapp_id


LEFT OUTER JOIN clinic c on c.clinic = o1.clinic
LEFT OUTER JOIN doctor d on d.code = o1.doctor

where 

o1.hn = :hnis  and
o1.nextdate=CURRENT_DATE

group by o1.hn
order by o1.oapp_id";

$row_3 = $mysql->selectOne($sql_3, $data3);

if ($row_3) {
    echo
        "&OappCheck=" . $row_3['app_date'] .

        "&OappTime_1=" . $row_3['Oactime1'] .
        "&OappClinicCode_1="  . $row_3['OaclinicCode1'].
        "&OappClinic_1="  . $row_3['Oaclinic1'] .
        "&OappDoctor_1="  . $row_3['Oadoctor1'].

        "&OappTime_2=" . $row_3['Oactime2'] .
        "&OappClinicCode_2="  . $row_3['OaclinicCode2'].
        "&OappClinic_2="  . $row_3['Oaclinic2'] .
        "&OappDoctor_2="  . $row_3['Oadoctor2'].


        "&OappTime_3=" . $row_3['Oactime3'] .
        "&OappClinicCode_3="  . $row_3['OaclinicCode3'].
        "&OappClinic_3="  . $row_3['Oaclinic3'] .
        "&OappDoctor_3="  . $row_3['Oadoctor3'];
} else {
    echo 
    	"&OappTime_1=NO".
        "&OappClinic_1=NO" .
        "&OappDoctor_1=NO".

        "&OappTime_2=NO".
        "&OappClinic_2=NO" .
        "&OappDoctor_2=NO".

        "&OappTime_3=NO".
        "&OappClinic_3=NO" .
        "&OappDoctor_3=NO";
}

?>



<?php exit(); ?>