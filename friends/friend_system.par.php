<?php
session_start();
$log_username = $_SESSION['username'];

include "../includes/dbh.inc.php";
if (isset($_POST['type']) && isset($_POST['user1']))
{
    $user1 = preg_replace('#[^a-z0-9]#i', '', $_POST['user1']);
    $sql = "SELECT COUNT(id) FROM users WHERE username='$user1'";
    $result = mysqli_query($conn, $sql);
    $exist_count = mysqli_fetch_row($result);

    $log_id = $_SESSION['id'];

  	$sqlGetUser1Id = "SELECT id FROM users WHERE username = '$user1'";
  	$queryGetUser1Id = mysqli_query($conn, $sqlGetUser1Id);
  	$user1_id = mysqli_fetch_assoc($queryGetUser1Id)['id'];


    if ($exist_count[0] < 1)
    {

        mysqli_close($conn);
        echo "$user1 does not exist.";
        exit();
    }
    if ($_POST['type'] == "friend")
    {
        $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user1' AND accepted='1' OR user2='$user1' AND accepted='1'";
        $query = mysqli_query($conn, $sql);
        $friend_count = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$user1' AND blockee='$log_username' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $blockcount1 = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$log_username' AND blockee='$user1' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $blockcount2 = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user1' AND accepted='1' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count1 = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user1' AND user2='$log_username' AND accepted='1' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count2 = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user1' AND accepted='0' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count3 = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user1' AND user2='$log_username' AND accepted='0' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count4 = mysqli_fetch_row($query);
        if ($friend_count[0] > 99)
        {
            mysqli_close($conn);
            echo "$user1 currently has the maximum number of friends, and cannot accept more.";
            exit();
        }
        else if ($blockcount1[0] > 0)
        {
            mysqli_close($conn);
            echo "$user1 has you blocked, we cannot proceed.";
            exit();
        }
        else if ($blockcount2[0] > 0)
        {
            mysqli_close($conn);
            echo "You must first unblock $user1 in order to friend with them.";
            exit();
        }
        else if ($row_count1[0] > 0 || $row_count2[0] > 0)
        {
            mysqli_close($conn);
            echo "You are already friends with $user1.";
            exit();
        }
        else if ($row_count3[0] > 0)
        {
            mysqli_close($conn);
            echo "You have a pending friend request already sent to $user1.";
            exit();
        }
        else if ($row_count4[0] > 0)
        {
            mysqli_close($conn);
            echo "$user1 has requested to friend with you first. Check your friend requests.";
            exit();
        }
        else
        {
            $sql = "INSERT INTO friends(user1, user2, datemade) VALUES('$log_username','$user1',now())";
            $query = mysqli_query($conn, $sql);
            $sqlNotifications = "INSERT INTO notifications(user_id, from_id, about, description) VALUES('$user1_id', '$log_id', '1', 'add_friend')";
  					$queryNotification = mysqli_query($conn, $sqlNotifications);

            echo "friend_request_sent";
            mysqli_close($conn);
            exit();
        }
        echo 'error 3';
    }
    else if ($_POST['type'] == "unfriend")
    {
        $sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user1' AND accepted='1' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count1 = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) FROM friends WHERE user1='$user1' AND user2='$log_username' AND accepted='1' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count2 = mysqli_fetch_row($query);
        if ($row_count1[0] > 0)
        {
            $sql = "DELETE FROM friends WHERE user1='$log_username' AND user2='$user1' AND accepted='1' LIMIT 1";
            $query = mysqli_query($conn, $sql);
            $friendcountsql = "SELECT ( SELECT COUNT(*) FROM friends WHERE user1 = '$user1' AND accepted = '1') + ( SELECT COUNT(*) FROM friends WHERE user2 = '$user1' AND accepted = '1')";
      			$friendcountquery = mysqli_query($conn, $friendcountsql);
      			$official_friend_count = mysqli_fetch_row($friendcountquery);
      			$official_friend_count = $official_friend_count[0];
      			$updatesql = "UPDATE users SET friend_count='$official_friend_count' WHERE username='$user1'";
      			$updatequery = mysqli_query($conn, $updatesql);
            $updatequery = mysqli_query($conn, $updatesql);
            $friendcountsql2 = "SELECT ( SELECT COUNT(*) FROM friends WHERE user1 = '$log_username' AND accepted = '1') + ( SELECT COUNT(*) FROM friends WHERE user2 = '$log_username' AND accepted = '1')";
      			$friendcountquery2 = mysqli_query($conn, $friendcountsql2);
      			$official_friend_count2 = mysqli_fetch_row($friendcountquery2);
      			$official_friend_count2 = $official_friend_count2[0];
      			$updatesql2 = "UPDATE users SET friend_count='$official_friend_count2' WHERE username='$log_username'";
      			$updatequery2 = mysqli_query($conn, $updatesql2);
            mysqli_close($conn);
            echo "unfriend_ok";
            exit();
        }
        else if ($row_count2[0] > 0)
        {
            $sql = "DELETE FROM friends WHERE user1='$user1' AND user2='$log_username' AND accepted='1' LIMIT 1";
            $query = mysqli_query($conn, $sql);
            $friendcountsql = "SELECT ( SELECT COUNT(*) FROM friends WHERE user1 = '$user1' AND accepted = '1') + ( SELECT COUNT(*) FROM friends WHERE user2 = '$user1' AND accepted = '1')";
      			$friendcountquery = mysqli_query($conn, $friendcountsql);
      			$official_friend_count = mysqli_fetch_row($friendcountquery);
      			$official_friend_count = $official_friend_count[0];
      			$updatesql = "UPDATE users SET friend_count='$official_friend_count' WHERE username='$user1'";
      			$updatequery = mysqli_query($conn, $updatesql);
            $updatequery = mysqli_query($conn, $updatesql);
            $friendcountsql2 = "SELECT ( SELECT COUNT(*) FROM friends WHERE user1 = '$log_username' AND accepted = '1') + ( SELECT COUNT(*) FROM friends WHERE user2 = '$log_username' AND accepted = '1')";
      			$friendcountquery2 = mysqli_query($conn, $friendcountsql2);
      			$official_friend_count2 = mysqli_fetch_row($friendcountquery2);
      			$official_friend_count2 = $official_friend_count2[0];
      			$updatesql2 = "UPDATE users SET friend_count='$official_friend_count2' WHERE username='$log_username'";
      			$updatequery2 = mysqli_query($conn, $updatesql2);
            mysqli_close($conn);
            echo "unfriend_ok";
            exit();
        }
        else
        {
            mysqli_close($conn);
            echo "No friendship could be found between your account and $user1, therefore we cannot unfriend you.";
            exit();
        }
        echo 'error 2';
    }
    echo 'error 1';
}

