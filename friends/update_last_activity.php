<?php
session_start();
include '../includes/dbh.inc.php';

$id = $_SESSION['id'];


$sql = "UPDATE user_details SET last_activity = now() WHERE user_id = '$id'";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  var_dump($sql);
  var_dump($stmt);
  exit();
} else{
  var_dump($_SESSION['id']);
  mysqli_stmt_execute($stmt);

}
 ?>
