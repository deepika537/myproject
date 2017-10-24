<?php
require_once 'dbconnect.php';
$sql = "UPDATE CRITERIA SET keyword1='".$_POST['keyword1']."',keyword2='".$_POST['keyword2']."',keyword3='".$_POST['keyword3']."',keyword4='".$_POST['keyword4']."',logic1='".$_POST['oper1']."',logic2='".$_POST['oper2']."',logic3='".$_POST['oper3']."' WHERE ID=".$_POST['id'];
if (mysqli_query($conn, $sql)) {
      echo "Record updated successfully";
   } else {
      echo "Error updating record: " . mysqli_error($conn);
   }

 ?>
