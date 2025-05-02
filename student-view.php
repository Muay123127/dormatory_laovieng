<?php
include_once 'connect-db.php';
$Stu_ID = $_POST['Stu_ID'];
$output = '';
$sql = "SELECT e.Stu_ID, e.Stu_name, e.gender, e.date_birth, "
    . " CONCAT(TIMESTAMPDIFF(YEAR, date_birth, curdate()),' ປີ ',MOD(TIMESTAMPDIFF(MONTH,  date_birth, curdate()), 12),' ເດືອນ ',TIMESTAMPDIFF(DAY, DATE_ADD( date_birth, INTERVAL TIMESTAMPDIFF(MONTH,  date_birth, curdate()) MONTH), curdate()),' ວັນ ') AS age, "
    . " e.address,  s.name AS name, c.Name, e.Sets,e.Gen,e.tell, e.status "
    . " FROM student e JOIN supplier s ON e.S_id = s.S_id JOIN customer c ON e.cid=c.cid WHERE e.Stu_ID='$Stu_ID'";

$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_assoc($result)) {

    $output .= '<table>';
    $output .= '<tr><td>ລະຫັດພະນັກງານ: </td><td>' . $row['Stu_ID'] . '</td></tr>';
    $output .= '<tr><td>ຊື່ ແລະ ນາມສະກຸນ: </td><td>' . $row['Stu_name'] . '</td></tr>';
    $output .= '<tr><td>ເພດ: </td><td>' . $row['gender'] . '</td></tr>';
    $output .= '<tr><td>ວັນ, ເດືອນ, ປີເກີດ: </td><td>' . date('d/m/Y', strtotime($row['date_birth'])) . '</td></tr>';
    $output .= '<tr><td>ອາຍຸ: </td><td>' . $row['age'] . '</td></tr>';
    $output .= '<tr><td>ທີ່ຢູ່: </td><td>' . $row['address'] . '</td></tr>';
    $output .= '<tr><td>ສາຂາຮຽນ: </td><td>' . $row['name'] . '</td></tr>';
    $output .= '<tr><td>ພາກ: </td><td>' . $row['Name'] . '</td></tr>';
    $output .= '<tr><td>ຊຸດຮຽນ: </td><td>' . $row['Sets'] . '</td></tr>';
    $output .= '<tr><td>ຮຸ້ນທີ: </td><td>' . $row['Gen'] . '</td></tr>';
    $output .= '<tr><td>ເບີໂທ: </td><td>' . $row['tell'] . '</td></tr>';
    $output .= '<tr><td>ສະຖານະ: </td><td>' . $row['status'] . '</td></tr>';
    $output .= '</table>';
}

echo $output;
