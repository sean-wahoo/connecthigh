<?php
include '../includes/dbh.inc.php';


if(isset($_POST['submit_like_topic'])){

  $topic_id = $_POST['topic_id'];
  $userid = $_POST['session_id'];
  $sqlCheckLikes = "SELECT topic_liked_by FROM likes WHERE topic_id = '$topic_id'";
  $queryCheckLikes = mysqli_query($conn, $sqlCheckLikes);

    if($checkLikes == NULL || !in_array($userid, $checkLikes)){
      $sql = "INSERT INTO likes (topic_id, topic_liked_by) VALUES (?, ?)";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sql)){
        echo 'wuh oh!';
      }
      else{
        mysqli_stmt_bind_param($stmt, "ii", $topic_id, $userid);
        mysqli_stmt_execute($stmt);
        $sql = "SELECT topic_subject FROM topics WHERE topic_id = '$topic_id'";
        $query = mysqli_query($conn, $sql);
        $topic_link = mysqli_fetch_row($query)[0];
        $topic_link = str_replace(' ', '', $topic_link);
        $topic_link = str_replace('?', '', $topic_link);
        $topic_link = str_replace('.', '', $topic_link);
        $topic_link = str_replace("\'", '\'', $topic_link);
        $topic_link = $topic_link."-".$topic_id;
        header("Location: ".$topic_link.".php");

      }
    }
    while($checkLikes = mysqli_fetch_assoc($queryCheckLikes)){

  if(in_array($userid, $checkLikes)){
    $sqlUnlike = "DELETE FROM likes WHERE topic_id = '$topic_id' AND topic_liked_by = '$userid'";
    $queryUnlike = mysqli_query($conn, $sqlUnlike);
    $sql = "SELECT topic_subject FROM topics WHERE topic_id = '$topic_id'";
    $query = mysqli_query($conn, $sql);
    $topic_link = mysqli_fetch_row($query)[0];
    $topic_link = str_replace(' ', '', $topic_link);
    $topic_link = str_replace('?', '', $topic_link);
    $topic_link = str_replace('.', '', $topic_link);
    $topic_link = str_replace("\'", '\'', $topic_link);
    $topic_link = $topic_link."-".$topic_id;
    header("Location: ".$topic_link.".php");
  }
  var_dump($checkLikes);
}
}
elseif(isset($_POST['submit_like_post'])){
  $topic_id = $_POST['topic_id'];
  $post_id = $_POST['post_id'];
  $userid = $_POST['session_id'];
  $sqlCheckLikes = "SELECT post_liked_by FROM likes WHERE post_id = '$post_id'";
  $queryCheckLikes = mysqli_query($conn, $sqlCheckLikes);
  $checkLikes = mysqli_fetch_assoc($queryCheckLikes);
  if($checkLikes == NULL || !in_array($userid, $checkLikes)){
    $sql = "INSERT INTO likes (post_id, post_liked_by) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      echo 'wuh oh!';
    }
    else{
      mysqli_stmt_bind_param($stmt, "ii", $post_id, $userid);
      mysqli_stmt_execute($stmt);

      $about = '4';
      $description = 'got_like';

      $sqlPOSTBY = "SELECT post_by FROM posts WHERE post_id = '$post_id'";
      $queryPOSTBY = mysqli_query($conn, $sqlPOSTBY);
      $post_by = mysqli_fetch_assoc($queryPOSTBY)['post_by'];

      $sqlNOTIF = "INSERT INTO notifications (user_id, from_id, replied_to, about, description) VALUES (?,?,?,?,?)";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sqlNOTIF)){
        echo 'bruh';
      }
      else{
        mysqli_stmt_bind_param($stmt, "iiiis", $post_by, $userid, $post_id, $about, $description);
        mysqli_stmt_execute($stmt);
      }

      $sql = "SELECT topic_subject FROM topics WHERE topic_id = '$topic_id'";
      $query = mysqli_query($conn, $sql);
      $topic_link = mysqli_fetch_row($query)[0];
      $topic_link = str_replace(' ', '', $topic_link);
      $topic_link = str_replace('?', '', $topic_link);
      $topic_link = str_replace('.', '', $topic_link);
      $topic_link = str_replace("\'", '\'', $topic_link);
      $topic_link = $topic_link."-".$topic_id;
      header("Location: ".$topic_link.".php");
    }
  }
  elseif(in_array($userid, $checkLikes)){
    $sqlUnlike = "DELETE FROM likes WHERE post_id = '$post_id' AND post_liked_by = '$userid'";
    $queryUnlike = mysqli_query($conn, $sqlUnlike);
    $sql = "SELECT topic_subject FROM topics WHERE topic_id = '$topic_id'";
    $query = mysqli_query($conn, $sql);
    $topic_link = mysqli_fetch_row($query)[0];
    $topic_link = str_replace(' ', '', $topic_link);
    $topic_link = str_replace('?', '', $topic_link);
    $topic_link = str_replace('.', '', $topic_link);
    $topic_link = str_replace("\'", '\'', $topic_link);
    $topic_link = $topic_link."-".$topic_id;
    header("Location: ".$topic_link.".php");
  }
}
else{
  $topic_id = $_POST['topic_id'];
  $sql = "SELECT COUNT(*) AS topic_likes FROM likes WHERE topic_id = '$topic_id'";
  $query = mysqli_query($conn, $sql);
  $count = mysqli_fetch_assoc($query);
  echo $count['topic_likes'];
}


 ?>
