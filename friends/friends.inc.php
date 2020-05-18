<?php
include "../includes/dbh.inc.php";
if (isset($_POST['type']) && isset($_POST['user'])) {
  $user = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
  $sql = "SELECT COUNT(id) FROM users WHERE username='$user' LIMIT 1";
  $query = mysqli_query($conn, $sql);
  $exist_count = mysqli_fetch_row($query);

  $sqlGetLogId = "SELECT id FROM users WHERE username = '$log_username'";
	$queryGetLogId = mysqli_query($conn, $queryGetLogId);
	$log_id = mysqli_fetch_assoc($queryGetLogId)['id'];

	$sqlGetUser1Id = "SELECT id FROM users WHERE username = '$user1'";
	$queryGetUser1Id = mysqli_query($conn, $sqlGetUser1Id);
	$user1_id = mysqli_fetch_assoc($queryGetUser1Id)['id'];

  if($exist_count[0] < 1){
    mysqli_close($conn);
    echo "$user does not exist.";
    exit();
  }
  if($_POST['type'] == "friend"){
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND accepted='1' OR user2='$user' AND accepted='1'";
    $query = mysqli_query($conn, $sql);
    $friend_count = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$log_username' AND blockee='$user' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $blockcount1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$user' AND blockee='$log_username' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $blockcount2 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $row_count1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user2='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $row_count2 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='0' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $row_count3 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user2='$user' AND user2='$log_username' AND accepted='0' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $row_count4 = mysqli_query($conn, $sql);
    if($friend_count[0] > 99){
      mysqli_close($conn);
      echo "$user can't have any more friends!";
    }elseif($blockcount1[0] > 0) {
      mysqli_close($conn);
      echo "$user blocked you. You can't view their profile.";
    }elseif($blockcount2[0] > 0){
      mysqli_close($conn);
      echo "You must unblock $user to be friends with them.";
    }elseif($row_count1[0] > 0 || $row_count2[0] > 0) {
      mysqli_close($conn);
      echo "You and $user are already friends.";
    }elseif($row_count3[0] > 0) {
      mysqli_close($conn);
      echo "You already sent a friend request to $user";
    }elseif($row_count4[0] > 0) {
      mysqli_close($conn);
      echo "$user already sent you a friend request.";
    }else{
      $sql = "INSERT INTO friends(user1, user2, datemade) VALUES('$log_username','$user',now())";
      $query = mysqli_query($conn, $sql);
      $sqlNotifications = "INSERT INTO notifications(user_id, from_id, about, description) VALUES('$user1_id', '$log_id', '1', 'add_friend')";
      $queryNotification = mysqli_query($conn, $sqlNotifications);
      mysqli_close($conn);
      echo "friend_request_sent";
      exit();
    }
  }elseif($_POST['type'] == "unfriend") {
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $row_count1 = mysqli_fetch_row($query);
    $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $row_count2 = mysqli_fetch_row($query);
    if ($row_count1[0] > 0){
      $sql = "DELETE FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
      $query = mysqli_query($conn);
      mysqli_close($conn);
      echo "unfriend_ok";
      exit();
    }elseif($row_count2[0] > 0){
      $sql = "DELETE FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
      $query = mysqli_query($conn);
      mysqli_close($conn);
      echo "unfriend_ok";
      exit();
  }else {
    mysqli_close($conn);
    echo "You can't unfriend someone you aren't friends with!";
  }
}
}

?><?php
if (isset($_POST['action']) && isset($_POST['reqid']) && isset($_POST['user1'])){
	$reqid = preg_replace('#[^0-9]#', '', $_POST['reqid']);
	$user = preg_replace('#[^a-z0-9]#i', '', $_POST['user1']);
	$sql = "SELECT COUNT(id) FROM users WHERE username=$user LIMIT=1";
	$query = mysqli_query($conn, $sql);
	$exist_count = mysqli_fetch_row($query);
	if($exist_count[0] < 1){
		mysqli_close($conn);
		echo "$user doesn't exist.";
		exit();
	}
	if($_POST['action'] == 'accept'){
		$sql = "SELECT COUNT(id) from friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
		$query = mysqli_query($conn, $sql);
		$row_count1 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) from friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
		$query = mysqli_query($conn, $sql);
		$row_count2 = mysqli_fetch_row($query);
		if($row_count1[0] > 0 || $row_count2[0] > 0){
			mysqli_close($conn);
			echo "You're already friends with $user.";
			exit();
		}else {
			$sql = "UPDATE friends SET accepted='1' WHERE user1='$user' AND user2='$log_username' LIMIT 1";
			$query = mysqli_query($conn, $sql);
      $friendcountsql = "SELECT ( SELECT COUNT(*) FROM friends WHERE user1 = 'bigtestman' AND accepted = '1') + ( SELECT COUNT(*) FROM friends WHERE user2 = 'bigtestman' AND accepted = '1')";
			$friendcountquery = mysqli_query($conn, $friendcountsql);
			$official_friend_count = mysqli_fetch_row($friendcountquery);

			if($official_friend_count[0] > 0){
  			$updatesql = "UPDATE users SET friend_count='$official_friend_count[0]' WHERE username='$user'";
  			$updatequery = mysqli_query($conn, $updatesql);
      }
			mysqli_close($conn);
			echo "accept_ok";
			exit();
		}
	}elseif($_POST['action'] == 'reject'){
		mysqli_query($conn, "DELETE FROM friends WHERE id = '$reqid' AND user1='$user' AND user2='$log_username' AND accepted='0'");
		echo "reject_ok";
		exit();
	}
	}
