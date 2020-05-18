<?php
      session_start();
  require 'dbh.inc.php';
  if(isset($_COOKIE['unm']) && isset($_COOKIE['pwd'])) {
    if($stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username=? OR email=?")) {
      parse_str($_COOKIE['unm']);
      $stmt->bind_param("ss",  $usr, $usr);
      $stmt->execute();

      $result = mysqli_stmt_get_result($stmt);
      if($row = mysqli_fetch_assoc($result)) {
        mysqli_stmt_bind_result($row);
      if ($row['username'] === $usr || $row['email'] === $usr){

        var_dump($row);
        $_SESSION['username'] = $row['username'];
        header("Location: ../index.php?autologin=success");

        exit();
      }else {
        var_dump($usr);
    }
  }
      $stmt->close();
      $conn->close();

    }else {
      header("Location: ../login.php?arrayerror");
  }
  }



 ?>
