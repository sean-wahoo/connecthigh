<?php
require 'dbh.inc.php';

//check if there are messages at all
include '../friends/chat.php';
$id2 = $_POST['id2'];
$sessionid = $_POST['session_id'];

$sql = "SELECT from_user_id, chat_message FROM chat_message WHERE from_user_id = '$sessionid' AND to_user_id = '$id2' OR to_user_id = '$sessionid' AND from_user_id = '$id2' ORDER BY datemade ASC";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) < 1){
  echo "<p style='font-family: Roboto, sans-serif;text-align:center;color:gray;font-style:italic;'>No messages yet!</p>";
}
while($row = mysqli_fetch_assoc($query)){
  if($row['from_user_id'] == $sessionid){
    $from_sender_msg = "<br /><li style='list-style-type:none;clear:left;'><p style='word-break:break-word;-webkit-hyphens:auto;-moz-hyphens:auto;hyphens:auto;-ms-hyphens:auto;font-family:sans-serif;position:relative;float:right;color:black;margin:auto;margin-bottom:3px;margin-right:3px;background-color:gray;padding:3px 6px 3px 6px;border-radius:8px;display:block;'>" . $row['chat_message'] . "</p></li><br />";
    echo $from_sender_msg;
  }
  echo "<li style='list-style-type:none;'></li>";
  if($row['from_user_id'] == $id2){
    $to_sender_msg = "<br /><li style='list-style-type:none;clear:right;'><p style='word-break:break-word;-webkit-hyphens:auto;-moz-hyphens:auto;hyphens:auto;-ms-hyphens:auto;font-family:sans-serif;position:relative;float:left;color:white;margin:auto;margin-left:6px;margin-bottom:3px;left:3px;background-color:green;padding:3px 6px 3px 6px;border-radius:8px;display:block;'>" . $row['chat_message'] . "</p></li><br />";
    echo $to_sender_msg;
  }

}

 ?>
