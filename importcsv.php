<?php
/*
* iTech Empires:  How to Import Data from CSV File to MySQL Using PHP Script
* Version: 1.0.0
* Page: Import.PHP
*/

ini_set("auto_detect_line_endings", true);

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
 $user=$userRow['userName'];

?>


<!doctype html>
<div>
 <ul style="list-style-type: none;float:right;padding-right:50px;padding-top:10px;">
   <li style="color:#216288"><span class="glyphicon glyphicon-user"></span> <b><?php echo $userRow['userName']; ?>&nbsp;&nbsp;</b></li>
   <li><a href="Logout.php?logout">Logout</a></li>
 </ul>
</div>
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
        <br>
        <br>
        <div class="table-responsive" style="width:fit-content;">
            <h3 align="center">Import New Monitors Here</h3><br />
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-0" style="width:fit-content;">
                <table><tr>
                    <td><button type="button" name="btn_import" id="btn_import" class="btn btn-info" onClick="document.location.href='crawl_index.php'">Home</button></td>
                    <td>
                        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div style="float:left;margin-left:70px;">
                                <!--label for="file">Select .CSV file to Import</label-->
                                <input name="file" type="file" class="form-control">
                            </div>
                            <div style="float:right;margin-left: 20px;">
                                <input type="submit" name="submit" class="btn btn-info" value="Import CSV"/>
                            </div>
                        </form>
                    </td>
                </tr></table>
                <br>
            </div>
        </div>
        <div class="row">
            <!-- Progress bar holder -->
            <div id="progress" style="width:500px;border:1px solid #ccc;"></div>
            <!-- Progress information -->
            <div id="information" style="width"></div>

            <?php
                ob_end_flush();
                //Function that extracts the domian name from a url
                function get_domain($url){
                        $new_url = parse_url($url, PHP_URL_HOST);
                        $url_array = explode('.', $new_url);

                        if(count($url_array) == 1){
                                if($url_array[0] != ''){
                                        return $url_array[0];
                                }else{
                                        return 'Empty array';
                                }
                        }
                        if(count($url_array) == 2){
                                return $url_array[0];
                        }
                        if(count($url_array) == 3){
                                return $url_array[1];
                        }
                        if(count($url_array) > 3 && $url_array[0] == 'www' && end($url_array) == 'com'){
                                $url_str = '';
                                $new_array = array_slice($url_array, 1, -1);
                                foreach($new_array as $part){
                                        $url_str .= "$part.";
                                }
                                return rtrim($url_str, '.');
                        }
                        if(count($url_array) > 3 && end($url_array) == 'com'){
                                $url_str = '';
                                array_pop($url_array);
                                foreach($url_array as $part){
                                        $url_str .= "$part.";
                                }
                                return rtrim($url_str, '.');
                        }
                }

                function urlExists($url) {

                    $handle = curl_init($url);
                    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

                    $response = curl_exec($handle);
                    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

                    if($httpCode >= 200 && $httpCode < 400) {
                        return true;
                    } else {
                        return false;
                    }
                    curl_close($handle);
                }

                $message = "";
                $message1 = '
        <div class="table-responsive">
          <table class="fixed table table-bordered display" id="myTable">
               <thead>
               <tr>
                    <th width="145px">Results</th>
                    <th width="145px">Status</th>
                    <th width="45px">Depth</th>
                    <th width="145px">URL</th>
               </tr></thead><tbody>';


                if (isset($_POST['submit'])) {
                    $allowed = array('csv');
                    $filename = $_FILES['file']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    if (!in_array($ext, $allowed)) {
                        // show error message
                        $message = 'Invalid file type, please use .CSV file!';
                    } else {

                        move_uploaded_file($_FILES["file"]["tmp_name"], "files/" . $_FILES['file']['name']);

                        $file = "files/" . $_FILES['file']['name'];

                        if (($handle = fopen($file, "r")) !== FALSE) {

                            $row = 0;

                            $csv_list = array();
                            array_push($csv_list,"Results,Statue,Depth,URL");


                            $invalid_count = 0;
                            $valid_count = 0;
                            $duplicate_count = 0;
                            $sql_error_count = 0;

                            //Getting the info from the CSV file
                            $monitor_array = array();
                            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {

                                if($row == 0){
                                    $row++;
                                } else {
                                    $row++;
                                    $tmp_domain = get_domain($data[2]);
                                    $row_array = array($user, $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14], $tmp_domain);
                                    array_push($monitor_array, $row_array);
                                }
                            }
                        } else {
                            echo 'File could not be opened.';
                        }
                        fclose($handle);
                        //DB Structure
                        // $data[0]=ID; $data[1]=USER; $data[2]=URL; $data[3]=DEPTH; $data[4]=EMAIL; $data[5]=keyword1; $data[6]=keyword2; data[7] = keyword3; $data[8]=keyword4;
                        // $data[9]=Status; $data[10]=logic1; $data[11]=logic2; $data[12]=logic3; $data[13]=Alerts; $data[14]=Cronvalue; $data[15]=Results; $data[16]=Domain;
                        /*********************************************************************************************************************/
                        $total = count($monitor_array);
                        $ct = 1;
                        foreach($monitor_array as $data){

                            // Calculate the percentation
                            $percent = intval($ct/$total * 100)."%";

                            $user = $data[0];
                            $url = $data[1];
                            $depth = $data[2];
                            $email = $data[3];
                            $keyword1 = $data[4];
                            $keyword2 = $data[5];
                            $keyword3 = $data[6];
                            $keyword4 = $data[7];
                            $status=$data[8];
                            $logic1 = $data[9];
                            $logic2 = $data[10];
                            $logic3 = $data[11];
                            $insertalert = $data[12];
                            $Cronvalue = $data[13];
                            $results = $data[14];
                            $markread = $data[15];
                            $sendemail = $data[16];
                            $domain  = $data[17];

                            //$check_url = mysqli_query($conn,"SELECT * FROM CRITERIA WHERE URL = '$url' AND USER = '$user'", $conn);
                            //$url_rows = mysqli_num_rows($check_url);

                            $check_domain = mysqli_query($conn,"SELECT * FROM CRITERIA WHERE Domain = '$domain' AND USER = '$user'");
                            $domain_rows = mysqli_num_rows($check_domain);

                            //Handle invalid urls
                            if(urlExists($url) === true && $domain_rows == 0){
                                //Add the valid monitor info
                                $sql = "INSERT INTO CRITERIA(USER,URL,DEPTH,EMAIL,keyword1,keyword2,keyword3,keyword4,Status,logic1,logic2,logic3,Alerts,Cronvalue,Results,Mark_read,sendemail,Domain) VALUES ('$user','$url','$depth','$email','$keyword1','$keyword2','$keyword3','$keyword4','$status','$logic1','$logic2','$logic3','$insertalert','$Cronvalue','$results', '$markread','$sendemail','$domain')";
                                if(mysqli_query($conn,$sql)){
                                    array_push($csv_list, "Success,Valid URL,$depth,$url");
                                    $message1 .= "<tr><td>Success</td><td>VALID URL</td><td>$depth</td><td>$url</td></tr>";
                                    $valid_count++;
                                }else{
                                    array_push($csv_list, "Fail,SQL Error,$depth,$url");
                                    $message1 .= "<tr><td>Fail</td><td>SQL Error</td><td>$depth</td><td>$url</td></tr>";
                                    $sql_error_count++;
                                }
                            //Handle invalid urls here
                            }elseif(urlExists($url) === false){
                                //Record what url and domain is invalid and write somewhere
                                array_push($csv_list, "Fail,Invalid URL,$depth,$url");
                                $message1 .= "<tr><td>Fail</td><td>Invalid URL</td><td>$depth</td><td>$url</td></tr>";
                                $invalid_count++;
                            }else{
                                //Record url and write duplicate somewhere
                                array_push($csv_list, "Fail,Duplicate URL,$depth,$url");
                                $message1 .= "<tr><td>Fail</td><td>Duplicate URL</td><td>$depth</td><td>$url</td></tr>";
                                $duplicate_count++;
                            }

                            // Javascript for updating the progress bar and information
                            echo '<script language="javascript">document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";document.getElementById("information").innerHTML="'.$ct.' row(s) processed.";</script>';
                            $ct++;

                            // This is for the buffer achieve the minimum size in order to flush data
                            echo str_repeat(' ',1024*64);

                            // Send output to browser immediately
                            flush();
                        }

                        // Tell user that the process is completed
                        echo '<script language="javascript">document.getElementById("information").innerHTML="Process completed"</script>';

                        $message1 .= "</tbody></table></div>";

                        $_SESSION['array_name'] = $csv_list;

                        $results_btn = '<form action="results_export.php" method="post" name="upload_excel"enctype="multipart/form-data"><input type="submit" name="Export" class="btn btn-success" value="Export CSV"/></form>';

                        $message = '
                        <h4>Bulk Upload Complete</h4>'.$results_btn.'<br>
                        <div class="table-responsive">
                          <table class="fixed table table-bordered display" id="myTable">
                               <thead>
                                   <tr>
                                       <th>Valid URL Count</th>
                                       <th>Invalid URL Count</th>
                                       <th>Duplicate URL Count</th>
                                       <th>SQL Error Count</th>
                                   </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                       <td>'.$valid_count.'</td>
                                       <td>'.$invalid_count.'</td>
                                       <td>'.$duplicate_count.'</td>
                                       <td>'.$sql_error_count.'</td>
                                   </tr>
                               </tbody>
                            </table>
                        </div>';

                        //fwrite($myfile, "\nDuplicate URL count: $duplicate_count\n");
                        //fwrite($myfile, "Invalid URL count: $invalid_count\n");
                        //fwrite($myfile, "Valid URL count: $valid_count\n");
                        //fclose($myfile);
                    }
                }
                ?>

        </div>
        <div class="row">
            <div class="form-group">
                <?php

                    echo '<br>'; echo $message; echo $message1;

                ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php
ob_end_flush();?>
