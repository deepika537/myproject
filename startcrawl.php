<?php
ob_start();
session_start();
require_once 'dbconnect.php';
// if session is not set this will redirect to login page
if( !isset($_SESSION['user']) ) {
 header("Location: login.php");
 exit;
}
// select loggedin users detail
$res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
$userRow=mysqli_fetch_array($res);
$response="empty";
global $response;
  $currentContent="";
  $url=$_POST['url'];
  $depth=$_POST['Depth'];
  $file = $url;
  $file_headers = @get_headers($file);
  if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
      $exists = "page not found";
  }
  else {
      $exists = "found";
  }
  //echo "url ".$url."<br>";
  //echo "depth ".$depth;
	/**
	 * This is a simple PHP web crawler that allows the developer to control exactly what is
	 * parsed and indexed. This is fit for sites that the developer knows exactly the format of
	 * what he or she is crawling.
	 */
     //ini_set('display_erros', 'On');
    //ini_set('max_execution_time', 300); //Increase max run time so server doesnt kill it; 300 seconds = 5 mins
    //ini_set('memory_limit', '4096M');
    //ini_set('max_execution_time', 3000000);
    // Include DOM Parser which can be downloaded at:
    // 		http://simplehtmldom.sourceforge.net/manual.htm
    include('simple_html_dom.php');

    /**
     * This function downloads the DOM for the given URL and parses as defined
     * by the method indexPage().
     * @param $url is a String of a full absolute URL
     * @param $depth is a integer that defines the depth in which the crawler should parse
     */
     /**
     * Perform a simple text replace
     * This should be used when the string does not contain HTML
     * (off by default)
     */
     define('STR_HIGHLIGHT_SIMPLE', 1);

     /**
     * Only match whole words in the string
     * (off by default)
     */
     define('STR_HIGHLIGHT_WHOLEWD', 2);

     /**
     * Case sensitive matching
     * (off by default)
     */
     define('STR_HIGHLIGHT_CASESENS', 4);

     /**
     * Overwrite links if matched
     * This should be used when the replacement string is a link
     * (off by default)
     */
     define('STR_HIGHLIGHT_STRIPLINKS', 8);

     function str_highlight($text, $needle, $options = null, $highlight = null)
     {
      // Default highlighting
      if ($highlight === null) {
          $highlight = '<strong>\1</strong>';
      }

      // Select pattern to use
      if ($options & STR_HIGHLIGHT_SIMPLE) {
          $pattern = '#(%s)#';
          $sl_pattern = '#(%s)#';
      } else {
          $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
          $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';
      }

      // Case sensitivity
      if (!($options & STR_HIGHLIGHT_CASESENS)) {
          $pattern .= 'i';
          $sl_pattern .= 'i';
      }

     $needle = (array) $needle;
     foreach ($needle as $needle_s) {
          $needle_s = preg_quote($needle_s);

          // Escape needle with optional whole word check
          if ($options & STR_HIGHLIGHT_WHOLEWD) {
              $needle_s = '\b' . $needle_s . '\b';
          }

          // Strip links
          if ($options & STR_HIGHLIGHT_STRIPLINKS) {
              $sl_regex = sprintf($sl_pattern, $needle_s);
              $text = preg_replace($sl_regex, '\1', $text);
          }

          $regex = sprintf($pattern, $needle_s);
     $text = preg_replace($regex, $highlight, $text);
     }

      return $text;
     }
$savePath = "cachedPages/";
$emailContent = "";
$fileName = md5($url.$depth);
$oldContent = "";
$childurls=array();$i=0;
function crawl_page($url, $depth)
{   global $currentContent;
  global $childurls,$i;
    static $seen = array();
    if (isset($seen[$url]) || $depth === 0) {

        return;
    }
    $seen[$url] = true;

    $dom = new DOMDocument('1.0');
    @$dom->loadHTMLFile($url);

    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $element) {
        $href = $element->getAttribute('href');
        if (0 !== strpos($href, 'http')) {
            $path = '/' . ltrim($href, '/');
            if (extension_loaded('http')) {
                $href = http_build_url($url, array('path' => $path));
            } else {
                $parts = parse_url($url);
                $href = $parts['scheme'] . '://';
                if (isset($parts['user']) && isset($parts['pass'])) {
                    $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                }
                $href .= $parts['host'];
                if (isset($parts['port'])) {
                    $href .= ':' . $parts['port'];
                }
                $href .= $path;
            }
        }
        if(!in_array($href,$childurls))
        {$i++;
        $childurls[$i]=$href;}
      crawl_page($href, $depth - 1);
    }
    $currentContent.="Depth: ".$depth."<br>URL: ".$url.PHP_EOL."<br>CONTENT:<br>".PHP_EOL.$dom->saveHTML().PHP_EOL.PHP_EOL."<hr><br>";
}


    // execute crawl function for google.com example
    crawl_page($url, $depth);
    $newContent=$currentContent;
    // If a cached file exists
  	if(file_exists($savePath.$fileName)) {
  		// Retrieve the old content
  		$oldContent = file_get_contents($savePath.$fileName);
  	}
  function get_decorated_diff($old, $new){
    require_once './class.Diff.php';

    // output the result of comparing two files as a table
    $diff=Diff::compare($old, $new);
    $string1='';
    $string2='';
    foreach ($diff as $line){
          // extend the string with the line
          switch ($line[1]){
            case 1   : $string1 .= $line[0];break;
            case 2   : $string2 .= $line[0];break;
          }
    }
    return array("old"=>$string1, "new"=>$string2);
}
// If different, notify!
$purl=mysqli_query($conn,"SELECT * FROM CRITERIA WHERE URL='".$url."' and USER='".$userRow['userName']."'");
$pRow=mysqli_fetch_array($purl);
$keyword1="";$keyword2="";$keyword3="";$keyword4="";$select1="";$select2="";$select3="";
if($_POST['Alert']=="Keyword")
{if($pRow['keyword1'])
{$keyword1=explode(",",$pRow['keyword1']);}
if($pRow['keyword2'])
{$keyword2=explode(",",$pRow['keyword2']);}
if($pRow['keyword3'])
{$keyword3=explode(",",$pRow['keyword3']);}
if($pRow['keyword4'])
{$keyword4=explode(",",$pRow['keyword4']);}
if($pRow['logic1'])$select1=$pRow['logic1'];
if($pRow['logic2'])$select2=$pRow['logic2'];
if($pRow['logic3'])$select3=$pRow['logic3'];
}

