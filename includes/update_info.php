<?php
session_start();

include 'dbh.inc.php';

$id = $_SESSION['id'];

if(isset($_POST['submit_update'])) {
  if(strlen($_POST['bio']) > 100) {
    echo "Bio can't exceed 255 characters";
    header('Location: ../index.php?error=toomuchbio');
  }elseif(strlen($_POST['location']) > 42) {
    echo "Please choose a reasonable location.";
    header('Location: ../index.php?error=badlocation');
  } else{
  $bio = $_POST['bio'];
  $loc = $_POST['location'];
  $bday = $_POST['birthday'];
    $sql = "UPDATE users SET bio = ?, location_from = ?, bday = ? WHERE id='$id'";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
      var_dump($_POST);
      var_dump($conn);
    }else {
      $bio = $_POST['bio'];
      $loc = $_POST['location'];
      $bday = $_POST['birthday'];
      mysqli_stmt_bind_param($stmt,('sss'), $bio, $loc, $bday);
      mysqli_stmt_execute($stmt);
      header("Location: ../index.php?infoupdated");
    }
  }
    var_dump($bio);
  }else {
    var_dump($result);
    var_dump($_POST['bio']);
  }







 ?>
