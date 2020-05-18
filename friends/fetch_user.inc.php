<?php
session_start();

$outputtablehead = '

<table class="table table-hover p-0" id="friendListTable">


';
$output = '<tr>';
echo $outputtablehead;
$connect = new PDO("mysql:host=localhost;dbname=devtest", "root", "Qwey7676@");

include '../includes/dbh.inc.php';
include 'fetch_last_activity.inc.php';


$id = $_SESSION['id'];
$username = $_SESSION['username'];
// PREREQ QUERY
//$sql = "SELECT user1_id, user2_id, datemade, accepted FROM friends WHERE user1_id = '$username' OR user2_id = '$username'";
//$stmt = mysqli_stmt_init($conn);
//mysqli_stmt_prepare($stmt, $sql);
//mysqli_stmt_execute($stmt);
//$result = mysqli_stmt_get_result($stmt);
//foreach($result as $row){

// USER 1 USER 2 FRIEND LIST

//if($row['user1_id'] == $username){

// USER 1 QUERY

$query = "SELECT DISTINCT users.username, users.id FROM users INNER JOIN friends ON users.username = friends.user2 WHERE user1 = ? AND accepted = '1'";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $query))
{
    var_dump($stmt);
    var_dump($query);
}
else
{
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);



    foreach ($result as $row)
    {
      if($row['username'] !== $_SESSION['username']){

        $status = '';
        $current_timestamp = strtotime(date('Y-m-d H:i:s') . '-10 second');
        $id = $row['id'];
        $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
        $user_last_activity = fetch_user_last_activity($id, $conn);

        if ($user_last_activity > $current_timestamp)
        {
            $status = '<span class="statusonlinelabel align-middle">Online</span>';
        }
        else
        {
            $status = '<span class="statusofflinelabel align-middle">Offline</span>';
        }

        $friend_username = $row['username'];

          $id = $row['id'];
          if (is_file('../uploads/profile' . $id . '.jpg') === true)
          {
              $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="profile-picture-friends-jpeg"><img id="profile_picture" src="../uploads/profile'.$id.'.jpg" class="" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
          }
          // if a .png file
          elseif (is_file('../uploads/profile' . $id . '.png') === true)

          {
              $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="profile-picture-friends"><img id="profile_picture" src="../uploads/profile'.$id.'.png" class="" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
          }
          // stupid meticulous GOTTA MAKE A WHOLE NEW HANDLER .jpeg file check
          elseif (is_file('../uploads/profile' . $id . '.jpeg') === true)

          {
              $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="profile-picture-friends-jpeg"><img id="profile_picture" src="../uploads/profile'.$id.'.jpeg" class="" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
          }
          // doesn't exist so default image
          else
          {
              $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="p-0 profile-picture-default"><img id="profile_picture" src="../images/defaultimage.jpg" class="p-0" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
          }
        $output .= '
        <td id="status" class="align-middle">' . $status . '</td>
        <td class="pull-right align-middle">

          <button onclick="chatWindow(\'' . $row['username'] . '\',\'' . $row['id'] . '\'), updateScroll()" id="chat_start_button" value="' . $row['username'] . '" name="submit_chat" class="chat_start_button" type="submit" data-touserid="' . $row['id'] . '" data-tousername="' . $row['username'] . '"style="float:left;"><i class="fa fa-comment-alt fa-lg" aria-hidden="true" style="float: left;color:#66CA69"></i>
          </button>
          <input type="hidden" name="chat_username" value="' . $row['username'] . '" id="chat_username"/>
          <input type="hidden" name="chat_id" value="' . $row['id'] . '" id="chat_username"/>
          <input type="hidden" name="session_username" value="' . $_SESSION['username'] . '" id="chat_username"/>
          <input type="hidden" name="session_id" value="' . $_SESSION['userid'] . '" id="chat_id"/>

      </td>
      </tr>';



  }

}

}
//}


//elseif($row['user2_id'] == $username){

//USER 2 QUERY

$query = "SELECT DISTINCT users.username, users.id FROM users INNER JOIN friends ON users.username = friends.user1 WHERE user2 = ? AND accepted = '1'";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $query))
{
    var_dump($stmt);
    var_dump($query);
}
else
{
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);



    foreach ($result as $row)
    {
      if($row['username'] !== $_SESSION['username']){

        $status = '';
        $current_timestamp = strtotime(date('Y-m-d H:i:s') . '-10 second');
        $id = $row['id'];
        $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
        $user_last_activity = fetch_user_last_activity($id, $conn);

        if ($user_last_activity > $current_timestamp)
        {
            $status = '<span class="statusonlinelabel align-middle" >Online</span>';
        }
        else
        {
            $status = '<span class="statusofflinelabel align-middle">Offline</span>';
        }

        $friend_username = $row['username'];

        $id = $row['id'];
        if (is_file('../uploads/profile' . $id . '.jpg') === true)
        {
            $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="profile-picture-friends-jpeg"><img id="profile_picture" src="../uploads/profile'.$id.'.jpg" class="" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
        }
        // if a .png file
        elseif (is_file('../uploads/profile' . $id . '.png') === true)

        {
            $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="profile-picture-friends"><img id="profile_picture" src="../uploads/profile'.$id.'.png" class="" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
        }
        // stupid meticulous GOTTA MAKE A WHOLE NEW HANDLER .jpeg file check
        elseif (is_file('../uploads/profile' . $id . '.jpeg') === true)

        {
            $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="profile-picture-friends-jpeg"><img id="profile_picture" src="../uploads/profile'.$id.'.jpeg" class="" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
        }
        // doesn't exist so default image
        else
        {
            $output .= '<td><nobr><a href="profiles/' . $row['username'] . '.php"><div id="profile_picture_container" class="p-0 profile-picture-default"><img id="profile_picture" src="../images/defaultimage.jpg" class="p-0" height="24"/></div>' . $row['username'] . '</a></nobr></td>';
        }
        $output .= '
      <td id="status" class="align-middle">' . $status . '</td>
      <td class="pull-right align-middle">

        <button id="chat_start_button" value="' . $row['username'] . '" name="submit_chat" class="chat_start_button" onclick="chatWindow(\'' . $row['username'] . '\',\'' . $row['id'] . '\'), updateScroll()" type="submit" data-touserid="' . $row['id'] . '" data-tousername="' . $row['username'] . '"style="float:left;"><i class="fa fa-comment-alt fa-lg" aria-hidden="true" style="float: left;color:#66CA69"></i>
        </button>
        <input type="hidden" name="chat_username" value="' . $row['username'] . '" id="chat_username"/>
        <input type="hidden" name="chat_id" value="' . $row['id'] . '" id="chat_username"/>
        <input type="hidden" name="session_username" value="' . $_SESSION['username'] . '" id="chat_username"/>
        <input type="hidden" name="session_id" value="' . $_SESSION['userid'] . '" id="chat_id"/>

      </td>
      </tr>';



  }

}

}
//}
$output .= '</table></div>';


//} //entire operation + prereq query
echo $output;
?>
