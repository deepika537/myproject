<?php
/*
* iTech Empires:  How to Import Data from CSV File to MySQL Using PHP Script
* Version: 1.0.0
* Page: Import.PHP
*/

// Database Connection
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
//LOAD DATA LOCAL INFILE '$file' REPLACE INTO TABLE products FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\\' IGNORE 1 LINES (aw_product_id,merchant_id,merchant_image_url,aw_deep_link,description,in_stock,merchant_name,brand_name,display_price,product_name,rrp_price,merchant_category
// View records from the table
$output = '';
$query = "SELECT * FROM CRITERIA WHERE USER='".$userRow['userName']."'";
if (!$resultsec = mysqli_query($conn, $query)) {
    exit(mysqli_error($conn));
}
$output .= '
     <div class="table-responsive">
          <table class="fixed table table-bordered display" id="myTable">
               <thead>
               <tr>
                    <th width="30px"><input type="checkbox" id="selectall"/></th>
                    <th width="30px" style="display:none">Id</th>
                    <th width="30px"></th>
                    <th width="145px">URL</th>
                    <th width="45px">Depth</th>
                    <th width="150px">Crawled time</th>
                    <th width="150px">Alerts</th>
                    <th width="150px">Schedule</th>
                    <th width="150px">Results</th>
                    <th width="30px" style="display:none">Email</th>
                    <th width="30px" style="display:none">keyword1</th>
                    <th width="30px" style="display:none">keyword2</th>
                    <th width="30px" style="display:none">keyword3</th>
                    <th width="30px" style="display:none">keyword4</th>
                    <th width="30px" style="display:none">logic1</th>
                    <th width="30px" style="display:none">logic2</th>
                    <th width="30px" style="display:none">logic3</th>
                    <th width="30px" style="display:none">mark_read</th>
                    <th width="30px" style="display:none">sendemail</th>
                    <th width="30px" style="display:none">email</th>
                    <th width="150px">Properties</th>
               </tr></thead><tbody>';
if(mysqli_num_rows($resultsec) > 0)
{//$number = 1;
     while($row = mysqli_fetch_array($resultsec))
     {
          $output .= '
              <tr>
                    <td><input type="checkbox" name="box-check" class="sub_chk" data-id15="'.$row["ID"].'"/></td>
                    <td style="display:none">'.$row["ID"].'</td>
                    <td class="details-control"></td>
                    <td class="url" data-id1="'.$row["ID"].'"><a href="'.$row["URL"].'" target="_blank">'.$row["URL"].'</a></td>
                    <td class="Depth" data-id2="'.$row["ID"].'">'.$row["DEPTH"].'</td>
                    <td class="Status" data-id3="'.$row["ID"].'">'.$row["Status"].'</td>
                    <td class="Alerts" data-id4="'.$row["ID"].'">'.$row["Alerts"].'</td>
                    <td class="Schedule" data-id5="'.$row["ID"].'">'.$row["Cronvalue"].'</td>
                    <td class="Results" data-id6="'.$row["ID"].'"><a id=data-id6'.$row["ID"].' href="Results/'.md5($row["URL"].$row["DEPTH"]).'.html" target="_blank">'.$row["Results"].'</a></td>
                    <td style="display:none" class="Email" data-id7="'.$row["ID"].'">'.$row["EMAIL"].'</td>
                    <td style="display:none" class="keyword1" data-id8="'.$row["ID"].'">'.$row["keyword1"].'</td>
                    <td style="display:none" class="keyword2" data-id9="'.$row["ID"].'">'.$row["keyword2"].'</td>
                    <td style="display:none" class="keyword3" data-id10="'.$row["ID"].'">'.$row["keyword3"].'</td>
                    <td style="display:none" class="keyword4" data-id11="'.$row["ID"].'">'.$row["keyword4"].'</td>
                    <td style="display:none" class="logic1" data-id12="'.$row["ID"].'">'.$row["logic1"].'</td>
                    <td style="display:none" class="logic2" data-id13="'.$row["ID"].'">'.$row["logic2"].'</td>
                    <td style="display:none" class="logic3" data-id14="'.$row["ID"].'">'.$row["logic3"].'</td>
                    <td style="display:none" class="mark_read" data-id17="'.$row["ID"].'">'.$row["Mark_read"].'</td>
                  <td style="display:none" class="sendemail" data-id18="'.$row["ID"].'">'.$row["sendemail"].'</td>
                  <td style="display:none" class="email" data-id19="'.$row["ID"].'">'.$row["EMAIL"].'</td>
                    <td class="Properties" data-id16="'.$row["ID"].'"><button name="properties_btn" data-toggle="modal" data-target="#propertiesModal" class="btn btn-primary active btn_properties">Edit</button></td>
               </tr>
          ';
          //$number++;
     }
}
else
{
  $output .= '
       <tr>
            <td></td>
            <td style="display:none"></td>
            <td></td>
            <td id="url"></td>
            <td id="Depth"></td>
            <td id="Status"></td>
            <td id="Alerts"></td>
            <td id="Schedule"></td>
            <td id="Results"></td>
            <td style="display:none" id="Email"></td>
            <td style="display:none" id="keyword1"></td>
            <td style="display:none" id="keyword2"></td>
            <td style="display:none" id="keyword3"></td>
            <td style="display:none" id="keyword4"></td>
            <td style="display:none" id="logic1"></td>
            <td style="display:none" id="logic2"></td>
            <td style="display:none" id="logic3"></td>
            <td style="display:none" id="mark_read"></td>
            <td style="display:none" id="sendemail"></td>
            <td style="display:none" id="email"></td>
            <td id="Properties"></td>
       </tr>
  ';
     $output .= '<tr>
                         <td colspan="7">Data not Found. Please add search criteria to start crawling process</td>
                    </tr>';
}
$output .= '</tbody></table>
     </div>';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webcrawler</title>
    <!-- Bootstrap CSS File  -->
    <!--link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css"/-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
