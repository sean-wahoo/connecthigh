<?php
session_start();
include '../includes/friends.inc.php';
$servername = "localhost";
$dbusername = "root";
$dbpassword = "Qwey7676@";
$dbname = "devtest";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error){
  die('Connect Error (' . $conn->connect_errno .')'
    . $conn->connect_error);
}
if (!$conn) {
  die("Connection failed: ".mysqli_connect_error());
}

require 'friends.inc.php';

if(isset($_POST['tags'])) {
  if($_POST['tags'] == "addFriend"){
    $friends =  new Friends;
    $friends->add($_POST['uid'], $_SESSION['id']);
  }
}



 ?>
