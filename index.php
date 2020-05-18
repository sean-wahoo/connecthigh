<?php
session_start();

// a big mistake i made when writing the first time was organization. as i
// continued writing, i just wanted it to work and leave it at that. i soon
// started to get really frustrated that the code was super unintuitive and
// hard to follow, so since i have basically a fresh start, i'm gonna do my
// best to separate big php code from big html code from big javascript code
// and stuff like that.



// making sure user is logged in and handling a logout

if (!isset($_SESSION['username']))
{
    header("Location: login.php?nosession");
    die("Redirecting to login.php");
}

include 'includes/dbh.inc.php';
include 'friends/fetch_last_activity.inc.php';

if (isset($_GET['logout']))
{
    setcookie("unm", "", $unsethour, "/");
    setcookie("pwd", "", $unsethour, "/");
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}


// declaring session variables

$sessuser = $_SESSION['username'];
if ($stmtpre = $conn->prepare("SELECT id, username, email, password, bio, location_from, bday, friend_count, notescheck FROM users WHERE username='$sessuser'"))
{
    $stmtpre->execute();
    $resultpre = mysqli_stmt_get_result($stmtpre);
    if ($rowpre = mysqli_fetch_assoc($resultpre))
    {
        mysqli_stmt_bind_result($stmtpre, $rowpre['id'], $rowpre['username'], $rowpre['email'], $rowpre['password'], $rowpre['bio'], $rowpre['location_from'], $rowpre['bday'], $rowpre['friend_count'], $rowpre['notescheck']);
        $_SESSION['id'] = $rowpre['id'];
        $_SESSION['email'] = $rowpre['email'];
        $_SESSION['password'] = $rowpre['password'];
        $_SESSION['bio'] = $rowpre['bio'];
        $_SESSION['loc'] = $rowpre['location_from'];
        $_SESSION['bday'] = $rowpre['bday'];
        $stmtpre->close();

    }
}

// making life easier
$username = $_SESSION['username'];
$sessid = $_SESSION['id'];

// probably removing the big modal thing to choose a subject because it's
// super clunky and not really necessary


// figuring out the users profile picture

// if a .jpg file
if (is_file('uploads/profile' . $sessid . '.jpg') === true)
{
    $profileImg = '<div id="profile_picture_container" class="navbar-collapse profile-picture-index-jpeg">
                     <img id="profile_picture" src="uploads/profile'.$sessid.'.jpg" class="" height="44"/>
                   </div>';
}
// if a .png file
elseif (is_file('uploads/profile' . $sessid . '.png') === true)

{
    $profileImg .= '<div id="profile_picture_container" class="navbar-collapse profile-picture-index">
                     <img id="profile_picture" src="uploads/profile'.$sessid.'.png" class="" height="44"/>
                   </div>';
}
elseif (is_file('uploads/profile' . $sessid . '.jpeg') === true)

{
    $profileImg .= '<div id="profile_picture_container" class="navbar-collapse profile-picture-index-jpeg">
                     <img id="profile_picture" src="uploads/profile'.$sessid.'.jpeg" class="" height="44"/>
                   </div>';
}
// doesn't exist so default image
else
{
    $profileImg .= '<div id="profile_picture_container" class="navbar-collapse profile-picture-default-index">
                     <img id="profile_picture" src="images/defaultimage.jpg" class="" height="44"/>
                    </div>';
}


// dump little php elasped time function i.e. "x hours ago"
// i don't know where else to put it to be honest

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



// now here comes a huge amount of code to display the
// posts for each forum subject. i feel like i should put
// this in a separate file but i don't want to compromise
// reliabilty and end user experience


// English part

