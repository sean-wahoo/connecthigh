<?php
include 'dbh.inc.php';

$id = $_POST['id'];

$sql = "SELECT * FROM notifications WHERE user_id = '$id'";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) == 0){
  $notificationButton = '<button id="notifications" style="float: left;position:static"onclick = "getNotifications(), getButton()"><i id="notifications" class="far fa-bell fa-2x float-left"></i></button>';
}
else{
  $notificationButton = '<button id="notifications" style="float: left;position:static"onclick = "getNotifications(), getButton()"><i id="notifications" class="fas fa-bell fa-2x float-left"></i></button>';

}

echo $notificationButton;


 ?>
