<?php
#https://www.sanwebe.com/2014/08/css-html-forms-designs
#https://github.com/bboyairwreck/phpCrawler
#http://www.salvex.com/listings/index.cfm?catID=1280®ID=0&mmID=0&orderBy=1&order=0&filterWithin=&filterRegionID=&filterMfrID=&filterModelID=
//our fopen, fgets here
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
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <ul style="list-style-type: none;float:right;">
    <li style="color:#216288"><span class="glyphicon glyphicon-envelope"></span> <b><?php echo $userRow['userName']; ?></b></li>
    <li><a href="Logout.php?logout">Logout</a></li>
  </ul>
  <br>
<form class="form-style-9" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <ul>
  <li><input type="text" name="url" value="" class="field-style field-full align-none" placeholder="URL"></li>
  <li><input type="text" name="depth" value="" class="field-style field-full align-none" placeholder="Depth"></li>
  <li><input type="text" name="email" value="" class="field-style field-full align-none" placeholder="Email"></li>
  <li><input type="text" name="keyword" value="" class="field-style field-full align-none" placeholder="Keywords (Seperated by comma)"></li>
  <li><input type="submit" value="Submit"></li></ul>
</form>
</body>
</html>
<?php
$criteria=mysqli_query($conn,"SELECT * FROM CRITERIA WHERE USER='".$userRow['userName']."'");
if(mysqli_num_rows($criteria)>0)
{
  echo "<table border=1>
    <tr><td>URL</td><td>Depth</td><td>Email</td><td>Keyword</td><td>Edit</td><td>Delete</td></tr>";
while($criteriaresult=mysqli_fetch_array($criteria))
{echo "<tr><td>".$criteriaresult['URL']."</td>";
echo "<td>".$criteriaresult['DEPTH']."</td>";
echo "<td>".$criteriaresult['EMAIL']."</td>";
echo "<td>".$criteriaresult['KEYWORDS']."</td>";
echo "<td> Edit </td>";
echo "<td> Delete </td></tr>";
}
echo "</table>";
}
$currentContent="";
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $user=$userRow['userName'];
  $url=$_POST['url'];
  $depth=$_POST['depth'];
  $email=$_POST['email'];
  $key=$_POST['keyword'];
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "web_crawl";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO CRITERIA(USER,URL,DEPTH,EMAIL,KEYWORDS) VALUES ('$user','$url','$depth','$email','$key')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
	/**
	 * This is a simple PHP web crawler that allows the developer to control exactly what is
	 * parsed and indexed. This is fit for sites that the developer knows exactly the format of
	 * what he or she is crawling.
	 */
    ini_set('max_execution_time', 300); //Increase max run time so server doesnt kill it; 300 seconds = 5 mins

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
function crawl_page($url, $depth)
{   global $currentContent;
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
      //$currentContent.="Depth: ".$depth."<br>URL: ".$url.PHP_EOL."<br>CONTENT:<br>".PHP_EOL.$dom->saveHTML().PHP_EOL.PHP_EOL."<hr><br>";
      crawl_page($href, $depth - 1);
    }
    //echo "Depth: ".$depth."<br>URL: ".$url.PHP_EOL."<br>CONTENT:<br>".PHP_EOL.$dom->saveHTML().PHP_EOL.PHP_EOL."<hr><br>";
    $currentContent.="Depth: ".$depth."<br>URL: ".$url.PHP_EOL."<br>CONTENT:<br>".PHP_EOL.$dom->saveHTML().PHP_EOL.PHP_EOL."<hr><br>";
    //echo "Depth: ",$depth,"<br>URL: ",$url,PHP_EOL,"<br>CONTENT:<br>",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL,"<hr><br>";
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
$keyword=explode(",",$_POST['keyword']);
if($oldContent && $newContent != $oldContent) {
  // Build simple email content
#$newContent=str_highlight($newContent,$keyword);
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
{$diff = get_decorated_diff($old_con[$i], $new_con[$i]);
$diff['new'] = str_highlight($diff['new'],$keyword);
$label="false";
foreach ($keyword as $word)
{
  if(stripos($diff['new'],$word))
  $label=true;
}
if($label=="true")
{$percentage=similar_text($diff['old'], $diff['new'], $percent);
$emailContent.="The following page has changed!<br>".extractString($new_con[$i],'URL:','CONTENT:')."<br>".
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
}
//echo $emailContent;
  	// Save new content
  	file_put_contents($savePath.$fileName,$newContent);


  // Send the email if there's content!
  if($emailContent) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
     $headers .= 'From: '.$_POST['email'] . "\r\n";
  	// Sendmail!
  	mail("programmer2@partslogistics.com","Sites Have Changed!",$emailContent,$headers);
  	// Debug
  	echo $emailContent;
  }
}
}
?>
