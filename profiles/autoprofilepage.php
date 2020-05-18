<?php
session_start();

include '../includes/dbh.inc.php';
  $lit_slash = "\'";
  $autowrite = fopen($_SESSION['username'].".php", "w") or die('cannot open the file');
  $txt = '<?php
  session_start();

  include \'../includes/dbh.inc.php\';

  $sessid = $_SESSION[\'id\'];
  $pageid = "'.$_SESSION['id'].'"; //<-- that is the id of the user whos page we are on
  $u = "'.$_SESSION['username'].'"; //<-- that is the user whos page we are on
  $log_username = $_SESSION[\'username\']; //<-- that is the user that is logged in

  $sql = "SELECT when_join FROM users WHERE id = \'$pageid\'";
  $query = mysqli_query($conn, $sql);
  $when_join = mysqli_fetch_assoc($query)[\'when_join\'];


  if (is_file(\'../uploads/profile\' . $sessid . \'.jpg\') === true)
  {
      $profileImg = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-index">
                       <img id="profile_picture" src="../uploads/profile\'.$sessid.\'.jpg" class="" height="44"/>
                     </div>\';
  }
  // if a .png file
  elseif (is_file(\'../uploads/profile\' . $sessid . \'.png\') === true)

  {
      $profileImg .= \'<div id="profile_picture_container" class="navbar-collapse profile-picture-index">
                       <img id="profile_picture" src="../uploads/profile\'.$sessid.\'.png" class="" height="44"/>
                     </div>\';
  }
  elseif (is_file(\'../uploads/profile\' . $sessid . \'.jpeg\') === true)

  {
      $profileImg .= \'<div id="profile_picture_container" class="navbar-collapse profile-picture-index-jpeg">
                       <img id="profile_picture" src="../uploads/profile\'.$sessid.\'.jpeg" class="" height="44"/>
                     </div>\';
  }
  // doesn\'t exist so default image
  else
  {
      $profileImg .= \'<div id="profile_picture_container" class="navbar-collapse profile-picture-default-index">
                       <img id="profile_picture" src="../images/defaultimage.jpg" class="" height="44"/>
                      </div>\';
  }
  function elapsed_time($datetime, $full = false){
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
      \'y\' => \'year\',
      \'m\' => \'month\',
      \'w\' => \'week\',
      \'d\' => \'day\',
      \'h\' => \'hour\',
      \'i\' => \'minute\',
      \'s\' => \'second\',
    );
    foreach ($string as $k => &$v){
      if ($diff->$k){
        $v = $diff->$k . \' \' . $v . ($diff->$k > 1 ? \'s\' : \'\');
      }else {
        unset($string[$k]);
      }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(\', \', $string) . \' ago\' : \'just now\';
  }

  $join_ago = elapsed_time($when_join);

  // gotta make sure the friend/unfriend button is working properly and gotta make sure it shows up at all
  $friend_button = \'<button class="friend_buttons" onclick="friendToggle('.$lit_slash.'friend'.$lit_slash.','.$lit_slash.'\' . $u . \''.$lit_slash.','.$lit_slash.'friendBtn'.$lit_slash.')" id="friendButton" ><i class="fas fa-user-plus fa-2x text-info"></i></button>\';
  // $block_button = \'<button disabled>Block User</button>\';   <-- he is gonna be used eventually
  $smile = \'\';

  // checking if these two users are friends
  $friend_check = "SELECT COUNT(id) FROM friends WHERE user1=\'$log_username\' AND user2=\'$u\' AND accepted=\'1\' OR user1=\'$u\' AND user2=\'$log_username\' AND accepted=\'1\'";
  $result = mysqli_query($conn, $friend_check);
  if (mysqli_fetch_row($result) > 0) // if they are friends...
  {
    // actually getting relevant data now...
      $sql = "SELECT * FROM friends WHERE user1=\'$log_username\' AND user2=\'$u\' OR user1=\'$u\' AND user2=\'$log_username\' LIMIT 1";
      $result2 = mysqli_query($conn, $sql);

      // checking friend request status
      foreach ($result2 as $row)
      {
          // if the request is accepted and the users are for sure friends
          if ($row[\'accepted\'] == \'1\')
          {
              // little smiley guy to show friendship :)
              $smile = "<i class=\'far fa-smile d-inline-block\'></i>";
              // chat button to jump to friends list with chat open
              $chat_button = "<a href=\'../friends/friends.php?quickchat=".$u."\'><i class=\'fas fa-comment-alt fa-2x\' style=\'color: #66CA69\'></i></button>";

              // unfriend button
              $friend_button = \'<button class="friend_buttons" onclick="friendToggle('.$lit_slash.'unfriend'.$lit_slash.','.$lit_slash.'\' . $u . \''.$lit_slash.','.$lit_slash.'friendBtn'.$lit_slash.')"><i class="fas fa-user-minus fa-2x text-info"></i></button>\';
              $block_check1 = "SELECT id FROM blockedusers WHERE blocker=\'$u\' AND blockee=\'$log_username\' LIMIT 1";

              if (mysqli_num_rows(mysqli_query($conn, $block_check1)) > 0)
              {
                  $ownerBlockViewer = true;
              }
              $block_check2 = "SELECT id FROM blockedusers WHERE blocker=\'$log_username\' AND blockee=\'$u\' LIMIT 1";
              if (mysqli_num_rows(mysqli_query($conn, $block_check2)) > 0)
              {
                  $viewerblockowner = true;
              }
          }
          // if there was a request sent but it hasn\'t been accepted yet
          elseif ($row[\'accepted\'] == \'0\')
          {
              $friend_button = \'<button class="friend_buttons" disabled ><i class="fas fa-user-clock fa-2x text-info"></i></button>\';

          }
          // if they aren\'t friends -- no friend data, not blocked, different users
          elseif ($u != $log_username && $ownerblockviewer == false)
          {
              $friend_button = \'<button class="friend_buttons" onclick="friendToggle('.$lit_slash.'friend'.$lit_slash.','.$lit_slash.'\' . $u . \''.$lit_slash.','.$lit_slash.'friendBtn'.$lit_slash.')" id="friendButton"><i class="fas fa-user-plus fa-2x text-info"></i></button>\';
          }
          // also if they aren\'t friends -- statement returned null
          elseif (is_null($row))
          {
              $friend_button = \'<button class="friend_buttons" onclick="friendToggle('.$lit_slash.'friend'.$lit_slash.','.$lit_slash.'\' . $u . \''.$lit_slash.','.$lit_slash.'friendBtn'.$lit_slash.')" id="friendButton"><i class="fas fa-user-plus fa-2x text-info"></i></button>\';
          }
      }
  }

  if($_SESSION[\'id\'] == $pageid){
    $friend_button = \'\';
  }



  // page profile profile_picture

  if (is_file(\'../uploads/profile\' . $pageid . \'.jpg\') === true)
  {
      $pageProfileImg = \'<div id="profile_picture_container" class="navbar-collapse profile-page-picture-jpeg">
                       <img id="profile_picture" src="../uploads/profile\'.$pageid.\'.jpg" class="" height="44"/>
                     </div>\';
  }
  // if a .png file
  elseif (is_file(\'../uploads/profile\' . $pageid . \'.png\') === true)

  {
      $pageProfileImg .= \'<div id="profile_picture_container" class="navbar-collapse profile-page-picture">
                       <img id="profile_picture" src="../uploads/profile\'.$pageid.\'.png" class="" height="44"/>
                     </div>\';
  }
  elseif (is_file(\'../uploads/profile\' . $pageid . \'.jpeg\') === true)

  {
      $pageProfileImg .= \'<div id="profile_picture_container" class="navbar-collapse profile-page-picture-jpeg">
                       <img id="profile_picture" src="../uploads/profile\'.$pageid.\'.jpeg" class="" height="44"/>
                     </div>\';
  }
  // doesn\'t exist so default image
  else
  {
      $pageProfileImg .= \'<div id="profile_picture_container" class="navbar-collapse profile-page-picture-default">
                       <img id="profile_picture" src="../images/defaultimage.jpg" class="" height="44"/>
                      </div>\';
  }

  // personal data stuff i.e. subjects/Bio

  $userSubjects = \'<h4>Subjects</h4>\';
  $sql = "SELECT DISTINCT subject FROM users_subject WHERE user_id = \'$pageid\'";
  $query = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($query)){
    $subject = $row[\'subject\'];
    $userSubjects .= "<p>".$subject." - ";
    $subsql = "SELECT subtopic FROM users_subject WHERE subject = \'$subject\' AND user_id = \'$pageid\'";
    $subquery = mysqli_query($conn, $subsql);
    $subtopicAll = mysqli_fetch_all($subquery);
    $firstSubtopic = $subtopicAll[0][0];
    $secondSubtopic = $subtopicAll[1][0];

    $userSubjects .= $firstSubtopic." and ".$secondSubtopic."</p>";

    }

// picture changing stuff
    if($_SESSION[\'id\'] == $pageid){
      $changeProfileImage = \'<div id="file_upload_form" class="d-inline-block align-top pt-3 mr-3"><form action="../upload.php" id="img_upload" style="display: inline;" class="w-auto" method="POST" enctype="multipart/form-data"  >
          <input id="files" type="file" class="file_upload" name="file" onchange="form.submit()" style="display:none;">
          <label for="files" class="update_img_link" style="display: none;"></label>
            <span id="button" onclick="profilePicSpanButton()" style="cursor: pointer;" class="w-auto h-auto"><i class="fas fa-edit fa-lg bg-lightgray px-1 py-2 br"></i></span>
          <input type="submit" style="display: none;">
          </form></div>\';

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
    <script src="../js/ajax.js"></script>
    <script src="../friends/friends.js"></script>
    <script src="../friends/friendReqHandler.js"></script>
    <script src="getPosts.js"></script>
    <script src="getLikes.js"></script>
    <script>
    function profilePicSpanButton(){
      $("input[type=\'file\']").trigger(\'click\')
        };
    </script>
    <title><?php echo $u; ?></title>
  </head>
  <body>
    <input type="hidden" id="profileId" value="<?php echo $pageid; ?>" />
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
          <a href="<?php echo $username?>.php" class="dropdown-item">My Profile</a>
          <hr>
          <a href="../notifications/notifications.php" class="dropdown-item">Notifications</a>
          <hr>
          <a href="../friends/friends.php" class="dropdown-item">My Friends</a>
          <hr>
          <a href="../index.php?logout=\'1\'" class="dropdown-item text-danger">Logout</a>
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
          <a href="<?php echo $_SESSION[\'id\'];?>.php" class="dropdown-item">My Profile</a>
          <hr>
          <a href="../notifications/notifications.php" class="dropdown-item">Notifications</a>
          <hr>
          <a href="../friends/friends.php" class="dropdown-item">My Friends</a>
          <hr>
          <a href="../index.php?logout=\'1\'" class="dropdown-item text-danger">Logout</a>
          <hr>
        </div>
      </div>
    </nav>

    <!-- rest of the page -->
    <!-- grid stuff -->
    <div class="container-fluid">
      <div class="row">
        <div id="main_content_block" class="col mx-2 w-auto h-auto profile-page-padding">
          <?php echo $pageProfileImg; ?>
          <?php echo $changeProfileImage; ?>
          <div id="profileSmallData" class="d-inline-block align-top mt-0 mx-2">
            <h1 class="d-inline-block align-top"><?php echo $u ?></h1><?php echo $smile; ?>
            <p class=""><i>Joined <?php echo $join_ago?></i></p>
          </div>
          <div id="profileButtons" class="d-inline-block align-top float-right m-2">
            <?php echo $friend_button;
            if(isset($chat_button)){
              echo $chat_button;
            }?>
          </div>
          <div id="user_data">
            <?php echo $userSubjects;?>
          </div>
          <nav class="mt-6 d-inline-block">
            <div class="nav nav-tabs m-4 pt-2" id="post_tabs" role="tablist">
              <a class="nav-item nav-link active center-tabs" id="profile-posts" data-toggle="tab"  role="tab" aria-controls="profilePosts" aria-selected="true" href="#profilePosts"><h3>Posts</h3></a>
              <a class="nav-item nav-link center-tabs" id="profile-likes" data-toggle="tab" role="tab" aria-controls="profileLikes" aria-selected="false" href="#profileLikes"><h3>Likes</h3></a>
            </div>
          </nav>
          <div class="tab-content" id="forumContent">
            <div id="profilePosts" class="tab-pane fade show active" role="tabpanel" aria-labelledby="profile-posts">
              <ul id="profilePosts">
                posts here!
              </ul>
            </div>
            <div id="profileLikes" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-likes">
              <ul id="profileLikes">
                likes here!
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  </html>
';
if(!fwrite($autowrite, $txt)) {
  var_dump($autowrite, $txt);
}else {
fclose($autowrite);

header("Location: ../index.php");
}

 ?>
