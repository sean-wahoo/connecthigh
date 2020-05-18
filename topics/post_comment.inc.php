<?php
include '../includes/dbh.inc.php';
if(isset($_POST['post_submit'])){
  if(!empty($_POST['post_content'])){
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
    $post_content = str_replace('\r\n','<br>', $post_content);
    $post_content = str_replace("\'", '\'', $post_content);
    $now = date("Y-m-d H:i:s");
    $topic_id = $_POST['post_topic_id'];
    $poster_id = $_POST['poster_id'];

    $sql = "INSERT INTO posts (post_content, post_date, post_topic, post_by) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      echo 'error adding your comment!';
    }
    else{
      mysqli_stmt_bind_param($stmt, "ssss", $post_content, $now, $topic_id, $poster_id);
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
      exit();

    }
  }
}
elseif(isset($_POST['post_reply_submit'])){
  if(!empty($_POST['post_content'])){
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
    $post_content = str_replace('\r\n','<br>', $post_content);
    $post_content = str_replace("\'", '\'', $post_content);
    $now = date("Y-m-d H:i:s");
    $topic_id = $_POST['post_topic_id'];
    $poster_id = $_POST['poster_id'];
    $post_reply_to = $_POST['post_reply_to'];
    $user_reply_to = $_POST['user_reply_to'];

    $sql = "INSERT INTO posts (post_content, post_date, post_topic, post_by, post_reply_to, user_reply_to) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
      echo 'error adding your comment!';
    }
    else{
      mysqli_stmt_bind_param($stmt, "ssssss", $post_content, $now, $topic_id, $poster_id, $post_reply_to, $user_reply_to);
      mysqli_stmt_execute($stmt);
      $sql = "SELECT topic_subject FROM topics WHERE topic_id = '$topic_id'";
      $query = mysqli_query($conn, $sql);
      $topic_link = mysqli_fetch_row($query)[0];
      $topic_link = str_replace(' ', '', $topic_link);
      $topic_link = str_replace('?', '', $topic_link);
      $topic_link = str_replace('.', '', $topic_link);
      $topic_link = str_replace("\'", '\'', $topic_link);
      $topic_link = $topic_link."-".$topic_id;

      $sqlFINDUSER = "SELECT post_by FROM posts WHERE post_id = '$post_reply_to'";
      $queryFINDUSER = mysqli_query($conn, $sqlFINDUSER);
      $post_by = mysqli_fetch_assoc($queryFINDUSER)['post_by'];


      $about = '3';
      $description = 'got_reply';
      $sqlNOTIF = "INSERT INTO notifications (user_id, from_id, replied_to, about, description) VALUES (?,?,?,?,?)";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sqlNOTIF)){
        header("Location: ".$topic_link.".php?err=noidea");
      }
      else{
        mysqli_stmt_bind_param($stmt, "iiiis", $post_by, $poster_id, $post_reply_to, $about, $description);
        mysqli_stmt_execute($stmt);
      }

      header("Location: ".$topic_link.".php");
      exit();

    }
  }
  echo 'um';
}




 ?>