// Save new content
file_put_contents($savePath.$fileName,$newContent);

error_reporting(0);

function fatalErrorHandler() {
# Getting last error
$error = error_get_last();
# Checking if last error is a fatal error
 if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)|| ($error['type'] === E_USER_NOTICE)) {
 # Here we handle the error, displaying HTML, logging, ...
 echo "ERRORnr : " . $error['type']. " |Msg : ".$error['message']." |File : ".$error['file']. " |Line : " . $error['line'];
    }else {
    echo "" ;

    }
}

# Registering shutdown function
register_shutdown_function('fatalErrorHandler');
if($exists=="found")
{

if($oldContent && $newContent != $oldContent) {
  // Build simple email content
#$newContent=str_highlight($newContent,$keyword1);
$new_con=explode("<hr><br>",$newContent);
$old_con=explode("<hr><br>",$oldContent);

// Function that returns the string between two strings.
function extractString($string, $start, $end) {
    $string = " ".$string;
    $ini = strpos($string, $start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
for($i=0;$i<sizeof($new_con);$i++)
{
$diff = get_decorated_diff($old_con[$i], $new_con[$i]);

if($diff['new'])
{
$checkStr1=0;$checkStr2=0;$checkStr3=0;$checkStr4=0;
if($keyword1!="")
{foreach($keyword1 as $key_word1)
{
  if(stripos($diff['new'],$key_word1))
  $checkStr1=1;
}
}
if($keyword2!="")
{foreach($keyword2 as $key_word2)
{
  if(stripos($diff['new'],$key_word2))
  $checkStr2 =1;
}
}
if($keyword3!="")
{foreach($keyword3 as $key_word3)
{
  if(stripos($diff['new'],$key_word3))
  $checkStr3=1;
}
}
if($keyword4!="")
{foreach($keyword4 as $key_word4)
{
  if(stripos($diff['new'],$key_word4))
  $checkStr4 =1;
}
}
//console.log($_POST['Alert']);
$val=$checkStr1.$select1.$checkStr2.$select2.$checkStr3.$select3.$checkStr4;
$result=eval("return (".$val.");");
if($_POST['Alert']=="Anychange")
 {
   $result=true;}

if($result)
{
$percentage=similar_text($diff['old'], $diff['new'], $percent);
$linkurl=extractString($new_con[$i],'URL:','<br>CONTENT:');
$emailContent.="The following page has changed!<br><a href='".$linkurl."' target=_blank>".$linkurl."</a><br>".
"<br>change percentage ".$percentage."%".
"<table>
    <tr>
        <td><b>deleted - </b></td>
    </tr>
    <tr>
        <td><div style='background-color: #faa'><strike>".$diff['old']."</strike></div></td>
    </tr>
    <tr>
        <td><b>Added + <br> </b></td>
    </tr>
    <tr>
        <td><div style='background-color: #dfd'>".$diff['new']."</div></td>
    </tr>
    <tr><td><hr><br></td></tr>
</table>";
}
else {
  $response="no new content added for searched keywords";
  echo $response;
}
// Send the email if there's content!
$emailfrom='programmer2@partslogistics.com';
//if($emailContent&&$_POST['sendemail']=="yes"&&$_POST['email']!='') {
  if($emailContent) {
  //echo $emailContent;
  $response=$emailContent;
  echo $response;
  if($_POST['sendemail']=="yes"&&$_POST['email']!='')
  {
  // Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
//$headers .= 'From: <programmer2@partslogistics.com>' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";
@mail($_POST['email'],"Sites Have Changed!",$emailContent,$headers,"programmer2@partslogistics.com");
}
}
}
}
}
else {
  $response="no new content added";
  echo $response;
}
}
else {
  $response=$exists;
  echo $response;
}
foreach($childurls as $child)
{
  if(!strpos($child,"javascript"))
{$duplicatec=mysqli_fetch_array(mysqli_query($conn,"SELECT ID FROM childurls where childurl='$child'"));
  if(!$duplicatec['ID'])
  {
    $sql = "INSERT INTO childurls(url_id,childurl) VALUES ($pRow[ID],'$child')";
    if(!mysqli_query($conn, $sql))
    {
     $response=$child."childurl not Inserted";
     echo $response;
    }
  }
}
}
//echo $response;
?>
