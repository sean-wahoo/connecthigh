<?php
session_start();
include 'includes/dbh.inc.php';

$imgid = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $file = $_FILES['file'];

  $fileName = $_FILES['file']['name'];
  $fileTmpName = $_FILES['file']['tmp_name'];
  $fileSize = $_FILES['file']['size'];
  $fileError = $_FILES['file']['error'];
  $fileType = $_FILES['file']['type'];

  $fileExt = explode('.', $fileName);
  $fileActualExt = strtolower(end($fileExt));

  $allowed = array('jpg', 'jpeg', 'png');

  if (in_array($fileActualExt, $allowed)) {
    if ($fileError === 0){
      if ($fileSize < 800000) {
        $fileNameNew = "profile".$imgid.".".$fileActualExt;
        $filearraycheck = array_search($fileNameNew, $allowed);
        $fileNameAllowed = "profile".$imgid.".".$fileActualExt;
        $fileForDeletePng = 'uploads/profile'.$imgid.'.png';
        $fileForDeleteJpg = 'uploads/profile'.$imgid.'.jpg';
        $fileForDeleteJpeg = 'uploads/profile'.$imgid.'.jpeg';
        $fileDestination = 'uploads/'.$fileNameAllowed;
        if (file_exists($fileForDeletePng) || file_exists($fileForDeleteJpg) || file_exists($fileForDeleteJpeg)) {
          $filename = "uploads/profile".$imgid."*";
          $fileinfo = glob($filename);
          $fileext = explode(".", $fileinfo[0]);
          $fileactualext = $fileext[1];

          $fileDelete = "uploads/profile".$imgid.".".$fileactualext;

          if (!unlink($fileDelete)){
        var_dump($fileDelete);
        }
        elseif(move_uploaded_file($fileTmpName, $fileDestination)) {
        header("Location: ../profiles/".$_SESSION['username'].".php");
      }

      else {
        echo "Your file is too big!";
      }
    }elseif(move_uploaded_file($fileTmpName, $fileDestination)) {

      $sql = "UPDATE profileimg SET status = 0 WHERE userid='$imgid'";
      $result = mysqli_query($conn, $sql);
    header("Location: ../profiles/".$_SESSION['username'].".php");
  }else{
      echo 'Something went wrong while uploading!';
    }
  }else {
    echo "You can only upload .jpg, .jpeg, and .png files!";
  }
}else {
  var_dump($fileError);
}
}else{ var_dump($sql);}
}else{echo 'huh?';}
 ?>
