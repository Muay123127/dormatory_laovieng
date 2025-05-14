<?php
include_once 'login-check.php';
include_once 'laokip-read.php';

// Filter the excel data 
function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

//set timezone
date_default_timezone_set('Asia/Bangkok');
// Column names 
$fields = array('ລະຫັດ', 'ຊື່ ແລະ ນາມສະກຸນ', 'ເພດ', 'ເງິນເດືອນ', 'ເງິນອຸດໜູນ', 'ລາຍຮັບລວມ', 'ພະແນກ');
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n";

if ($_GET['department'] !== "") {
    $fileName = "employee_" . $_GET['department'] . "_" . date('Y-m-d-H-i-s') . ".xls"; //File name

    $sql = "SELECT e.empno, e.name, e.gender, d.name AS department, s.sal, e.incentive, s.sal + e.incentive AS total, d.name AS department "
        . " FROM emp e JOIN dept d ON e.dno = d.dno JOIN salary s ON e.grade = s.grade WHERE d.dno = '" . $_GET['department'] . "' ORDER BY total DESC";
} else {
    $fileName = "employee_All" . date('Y-m-d-H-i-s') . ".xls"; //File name

    $sql = "SELECT e.empno, e.name, e.gender, d.name AS department, s.sal, e.incentive, s.sal + e.incentive AS total, d.name AS department "
        . " FROM emp e JOIN dept d ON e.dno = d.dno JOIN salary s ON e.grade = s.grade  ORDER BY department ASC, total DESC";
}


$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $lineData = array($row['empno'], $row['name'], $row['gender'], $row['sal'], $row['incentive'], $row['total'], $row['department']);
    array_walk($lineData, 'filterData');
    $excelData .= implode("\t", array_values($lineData)) . "\n";
}

// Headers for download 
header('Content-Type: application/xls');
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Render excel data 
print chr(255) . chr(254) . mb_convert_encoding($excelData, 'UTF-16LE', 'UTF-8');

exit;
