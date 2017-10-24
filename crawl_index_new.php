<?php
require_once 'dbconnect.php';
$sql = "SELECT * FROM childurls where url_id=".$_POST['urlid'];
 	$res = mysqli_query($conn, $sql);
 	$returnarray = array();
 	while($row = mysqli_fetch_assoc($res)) {
 		$rowarray = array(
 			"childurl"=> $row['childurl']
 		);
 		$returnarray[] = $rowarray;
 	}
 	mysqli_free_result($res);
 	echo json_encode($returnarray);
 ?>
