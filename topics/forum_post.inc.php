<?php
include '../dbh.inc.php';
if(isset($_POST['postSubmit'])){
  if($_POST['typePost'] !== 'Discussion' && $_POST['typePost'] !== 'Help' && $_POST['typePost'] !== 'Other'){
    header("Location: ../index.php?err=notrealtype");
  }else{
  //  UPLOADING TO TOPICS TABLE

  $pre_cat_name = $_POST['catType'];

  $_SESSION['cat_name'] = $pre_cat_name;

  $sqlpre = "SELECT cat_id FROM categories WHERE cat_name = '$pre_cat_name'";
  $querypre = mysqli_query($conn, $sqlpre);
  $cat_id = mysqli_fetch_assoc($querypre)['cat_id'];



    $id = $_POST['sessionId'];
    $catType = $_POST['catType'];
    $postContent = mysqli_real_escape_string($conn, $_POST['postData']);
    $postTopic = mysqli_real_escape_string($conn, $_POST['postTopic']);
    $postType = mysqli_real_escape_string($conn, $_POST['typePost']);
    $cat_id = mysqli_real_escape_string($conn, $cat_id);
    // GETTING CATEGORY VARIABLE

    $sql = "INSERT INTO topics(topic_subject, topic_description, topic_date, topic_cat, topic_by, topic_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
      header("Location: ../index.php?err=stmtfail");
    }else{
      $now = date("Y-m-d H:i:s");
      mysqli_stmt_bind_param($stmt, "ssssss", $postTopic, $postContent, $now, $cat_id, $id, $postType);
      mysqli_stmt_execute($stmt);
      session_start();
      $topic_id = mysqli_insert_id($conn);

      $_SESSION['cat_name'] = $pre_cat_name;
      $_SESSION['topic_id'] = $topic_id;
      $_SESSION['topic_create_id'] = $id;
      $_SESSION['topic_name'] = $postTopic;
      $_SESSION['time'] = $now;
      $_SESSION['category_id'] = $cat_id;
      $_SESSION['post_type'] = $postType;
      $_SESSION['post_content'] = $postContent;
      $_SESSION['topic_type'] = $postType;
      $sql = "SELECT username FROM users WHERE id = '$id'";
      $query = mysqli_query($conn, $sql);
      $username = mysqli_fetch_row($query)[0];
      $_SESSION['username'] = $username;
      header("Location: topic_page_create.php");
    }
  }
}






 ?>
