<?php
  session_start();
  include '../includes/dbh.inc.php';
  include 'forum_post.inc.php';
  $topic_id = $_SESSION['topic_id'];
  $id = $_SESSION['topic_create_id'];
  $postTopicWithSpaces = $_SESSION['topic_name'];
  $postTopicWithSpaces = str_replace("\'", '\'', $postTopicWithSpaces);
  $postTopic = str_replace(' ', '', $_SESSION['topic_name']);
  $postTopic = str_replace('?', '', $postTopic);
  $postTopic = str_replace('.', '', $postTopic);
  $postTopic = str_replace("\'", '\'', $postTopic);
  $now = $_SESSION['time'];
  $cat_name = $_SESSION['cat_name'];
  $cat_id = $_SESSION['category_id'];
  $postType = $_SESSION['post_type'];
  $postContent = $_SESSION['post_content'];
  $postContent = str_replace('\r\n','<br>', $postContent);
  $postContent = str_replace("\'", '\'', $postContent);
  $sql = "SELECT username FROM users WHERE id = '$id'";
  $query = mysqli_query($conn, $sql);
  $username = mysqli_fetch_row($query)[0];
  $_SESSION['topic_username'] = $username;
    $autowrite = fopen($postTopic."-".$topic_id.".php", "w");
    $txt = '<?php
        session_start();
        include \'../includes/dbh.inc.php\';

        if (!isset($_SESSION[\'username\']))
        {
            header("Location: login.php?nosession");
            die("Redirecting to login.php");
        }

        include \'../friends/fetch_last_activity.inc.php\';

        if (isset($_GET[\'logout\']))
        {
            setcookie("unm", "", $unsethour, "/");
            setcookie("pwd", "", $unsethour, "/");
            session_destroy();
            unset($_SESSION[\'username\']);
            header("location: login.php");
        }

        $sessid = $_SESSION[\'id\'];
        $username = $_SESSION[\'username\'];


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

        // if a .jpg file
        if (is_file(\'../uploads/profile\' . $sessid . \'.jpg\') === true)
        {
            $profileImg = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-index-jpeg">
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


        // BIG LOT OF PHP GARBAGE TO DEAL WITH AND SHOW DIFFERENT COMMENTS




        $topic_id = '.$topic_id.';

        // getting time ago of topic post

        $topicAgo = elapsed_time("'.$now.'");

        // checking the likes on the actual topic itself
        $sqlCheckLikes = "SELECT topic_liked_by FROM likes WHERE topic_id = \'$topic_id\'";
        $queryCheckLikes = mysqli_query($conn, $sqlCheckLikes);
        if(mysqli_num_rows($queryCheckLikes) == 0){
          $starIcon = "<i class=\'far fa-star fa-2x d-inline-block align-baseline text-warning\'></i>";
        }
        while($isLikedTopic = mysqli_fetch_assoc($queryCheckLikes)){
          if($isLikedTopic == NULL || !in_array($sessid, $isLikedTopic)){
            $starIcon = "<i class=\'far fa-star fa-2x d-inline-block align-baseline text-warning\'></i>";
          }
          elseif(in_array($sessid, $isLikedTopic)){
            $starIcon = "<i class=\'fas fa-star fa-2x d-inline-block align-baseline text-warning\'></i>";
          }
        }

        $sql = "SELECT post_id, post_content, post_date, post_by, post_reply_to, user_reply_to FROM posts WHERE post_topic = \'$topic_id\' ORDER BY post_date DESC";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) < 1){
          $topicPost = \'<p class="text-secondary text-center"><i>Be the first to comment!</i></p>\';
        }
        else{
          $topicPost = "<h1 class=\'mx-5\'>Comments</h1>";
          function showComments($conn, $post_id, $post_reply_to, $margin_left, $width){
            $sql = "SELECT post_id, post_content, post_date, post_by, post_topic, post_reply_to, user_reply_to FROM posts WHERE post_reply_to = \'$post_id\' ORDER BY post_date ASC";
            $query = mysqli_query($conn, $sql);




            // this section is for reply comments because those behave differently and more annoying than 1st level comments

            while($reply = mysqli_fetch_assoc($query)){

              // tons of relevant data
              $topic_id = '.$topic_id.';
              $id = $_SESSION[\'id\'];
              $replyId = $reply[\'post_id\'];
              $replyContent = $reply[\'post_content\'];
              $replyDate = elapsed_time($reply[\'post_date\']);
              $replyTopic = $reply[\'post_topic\'];
              $replyBy = $reply[\'post_by\'];
              $sqlnamereply = "SELECT username FROM users WHERE id = \'$replyBy\'";
              $querynamereply = mysqli_query($conn, $sqlnamereply);
              $usernamereply = mysqli_fetch_row($querynamereply)[0];
              $replyToPost = $reply[\'post_reply_to\'];
              $replyToUser = $reply[\'user_reply_to\'];

              $sqlCheckLikesReply = "SELECT post_liked_by FROM likes WHERE post_id = \'$replyId\'";
              $queryCheckLikesReply = mysqli_query($conn, $sqlCheckLikesReply);
              $isLikedReply = mysqli_fetch_assoc($queryCheckLikesReply);

              //figuring out the situation with likes
              if($isLikedReply == NULL || !in_array($id, $isLikedReply)){
                $starIconReply = "<i class=\'far fa-star fa-lg text-warning d-inline ml-auto\'></i>";
              }
              elseif(in_array($id, $isLikedReply)){
                $starIconReply = "<i class=\'fas fa-star fa-lg text-warning d-inline ml-auto\'></i>";
              }

              //  counting the likes
              $sqlLikeCount = "SELECT COUNT(*) AS likes FROM likes WHERE post_id = \'$replyId\'";
              $queryLikeCount = mysqli_query($conn, $sqlLikeCount);
              $likeCount = mysqli_fetch_assoc($queryLikeCount)[\'likes\'];

              // finding new left margin of comment based on reply level
              $sqlMARGIN = "SELECT post_id, post_reply_to, user_reply_to FROM posts WHERE post_id = \'$replyToPost\'";
              $queryMARGIN = mysqli_query($conn, $sqlMARGIN);
              $margin_left = 5;
              $width = 812;
              $sqlnamereply2 = "SELECT username FROM users WHERE id = \'$replyBy\'";
              $querynamereply2 = mysqli_query($conn, $sqlnamereply2);
              $usernamereply2 = mysqli_fetch_row($querynamereply2)[0];
              if (is_file(\'../uploads/profile\' . $replyBy . \'.jpg\') === true)
              {
                  $profileImgReply = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-friends-jpeg">
                                   <img id="profile_picture" src="../uploads/profile\'.$replyBy.\'.jpg" class="" height="36"/>
                                 </div>\';
              }
              // if a .png file
              elseif (is_file(\'../uploads/profile\' . $replyBy . \'.jpeg\') === true)

              {
                  $profileImgReply = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-friends-jpeg">
                                   <img id="profile_picture" src="../uploads/profile\'.$replyBy.\'.jpeg" class="" height="36"/>
                                 </div>\';
              }
              elseif (is_file(\'../uploads/profile\' . $replyBy . \'.png\') === true)

              {
                  $profileImgReply = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-friends">
                                   <img id="profile_picture" src="../uploads/profile\'.$replyBy.\'.png" class="" height="36"/>
                                 </div>\';
              }
              // doesn\'t exist so default image
              else
              {
                  $profileImgReply = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-default">
                                   <img id="profile_picture" src="../images/defaultimage.jpg" class="" height="36"/>
                                  </div>\';
              }

              while($replyMargin = mysqli_fetch_assoc($queryMARGIN)){
              if($replyMargin[\'post_reply_to\'] == 0 || $replyMargin[\'user_reply_to\'] == 0){
                $margin_left = 5;
                $width = 812;
              }elseif($replyMargin[\'post_reply_to\'] !== 0 || $replyMargin[\'user_reply_to\'] !== 0){
                $replyMarginId = $replyMargin[\'post_id\'];
                $replyMarginBy = $replyMargin[\'user_reply_to\'];
                $sqlnamereply2 = "SELECT username FROM users WHERE id = \'$replyMarginBy\'";
                $querynamereply2 = mysqli_query($conn, $sqlnamereply2);
                $usernamereply2 = mysqli_fetch_row($querynamereply2)[0];
                $link_to_parent = "<a href=\'#topicPost".$replyMarginId."\'>@".$usernamereply2."</a>";
                $margin_left = $margin_left + 5;
                $width = $width - 30;

              }
            }

              // defining the layout of the reply (exact same as 1st level comment)
              $replies .= "<div class=\'mb-2\' id=\'topicPost".$replyId."\'style=\'width:".$width."px;margin-left:".$margin_left."%;>";
              $replies .= "<a href=\'../profiles/".$usernamereply.".php\'>".$profileImgReply."<h4 class=\'d-inline mr-3\'><b>".$usernamereply."</b></h4></a><p class=\'d-inline align-middle text-secondary\'>".$replyDate."</p>";
              $replies .= "<p class=\'mb-1 ml-2\'>".$link_to_parent." ".$replyContent."</p>";
              $replies .= "<button type=\'button\' name=\'reply_button\' id=\'reply_button\' class=\'d-inline text-info\' onclick=\'showReply(".$replyId.",".$replyBy.")\'>Reply</button>";
              $replies .= "<form id=\'post_like_post\' action=\'update-likes.inc.php\' class=\'d-inline\' method=\'post\'>
                <button type=\'submit\' name=\'submit_like_post\' id=\'submit_like\'>".$starIconReply."</button>".$likeCount."
                <input type=\'hidden\' name=\'session_id\' value=\'".$id."\' />
                <input type=\'hidden\' name=\'post_id\' value=\'".$replyId."\' />
                <input type=\'hidden\' name=\'topic_id\' value=\'".$topic_id."\' />
              </form>";
              $replies .= "<div style=\'display:none;\' class=\'reply_form_".$replyToPost."+".$replyToUser."\' id=\'reply_form_".$replyId."+".$replyBy."\'>
                            <form method=\'post\' class=\'form-group form-inline\' action=\'post_comment.inc.php\' id=\'reply_form\'>
                              <input type=\'text\' class=\'form-control form-control-sm\' name=\'post_content\' id=\'comment_post\' required />
                              <button type=\'submit\' class=\'form-control form-control-sm\' name=\'post_reply_submit\'>Send</button>
                              <input type=\'hidden\' name=\'post_topic_id\' value=\'".$replyTopic."\' />
                              <input type=\'hidden\' name=\'poster_id\' value=\'".$id."\' />
                              <input type=\'hidden\' name=\'post_reply_to\' value=\'".$replyId."\' />
                              <input type=\'hidden\' name=\'user_reply_to\' value=\'".$replyBy."\' />
                              </form>
                            </div>";
              $replies .= "</div>";

              $replies .= "<div class=\'replies\' id=\'replies_for_".$replyId."\'>";

                          // doing some fancy math yet again
              $replies .= showComments($conn, $replyId, $replyToPost, $margin_left, $width);
              $replies .=  "</div>";

              if(empty($reply)){
                $replies .= \'\';
              }
            }
            return $replies;
          }
          while($post = mysqli_fetch_assoc($query)){
            if($post[\'post_reply_to\'] !== \'0\' || $post[\'user_reply_to\'] !== \'0\'){
              $topicPost .= \'\';
            }
            else{
            $postId = $post[\'post_id\'];
            $userid = $post[\'post_by\'];

            $sqlCheckLikesPost = "SELECT post_liked_by FROM likes WHERE post_id = \'$postId\'";
            $queryCheckLikesPost = mysqli_query($conn, $sqlCheckLikesPost);
            $isLikedPost = mysqli_fetch_assoc($queryCheckLikesPost);
            if($isLikedPost == NULL || !in_array($sessid, $isLikedPost)){
              $starIconPost = "<i class=\'far fa-star fa-lg text-warning d-inline ml-auto\'></i>";
            }
            else{
              $starIconPost = "<i class=\'fas fa-star fa-lg text-warning d-inline ml-auto\'></i>";
            }

            $sqlLikeCount = "SELECT COUNT(*) AS likes FROM likes WHERE post_id = \'$postId\'";
            $queryLikeCount = mysqli_query($conn, $sqlLikeCount);
            $likeCount = mysqli_fetch_assoc($queryLikeCount)[\'likes\'];



            $postAgo = elapsed_time($post[\'post_date\']);
            $sqlname = "SELECT username FROM users WHERE id = \'$userid\'";
            $queryname = mysqli_query($conn, $sqlname);
            $username = mysqli_fetch_row($queryname)[0];
            $topicPost .= "<div class=\'mb-2\' id=\'topicPost".$postId."\'>";
            if (is_file(\'../uploads/profile\' . $userid . \'.jpg\') === true)
            {
                $profileImgComment = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-friends-jpeg mr-2">
                                 <img id="profile_picture" src="../uploads/profile\'.$userid.\'.jpg" class="" height="36"/>
                               </div>\';
            }
            // if a .png file
            elseif (is_file(\'../uploads/profile\' . $userid . \'.png\') === true)

            {
                $profileImgComment = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-friends mr-2">
                                 <img id="profile_picture" src="../uploads/profile\'.$userid.\'.png" class="" height="36"/>
                               </div>\';
            }
            elseif (is_file(\'../uploads/profile\' . $userid . \'.jpeg\') === true)

            {
                $profileImgComment = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-friends-jpeg mr-2">
                                 <img id="profile_picture" src="../uploads/profile\'.$userid.\'.jpeg" class="" height="36"/>
                               </div>\';
            }
            // doesn\'t exist so default image
            else
            {
                $profileImgComment = \'<div id="profile_picture_container" class="navbar-collapse profile-picture-default mr-2">
                                 <img id="profile_picture" src="../images/defaultimage.jpg" class="" height="36"/>
                                </div>\';
            }
            $topicPost .= "<a href=\'../profiles/".$username.".php\'>".$profileImgComment."<h4 class=\'d-inline align-middle mr-3\'><b>".$username."</b></h4></a><p class=\'d-inline align-middle text-secondary\'>".$postAgo."</p>";
            $topicPost .= "<p class=\'mb-1 ml-2\'>".$post[\'post_content\']."</p>";
            $topicPost .= "<button type=\'button\' name=\'reply_button\' class=\'d-inline text-info\' id=\'reply_button\' onclick=\'showReply(".$postId.",".$userid.")\'>Reply</button>";
            $topicPost .= "<form id=\'post_like_post\' action=\'update-likes.inc.php\' class=\'d-inline\' method=\'post\'>
              <button type=\'submit\' name=\'submit_like_post\' id=\'submit_like\'>".$starIconPost."</button>".$likeCount."
              <input type=\'hidden\' name=\'session_id\' value=\'".$sessid."\' />
              <input type=\'hidden\' name=\'post_id\' value=\'".$postId."\' />
              <input type=\'hidden\' name=\'topic_id\' value=\'".$topic_id."\' />
            </form>";
            $topicPost .= "<div style=\'display:none;\' class=\'mt-1 reply_form_".$postId."+".$userid."\' id=\'reply_form_".$postId."+".$userid."\'>
                            <form method=\'post\' class=\'form-group form-inline\' action=\'post_comment.inc.php\' id=\'reply_form\'>
                            <input type=\'text\' class=\'form-control form-control-sm\' name=\'post_content\' id=\'comment_post\' style=\'margin-left:15px;\'required />
                            <button type=\'submit\' class=\'form-control form-control-sm\' name=\'post_reply_submit\'>Send</button>
                            <input type=\'hidden\' name=\'post_topic_id\' value=\'".$topic_id."\' />
                            <input type=\'hidden\' name=\'poster_id\' value=\'".$sessid."\' />
                            <input type=\'hidden\' name=\'post_reply_to\' value=\'".$postId."\' />
                            <input type=\'hidden\' name=\'user_reply_to\' value=\'".$userid."\' />
                          </form></div>";

            $topicPost .= "</div>";
            $topicPost .= "<div class=\'replies\' id=\'replies_for_".$post[\'post_id\']."\'>";
            $topicPost .= showComments($conn, $post[\'post_id\'], $post[\'post_reply_to\'], $margin_left, $width);
            $topicPost .=  "</div>";

          }
          }
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
          <script src="showReply.js"></script>
          <title>'.$postTopicWithSpaces.'</title>
        </head>
        <body>
          <nav id="top_navbar" class="navbar navbar-light green-gradient position-sticky">
            <a href="../index.php" class="navbar-brand" id="navbar_logo">
              <img src="../images/white_logo_transparent_background.png" height="42"/>
            </a>
              <form class="form-inline float-right w-50 my-auto" id="search_form">
                <input class="form-control w-100" id="navbar_search" type="search" placeholder="Search" name="bigSearch">
              </form>
              <form class="form-inline float-left w-75 my-auto" id="search_form_small">
                <input class="form-control w-100" id="navbar_search" type="search" placeholder="Search" name="bigSearch">
              </form>
            <div id="profile_picture_navbar" class="position-absolute dropdown">
              <button class="btn nav-link dropdown-toggle" type="button" id="profile_picture_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo $profileImg; ?>
              </button>
              <div class="dropdown-menu dropdown-menu-right animate slideIn" id="profile_picture_dropdown" aria-labelledby="profile_picture_dropdown">
                <a href="../profiles/profile.php" class="dropdown-item">My Profile</a>
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
                <a href="../profiles/profile.php" class="dropdown-item">My Profile</a>
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
          <div class="container-fluid">
            <div class="row">
              <div id="main_content_block" class="col row my-4 mx-2 w-auto h-auto">
                <div id="topic_area" class="col-md-12">
                  <div id="topic_headers" class="m-1">
                    <p class="text-secondary mb-0 mr-4 d-inline"><small>posted by '.$username.' <?php echo $topicAgo ?></small></p>
                    <p class="text-secondary d-inline"><small><nobr>'.$postType.' &middot; '.$cat_name.'</nobr></small></p>
                    <form id="topic_like_form" action="update-likes.inc.php" method="post" class=\'d-inline align-middle float-right mt-2\'>
                      <button type="submit" class="d-inline align-middle" name="submit_like_topic" id="submit_like"><?php echo $starIcon ?></button>
                      <input type="hidden" name="session_id" value="<?php echo $sessid;?>" />
                      <input type="hidden" name="topic_id" value="'.$topic_id.'" />
                    </form>
                  </div>
                  <div id="topic_description">
                    <h1 class="h-auto w-auto mx-3 d-inline">'.$postTopicWithSpaces.'</h1>
                    <p class="mx-3 mt-2">'.$postContent.'</p>
                  </div>
                  <div id="topic_post_section" class="col-md-12 row">
                    <form id="topic_post_form" method="post" action="post_comment.inc.php" class="form-inline p-0 d-inline col-12 align-middle">
                      <div class="input-group col-md-6 col-sm-8 p-0 mx-3">
                        <input type="text" name="post_content" id="comment_post" class="form-control w-75 p-0" required />
                        <div class="input-group-append">
                          <button type="submit" class="btn btn-success" name="post_submit">Post</button>
                        </div>
                        <input type="hidden" name="post_topic_id" value="'.$_SESSION['topic_id'].'" />
                        <input type="hidden" name="poster_id" value="<?php echo $sessid; ?>" />
                      </div>
                    </form>
                  </div>
                  <hr class=\'betweens mb-2\'>
                  <div id="comment_section" class=\'ml-4\'>
                    <?php echo $topicPost;
                    echo $replies;
                    ?>
                  </div>
                </div>
              </div>
                <!-- other area for user links and stuff -->

            </div>
          </div>
        </body>
        </html>


        <?php

        unset($_SESSION[\'topic_create_id\']);
        unset($_SESSION[\'topic_name\']);
        unset($_SESSION[\'time\']);
        unset($_SESSION[\'category_id\']);
        unset($_SESSION[\'post_type\']);
        unset($_SESSION[\'post_content\']);
        unset($_SESSION[\'topic_type\']);
        unset($_SESSION[\'topic_id\']);

        //notification stuff
        $sqlTOPIC = "SELECT topic_by FROM topics WHERE topic_id = \'$topic_id\'";
        $queryTOPIC = mysqli_query($conn, $sqlTOPIC);
        $topic_by = mysqli_fetch_assoc($queryTOPIC)[\'topic_by\'];

        $userid = $_SESSION[\'id\'];

        // about = 3 (reply)
        $sqlPOST2_3 = "SELECT replied_to FROM notifications WHERE user_id = \'$userid\' AND about = \'3\' AND description = \'got_reply\'";

        if($queryPOST2_3 = mysqli_query($conn, $sqlPOST2_3)){
          $replied_to_3 = mysqli_fetch_assoc($queryPOST2_3)[\'replied_to\'];

          $sqlNOTIF2_3 = "DELETE FROM notifications WHERE user_id = \'$userid\' AND replied_to = \'$replied_to_3\' AND about = \'3\' AND description = \'got_reply\'";
          $queryNOTIF_3 = mysqli_query($conn, $sqlNOTIF2_3);
        }
        // about = 4 (like)
        $sqlPOST2_4 = "SELECT replied_to FROM notifications WHERE user_id = \'$userid\' AND about = \'4\' AND description = \'got_like\'";
        if($queryPOST2_4 = mysqli_query($conn, $sqlPOST2_4)){
          $replied_to_4 = mysqli_fetch_assoc($queryPOST2_4)[\'replied_to\'];

          $sqlNOTIF3_4 = "DELETE FROM notifications WHERE user_id = \'$userid\' AND replied_to = \'$replied_to_4\' AND about = \'4\' AND description = \'got_like\'";
          $queryNOTIF2_4 = mysqli_query($conn, $sqlNOTIF3_4);
        }
        // about = 5 (forum help)
        $sqlPOST2_5 = "SELECT replied_to FROM notifications WHERE user_id = \'$userid\' AND about = \'5\' AND description = \'forum_help\'";
        if($queryPOST2_5 = mysqli_query($conn, $sqlPOST2_5)){
          $replied_to_5 = mysqli_fetch_assoc($queryPOST2_5)[\'replied_to\'];

          $sqlNOTIF4_5 = "DELETE FROM notifications WHERE user_id = \'$userid\' AND replied_to = \'$replied_to_5\' AND about = \'5\' AND description = \'forum_help\'";
          $queryNOTIF3_5 = mysqli_query($conn, $sqlNOTIF4_5);
        }


        ?>
';
if(!fwrite($autowrite, $txt)) {
  var_dump($autowrite, $txt);
}else {
fclose($autowrite);

$user_id_1 = $_SESSION['id'];


if($postType == "Help"){
  $sql1 = "SELECT cat_name FROM categories WHERE cat_id = '$cat_id'";
  $query1 = mysqli_query($conn, $sql1);
  $cat_name = mysqli_fetch_assoc($query1)['cat_name'];
  $sql2 = "SELECT DISTINCT user_id FROM users_subject WHERE subject = '$cat_name'";
  $query2 = mysqli_query($conn, $sql2);
  $about = '5';
  $description = 'forum_help';
  while($user = mysqli_fetch_assoc($query2)){
    $user_id_2 = $user['user_id'];
    if($user_id_2 != $user_id_1){
      $sqlNOTIF = "INSERT INTO notifications (user_id, from_id, replied_to, about, description) VALUES (?,?,?,?,?)";
      $stmt = mysqli_stmt_init($conn);
      if(!mysqli_stmt_prepare($stmt, $sqlNOTIF)){
        echo 'damn';
      }
      else{
        mysqli_stmt_bind_param($stmt, "iiiis", $user_id_2, $user_id_1, $topic_id, $about, $description);
        mysqli_stmt_execute($stmt);
      }
    }
  }
}

header("Location: ".$postTopic."-".$topic_id.".php");
}




?>
