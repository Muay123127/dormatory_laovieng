<?php
include_once 'connect-db.php';
$Stu_ID = $_POST['Stu_ID'];

$sql = "DELETE FROM student WHERE Stu_ID='$Stu_ID'";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo mysqli_error($link);
}
