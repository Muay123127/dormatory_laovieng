<?php
session_start();
require_once 'connect-db.php';

$username =  $_SESSION['username'];
$password = $_SESSION['password'];

$sql = "SELECT *FROM users WHERE username = '$username' AND password='$password'";
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result) <= 0) {
    header('location: login-form.php');
}