$sqlenglishposts = "SELECT topic_id, topic_subject, topic_date, topic_by, topic_type FROM topics WHERE topic_cat = '1'";
$queryenglishposts = mysqli_query($conn, $sqlenglishposts);
if(mysqli_num_rows($queryenglishposts) == 0){
    $englishForumData = "<p style='color:lightgray;font-style:italic'>Nobody has posted anything here yet. Be the first!</p>";

}
else{
  $englishForumData = "<h1 style='font-size:22px;'>Posts:</h1>";
  while($forumEnglish = mysqli_fetch_assoc($queryenglishposts)){

    // bunch of super tedious sql queries to gather the right data

    $topicIdEnglish = $forumEnglish['topic_id'];
    $topicNameEnglish = $forumEnglish['topic_subject'];
    $topicLinkEnglish = str_replace(' ', '', $topicNameEnglish);
    $topicLinkEnglish = str_replace('?', '', $topicLinkEnglish);
    $topicLinkEnglish = str_replace('.', '', $topicLinkEnglish);
    $topicLinkEnglish = str_replace("\'", '\'', $topicLinkEnglish);
    $topicDateEnglish = $forumEnglish['topic_date'];
    $topicDateAgoEnglish = elapsed_time($topicDateEnglish);
    $topicByIdEnglish = $forumEnglish['topic_by'];
    $sqlIDTONAME = "SELECT username FROM users WHERE id = '$topicByIdEnglish'";
    $queryIDTONAME = mysqli_query($conn, $sqlIDTONAME);
    $topicByEnglish = mysqli_fetch_row($queryIDTONAME);
    $topicTypeEnglish = $forumEnglish['topic_type'];
    $topicToPostssql = "SELECT post_content FROM posts WHERE post_topic = '$topicIdEnglish' ORDER BY post_content DESC LIMIT 1";
    $topicToPostsquery = mysqli_query($conn, $topicToPostssql);
    $topicToPost = mysqli_fetch_row($topicToPostsquery);
    $englishForumData .= "<li>
                            <a href='topics/".$topicLinkEnglish."-".$topicIdEnglish.".php' style='color:black;'>
                              <h4 class='d-inline'>".$topicNameEnglish."</h4>
                              <h5 class='d-inline'>&middot;</h5>";
      // showing up differently depending on what kind of post it is

    if($topicTypeEnglish == 'Help'){
      $englishForumData .= "<h5 class='d-inline text-danger' id='post_type'> ".$topicTypeEnglish."</h5>";
    }
    elseif($topicTypeEnglish == 'Discussion'){
      $englishForumData .= "<h5 class='d-inline text-success' id='post_type'> ".$topicTypeEnglish."</h5>";
    }
    elseif($topicTypeEnglish == 'Other'){
      $englishForumData .= "<h5 class='d-inline text-dark' id='post_type'> ".$topicTypeEnglish."</h5>";
    }
    $englishForumData .= "<p class='text-secondary'>".$topicByEnglish[0]." &middot; ".$topicDateAgoEnglish."</p>";
    $englishForumData .= "</a></li><hr>";

  }
}


// same thing but for math

$sqlmathposts = "SELECT topic_id, topic_subject, topic_date, topic_by, topic_type FROM topics WHERE topic_cat = '2'";
$querymathposts = mysqli_query($conn, $sqlmathposts);
if(mysqli_num_rows($querymathposts) == 0){
    $mathForumData = "<p style='color:lightgray;font-style:italic'>Nobody has posted anything here yet. Be the first!</p>";

}else{
  $mathForumData = "<h1 style='font-size:22px;'>Posts:</h1>";
  while($forumMath = mysqli_fetch_assoc($querymathposts)){
    $topicIdMath = $forumMath['topic_id'];
    $topicNameMath = $forumMath['topic_subject'];
    $topicLinkMath = str_replace(' ', '', $topicNameMath);
    $topicLinkMath = str_replace('?', '', $topicLinkMath);
    $topicLinkMath = str_replace('.', '', $topicLinkMath);
    $topicLinkMath = str_replace("\'", '\'', $topicLinkMath);
    $topicDateMath = $forumMath['topic_date'];
    $topicDateAgoMath = elapsed_time($topicDateMath);
    $topicByIdMath = $forumMath['topic_by'];
    $sqlIDTONAME = "SELECT username FROM users WHERE id = '$topicByIdMath'";
    $queryIDTONAME = mysqli_query($conn, $sqlIDTONAME);
    $topicByMath = mysqli_fetch_row($queryIDTONAME);
    $topicTypeMath = $forumMath['topic_type'];
    $topicToPostssql = "SELECT post_content FROM posts WHERE post_topic = '$topicIdMath' ORDER BY post_content DESC LIMIT 1";
    $topicToPostsquery = mysqli_query($conn, $topicToPostssql);
    $topicToPost = mysqli_fetch_row($topicToPostsquery);
    $mathForumData .= "<li>
                          <a href='topics/".$topicLinkMath."-".$topicIdMath.".php' style='color:black;'>
                              <h4 class='d-inline'>".$topicNameMath."</h4>
                              <h5 class='d-inline'>&middot;</h5>";
      // showing up differently depending on what kind of post it is

    if($topicTypeMath == 'Help'){
      $mathForumData .= "<h5 class='d-inline text-danger' id='post_type'> ".$topicTypeMath."</h5>";
    }
    elseif($topicTypeMath == 'Discussion'){
      $mathForumData .= "<h5 class='d-inline text-success' id='post_type'> ".$topicTypeMath."</h5>";
    }
    elseif($topicTypeMath == 'Other'){
      $mathForumData .= "<h5 class='d-inline text-dark' id='post_type'> ".$topicTypeMath."</h5>";
    }
    $mathForumData .= "<p class='text-secondary'>".$topicByMath[0]." &middot; ".$topicDateAgoMath."</p>";
    $mathForumData .= "</a></li><hr>";

  }
}

