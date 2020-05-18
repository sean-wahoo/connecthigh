<?php
session_start();
include 'dbh.inc.php';
$username = $_SESSION['username'];
$id = $_SESSION['id'];
if(isset($_POST['submitSubjects'])){
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
        mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
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
          mysqli_stmt_bind_param($stmt, "iss", $id, $subject, $subtopic);
          mysqli_stmt_execute($stmt);
        }
    }
  }
}
header("Location: ../index.php")
?>
