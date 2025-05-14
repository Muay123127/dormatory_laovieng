<?php
include_once 'connect-db.php';

$grade =  $_POST['grade'];

$sql = "DELETE FROM salary WHERE grade='$grade' ";
$result = mysqli_query($link, $sql);

if(!$result) {
    echo mysqli_error($link);
}



