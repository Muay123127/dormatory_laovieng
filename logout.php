<?php
session_start();
unset($_SESSION['name']);
unset($_SESSION['username']);
unset($_SESSION['paasword']);

header('location: login-form.php');
?>