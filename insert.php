<?php
ob_start();
session_start();
 require_once 'dbconnect.php';
 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }
 // select loggedin users detail
 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 $user=$userRow['userName'];
 $url=$_POST['url'];
 $depth=$_POST['Depth'];
 $email=$_POST['Email'];
 $keyword1=$_POST['keyword1'];
 $keyword2=$_POST['keyword2'];
 $keyword3=$_POST['keyword3'];
 $keyword4=$_POST['keyword4'];
 $logic1=$_POST['logic1'];
 $logic2=$_POST['logic2'];
 $logic3=$_POST['logic3'];
 $insertalert=$_POST['insertalert'];
 $results=$_POST['results'];
 $Cronvalue=$_POST['Cronvalue'];
 $sendemail=$_POST['sendemail'];
 $duplicate=mysqli_fetch_array(mysqli_query($conn,"SELECT ID FROM CRITERIA where URL='$url'"));
   if(!$duplicate['ID'])
   {
 $sql = "INSERT INTO CRITERIA(USER,URL,DEPTH,EMAIL,keyword1,keyword2,keyword3,keyword4,logic1,logic2,logic3,Alerts,Cronvalue,Results,sendemail) VALUES ('$user','$url','$depth','$email','$keyword1','$keyword2','$keyword3','$keyword4','$logic1','$logic2','$logic3','$insertalert','$Cronvalue','$results','$sendemail')";
 if(mysqli_query($conn, $sql))
 {
      echo 'Data Inserted';
 }
 }
 else {
   echo "url already exists";
 }
 ob_end_flush();
 ?>
