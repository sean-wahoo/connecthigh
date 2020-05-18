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
if (is_file('../uploads/profile' . $sessid . '.jpg') === true)
{
    $profileImg = '<div id="profile_picture_container" class="navbar-collapse profile-picture-index-jpeg">
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
elseif (is_file('../uploads/profile' . $sessid . '.jpeg') === true)

{
    $profileImg .= '<div id="profile_picture_container" class="navbar-collapse profile-picture-index-jpeg">
                     <img id="profile_picture" src="../uploads/profile'.$sessid.'.jpeg" class="" height="44"/>
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


<!DOCTYPE html>
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
  <!-- big green navbar up top -->
  <nav id="top_navbar" class="navbar navbar-light green-gradient position-sticky">
    <a href="../index.php" class="navbar-brand" id="navbar_logo">
      <img src="../images/white_logo_transparent_background.png" height="42"/>
    </a>
      <form class="form-inline float-right w-50" id="search_form" autocomplete="off">
        <input class="form-control w-100 dropdown" id="navbar_search" type="search" placeholder="Search" name="bigSearch">
        <div id="output" class="bg-light dropdown-menu dropdown-menu-right w-50" onclick="showSearchResults()" style="display:none;">
        </div>
      </form>
      <form class="form-inline float-left w-75" id="search_form_small" autocomplete="off">
        <input class="form-control w-100 dropdown" id="navbar_search" type="search" placeholder="Search" name="bigSearch">
        <div id="outputSmall" class="ml-3 bg-light dropdown-menu dropdown-menu-lefts w-50" onclick="showSearchResults()" style="display:none;">
        </div>
      </form>
    <div id="profile_picture_navbar" class="position-absolute dropdown">
      <button class="btn nav-link dropdown-toggle" type="button" id="profile_picture_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $profileImg; ?>
      </button>
      <div class="dropdown-menu dropdown-menu-right animate slideIn" id="profile_picture_dropdown" aria-labelledby="profile_picture_dropdown">
        <a href="../profiles/<?php echo $username?>.php" class="dropdown-item">My Profile</a>
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
        <a href="../profiles/<?php echo $username?>.php" class="dropdown-item">My Profile</a>
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

  <!-- rest of the page -->
  <!-- grid stuff -->
  <div class="container-fluid">
    <div class="row">
      <div id="main_content_block" class="col my-4 mx-2 w-auto h-auto row">

        <!-- big form for creating posts -->
        <form method="post" action="forum_post.inc.php" id="postForum" class="form-group row mt-4">
          <div class="form-group row col-md-12 mx-3">
            <label for="topicPost" class="col-sm-6 col-md-6 col-form-label mr-3 text-center">What's the title?</label>
            <div class="col-sm-10">
              <input type="text" class="form-control px-0 mx-0 col-md-12 col-sm-9 w-100" id="topicPost" name="postTopic" required />
            </div>
          </div>
          <div class="form-group row col-md-12 mx-3">
            <label for="typePost" class="col-sm-6 col-md-6 col-form-label mr-3 text-center">What type of post is it?</label>
            <div class="col-sm-10 ">
              <select name="typePost" id="typePost" class="form-control px-0 mx-0 col-md-12 col-sm-9 w-100" required>
                <option>Help</option>
                <option>Discussion</option>
                <option>Other</option>
              </select>
            </div>
          </div>
          <br />
          <div class="form-group row mx-3 col-md-12">
            <label for="postText" class="col-sm-6 col-md-6 col-form-label mr-3 text-center">Give a description of your post:</label>
            <div class="col-sm-10">
              <textarea form="postForum" class="form-control col-md-12 col-sm-9" maxlength="255" rows="3" id="postText" name="postData" required></textarea>
            </div>
          </div>
          <div class="form-group row col-md-12 mx-3">
            <label for="catType" class="col-sm-6 col-md-6 col-form-label-xs text-center">What's the topic?</label>
            <div class="col-md-8 ">
              <select name="catType" id="catType" class="form-control-xs col-md-12 col-sm-9 w-100" required>
                <option>English</option>
                <option>Math</option>
                <option>Science</option>
                <option>Social Studies</option>
              </select>
            </div>
          </div>
          <div class="form-group row col-md-12 text-center">
            <div class="col-sm-10 text-center">
              <button type="submit" class="form-control w-auto bg-success text-light mx-auto" name="postSubmit" id="postSubmit">Post</button>
            </div>
          </div>

          <!-- this is for letting the post creating pages know who did it -->
          <input type="hidden" value="<?php echo $_SESSION['id'] ?>" name="sessionId" />
        </form>
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
