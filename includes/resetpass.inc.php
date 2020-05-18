<?php

  if (isset($_POST["resetpass"])) {

    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
    $password = $_POST["password"];
    $password2 = $_POST["confirmpassword"];

    if(empty($password) || empty($password2)) {
      header("Location: ../passreset.php?newpass=empty");
      exit();
    }elseif($password != $password2) {
      header("Location: ../passreset.php?newpass=mismatchpass")
      exit();
    }

    $currentDate = date("U");

    require 'dbh.inc.php';

    $sql = "SELECT * FROM resetpass WHERE resetSelector=? AND resetExpires >= ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "Something messed up!";
      exit();
    }else {
      mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);
      if (!$row = mysqli_fetch_assoc($result)) {
        echo "Try and resubmit a password reset again.";
        exit();
      }else {

        $tokenBin = hex2bin($validator);
        $tokenCheck = password_verify($tokenBin, $row['resetToken']);

        if ($tokenCheck === false) {
          echo "Try and resubmit a password reset again.";
          exit();
        }elseif ($tokenCheck === true) {

          $tokenEmail = $row['resetEmail'];

          $sql = "SELECT * FROM users WHERE email=?;";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "Something messed up!";
            exit();
        }else {
          mysqli_stmt_bind_param($stmt, 's' $tokenEmail);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          if (!$row = mysqli_fetch_assoc($result)) {
            echo "There was an error!";
            exit();
          }else {

            $sql = "UPDATE users SET password=? WHERE email=?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "Something messed up!";
              exit();
          }else {
            $newPassHash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, 'ss' $newPassHash, $tokenEmail);
            mysqli_stmt_execute($stmt);

            $sql = "DELETE FROM resetpass WHERE resetEmail=?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "Something messed up!";
              exit();
          }else {
            mysqli_stmt_bind_param($stmt, 's' $tokenEmail);
            mysqli_stmt_execute($stmt);
            header("Location: ../login.php?newpass=passwordchanged");
          }
        }
        }
      }
      }
    }
  }else {
    header("Location: ../login.php")
  }

 ?>
