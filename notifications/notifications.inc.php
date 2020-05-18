<?php

$log_username = $_POST['username'];
$log_id = $_POST['id'];

include '../includes/dbh.inc.php';

$sql = "SELECT * FROM notifications WHERE user_id = '$log_id' ORDER BY date_time DESC";
$query = mysqli_query($conn, $sql);
if(mysqli_num_rows($query) < '1'){
  $notifications_list = '...nothing yet!';
}else {
  while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    if($row['about'] == '1'){
      $user_id = $row['user_id'];
      $from_id = $row['from_id'];

      $sqlNameUser1 = "SELECT username FROM users WHERE id = '$user_id'";
      $queryNameUser1= mysqli_query($conn, $sqlNameUser1);
      $user_username = mysqli_fetch_assoc($queryNameUser1)['username'];
      $sqlNameFrom = "SELECT username FROM users WHERE id = '$from_id'";
      $queryNameFrom = mysqli_query($conn, $sqlNameFrom);
      $from_username = mysqli_fetch_assoc($queryNameFrom)['username'];

      $sql = "SELECT id, user1, user2 FROM friends WHERE user1 = '$from_username' AND user2 = '$user_username' AND accepted = '0'";
      $query = mysqli_query($conn, $sql);
      $request = mysqli_fetch_assoc($query);
      if(is_file('../uploads/profile'.$from_id.'.jpg') === TRUE)  {
        $imgoutput = '<div class="d-inline-block profile-picture-notifications"><img height="36" src="../uploads/profile'.$from_id.'.jpg"></div>';
      }elseif(is_file('http://localhost/uploads/profile'.$from_id.'.png') === TRUE){
        $imgoutput = '<div class="d-inline-block profile-picture-notifications"><img height="36" src="../uploads/profile'.$from_id.'.png"></div>';
      }elseif(is_file('http://localhost/uploads/profile'.$from_id.'.jpeg') === TRUE){
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpeg"></div>';
      }
      else{
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-default"><img height="36" src="../uploads/defaultimage.jpg"></div>';
      }
      $notifications_list .= '<div id="friendreq_'.$request['id'].'" class="d-inline mr-1 mt-2 friendrequests">';
      $notifications_list .= '<a href="../profiles/'.$request['user1'].'.php">'.$imgoutput.'</a>';
      $notifications_list .= '<div class="d-inline user_info" id="user_info_'.$reqid.'"><p class="d-inline">'.$request['user1'].'<a href="../profiles/'.$request['user1'].'.php"></a> wants to be friends!</p>';
      $notifications_list .= '<button class="friend_buttons friend_buttons_accept ml-2" id="user_info_'.$request['id'].'"onclick="friendReqHandler(\'accept\',\''.$request['id'].'\',\''.$request['user1'].'\',\'user_info_'.$request['id'].'\')">Accept</button> or ';
      $notifications_list .= '<button class="friend_buttons friend_buttons_ignore" id="user_info_'.$request['id'].'"onclick="friendReqHandler(\'reject\',\''.$request['id'].'\',\''.$request['user1'].'\',\'user_info_'.$request['id'].'\')">Ignore</button>';
      $notifications_list .= '</div></div><hr>';
      $notifications_list .= '<br />';
    }
    if($row['about'] == '2'){
      $user_id = $row['user_id'];
      $from_id = $row['from_id'];

      if($user_id = $_POST['id']){

      $sqlNameUser1 = "SELECT username FROM users WHERE id = '$user_id'";
      $queryNameUser1= mysqli_query($conn, $sqlNameUser1);
      $user_username = mysqli_fetch_assoc($queryNameUser1)['username'];
      $sqlNameFrom = "SELECT username FROM users WHERE id = '$from_id'";
      $queryNameFrom = mysqli_query($conn, $sqlNameFrom);
      $from_username = mysqli_fetch_assoc($queryNameFrom)['username'];

      if(is_file('../uploads/profile'.$from_id.'.jpg') === TRUE)  {
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpg"></div>';
      }elseif(is_file('../uploads/profile'.$from_id.'.png') === TRUE){
        $imgoutput = '<div class="d-inline-block profile-picture-notifications"><img height="36" src="../uploads/profile'.$from_id.'.png"></div>';
      }elseif(is_file('../uploads/profile'.$from_id.'.jpeg') === TRUE){
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpeg"></div>';
      }
      else{
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-default"><img height="36" src="../uploads/defaultimage.jpg"></div>';
      }

      $notifications_list .= '<div class="mr-1 mt-2">';
      $notifications_list .= '<a href="../profiles/'.$from_username.'.php">'.$imgoutput.'<p class="d-inline">'.$from_username.'</a> sent you a <a href="../friends/friends.php?quickchat='.$from_username.'">message!</button></p>';
      $notifications_list .= '</div><hr>';



    }
    }
    if($row['about'] == '3'){
      $user_id = $row['user_id'];
      $from_id = $row['from_id'];

      if($user_id = $row['user_id']){
        $sqlNameUser1 = "SELECT username FROM users WHERE id = '$user_id'";
        $queryNameUser1= mysqli_query($conn, $sqlNameUser1);
        $user_username = mysqli_fetch_assoc($queryNameUser1)['username'];
        $sqlNameFrom = "SELECT username FROM users WHERE id = '$from_id'";
        $queryNameFrom = mysqli_query($conn, $sqlNameFrom);
        $from_username = mysqli_fetch_assoc($queryNameFrom)['username'];

        if(is_file('../uploads/profile'.$from_id.'.jpg') === TRUE)  {
          $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpg"></div>';
        }elseif(is_file('../uploads/profile'.$from_id.'.png') === TRUE){
          $imgoutput = '<div class="d-inline-block profile-picture-notifications"><img height="36" src="../uploads/profile'.$from_id.'.png"></div>';
        }elseif(is_file('../uploads/profile'.$from_id.'.jpeg') === TRUE){
          $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpeg"></div>';
        }
        else{
          $imgoutput = '<div class="d-inline-block profile-picture-notifications-default"><img height="36" src="../uploads/defaultimage.jpg"></div>';
        }
        $post_reply_to = $row['replied_to'];
        $sqlFindPost = "SELECT post_id, post_topic FROM posts WHERE post_id = '$post_reply_to'";
        $queryFindPost = mysqli_query($conn, $sqlFindPost);
        while($post = mysqli_fetch_assoc($queryFindPost)){
        $post_id = $post['post_id'];
        $post_topic = $post['post_topic'];
        $sqlTopicName = "SELECT topic_subject FROM topics WHERE topic_id = '$post_topic'";
        $queryTopicName = mysqli_query($conn, $sqlTopicName);
        $topic_name = mysqli_fetch_assoc($queryTopicName)['topic_subject'];
        $topic_link_name = str_replace(' ', '', $topic_name);
        $topic_link_name = str_replace('?', '', $topic_link_name);
        $topic_link_name = str_replace('.', '', $topic_link_name);
        $topic_link_name = str_replace("\'", '\'', $topic_link_name);

        $notifications_list .= '<div class="mr-1 mt-2">';
        $notifications_list .= '<a href="../profiles/'.$from_username.'.php">'.$imgoutput.'<p style="display:inline;">'.$from_username.'</a> replied to your <a href="../topics/'.$topic_link_name.'-'.$post_topic.'.php">post!</a></p>';
        $notifications_list .= '</div><hr>';
      }
      }
    }
    if($row['about'] == '4'){
      $user_id = $row['user_id'];
      $from_id = $row['from_id'];

      $sqlNameUser1 = "SELECT username FROM users WHERE id = '$user_id'";
      $queryNameUser1= mysqli_query($conn, $sqlNameUser1);
      $user_username = mysqli_fetch_assoc($queryNameUser1)['username'];
      $sqlNameFrom = "SELECT username FROM users WHERE id = '$from_id'";
      $queryNameFrom = mysqli_query($conn, $sqlNameFrom);
      $from_username = mysqli_fetch_assoc($queryNameFrom)['username'];

      if(is_file('../uploads/profile'.$from_id.'.jpg') === TRUE)  {
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpg"></div>';
      }elseif(is_file('../uploads/profile'.$from_id.'.png') === TRUE){
        $imgoutput = '<div class="d-inline-block profile-picture-notifications"><img height="36" src="../uploads/profile'.$from_id.'.png"></div>';
      }elseif(is_file('../uploads/profile'.$from_id.'.jpeg') === TRUE){
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpeg"></div>';
      }
      else{
        $imgoutput = '<div class="d-inline-block profile-picture-notifications-default"><img height="36" src="../uploads/defaultimage.jpg"></div>';
      }
      $post_reply_to = $row['replied_to'];
      $sqlFindPost = "SELECT post_id, post_topic FROM posts WHERE post_id = '$post_reply_to'";
      $queryFindPost = mysqli_query($conn, $sqlFindPost);
      while($post = mysqli_fetch_assoc($queryFindPost)){
        $post_id = $post['post_id'];
        $post_topic = $post['post_topic'];
        $sqlTopicName = "SELECT topic_subject FROM topics WHERE topic_id = '$post_topic'";
        $queryTopicName = mysqli_query($conn, $sqlTopicName);
        $topic_name = mysqli_fetch_assoc($queryTopicName)['topic_subject'];


        $topic_link_name = str_replace(' ', '', $topic_name);
        $topic_link_name = str_replace('?', '', $topic_link_name);
        $topic_link_name = str_replace('.', '', $topic_link_name);
        $topic_link_name = str_replace("\'", '\'', $topic_link_name);

        $notifications_list .= '<div class="mr-1 mt-2">';
        $notifications_list .= '<a href="../profiles/'.$from_username.'.php">'.$imgoutput.'
                                <p class="d-inline">'.$from_username.'</a> liked your <a href="../topics/'.$topic_link_name.'-'.$post_topic.'.php">post!</a></p>';
        $notifications_list .= '<hr>';

      }


  }
  if($row['about'] == '5'){
    $user_id = $row['user_id'];
    $from_id = $row['from_id'];

    $sqlNameUser1 = "SELECT username FROM users WHERE id = '$user_id'";
    $queryNameUser1 = mysqli_query($conn, $sqlNameUser1);
    $user_username = mysqli_fetch_assoc($queryNameUser1)['username'];
    $sqlNameFrom = "SELECT username FROM users WHERE id = '$from_id'";
    $queryNameFrom = mysqli_query($conn, $sqlNameFrom);
    $from_username = mysqli_fetch_assoc($queryNameFrom)['username'];

    if(is_file('../uploads/profile'.$from_id.'.jpg') === TRUE)  {
      $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.jpg"></div>';
    }elseif(is_file('../uploads/profile'.$from_id.'.png') === TRUE){
      $imgoutput = '<div class="d-inline-block profile-picture-notifications"><img height="36" src="../uploads/profile'.$from_id.'.png"></div>';
    }elseif(is_file('../uploads/profile'.$from_id.'.jpeg') === TRUE){
      $imgoutput = '<div class="d-inline-block profile-picture-notifications-jpeg"><img height="36" src="../uploads/profile'.$from_id.'.png"></div>';
    }
    else{
      $imgoutput = '<div class="d-inline-block profile-picture-notifications-default"><img height="36" src="../uploads/defaultimage.jpg"></div>';
    }
    $topic_reply_to = $row['replied_to'];
    $sqlFindTopic = "SELECT topic_id, topic_subject, topic_cat FROM topics WHERE topic_id = '$topic_reply_to'";
    $queryFindTopic = mysqli_query($conn, $sqlFindTopic);
    while($topic = mysqli_fetch_assoc($queryFindTopic)){
      $topic_subject = $topic['topic_subject'];
      $topic_id = $topic['topic_id'];
      $topic_cat = $topic['topic_cat'];
      $sqlCAT = "SELECT cat_name FROM categories WHERE cat_id = '$topic_cat'";
      $queryCAT = mysqli_query($conn, $sqlCAT);
      $cat_name = mysqli_fetch_assoc($queryCAT)['cat_name'];
      $topic_link_name = str_replace(' ', '', $topic_subject);
      $topic_link_name = str_replace('?', '', $topic_link_name);
      $topic_link_name = str_replace('.', '', $topic_link_name);
      $topic_link_name = str_replace("\'", '\'', $topic_link_name);
    }

    $notifications_list .= '<div class="mr-1 mt-2">';
    $notifications_list .= '<a href="../profiles/'.$from_username.'.php">'.$imgoutput.'<p style="display:inline;">'.$from_username.'</a> needs help with <a href="../topics/'.$topic_link_name.'-'.$topic_id.'.php">'.$cat_name.'!</a></p>';
    $notifications_list .= '</div><hr>';
  }

}



}
echo $notifications_list;
  ?>
