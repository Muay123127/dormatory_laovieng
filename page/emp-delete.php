<?php
include_once 'connect-db.php';
$empno = $_POST['empno'];

//ລືບຮູບ
$sql = "SELECT picture FROM emp WHERE empno='$empno'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$picture = $row['picture'];

unlink("images/$picture"); //ລືບຮູບອອກຈາກ ໂພນເດີ images

//ລືບຂໍ້ມູນ
$sql = "DELETE FROM emp WHERE empno='$empno'";
mysqli_query($link, $sql);