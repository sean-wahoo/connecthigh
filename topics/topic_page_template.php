<?php
session_start();
include '../includes/dbh.inc.php';

if (!isset($_SESSION['username']))
{
    header("Location: login.php?nosession");
    die("Redirecting to login.php");
}

include '../friends/fetch_last_activity.inc.php';

if (isset($_GET['logout']))
{
    setcookie("unm", "", $unsethour, "/");
    setcookie("pwd", "", $unsethour, "/");
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}

$sessid = $_SESSION['id'];
$username = $_SESSION['username'];


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

// if a .jpg file
if (is_file('../uploads/profile' . $sessid . '.jpg') === true)
{
    $profileImg = '<div id="profile_picture_container" class="navbar-collapse profile-picture-index">
                     <img id="profile_picture" src="../uploads/profile'.$sessid.'.jpg" class="" height="44"/>
                   </div>';
}
// if a .png file
elseif (is_file('../uploads/profile' . $sessid . '.png') === true)

{
    $profileImg .= '<div id="profile_picture_container" class="navbar-collapse profile-picture-index">
                     <img id="profile_picture" src="../uploads/profile'.$sessid.'.png" class="" height="44"/>
                   </div>';
}
// doesn't exist so default image
else
{
    $profileImg .= '<div id="profile_picture_container" class="navbar-collapse profile-picture-default-index">
                     <img id="profile_picture" src="../images/defaultimage.jpg" class="" height="44"/>
                    </div>';
}



 ?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <link href="../index.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/eb9214e190.js" crossorigin="anonymous"></script>
  <script src="../js/search.js"></script>
  <script src="../js/forumTabShift.js"></script>
</head>
<body>
  <nav id="top_navbar" class="navbar navbar-light green-gradient position-sticky">
    <a href="../index.php" class="navbar-brand">
      <img src="../images/white_logo_transparent_background.png" id="navbar_logo" height="42"/>
    </a>
      <form class="form-inline float-right w-50 my-auto" id="search_form">
        <input class="form-control w-100" id="navbar_search" type="search" placeholder="Search" name="bigSearch">
      </form>
      <form class="form-inline float-left w-75" id="search_form_small">
        <input class="form-control w-100" id="navbar_search" type="search" placeholder="Search" name="bigSearch">
      </form>
    <div id="profile_picture_navbar" class="position-absolute dropdown">
      <button class="btn nav-link dropdown-toggle" type="button" id="profile_picture_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $profileImg; ?>
      </button>
      <div class="dropdown-menu dropdown-menu-right animate slideIn" id="profile_picture_dropdown" aria-labelledby="profile_picture_dropdown">
        <a href="../profiles/<?php echo $username;?>.php" class="dropdown-item">My Profile</a>
        <hr>
        <a href="../notifications/notifications.php" class="dropdown-item">Notifications</a>
        <hr>
        <a href="../friends/friends.php" class="dropdown-item">My Friends</a>
        <hr>
        <a href="../index.php?logout='1'" class="dropdown-item text-danger">Logout</a>
        <hr>
      </div>
    </div>
    <div id="nav_dropdown_small" class="position-absolute dropdown">
      <button class="btn nav-link dropdown-toggle" type="button" id="profile_dropdown_small" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-caret-down fa-2x text-light navbar-collapse"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right animate slideIn" id="profile_dropdown_small" aria-labelledby="profile_dropdown_small">
        <a href="../index.php" class="dropdown-item">Home</a>
        <hr>
        <a href="../profiles/profile.php" class="dropdown-item">My Profile</a>
        <hr>
        <a href="../notifications/notifications.php" class="dropdown-item">Notifications</a>
        <hr>
        <a href="../friends/friends.php" class="dropdown-item">My Friends</a>
        <hr>
        <a href="../index.php?logout='1'" class="dropdown-item text-danger">Logout</a>
        <hr>
      </div>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div id="main_content_block" class="col row my-4 mx-2 w-auto h-auto">
        <div id="topic_area" class="col-md-12">
          <div id="topic_headers" class="m-1">
            <p class="text-secondary mb-0 mr-4 d-inline"><small>posted by bigtestman 3 hours ago</small></p>
            <p class="text-secondary d-inline"><small><nobr>Help &middot; English</nobr></small></p>
          </div>
          <div id="topic_description">
            <h1 class="h-auto w-auto mx-3">super rad post</h1>
            <p class="mx-3">my super cool description for my awfully rad post</p>
          </div>
          <div id="topic_post_section" class="col-md-12">
            <form id="topic_post_form" method="post" action="../includes/post_comment.inc.php" class="form-inline p-0 col-md-12">
              <div class="input-group col-md-12 p-0">
                <input type="text" name="post_content" id="comment_post" class="form-control w-75 p-0" required />
                <div class="input-group-append">
                  <button type="submit" class="btn btn-success" name="post_submit">Post</button>
                </div>
                <input type="hidden" name="post_topic_id" value="<?php echo $topic_id; ?>" />
                <input type="hidden" name="poster_id" value="<?php echo $id; ?>" />
              </div>
            </form>
          </div>
        </div>
      </div>
        <!-- other area for user links and stuff -->
      <div class="col-md-3 col-xs-4 my-4 mx-2 w-auto h-100" id="userLinks">
        <ul class="mt-3 pl-0 align-center">
          <li>
            <a href="../index.php">
              <i class="fas fa-home fa-lg"></i>
              <h4 id="homeLink" class="ml-2">Home</h4>
            </a>
          </li>
          <br />
          <li>
            <a href="#subjects">
              <i class="fas fa-school fa-lg"></i>
              <h4 id="subjectLink" class="ml-2">Subjects</h4>
            </a>
          </li>
          <br />
          <li>
            <a href="#forums">
              <i class="fas fa-align-justify fa-lg"></i>
              <h4 id="forumLink" class="ml-2">Forums</h4>
            </a>
          </li>
          <br />
          <li>
            <a href="post.php">
              <i class="fas fa-edit fa-lg"></i>
              <h4 id="postLink" class="ml-2">Post</h4>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>
