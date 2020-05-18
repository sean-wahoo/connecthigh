<?php

if(!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirmpassword'])){
  header("Location: register.php");
}
if($_SERVER['HTTP_REFERER'] != "http://localhost/register.php"){
  header("Location: register.php");
}


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$password2 = $_POST['confirmpassword'];
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
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
} elseif (!$password == $password2) {
header("location: ../register.php?error=passwordcheck&username=".$username."&email=".$email);
exit();
} elseif(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) <= 8) {
  header("location: ../register.php?error=passwordreq&username=".$username."&email=".$email);
  exit();
}
else{


  session_start();

  $_SESSION['REGISTER_username'] = $username;
  $_SESSION['REGISTER_email'] = $email;
  $_SESSION['REGISTER_password'] = $password;
  $_SESSION['REGISTER_confirmpassword'] = $password2;

}
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <link href="index.css" rel="stylesheet" />
  <script src="showSelectOptions.js"></script>
  <script src="maxSelections.js" defer></script>
  <style>
  body, html {
    height:100vh;
    background: url("images/register_background.jpeg") no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  }
  </style>
  <title>Create an Account</title>
</head>
<body class="register">
  <div class="logincontainer">
    <img id="logo_register" class="mt-5" src="images/white_logo_transparent_background.png" height="96" alt="connecthigh">
    <div class="loginwindow">
      <h1 class="text-center">Select your best subjects</h1>
      <p class="text-center lead">To encourage help and participation, new users are required to select at least one general subject they are proficient with and
       at least two more specific areas of that subject so that those seeking help can more easily find the right fit.</p>
      <div class="infofields" id="infofields">
        <form name="subjectSubmit" id="subjectSubmit" action="includes/signup.php" method="post">
          <div class="form-check p-0">
            <input type="checkbox" name="English" class="form-check-input English" id="English" onclick="showEnglishSubtopics(this)"/>
            <label for="English" class="form-check-label h5">English</label>
          </div>
          <div id="englishSubtopicSelection" style="display:none;" class="form-check p-0 mt-2">
            <input type="checkbox" name="Composition" class="form-check-input englishSub" value="Composition" id="Composition" />
            <label for="Composition" class="form-check-label">Composition</label>
              <br />
            <input type="checkbox" name="Writing" class="englishSub form-check-input " value="Writing" id="Writing" />
            <label for="Writing" class="form-check-label">Writing</label>
              <br />
            <input type="checkbox" name="OlderLiterature" class="form-check-input englishSub" value="Older Literature" id="OlderLiterature" />
            <label for="OlderLiterature" class="form-check-label">Older Literature</label>
              <br />
            <input type="checkbox" name="ModernLiterature" class="form-check-input englishSub" value="Modern Literature" id="ModernLiterature" />
            <label for="ModernLiterature" class="form-check-label">Modern Literature</label>
              <br />
          </div>
            <br />
          <div class="form-check p-0">
            <input type="checkbox" name="Math" class="form-check-input Math" id="Math" class="Math" onclick="showMathSubtopics(this)"/>
            <label for="Math" class="form-check-label h5">Math</label>
          </div>
          <div id="mathSubtopicSelection" class="form-check p-0 mt-2" style="display:none;">
            <input type="checkbox" name="Algebra" class="form-check-input mathSub" id="Algebra" />
            <label for="Algebra" class="form-check-label mathSub">Algebra</label>
              <br />
            <input type="checkbox" name="Geometry" class="form-check-input mathSub" id="Geometry" />
            <label for="Geometry" class="form-check-label">Geometry</label>
              <br />
            <input type="checkbox" name="Trigonometry" class="form-check-input mathSub" id="Trigonometry" />
            <label for="Trigonometry" class="form-check-label">Trigonometry</label>
              <br />
            <input type="checkbox" name="Calculus" class="form-check-input mathSub" id="Calculus" />
            <label for="Calculus" class="form-check-label">Calculus</label>
              <br />
            <input type="checkbox" name="Quadratics" class="form-check-input mathSub" id="Quadratics" />
            <label for="Quadratics" class="form-check-label">Quadratics</label>
              <br />
          </div>
            <br />
          <div class="form-check p-0">
            <input type="checkbox" name="Science" class="form-check-input Science" id="Science" onclick="showScienceSubtopics(this)"/>
            <label for="Science" class="form-check-label h5">Science</label>
          </div>
          <div id="scienceSubtopicSelection" style="display:none;" class="form-check p-0 mt-2">
            <input type="checkbox" name="Chemistry" class="form-check-input scienceSub" id="Chemistry" />
            <label for="Chemistry" class="form-check-label">Chemistry</label>
              <br />
            <input type="checkbox" name="Biology" class="form-check-input scienceSub" id="Biology" />
            <label for="Biology" class="form-check-label">Biology</label>
              <br />
            <input type="checkbox" name="Physics" class="form-check-input scienceSub" id="Physics" />
            <label for="Physics" class="form-check-label">Physics</label>
              <br />
            <input type="checkbox" name="ScientificMethodology" class="form-check-input scienceSub" id="ScientificMethodology" />
            <label for="ScientificMethodology" class="form-check-label">Scientific Methodology</label>
              <br />
          </div>
            <br />
          <div class="form-check p-0">
            <input type="checkbox" name="SocialStudies" class="form-check-input SocialStudies" id="SocialStudies" onclick="showSocialStudiesSubtopics(this)"/>
            <label for="SocialStudies" class="form-check-label h5">Social Studies</label>
          </div>
          <div id="socialStudiesSubtopicSelection"style="display:none;" class="form-check p-0 mt-2">
            <input type="checkbox" name="WorldHistory" class="form-check-input sstudiesSub" id="WorldHistory" />
            <label for="WorldHistory" class="form-check-label">World History</label>
              <br />
            <input type="checkbox" name="USGovernment" class="form-check-input sstudiesSub" id="USGovernment" />
            <label for="USGovernment" class="form-check-label">U.S. Government</label>
              <br />
            <input type="checkbox" name="Economics" class="form-check-input sstudiesSub" id="Economics" />
            <label for="Economics" class="form-check-label">Geography</label>
              <br />
            <input type="checkbox" name="Geography" class="form-check-input sstudiesSub" id="Geography" />
            <label for="Geography" class="form-check-label">Economics</label>
              <br />
            <input type="checkbox" name="Culture" class="form-check-input sstudiesSub" id="Culture" />
            <label for="Culture" class="form-check-label">Culture</label>
              <br />
          </div>
            <br/>
        </div>
          <button disabled type="submit" name="register" id="submitSubjects" class="btn btn-success">Submit</button>
          <input type="hidden" name="username" value="<?php echo $_SESSION['REGISTER_username']; ?>" />
          <input type="hidden" name="email" value="<?php echo $_SESSION['REGISTER_email']; ?>" />
          <input type="hidden" name="password" value="<?php echo $_SESSION['REGISTER_password']; ?>" />
          <input type="hidden" name="confirmpassword" value="<?php echo $_SESSION['REGISTER_confirmpassword']; ?>" />
      </form>
      <h3 id="login_link">Already have an account? <a href="login.php">Click here</a> to sign in.</h3>
    </div>
  </div>
</div>
</body>
</html>
