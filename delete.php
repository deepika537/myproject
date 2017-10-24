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
 $sql = "DELETE FROM CRITERIA WHERE ID IN(".$_POST["ids"].")";
 if(mysqli_query($conn, $sql))
 {
      echo 'Data Deleted';
 }
 else {
   echo mysqli_error($conn);
 }
 ob_end_flush();
 ?>
