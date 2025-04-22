<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "php_workshop";


$link = mysqli_connect($host,$user,$password,$database);
if($link) {
        //echo "ເຊື່ອມຕໍ່ຖານຂໍ້ມູນສຳເລັດ";
}
else {
        echo mysqli_connect_error();
    }
    mysqli_set_charset($link, "utf8");