</head>
<body>
<div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-0" style="width:fit-content;">
        <table><tr>
        <!--td><button name="delete_btn" data-toggle="modal" data-target="#myModal" class="btn btn-warning btn_filter">Filter By</button>&nbsp;</td-->
        <td><button type="button" name="btn_mark" id="btn_mark" class="btn btn-warning">Mark as Read</button>&nbsp;</td>
        <td><button type="button" name="btn_add" id="btn_add" class="btn btn-success">Add</button>&nbsp;</td>
        <td><button type="button" name="delete_btn" data-id5="'.$row["ID"].'" class="btn btn-danger btn_delete">Delete</button>&nbsp;</td>
        <td><button type="button" name="btn_import" id="btn_import" class="btn btn-info"
onClick="document.location.href='importcsv.php'">Import</button></td>
        <td>&nbsp;<button type="button" name="start-crawl" id="start-crawl" data-id6="'.$row["ID"].'" class="btn btn-primary startcrawl">Start</button></td>
        <!--td>&nbsp;<button type="button" name="edit-info" id="edit-info" data-id7="'.$row["ID"].'" class="btn btn-success">Edit</button></td-->
        <td>
          <form style="margin-top: 15px;margin-left:-30px;" class="form-horizontal" action="functions.php" method="post" name="upload_excel"
                    enctype="multipart/form-data">
                <div class="form-group">
                          <div class="col-md-4 col-md-offset-4">
                              <input type="submit" name="Export" class="btn btn-success" value="Export CSV"/>
                          </div>
                 </div>
             </form>
           </td>
       </tr></table>
             <br>
        </div>
    </div>
