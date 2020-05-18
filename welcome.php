
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name=viewport content="width=device-width initial-scale=1">

  <link href="reset.css" rel="stylesheet" />
  <link href="welcome.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Lilita+One|Oswald&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css?family=Raleway:900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans+SC:900|Raleway:900&display=swap" rel="stylesheet">
  <title>Create an Account</title>
</head>
<body>
  <div class="logincontainer">
    <img src="images/logo.png" alt="connecthigh">
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
    <div class="infofields">
      <form method="post" action="includes/signup.php" class="login">

        <input type="text" name="username" placeholder="Username" value="" required>
      <br />
        <input type="email" name="email" placeholder="Email" value="" required>
        <br />
        <input type="password" name="password" placeholder="Password"required>
        <br />
        <input type="password" name="confirmpassword" placeholder="Confirm Password"required>
        <br />
        <button type="submit" class="submit_button" name="register">Register</button>
      </form>
    <h3>Already have an account? <a href="login.php">Click here</a> to sign in.</h3>
  </div>
</div>
</div>
</body>
</html>
