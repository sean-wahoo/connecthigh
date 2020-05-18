<?php
if (isset($_POST['register'])) {

  require 'dbh.inc.php';

  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password2 = $_POST['confirmpassword'];
  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number = preg_match('@[0-9]@', $password);
  $specialChars = preg_match('@[^\w]@', $password);

  if (empty($username) || empty($email) || empty($password) || empty($password2)) {
    header("location: ../register.php?error=emptyfields&username=".$username."&email=".$email);
    exit();
  }
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("location: ../register.php?error=invaliduseremail");
    exit();
  }
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("location: ../register.php?error=invalidemail&username=".$username);
    exit();
  }
  elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("location: ../register.php?error=invalidusername&email=".$email);
    exit();
  }
  elseif (!$password == $password2) {
    header("location: ../register.php?error=passwordcheck&username=".$username."&email=".$email);
    exit();
  }
  elseif(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) <= 8) {
    header("location: ../register.php?error=passwordreq&username=".$username."&email=".$email);
    exit();
  }
  else{

    $sql = "SELECT username FROM users WHERE username=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../register.php?error=sqlerror");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      if ($resultCheck > 0) {
        header("Location: ../register.php?error=usernametaken&email=".$email);
        exit();
      }
      else {
        $sql = "SELECT email FROM users WHERE email=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../register.php?error=sqlerror");
          exit();
        }
        else{
          mysqli_stmt_bind_param($stmt, "s", $email);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_store_result($stmt);
          $resultCheck = mysqli_stmt_num_rows($stmt);
          if ($resultCheck > 0) {
            header("Location: ../register.php?error=emailtaken&username=".$username);
            exit();
          }
          else {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
              header("Location: ../register.php?error=sqlerror");
              exit();
            }
            else{
              session_start();
              $hashedpass = password_hash($password, PASSWORD_DEFAULT);
              mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedpass);
              mysqli_stmt_execute($stmt);
              $sql = "SELECT * FROM users WHERE username='$username'";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../register.php?error=sqlerror");
                exit();
              }
              else {
                $result = mysqli_query($conn, $sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)) {
                  $_SESSION['id'] = $row['id'];
                  $_SESSION['username'] = $row['username'];
                  $_SESSION['email'] = $row['email'];
                  $_SESSION['bio'] = $row['bio'];
                  $_SESSION['loc'] = $row['location_from'];
                  $_SESSION['bday'] = $row['bday'];

                  $uid = $_SESSION['id'];
                  $defaultimgid = 1;
                  $sql = "INSERT INTO profileimg (id, userid, status) VALUES (?, ?, ?)";
                  $stmt = mysqli_stmt_init($conn);
                  if(!mysqli_stmt_prepare($stmt, $sql)) {
                    var_dump($_SESSION);
                    var_dump($sql);
                  }
                  else{
                    mysqli_stmt_bind_param($stmt, "iii", $uid, $uid, $defaultimgid);
                    mysqli_stmt_execute($stmt);

                    $sql = "INSERT INTO user_details (user_id) VALUES (?)";
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                      var_dump($conn);
                      var_dump($sql);
                    }
                    else{
                      mysqli_stmt_bind_param($stmt, "i", $uid);
                      mysqli_stmt_execute($stmt);
                      if(isset($_POST['English'])){
                        $subject = "English";
                        if(isset($_POST['Composition'])){
                          $subtopic = "Composition";
                          $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                          $stmt = mysqli_stmt_init($conn);
                          if(!mysqli_stmt_prepare($stmt, $sql)){
                            echo "something went wrong with choosing your subject";
                          }
                          else{
                            mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                            mysqli_stmt_execute($stmt);
                          }
                        }
                          if(isset($_POST['Writing'])){
                            $subtopic = "Writing";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['OlderLiterature'])){
                            $subtopic = "Older Literature";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['ModernLiterature'])){
                            $subtopic = "Modern Literature";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                               "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                        }
                        if(isset($_POST['Math'])){
                          $subject = "Math";
                          if(isset($_POST['Algebra'])){
                            $subtopic = "Algebra";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Geometry'])){
                            $subtopic = "Geometry";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Trigonometry'])){
                            $subtopic = "Trigonometry";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Calculus'])){
                            $subtopic = "Calculus";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Quadratics'])){
                            $subtopic = "Quadratics";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                        }
                        if(isset($_POST['Science'])){
                          $subject = "Science";
                          if(isset($_POST['Chemistry'])){
                            $subtopic = "Chemistry";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Biology'])){
                            $subtopic = "Biology";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Physics'])){
                            $subtopic = "Physics";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['ScientificMethodology'])){
                            $subtopic = "Scientific Methodology ";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                        }
                        if(isset($_POST['SocialStudies'])){
                          $subject = "Social Studies";
                          if(isset($_POST['WorldHistory'])){
                            $subtopic = "World History";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['USGovernment'])){
                            $subtopic = "U.S. Government";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Economics'])){
                            $subtopic = "Economics";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Geography'])){
                            $subtopic = "Geography";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                          if(isset($_POST['Culture'])){
                            $subtopic = "Culture";
                            $sql = "INSERT INTO users_subject (user_id, subject, subtopic) VALUES (?,?,?);";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                              echo "something went wrong with choosing your subject";
                            }
                            else{
                              mysqli_stmt_bind_param($stmt, "iss", $uid, $subject, $subtopic);
                              mysqli_stmt_execute($stmt);
                            }
                          }
                        }
                      $_SESSION['username'] = $username;
                      $_SESSION['login_details_id'] = mysqli_insert_id($conn);
                      header('Location: ../profiles/autoprofilepage.php');
                      exit();
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
else {
  session_start();
  $_SESSION['username'] = $username;
  header("Location: ../index.php");
  exit();
}
?>
