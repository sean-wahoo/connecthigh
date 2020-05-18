<?php
session_start();
include 'dbh.inc.php';
if(isset($_POST['message_send'])){
  if(isset($_POST['chat_message']) && isset($_POST['to_user_id']) && isset($_POST['from_user_id']) && isset($_POST['read_status'])){
    if(!empty($_POST['chat_message'])){
    $chat_message = $_POST['chat_message'];
    $to_user_id = $_POST['to_user_id'];
    $from_user_id = $_POST['from_user_id'];
    $sql = "INSERT INTO chat_message (to_user_id, from_user_id, chat_message) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../friends/chat.php?error=chatsqlerror");
      exit();
    }else{
      mysqli_stmt_bind_param($stmt, "sss", $to_user_id, $from_user_id, $chat_message);
      mysqli_stmt_execute($stmt);
      $sqlMessageNotification = "INSERT INTO notifications (user_id, from_id, about, description) VALUES (?, ?, ?, ?);";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sqlMessageNotification)){
        header("Location: ../friends/chat.php?error=chatnotificationerror");
      }
      else{
        $about = '2';
        $description = 'message_sent';
        mysqli_stmt_bind_param($stmt, "iiis", $to_user_id, $from_user_id, $about, $description);
        mysqli_stmt_execute($stmt);
      }
      header("Location: ../friends/chat.php");
    }
  echo '1';
}
echo '2';
}
echo '3';
}



 ?>