</div>
<div class="modal" id="propertiesModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content" style="width: 800px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Properties</h4>
      </div>
      <div class="modal-body">
      <table>
      <tr style="display:none"><td>ID:&nbsp;</td><td><input type="text" id="editid" value=""></td></tr>
      <tr><td>URL:&nbsp;</td><td><input type="text" id="editurl" value=""></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td>Alerts:&nbsp;</td><td><select id="editalert"><option value="Keyword">Keyword</option><option value="Anychange">Anychange</option></select></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr id="editdepthrow"><td>Depth:&nbsp;</td><td><input type="text" id="editdepth" value=""></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td width="200px">Schedule: </td><td width="600px"><div id="editexample1"></div></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td>cron value:&nbsp;</td><td><span class="example-text" id="editCronvalue"></span></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td><input type="checkbox" id="editresults">&nbsp;</td><td>Send me a copy of the results</td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr id="editemailrow" style="display:none"><td>Email:&nbsp;</td><td><input type="text" id="editemail" value=""></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td colspan="2">Enter Keywords separated by comma&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
      <tr><td colspan="2"><textarea id="kword1" class="form-control input-sm"  type="text"></textarea></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
     <tr><td>
     <select id="select1">
      <option value="&&">AND</option>
      <option value="||">OR</option>
      <option value="&&!">NOT</option>
     </select>
     </td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td colspan="2"><textarea id="kword2" class="form-control input-sm"type="text"></textarea></td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td>
      <select id="select2">
      <option value="||">OR</option>
      <option value="&&">AND</option>
      <option value="&&!">NOT</option>
      </select>
     </td></tr>
     <tr><td colspan="2">&nbsp;</td></tr>
     <tr><td colspan="2"><textarea id="kword3" class="form-control input-sm"  type="text"></textarea></td></tr>
     <tr><td colspan="2">&nbsp;</td></tr>
     <tr><td>
      <select id="select3">
      <option value="&&!">NOT</option>
      <option value="&&">AND</option>
      <option value="||">OR</option>
     </select>
    </td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td colspan="2"><textarea id="kword4" class="form-control input-sm"   type="text"></textarea></td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success saveProperties">Update</button>
      </div>
    </div>
  </div>
</div>
<!--insertdiv-->
<div class="modal fade" id="insertModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content" style="width:800px">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New Information</h4>
      </div>
      <div class="modal-body">
      <table>
      <tr><td>URL:&nbsp;</td><td><input type="text" id="inserturl" value=""></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td>Alerts:&nbsp;</td><td><select id="insertalert"><option value="Keyword">Keyword</option><option value="Anychange">Anychange</option></select></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr id="depthrow"><td>Depth:&nbsp;</td><td><input type="text" id="insertdepth" value=""></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td width="200px">Schedule:</td><td width="600px"><div id="insertexample1"> </div></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td>cron value:&nbsp;</td><td><span class="example-text" id="insertCronvalue"></span></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td><input type="checkbox" id="insertresults">&nbsp;</td><td>Send me a copy of the results</td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr id="emailrow" style="display:none"><td>Email:&nbsp;</td><td><input type="text" id="insertemail" value="dummy@dummy.com"></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr><td td colspan="2">Enter Keywords separated by comma&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
      <tr><td colspan="2"><textarea id="insertkword1" class="form-control input-sm"  type="text"></textarea></td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>
     <tr><td>
     <select id="insertselect1">
      <option value="&&">AND</option>
      <option value="||">OR</option>
      <option value="&&!">NOT</option>
     </select>
     </td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td colspan="2"><textarea id="insertkword2" class="form-control input-sm"type="text"></textarea></td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td>
      <select id="insertselect2">
      <option value="||">OR</option>
      <option value="&&">AND</option>
      <option value="&&!">NOT</option>
      </select>
     </td></tr>
     <tr><td colspan="2">&nbsp;</td></tr>
     <tr><td colspan="2"><textarea id="insertkword3" class="form-control input-sm"  type="text"></textarea></td></tr>
     <tr><td colspan="2">&nbsp;</td></tr>
     <tr><td>
      <select id="insertselect3">
      <option value="&&!">NOT</option>
      <option value="&&">AND</option>
      <option value="||">OR</option>
     </select>
    </td></tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr><td colspan="2"><textarea id="insertkword4" class="form-control input-sm"   type="text"></textarea></td></tr>
    </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success insertProperties">Insert</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<?php
echo $output;
ob_end_flush();?>
