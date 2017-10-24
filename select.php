<?php
#http://www.webslesson.info/2016/02/live-table-add-edit-delete-using-ajax-jquery-in-php-mysql.html
#https://www.sourcecodester.com/tutorials/php/6989/how-import-excelcsv-file-mysql-database-using-php.html
#https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/
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
 ?>
 <?php
 $output = '';
 $sql = "SELECT * FROM CRITERIA WHERE USER='".$userRow['userName']."'";
 $result = mysqli_query($conn, $sql);
 $output .= '
      <div class="table-responsive">
           <table class="table table-bordered">
                <tr>
                     <th width="5%">Id</th>
                     <th width="40%">URL</th>
                     <th width="5%">Depth</th>
                     <th width="20%">Email</th>
                     <th width="30%">Keywords</th>
                     <th width="3%">Delete</th>
                     <th width="10%">Crawling</th>
                     <th width="10%">Edit</th>
                </tr>';
 if(mysqli_num_rows($result) > 0)
 {
      while($row = mysqli_fetch_array($result))
      {
           $output .= '
                <tr>
                     <td>'.$row["ID"].'</td>
                     <td class="url" data-id1="'.$row["ID"].'" contenteditable=false>'.$row["URL"].'</td>
                     <td class="Depth" data-id2="'.$row["ID"].'" contenteditable=false>'.$row["DEPTH"].'</td>
                     <td class="Email" data-id3="'.$row["ID"].'" contenteditable=false>'.$row["EMAIL"].'</td>
                     <td class="Keywords" data-id4="'.$row["ID"].'" contenteditable=false>'.$row["KEYWORDS"].'</td>
                     <td><button type="button" name="delete_btn" data-id5="'.$row["ID"].'" class="btn btn-xs btn-danger btn_delete">x</button></td>
                     <td><button type="button" name="start-crawl" id="start-crawl" data-id6="'.$row["ID"].'" class="btn btn-primary">Start</button></td>
                     <td><button type="button" name="edit-info" id="edit-info" data-id7="'.$row["ID"].'" class="btn btn-success">Edit</button></td>
                </tr>
           ';
      }
      $output .= '
           <tr>
                <td></td>
                <td id="url" contenteditable></td>
                <td id="Depth" contenteditable></td>
                <td id="Email" contenteditable></td>
                <td id="Keywords" contenteditable></td>
                <td><button type="button" name="btn_add" id="btn_add" class="btn btn-xs btn-success">+</button></td>
                <td></td>
                <td></td>
           </tr>
      ';
 }
 else
 {
   $output .= '
        <tr>
             <td></td>
             <td id="url" contenteditable></td>
             <td id="Depth" contenteditable></td>
             <td id="Email" contenteditable></td>
             <td id="Keywords" contenteditable></td>
             <td><button type="button" name="btn_add" id="btn_add" class="btn btn-xs btn-success">+</button></td>
             <td></td>
             <td></td>
        </tr>
   ';
      $output .= '<tr>
                          <td colspan="8">Data not Found. Please add search criteria to start crawling process</td>
                     </tr>';
 }
 $output .= '</table>
      </div>';
 echo $output;
ob_end_flush();
 ?>
