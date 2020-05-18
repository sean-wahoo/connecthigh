<?php

if (isset($_POST['resetrequestsubmit'])){

  $selector = bin2hex(random_bytes(8));
  $token = random_bytes(32);

  $url = "www.connecthigh.net/passreset.php?selector=" . $selector . "$validator=" . bin2hex($token);

  $expires = date("U") + 1800;

  require 'dbh.inc.php';

  $userEmail = $_POST['email'];

  $sql = "DELETE FROM resetpass WHERE resetEmail=?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Something messed up!";
    exit();
  }else {
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
  }

  $sql = "INSERT INTO resetpass (resetEmail, resetSelector, resetToken, resetExpires) VALUES (?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Something messed up!";
    exit();
  }else {
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
    mysqli_stmt_execute($stmt);
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conn);

  $to = $userEmail;

  $subject = "Password Reset for Connecthigh";

  $message = "<p>Click on the link below to reset your password. If you are recieving this email and didn't attempt to reset your password, just ignore it.</p>";
  $message .="<p>Click this link to reset your password: <br />";
  $message .='<a href="' . $url . '">' . $url . '</a></p>';

  $headers = "From: connecthigh <connecthighsupport@gmail.com>\r\n";
  $headers .= "Reply-To: connecthighsupport@gmail.com\r\n";
  $headers .= "Content-type: text/html\r\n";

  mail($to, $subject, $message, $headers);

  header("Location: ../passreset.php?reset=success");

}else {
  header("Location: ../login.php");
}
 ?>
