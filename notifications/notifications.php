<?php
session_start();

// i want the notification page and most subsequent pages to look a lot
// alike because it makes coding easier and it keeps the design simpler



// making sure user is logged in and handling a logout

if (!isset($_SESSION['username']))
{
    header("Location: login.php?nosession");
    die("Redirecting to login.php");
}

include '../includes/dbh.inc.php';
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
  <script src="../js/messageRead.js"></script>
  <script src="../friends/friendReqHandler.js"></script>

  <!-- this function needs session data so it has to be completely written out on the index page :( -->

  <script>
  function getNotifications(){
      var username = "<?php echo $_SESSION['username']; ?>";
      var id = "<?php echo $_SESSION['id']; ?>";

      $.ajax({
        type: 'post',
        url: 'notifications.inc.php',
        data: {
          username:username,
          id:id
        },
        success: function(response){
          $("#notifications").html(response);
        }
      });
    };
    $(document).ready(function(){
      getNotifications();
    });
</script>

  <title>Connecthigh - Home</title>
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
        <a href="../<?php echo $username?>.php" class="dropdown-item">My Profile</a>
        <hr>
        <a href="notifications.php" class="dropdown-item">Notifications</a>
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
        <a href="../<?php echo $username?>.php" class="dropdown-item">Home</a>
        <hr>
        <a href="../profiles/profile.php" class="dropdown-item">My Profile</a>
        <hr>
        <a href="notifications.php" class="dropdown-item">Notifications</a>
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
      <div id="main_content_block" class="col my-4 mx-2 w-auto h-auto">
        <!-- notifications area -->
        <div class="content ml-3 mt-2" id="notificationPage">
          <h2>Notifications</h2>
          <ul>
            <div id="notifications">
            </div>
          </ul>
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
            <a href="../topics/post.php">
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
