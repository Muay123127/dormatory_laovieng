<?php
include_once 'connect-db.php';
$S_id = $_POST['S_id'];

$sql = "DELETE FROM majors WHERE S_id='$S_id'";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo mysqli_error($link);
}
