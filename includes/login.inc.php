<?php
  session_start();
if (isset($_POST['login'])) {

  require 'dbh.inc.php';

  $useremail = $_POST['userlogin'];
  $password = $_POST['password'];

  if (empty($useremail) || empty($password)) {
    header("Location: ../login.php?error=emptyfields");
    exit();

  }else {
    $sql = "SELECT * FROM users WHERE username=? OR email=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../login.php?error=sqlerror");
      exit();
    } else{
      mysqli_stmt_bind_param($stmt, "ss", $useremail, $useremail);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)) {
        $passwordcheck = password_verify($password, $row['password']);
        if ($passwordcheck == false) {
          header("Location: ../login.php?error=wrongpass");
          exit();
        } elseif ($passwordcheck == true) {
          if(isset($_POST['rememberme'])) {
            $hour = time() + 3600 * 24 * 30;
            $unsethour = time() - 3600 * 24 * 30;
            $hashedpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            setcookie("unm", 'usr='.$_POST['userlogin'], $hour, "/");
            setcookie("pwd", "pss=".$hashedpass, $hour, "/");
          }

          $_SESSION['userid'] = $row['id'];
          $_SESSION['username'] = $row['username'];

          header("Location: ../index.php?login=success");
          exit();
        } else {
          header("Location: ../login.php?error=wrongpass");
          exit();
        }
      } else{
        header("Location: ../login.php?error=nouser");
        exit();
      }
    }
  }


}else {
  header("Location: ../index.php?login=success");
}
 ?>
