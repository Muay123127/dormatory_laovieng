<?php
session_start();
// session_destroy();
unset($_SESSION['name']);
unset($_SESSION['username']);
unset($_SESSION['paasword']);

header('location: login-form.php');
?>