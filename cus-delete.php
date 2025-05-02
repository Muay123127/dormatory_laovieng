<?php
include_once 'connect-db.php';
$cid = $_POST['cid'];

$sql = "DELETE FROM customer WHERE cid='$cid'";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo mysqli_error($link);
}