if (isset($_POST['action']) && isset($_POST['reqid']) && isset($_POST['user1']))
{
    $reqid = preg_replace('#[^0-9]#', '', $_POST['reqid']);
    $user1 = preg_replace('#[^a-z0-9]#i', '', $_POST['user1']);
    $sql = "SELECT COUNT(id) FROM users WHERE username='$user1' LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $exist_count = mysqli_fetch_row($query);
    if ($exist_count[0] < 1)
    {
        mysqli_close($conn);
        echo "$user1 doesn't exist.";
        exit();
    }
    if ($_POST['action'] == 'accept')
    {

        $sql = "SELECT COUNT(id) from friends WHERE user1='$log_username' AND user2='$user1' AND accepted='1' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count1 = mysqli_fetch_row($query);
        $sql = "SELECT COUNT(id) from friends WHERE user1='$user1' AND user2='$log_username' AND accepted='1' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row_count2 = mysqli_fetch_row($query);
        if ($row_count1[0] > 0 || $row_count2[0] > 0)
        {
            mysqli_close($conn);
            echo "You're already friends with $user1.";
            exit();
        }
        else
        {
            $log_id = $_SESSION['id'];

            $sqlGetUser1Id = "SELECT id FROM users WHERE username = '$user1'";
            $queryGetUser1Id = mysqli_query($conn, $sqlGetUser1Id);
            $user1_id = mysqli_fetch_assoc($queryGetUser1Id)['id'];
            $sql = "UPDATE friends SET accepted='1' WHERE id='$reqid' AND user1='$user1' AND user2='$log_username' LIMIT 1";
            $query = mysqli_query($conn, $sql);
            $friendcountsql = "SELECT ( SELECT COUNT(*) FROM friends WHERE user1 = '$user1' AND accepted = '1') + ( SELECT COUNT(*) FROM friends WHERE user2 = '$user1' AND accepted = '1')";
      			$friendcountquery = mysqli_query($conn, $friendcountsql);
      			$official_friend_count = mysqli_fetch_row($friendcountquery);
      			$official_friend_count = $official_friend_count[0];
      			$updatesql = "UPDATE users SET friend_count='$official_friend_count' WHERE username='$user1'";
      			$updatequery = mysqli_query($conn, $updatesql);
            $friendcountsql2 = "SELECT ( SELECT COUNT(*) FROM friends WHERE user1 = '$log_username' AND accepted = '1') + ( SELECT COUNT(*) FROM friends WHERE user2 = '$log_username' AND accepted = '1')";
      			$friendcountquery2 = mysqli_query($conn, $friendcountsql2);
      			$official_friend_count2 = mysqli_fetch_row($friendcountquery2);
      			$official_friend_count2 = $official_friend_count2[0];
      			$updatesql2 = "UPDATE users SET friend_count='$official_friend_count2' WHERE username='$log_username'";
      			$updatequery2 = mysqli_query($conn, $updatesql2);
            $sqlRemoveNotification = "DELETE FROM notifications WHERE user_id = '$user1_id' AND from_id = '$log_id' AND about = '1' OR user_id = '$log_id' AND from_id = '$user1_id' AND about = '1'";
            $queryRemoveNotification = mysqli_query($conn, $sqlRemoveNotification);
            mysqli_close($conn);
            echo "accept_ok";
            exit();
        }
    }
    elseif ($_POST['action'] == 'reject')
    {
        mysqli_query($conn, "DELETE FROM friends WHERE id = '$reqid' AND user1='$user1' AND user2='$log_username' AND accepted='0'");
        echo "reject_ok";
        exit();
    }
}

?>
