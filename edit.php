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
 $id =$_POST['editid'];
 $editurl = $_POST["editurl"];
 $editdepth = $_POST["editdepth"];
 $editalert = $_POST["editalert"];
 $editCronvalue = $_POST["editCronvalue"];
 //$editresults = $_POST["editresults"];
 $editemail=$_POST["editemail"];
 $editkeyword1=$_POST["editkeyword1"];
 $editkeyword2=$_POST["editkeyword2"];
 $editkeyword3=$_POST["editkeyword3"];
 $editkeyword4=$_POST["editkeyword4"];
 $editlogic1=$_POST["editlogic1"];
 $editlogic2=$_POST["editlogic2"];
 $editlogic3=$_POST["editlogic3"];
 $editsendemail=$_POST['editsendemail'];
 $sql = "UPDATE CRITERIA SET URL='".$editurl."',DEPTH=".$editdepth.",Alerts='".$editalert."',Cronvalue='".$editCronvalue."',Email='".$editemail."',keyword1='".$editkeyword1."',keyword2='".$editkeyword2."',keyword3='".$editkeyword3."',keyword4='".$editkeyword4."',logic1='".$editlogic1."',logic2='".$editlogic2."',logic3='".$editlogic3."',sendemail='".$editsendemail."' WHERE ID=".$id;
 if(mysqli_query($conn, $sql))
 {
      echo 'Data Updated';
 }
 ob_end_flush();
 ?>
