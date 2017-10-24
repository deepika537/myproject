<?php
 ob_start();
 session_start();
 if( isset($_SESSION['user'])!="" ){
  header("Location: home.php");
 }
 include_once 'dbconnect.php';

 $error = false;

 if ( isset($_POST['btn-signup']) ) {

  // clean user inputs to prevent sql injections
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  // basic name validation
  if (empty($name)) {
   $error = true;
   $nameError = "Please enter your full name.";
  } else if (strlen($name) < 3) {
   $error = true;
   $nameError = "Name must have atleat 3 characters.";
  } else if (!preg_match("/^[a-zA-Z]+$/",$name)) {
   $error = true;
   $nameError = "Name must contain alphabets.";
  }

  //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Please enter valid email address.";
  } else {
   // check email exist or not
   $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
   $result = mysqli_query($conn,$query);
   $count = mysqli_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "Provided Email is already in use.";
   }
  }
  // password validation
  if (empty($pass)){
   $error = true;
   $passError = "Please enter password.";
  } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "Password must have atleast 6 characters.";
  }

  // password encrypt using SHA256();
  $password = hash('sha256', $pass);

  // if there's no error, continue to signup
  if( !$error ) {

   $query = "INSERT INTO users(userName,userEmail,userPass) VALUES('$name','$email','$password')";
   $res = mysqli_query($conn,$query);

   if ($res) {
    $errTyp = "success";
    $errMSG = "Successfully registered, you may login now";
    unset($name);
    unset($email);
    unset($pass);
   } else {
    $errTyp = "danger";
    $errMSG = "Something went wrong, try again later...";
   }

  }


 }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registration page</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<form method="post" class="form-style-9" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    <h3>Sign Up Form</h3>
<?php
   if ( isset($errMSG) ) {
    echo ($errTyp=="success") ? "success" : $errTyp;
    echo "<br>";
    echo $errMSG;
    }
?>
<center>
<ul>
  <li><input type="text" name="name" class="field-style field-full align-none" placeholder="Enter Name" maxlength="50" value="<?php echo $name ?>" />
  <?php echo $nameError; ?></li>
  <li><input type="email" name="email" class="field-style field-full align-none" placeholder="Enter Your Email" maxlength="40" value="<?php echo $email ?>" />
  <?php echo $emailError; ?></li>
  <li><input type="password" name="pass" class="field-style field-full align-none" placeholder="Enter Password" maxlength="15" />
  <?php echo $passError; ?></li>
  <li><input type="submit" value="Sign Up" name="btn-signup"></li>
  <li>Already have an account? <a href="login.php"> Sign in</a></li></ul>
  </center>
</form>
</body>
</html>
<?php ob_end_flush(); ?>
