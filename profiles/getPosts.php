<?php

include '../includes/dbh.inc.php';

function elapsed_time($datetime, $full = false){
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);
  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;
  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );
  foreach ($string as $k => &$v){
    if ($diff->$k){
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    }else {
      unset($string[$k]);
    }
  }
  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}

$id = $_POST['id'];

$sql = "SELECT topic_id, topic_subject, topic_date, topic_cat, topic_type FROM topics WHERE topic_by = '$id' ORDER BY topic_date DESC";
$query = mysqli_query($conn, $sql);
while($topics = mysqli_fetch_assoc($query)){
  $topic_id = $topics['topic_id'];
  $topic_subject = $topics['topic_subject'];
  $topic_date = $topics['topic_date'];
  $topic_cat = $topics['topic_cat'];
  $topic_type = $topics['topic_type'];
  $topic_date_ago = elapsed_time($topic_date);
  $sqlIDTONAME = "SELECT username FROM users WHERE id = '$id'";
  $queryIDTONAME = mysqli_query($conn, $sqlIDTONAME);
  $topic_by_name = mysqli_fetch_row($queryIDTONAME)[0];

  $profilePosts = "<li class='mx-4'>
                        <a href='../topics/".str_replace(' ', '', $topic_subject)."-".$topic_id.".php' style='color:black;'>
                          <h4 class='d-inline'>".$topic_subject."</h4>
                          <h5 class='d-inline'>&middot;</h5>";
    // showing up differently depending on what kind of post it is

  if($topic_type == 'Help'){
    $profilePosts .= "<h5 class='d-inline text-danger' id='post_type'> ".$topic_type."</h5>";
  }
  elseif($topic_type == 'Discussion'){
    $profilePosts .= "<h5 class='d-inline text-success' id='post_type'> ".$topic_type."</h5>";
  }
  elseif($topic_type == 'Other'){
    $profilePosts .= "<h5 class='d-inline text-dark' id='post_type'> ".$topic_type."</h5>";
  }
  $sqlCAT = "SELECT cat_name FROM categories WHERE cat_id = '$topic_cat'";
  $queryCAT = mysqli_query($conn, $sqlCAT);
  $topic_cat_name = mysqli_fetch_assoc($queryCAT)['cat_name'];
  $profilePosts .= "<h5 class='d-inline text-secondary'> &middot; ".$topic_cat_name."</h5>";
  $profilePosts .= "<p class='text-secondary mx-4'>".$topic_by_name." &middot; ".$topic_date_ago."</p>";
  $profilePosts .= "</a></li><hr>";

  echo $profilePosts;
}





 ?>
