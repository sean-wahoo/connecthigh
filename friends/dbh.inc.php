<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error){
  die('Connect Error (' . $conn->connect_errno .')'
    . $conn->connect_error);
}
if (!$conn) {
  die("Connection failed: ".mysqli_connect_error());
}



date_default_timezone_set('America/New_York');
if(isset($_SESSION['id'])){
$user_id = $_SESSION['id'];




}
 ?>
