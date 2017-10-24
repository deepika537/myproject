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
 $read=$_POST['read'];
 $sql = "UPDATE CRITERIA SET Mark_read='".$read."' WHERE ID IN(".$_POST["id"].")";
 if(!mysqli_query($conn, $sql))
 {
      echo 'Data not updated';
 }
 ob_end_flush();
 ?>
