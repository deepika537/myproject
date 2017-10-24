<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  URL:<br>
  <input type="text" name="url" value=""><br>
  Depth:<br>
  <input type="text" name="depth" value=""><br>
  Email:<br>
  <input type="text" name="email" value=""><br>
  <input type="submit" value="Submit">
</form>
<?php
#https://github.com/bboyairwreck/phpCrawler
#http://www.salvex.com/listings/index.cfm?catID=1280®ID=0&mmID=0&orderBy=1&order=0&filterWithin=&filterRegionID=&filterMfrID=&filterModelID=
//our fopen, fgets here
$currentContent="";
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $url=$_POST['url'];
  $depth=$_POST['depth'];
  //echo $url."<br>";
/*
include_once("simple_html_dom.php");
$target_url = $url;
$html = new simple_html_dom();
#$html->load_file($target_url);
$html=file_get_html($target_url);
foreach($html->find("a") as $link){
echo $link->href."<br />";
}
}
?>
<?php
*/
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
  /*function getSearchTermToBold($text, $words)
   {
       preg_match_all('~[A-Za-z0-9]+~', $words, $m);
       if (!$m)
           return $text;
       $re = '~(' . implode('|', $m[0]) . ')~i';
       return preg_replace($re, '<b>$0</b>', $text);
   }*/
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
$savePath = "cachedPages/";
$emailContent = "";
$fileName = md5($url);
$oldContent = "";
    function crawlPage($url, $depth,$currentContent){	// by default, the depth is set to 5

        static $visted = array();
        // Exit if saw URL or Depth is 0
        if (isset($visted[$url]) || $depth === 0) {
            return $currentContent;
        }
        // Mark current URL as seen
        $visted[$url] = true;
        // Download HTML
        $html = file_get_html($url);
        //$keyword="A250 aeroplane AH-1 AH-64  aircraft airframe airplane Allison AS365 Dauphin auction aviation Aviation Fueling Directory Aviation Museums Avionics Bell 205 Bell 206 Bell 212 Bell 214 Bell 412 blades Blades Boeing C20B CH-47 CH-47  CH-53  Chinook Cobra driveshaft engine,Eurocopter AS350,FLIR unit,fuel cell,Fuel Control,fuselage,gearbox,Ground Support Equipment (GSE) Helicopter hub assembly Huey Hughs,J85  Jet Ranger JT8  JT9  Kamon Kiowa Long Ranger LTS101  Lycoming M250 main //rotor blades MR Blade main rotor hub MR Hub MD500 OH-58 OH-58 OH-6 Pratt and Whitney PT-6 PT6 Rolls Royce servos Sikorsky skids surplus swashplate assembly T53 T53-L-13B T53-L-703 T55 T56  T58 T63 T63  T700  tail rotor blades TR Blades tail rotor hub TR Hub tailboom transmission turbine Turboprop UH1 UH-1 UH-1H UH-60 UH60  vertical stabilizer wire strike kit";
        // **Do what you want with the HTML here**
        echo "URL: ".$url."<br>";
        echo "Depth: ".$depth."<br>";
        $currentContent.=$html->plaintext;
        //indexPage($html);
        // Get Anchor tags
        $anchors = $html->find('a');
        // Get URL's of each Anchor
        foreach ($anchors as $anchor) {
            $href = $anchor->href;  // get href
            // $href = getAbsURL($href, $url);  // check for relative urls
            // Crawl to next page
            if(strpos($href,'http')!== false||strpos($href,'https')!== false)
             {
               //if(@file_get_contents($href)!==false)
               return crawlPage($href, ($depth-1),$currentContent);
             }
            else {
              //if(@file_get_contents($url.$href)!==false)
              return crawlPage($url.$href, ($depth-1),$currentContent);
          }

        }
      }
    // Here is where you can utilize the HTML DOM conent
    // to index what you need out of the site.

    function indexPage($html) {
        echo "Title: ".$html->find('title', 0)->innertext."<br>";
        echo "<br><br>";// I'm echoing the title here but this is where you may
    												// want to link what you want to index into a SQL database
        // sqlQuery('INSERT INTO table .... VALUES ($title)');
    }


    // execute crawl function for google.com example
    $newContent=crawlPage($url, $depth,$currentContent);
    echo $newContent;
    // If a cached file exists
  	if(file_exists($savePath.$fileName)) {
  		// Retrieve the old content
  		$oldContent = file_get_contents($savePath.$fileName);
  	}
#$oldContent="rryyuu";
#$newContent="rryyffg";
  function get_decorated_diff($old, $new){
    $from_start = strspn($old ^ $new, "\0");
    $from_end = strspn(strrev($old) ^ strrev($new), "\0");

    $old_end = strlen($old) - $from_end;
    $new_end = strlen($new) - $from_end;

    $start = substr($new, 0, $from_start);
    $end = substr($new, $new_end);
    $new_diff = substr($new, $from_start, $new_end - $from_start);
    $old_diff = substr($old, $from_start, $old_end - $from_start);

    $new = "$start<ins style='background-color:#ccffcc'>$new_diff</ins>$end";
    $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
    #$new = "<ins style='background-color:#ccffcc'>$new_diff</ins>";
    #$old = "<del style='background-color:#ffcccc'>$old_diff</del>";
    return array("old"=>$old, "new"=>$new);
}
// If different, notify!
if($oldContent && $newContent != $oldContent) {
  // Here's where we can do a whoooooooooooooole lotta stuff
  // We could tweet to an address
  // We can send a simple email
  // We can text ourselves

  // Build simple email content
$diff = get_decorated_diff($oldContent, $newContent);
$percentage=similar_text($oldContent, $newContent, $percent);
$emailContent = "Hello, the following page has changed!<br>".$url."<br>".
"<br>change percentage ".$percentage."%".
"<table>
    <tr>
        <td>old content</td>
    </tr>
    <tr>
        <td>".$diff['old']."</td>
    </tr>
    <tr>
        <td>new content</td>
    </tr>
    <tr>
        <td>".$diff['new']."</td>
    </tr>
</table>";
}
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
?>