// again but science

$sqlscienceposts = "SELECT topic_id, topic_subject, topic_date, topic_by, topic_type FROM topics WHERE topic_cat = '3'";
$queryscienceposts = mysqli_query($conn, $sqlscienceposts);
if(mysqli_num_rows($queryscienceposts) == 0){
    $scienceForumData = "<p style='color:lightgray;font-style:italic'>Nobody has posted anything here yet. Be the first!</p>";

}
else{
  $scienceForumData = "<h1 style='font-size:22px;'>Posts:</h1>";
  while($forumScience = mysqli_fetch_assoc($queryscienceposts)){
    $topicIdScience = $forumScience['topic_id'];
    $topicNameScience = $forumScience['topic_subject'];
    $topicLinkScience = str_replace(' ', '', $topicNameScience);
    $topicLinkScience = str_replace('?', '', $topicLinkScience);
    $topicLinkScience = str_replace('.', '', $topicLinkScience);
    $topicLinkScience = str_replace("\'", '\'', $topicLinkScience);
    $topicDateScience = $forumScience['topic_date'];
    $topicDateAgoScience = elapsed_time($topicDateScience);
    $topicByIdScience = $forumScience['topic_by'];
    $sqlIDTONAME = "SELECT username FROM users WHERE id = '$topicByIdScience'";
    $queryIDTONAME = mysqli_query($conn, $sqlIDTONAME);
    $topicByScience = mysqli_fetch_row($queryIDTONAME);
    $topicTypeScience = $forumScience['topic_type'];
    $topicToPostssql = "SELECT post_content FROM posts WHERE post_topic = '$topicIdScience' ORDER BY post_content DESC LIMIT 1";
    $topicToPostsquery = mysqli_query($conn, $topicToPostssql);
    $topicToPost = mysqli_fetch_row($topicToPostsquery);
    $scienceForumData .= "<li>
                            <a href='topics/".$topicLinkScience."-".$topicIdScience.".php' style='color:black;'>
                              <h4 class='d-inline'>".$topicNameScience."</h4>
                              <h5 class='d-inline'>&middot;</h5>";
      // showing up differently depending on what kind of post it is

    if($topicTypeScience == 'Help'){
      $scienceForumData .= "<h5 class='d-inline text-danger' id='post_type'> ".$topicTypeScience."</h5>";
    }
    elseif($topicTypeScience == 'Discussion'){
      $scienceForumData .= "<h5 class='d-inline text-success' id='post_type'> ".$topicTypeScience."</h5>";
    }
    elseif($topicTypeScience == 'Other'){
      $scienceForumData .= "<h5 class='d-inline text-dark' id='post_type'> ".$topicTypeScience."</h5>";
    }
    $scienceForumData .= "<p class='text-secondary'>".$topicByScience[0]." &middot; ".$topicDateAgoScience."</p>";
    $scienceForumData .= "</a></li><hr>";

  }
}

// you get the idea; this one's for social studies

