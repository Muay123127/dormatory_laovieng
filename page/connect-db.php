<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$host     = $_ENV['DB_HOST'];
$user     = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$database = $_ENV['DB_NAME'];

$link = mysqli_connect($host, $user, $password, $database);

if ($link) {
} else {
    echo "Connection error: " . mysqli_connect_error();
}
mysqli_set_charset($link, "utf8");
