<?php
$server = "localhost";
$user = "root";
$password = "";
$database = "hay farm";

$db = mysqli_connect($server, $user, $password, $database);

if(!$db){
    die("Gagal Terhubung ke Database: ".mysqli_connect_error());
}
?>