<?php
session_start();
require_once 'dbh.inc.php';

if (isset($_POST['query']))
{
    $data = array();
    $wrongname = $_SESSION['username'];
    $wrongid = $_SESSION['id'];
    $sql = "SELECT id, username FROM users WHERE username LIKE '%{$_POST["query"]}%' AND username <> '$wrongname'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0)
    {
        while ($users = mysqli_fetch_array($result))
        {

            while ($users['username'] === $wrongname)
            {
                $html = '';
                $data[] = '';
            }
            $profileimagename = 'profile' . $users['id'];
            $profileimage = glob('../uploads/' . $profileimagename . '.{jpg,png,gif,jpeg}', GLOB_BRACE);
            if (is_file('../uploads/profile' . $users['id'] . '.jpg') === true)
            {

                $html .= "<div style='font-size:20px;'><a class='dropdown-item' href='http://localhost/profiles/" . $users['username'] . ".php'><img style='border-radius:50%;position:relative;width:25px;height:25px;'src='http://localhost/uploads/" . $profileimagename . ".jpg'><p style='display:inline;padding-left: 12px;'>" . $users['username'] . "</p></a></div>";
                $data[] = "<div style='font-size:20px;'><a class='dropdown-item' href='profiles/" . $users['username'] . ".php'><img style='border-radius:50%;position:relative;width:25px;height:25px;'src='http://localhost/uploads/" . $profileimagename . ".jpg'><p style='display:inline;padding-left: 12px;'>" . $users['username'] . "</p></a></div>";
            }
            elseif(is_file('../uploads/profile' . $users['id'] . '.png') === true)
            {
                $html .= "<div style='font-size:20px;'><a class='dropdown-item' href='http://localhost/profiles/" . $users['username'] . ".php'><img style='border-radius:50%;position:relative;width:25px;height:25px;'src='http://localhost/uploads/" . $profileimagename . ".png'><p style='display:inline;padding-left: 12px;'>" . $users['username'] . "</p></a></div>";
                $data[] = "<div style='font-size:20px;'><a class='dropdown-item' href='http://localhost/profiles/" . $users['username'] . ".php'><img style='border-radius:50%;position:relative;width:25px;height:25px;'src='http://localhost/uploads/" . $profileimagename . ".png'><p style='display:inline;padding-left: 12px;'>" . $users['username'] . "</p></a></div>";
            }
              else

            {
                $html .= "<div style='font-size:20px;'><a class='dropdown-item' href='http://localhost/profiles/" . $users['username'] . ".php'><img style='border-radius:50%;position:relative;width:25px;'src='http://localhost/uploads/defaultimage.jpg'><p style='padding-left: 12px;display:inline;'>" . $users['username'] . "</p></a></div>";
                $data[] = "<div style='font-size:20px;'><a class='dropdown-item' href='http://localhost/profiles/" . $users['username'] . ".php'><img style='border-radius:50%;position:relative;width:25px;'src='http://localhost/uploads/defaultimage.jpg'><p style='padding-left: 12px;display:inline;'>" . $users['username'] . "</p></a></div>";
            }
        }
    }
    else
    {
        $html .= "";
        $data[] = $html;
    }
    $sqlCatName = "SELECT cat_id FROM categories WHERE cat_name LIKE '%{$_POST["query"]}%'";
    $queryCatName = mysqli_query($conn, $sqlCatName);
    $cat_id = mysqli_fetch_assoc($queryCatName)['cat_id'];

    $sqltopics = "SELECT topic_id, topic_subject, topic_by, topic_cat, topic_type FROM topics WHERE topic_subject LIKE '%{$_POST["query"]}%' OR topic_by LIKE '%{$_POST["query"]}%' OR topic_type LIKE '%{$_POST["query"]}%' OR topic_cat = '$cat_id' AND topic_by <> '$wrongid'";
    $querytopics = mysqli_query($conn, $sqltopics);

    if(mysqli_num_rows($querytopics) > 0){
      while($topics = mysqli_fetch_assoc($querytopics)){
        while ($topics['topic_by'] == $wrongid){
          $html .= '';
          $data[] = $html;
        }
        $topic_name_link = str_replace(' ', '', $topics['topic_subject']);
        $topic_name_link = str_replace('?', '', $topic_name_link);
        $topic_name_link = str_replace('.', '', $topic_name_link);
        $topic_name_link = str_replace("\'", '\'', $topic_name_link);

        $topic_name = $topics['topic_subject'];
        $topic_id = $topics['topic_id'];
        $topic_cat = $topics['topic_cat'];
        $topic_by = $topics['topic_by'];
        $sqlName = "SELECT username FROM users WHERE id = '$topic_by'";
        $queryName = mysqli_query($conn, $sqlName);
        $topic_by_name = mysqli_fetch_assoc($queryName)['username'];


        $sqlCountPost = "SELECT COUNT(*) AS numposts FROM posts WHERE post_topic = '$topic_id'";
        $queryCountPost = mysqli_query($conn, $sqlCountPost);
        $numPosts = mysqli_fetch_assoc($queryCountPost)['numposts'];

        $data[] = $numposts;

        $sqlCountLike = "SELECT COUNT(*) AS numlikes FROM likes WHERE topic_id = '$topic_id'";
        $queryCountLike = mysqli_query($conn, $sqlCountLike);
        $numLikes = mysqli_fetch_assoc($queryCountLike)['numlikes'];

        $data[] = $numlikes;

        if($topic_cat == '1'){
          $html .= '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-book" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
          $data[] = '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-book" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
        }
        if($topic_cat == '2'){
          $html .= '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-square-root-alt" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
          $data[] = '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-square-root-alt" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
        }
        if($topic_cat == '3'){
          $html .= '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-flask" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
          $data[] = '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-flask" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
          }
        if($topic_cat == '4'){
          $html .= '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-globe-americas" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
          $data[] = '<div style="margin:6px;position:relative;height:auto;order:'.$numLikes.';-webkit-order:'.$numLikes.';"><a href="http://localhost/topics/'.$topic_name_link.'-'.$topic_id.'.php"><i class="fas fa-globe-americas" style="color:gray;margin-right:5px"></i><h1 style="font-size:18px;display:inline;">'.$topic_name.' <i style="color:gray">~ topic</i></h1><h2 style="display:inline;float:right;font-size:16px;color:gray;">created by '.$topic_by_name.'</h2>
          <p style="margin-bottom:6px;color:gray;font-size:14px;">'.$numPosts.' posts  '.$numLikes.' likes</p></a></div>';
        }


      }
    }

    echo $html;

}
//profiles/$users['username'].php

?>
