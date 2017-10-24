<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';

 // it will never let you open index(login) page if session is set
 if ( isset($_SESSION['user'])!="" ) {
  header("Location: crawl_index.php");
  exit;
 }

 $error = false;

 if( isset($_POST['btn-login']) ) {

  // prevent sql injections/ clear user invalid inputs
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  // prevent sql injections / clear user invalid inputs

  if(empty($email)){
   $error = true;
   $emailError = "Please enter your email address.";
  } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Please enter valid email address.";
  }

  if(empty($pass)){
   $error = true;
   $passError = "Please enter your password.";
  }

  // if there's no error, continue to login
  if (!$error) {

   $password = hash('sha256', $pass); // password hashing using SHA256

   $res=mysqli_query($conn,"SELECT userId, userName, userPass FROM users WHERE userEmail='$email'");
   $row=mysqli_fetch_array($res);
   $count = mysqli_num_rows($res); // if uname/pass correct it returns must be 1 row

   if( $count == 1 && $row['userPass']==$password ) {
    $_SESSION['user'] = $row['userId'];
    header("Location: crawl_index.php");
   } else {
    $errMSG = "Incorrect Credentials, Try again...";
   }

  }

 }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login page</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<form method="post" class="form-style-9" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
<h3>Sign In Form</h3>
<?php
   if ( isset($errMSG) ) {
   echo $errMSG;
   }
?>
<center>
<ul>
<li><input type="email" name="email" class="field-style field-full align-none" placeholder="Your Email" value="<?php echo $email; ?>" maxlength="40" />
<span><?php echo $emailError; ?></span>
</li>
<li><input type="password" name="pass" class="field-style field-full align-none" placeholder="Your Password" maxlength="15" />
<span><?php echo $passError; ?></span></li>
<li><input type="submit" value="Sign In" name="btn-login"></li>
<li>Don't have an account? <a href="register.php">Sign up</a></li>
<ul>
</center>
</form>
</body>
</html>
<?php ob_end_flush(); ?>
