<?php
include_once 'connect-db.php';
$Room_ID = $_POST['Room_ID'];

$sql = "DELETE FROM room WHERE Room_ID='$Room_ID'";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo mysqli_error($link);
}
