<?php
include_once 'connect-db.php';
$dno = $_POST['dno'];

$sql = "DELETE FROM dept WHERE dno='$dno'";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo mysqli_error($link);
}