$sqlsstudiesposts = "SELECT topic_id, topic_subject, topic_date, topic_by, topic_type FROM topics WHERE topic_cat = '4'";
$querysstudiesposts = mysqli_query($conn, $sqlsstudiesposts);
if(mysqli_num_rows($querysstudiesposts) == 0){
    $sstudiesForumData = "<p style='color:lightgray;font-style:italic'>Nobody has posted anything here yet. Be the first!</p>";

}
else{
  $sstudiesForumData = "<h1 style='font-size:22px;'>Posts:</h1>";
  while($forumSStudies = mysqli_fetch_assoc($querysstudiesposts)){
    $topicIdSStudies = $forumSStudies['topic_id'];
    $topicNameSStudies = $forumSStudies['topic_subject'];
    $topicLinkSStudies = str_replace(' ', '', $topicNameSStudies);
    $topicLinkSStudies = str_replace('?', '', $topicLinkSStudies);
    $topicLinkSStudies = str_replace('.', '', $topicLinkSStudies);
    $topicLinkSStudies = str_replace("\'", '\'', $topicLinkSStudies);
    $topicDateSStudies = $forumSStudies['topic_date'];
    $topicDateAgoSStudies = elapsed_time($topicDateSStudies);
    $topicByIdSStudies = $forumSStudies['topic_by'];
    $sqlIDTONAME = "SELECT username FROM users WHERE id = '$topicByIdSStudies'";
    $queryIDTONAME = mysqli_query($conn, $sqlIDTONAME);
    $topicBySStudies = mysqli_fetch_row($queryIDTONAME);
    $topicTypeSStudies = $forumSStudies['topic_type'];
    $topicToPostssql = "SELECT post_content FROM posts WHERE post_topic = '$topicIdSStudies' ORDER BY post_content DESC LIMIT 1";
    $topicToPostsquery = mysqli_query($conn, $topicToPostssql);
    $topicToPost = mysqli_fetch_row($topicToPostsquery);
    $sstudiesForumData .= "<li>
                            <a href='topics/".$topicLinkSStudies."-".$topicIdSStudies.".php' style='color:black;'>
                              <h4 class='d-inline'>".$topicNameSStudies."</h4>
                              <h5 class='d-inline'>&middot;</h5>";
      // showing up differently depending on what kind of post it is

    if($topicTypeSStudies == 'Help'){
      $sstudiesForumData .= "<h5 class='d-inline text-danger' id='post_type'> ".$topicTypeSStudies."</h5>";
    }
    elseif($topicTypeSStudies == 'Discussion'){
      $sstudiesForumData .= "<h5 class='d-inline text-success' id='post_type'> ".$topicTypeSStudies."</h5>";
    }
    elseif($topicTypeSStudies == 'Other'){
      $sstudiesForumData .= "<h5 class='d-inline text-dark' id='post_type'> ".$topicTypeSStudies."</h5>";
    }
    $sstudiesForumData .= "<p class='text-secondary'>".$topicBySStudies[0]." &middot; ".$topicDateAgoSStudies."</p>";
    $sstudiesForumData .= "</a></li><hr>";

  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <link href="index.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/eb9214e190.js" crossorigin="anonymous"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/search.js"></script>
  <script src="js/forumTabShift.js"></script>
  <script src="js/messageRead.js"></script>
  <script src="friends/friendReqHandler.js"></script>
  <title>Connecthigh - Home</title>
</head>
<body>
  <!-- big green navbar up top -->
  <nav id="top_navbar" class="navbar navbar-light green-gradient position-sticky">
    <a href="index.php" class="navbar-brand" id="navbar_logo">
      <img src="images/white_logo_transparent_background.png" height="42"/>
    </a>
      <form class="form-inline float-right w-50" id="search_form" autocomplete="off">
        <input class="form-control w-100 dropdown" id="navbar_search" type="search" placeholder="Search" name="bigSearch">
        <div id="output" class="bg-light dropdown-menu dropdown-menu-right w-50" onclick="showSearchResults()" style="display:none;">
        </div>
      </form>
      <form class="form-inline float-left w-75 h6" id="search_form_small" autocomplete="off">
        <input class="form-control w-100 dropdown" id="navbar_search_small" type="search" placeholder="Search" name="bigSearch">
        <div id="outputSmall" class="ml-3 bg-light dropdown-menu dropdown-menu-lefts w-50" onclick="showSearchResults()" style="display:none;">
        </div>
      </form>
    <div id="profile_picture_navbar" class="position-absolute dropdown">
      <button class="btn nav-link dropdown-toggle" type="button" id="profile_picture_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $profileImg; ?>
      </button>
      <div class="dropdown-menu dropdown-menu-right animate slideIn" id="profile_picture_dropdown" aria-labelledby="profile_picture_dropdown">
        <a href="profiles/<?php echo $username?>.php" class="dropdown-item">My Profile</a>
        <hr>
        <a href="notifications/notifications.php" class="dropdown-item">Notifications</a>
        <hr>
        <a href="friends/friends.php" class="dropdown-item">My Friends</a>
        <hr>
        <a href="index.php?logout='1'" class="dropdown-item text-danger">Logout</a>
        <hr>
      </div>
    </div>
    <div id="nav_dropdown_small" class="position-absolute dropdown">
      <button class="btn nav-link dropdown-toggle" type="button" id="profile_dropdown_small" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-caret-down fa-2x text-light navbar-collapse"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right animate slideIn" id="profile_dropdown_small" aria-labelledby="profile_dropdown_small">
        <a href="index.php" class="dropdown-item">Home</a>
        <hr>
        <a href="profiles/<?php echo $username?>.php" class="dropdown-item">My Profile</a>
        <hr>
        <a href="notifications/notifications.php" class="dropdown-item">Notifications</a>
        <hr>
        <a href="friends/friends.php" class="dropdown-item">My Friends</a>
        <hr>
        <a href="index.php?logout='1'" class="dropdown-item text-danger">Logout</a>
        <hr>
      </div>
    </div>
  </nav>

  <!-- rest of the page -->
  <!-- grid stuff -->
  <div class="container-fluid">
    <div class="row">
      <div id="main_content_block" class="col my-4 mx-2 w-auto h-auto container-fluid row">
        <!-- buttons for the forum tabs -->
        <nav id="subject_buttons" class="col-2 col-sm-12 col-md-12">
          <div class="nav nav-tabs m-4 pt-2" id="post_tabs" role="tablist">
            <a class="nav-item nav-link active" id="english-tab" data-toggle="tab"  role="tab" aria-controls="englishContent" aria-selected="true" href="#englishContent"><h3>English</h3></a>
            <a class="nav-item nav-link" id="math-tab" data-toggle="tab" role="tab" aria-controls="mathContent" aria-selected="false" href="#mathContent"><h3>Math</h3></a>
            <a class="nav-item nav-link" id="science-tab" data-toggle="tab" aria-controls="scienceContent" aria-selected="false" href="#scienceContent"><h3>Science</h3></a>
            <a class="nav-item nav-link" id="sstudies-tab" data-toggle="tab" role="tab" aria-controls="sstudiesContent" aria-selected="false" href="#sstudiesContent"><h3>Social Studies</h3></a>
          </div>

          <!-- tabs/pills for smaller screens -->
          <div class="nav flex-column nav-pills" id="post_tabs_vert" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="english-tab-vert" data-toggle="pill" href="#englishContent" role="tab" aria-controls="englishContent" aria-selected="true"><i class="fas fa-book fa-2x"></i></a>
            <a class="nav-link" id="math-tab-vert" data-toggle="pill" href="#mathContent" role="tab" aria-controls="mathContent" aria-selected="false"><i class="fas fa-square-root-alt fa-2x"></i></a>
            <a class="nav-link" id="science-tab-vert" data-toggle="pill" href="#scienceContent" role="tab" aria-controls="scienceContent" aria-selected="false"><i class="fas fa-atom fa-2x"></i></a>
            <a class="nav-link" id="sstudies-tab-vert" data-toggle="pill" href="#sstudiesContent" role="tab" aria-controls="sstudiesContent" aria-selected="false"><i class="fas fa-globe-americas fa-2x"></i></a>
          </div>
        </nav>


        <!-- actual forum tabs -->
        <div class="tab-content col-8" id="forumContent">
          <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="english-tab" id="englishContent">
            <ul id="englishPosts">
              <?php echo $englishForumData; ?>
            </ul>
          </div>
          <div id="mathContent" class="tab-pane fade" role="tabpanel" aria-labelledby="math-tab">
            <ul id="mathPosts">
              <?php echo $mathForumData; ?>
            </ul>
          </div>
          <div class="tab-pane fade" role="tabpanel" aria-labelledby="science-tab" id="scienceContent">
            <ul id="sciencePosts">
              <?php echo $scienceForumData; ?>
            </ul>
          </div>
          <div class="tab-pane fade" role="tabpanel" aria-labelledby="sstudies-tab" id="sstudiesContent">
            <ul id="sstudiesPosts">
              <?php echo $sstudiesForumData; ?>
            </ul>
          </div>
        </div>
      </div>
        <!-- other area for user links and stuff -->
      <div class="col-md-3 col-xs-4 my-4 mx-2 w-auto h-100" id="userLinks">
        <ul class="mt-3 pl-0 align-center">
          <li>
            <a href="index.php">
              <i class="fas fa-home fa-lg"></i>
              <h4 id="homeLink" class="ml-2">Home</h4>
            </a>
          </li>
          <br />
          <li>
            <a href="topics/post.php">
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
