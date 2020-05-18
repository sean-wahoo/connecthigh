<!DOCTYPE html>
<html >
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
  <title>Log In</title>
</head>
<body class="register">
  <div class="logincontainer">
    <img id="logo_register" class="mt-5" src="images/white_logo_transparent_background.png" height="96" alt="connecthigh">
    <div class="loginwindow">
      <h1>Log In!</h1>
      <?php
        if (isset($_GET['error'])) {
          if ($_GET['error'] == 'emptyfields') {
            echo '<p class="signuperror">Fill in all fields!</p>';
          }elseif ($_GET['error'] == 'wrongpass') {
            echo '<p class="signuperror">Password is incorrect!</p>';
          }
          elseif ($_GET['error'] == 'nouser') {
            echo '<p class="signuperror">There is no account with that username or email!</p>';
          }
        }
       ?>
      <div class="infofields" id="infofields">
        <form method="post" action="includes/login.inc.php" class="login">
          <div class="form-group mx-auto;">
            <input type="text" name="userlogin" class="form-control w-50 m-auto" placeholder="Username or email" value="<?php if(isset($_COOKIE["unm"])){ echo $_COOKIE["unm"]; }?>" required>
          </div>
          <div class="form-group ">
            <input type="password" class="form-control w-50 m-auto" name="password" placeholder="Password" value="<?php if(isset($_COOKIE["pwd"])){ echo $_COOKIE["pwd"];} ?>" required>
          </div>
          <div class="form-group ">
            <input type="checkbox" name="rememberme" id="rememberme" />
            <label for="rememberme">Remember Me</label>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-success form-control w-25 m-auto" class="submit_button" name="login">Login</button>
          </div>
          <?php
            if (isset($_GET["newpass"])) {
              if ($_GET["newpass"] == "passwordchanged") {
                echo '<p class="signuperror">Your password has been reset!</p>';
              }
            }
            ?>
        </form>
      <h3 id="login_link">Don't have an account? <a href="register.php">Click here</a> to sign up.</h3>
    </div>
  </div>
</div>
</body>
</html>
