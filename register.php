<!DOCTYPE html>
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
      <h1>Create an Account</h1>
      <?php
        if (isset($_GET['error'])) {
          if ($_GET['error'] == 'emptyfields') {
            echo '<p class="signuperror">Fill in all fields!</p>';
          }elseif ($_GET['error'] == 'invalidemail') {
            echo '<p class="signuperror">Invalid email address!</p>';
          }
          elseif ($_GET['error'] == 'invaliduseremail') {
            echo '<p class="signuperror">Invalid email address and username!</p>';
          }
          elseif ($_GET['error'] == 'invalidusername') {
            echo '<p class="signuperror">Invalid username!</p>';
          }
          elseif ($_GET['error'] == 'passwordcheck') {
            echo "<p class='signuperror'>Passwords don't match!</p>";
          }
          elseif ($_GET['error'] == 'passwordreq') {
            echo '<p class="signuperror">Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.</p>';
          }
        }
       ?>
      <div class="infofields" id="infofields">
        <form method="post" action="subjectChoose.php" class="login">
          <div class="form-group mx-auto;">
            <input type="text" name="username" class="form-control w-50 m-auto" placeholder="Username" value="" required>
          </div>

          <div class="form-group ">
            <input type="email" name="email" class="form-control w-50 m-auto" placeholder="Email" value="" required>
          </div>

          <div class="form-group ">
            <input type="password" class="form-control w-50 m-auto" name="password" placeholder="Password"required>
          </div>

          <div class="form-group ">
            <input type="password" class="form-control w-50 m-auto" name="confirmpassword" placeholder="Confirm Password"required>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-success form-control w-25 m-auto" class="submit_button" name="register">Register</button>
          </div>
        </form>
      <h3 id="login_link">Already have an account? <a href="login.php">Click here</a> to sign in.</h3>
    </div>
  </div>
</div>
</body>
</html>
