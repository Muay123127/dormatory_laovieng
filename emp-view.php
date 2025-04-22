<?php
include_once 'connect-db.php';

$empno = $_POST['empno'];
$output = '';
$sql = "SELECT e.empno, e.name, e.gender, e.dateOfBirth, "
    . " CONCAT(TIMESTAMPDIFF(YEAR, dateOfBirth, curdate()),' ປີ ',MOD(TIMESTAMPDIFF(MONTH,  dateOfBirth, curdate()), 12),' ເດືອນ ',TIMESTAMPDIFF(DAY, DATE_ADD( dateOfBirth, INTERVAL TIMESTAMPDIFF(MONTH,  dateOfBirth, curdate()) MONTH), curdate()),' ວັນ ') AS age, "
    . " e.address, e.picture, d.name AS department, s.sal, e.incentive, s.sal+e.incentive AS total , e.language "
    . " FROM emp e JOIN dept d ON e.dno = d.dno JOIN salary s ON e.grade=s.grade WHERE e.empno='$empno'";

$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $picture = is_numeric($row['picture']) ? "avatar_img.png" : $row['picture'];
    $output .= '<p style= "text-align: center">';
    $output .= '<img src="images/' . $picture . '" alt="ຮູບພະນັກງານ" width="150px" height="200px" class="img-thumbnail">';
    $output .= '</p>';
    $output .= '<table>';
    $output .= '<tr><td>ລະຫັດພະນັກງານ: </td><td>' . $row['empno'] . '</td></tr>';
    $output .= '<tr><td>ຊື່ ແລະ ນາມສະກຸນ: </td><td>' . $row['name'] . '</td></tr>';
    $output .= '<tr><td>ເພດ: </td><td>' . $row['gender'] . '</td></tr>';
    $output .= '<tr><td>ວັນ, ເດືອນ, ປີເກີດ: </td><td>' . date('d/m/Y', strtotime($row['dateOfBirth'])) . '</td></tr>';
    $output .= '<tr><td>ອາຍຸ: </td><td>' . $row['age'] . '</td></tr>';
    $output .= '<tr><td>ທີ່ຢູ່: </td><td>' . $row['address'] . '</td></tr>';
    $output .= '<tr><td>ພະແນກ: </td><td>' . $row['department'] . '</td></tr>';
    $output .= '<tr><td>ເງິນເດືອນ: </td><td>' . number_format($row['sal'], 0, ",", ".") . ' ກີບ</td></tr>';
    $output .= '<tr><td>ເງິນອຸດໜູນ: </td><td>' . number_format($row['incentive'], 0, ",", ".") . ' ກີບ</td></tr>';
    $output .= '<tr><td>ລາຍຮັບລວມ: </td><td>' . number_format($row['total'], 0, ",", ".") . ' ກີບ</td></tr>';
    $output .= '<tr><td>ພາສາຕ່າງປະເທດ: </td><td>' . $row['language'] . '</td></tr>';
    $output .= '</table>';
}

echo $output;
