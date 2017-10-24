<?php
$time_start = microtime(true);
include('simple_html_dom.php');
$savePath = "cache-pages/";
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
      crawl_page($href, $depth - 1);
    }
    $currentContent.="Depth: ".$depth."<br>URL: ".$url.PHP_EOL."<br>CONTENT:<br>".PHP_EOL.$dom->saveHTML().PHP_EOL.PHP_EOL."<hr><br>";
}
// $urls=array(array('http://gsaauctions.gov/gsaauctions/gsaauctions/',1),
// array('http://www.govliquidation.com/events',1),
// array('http://www.publicsurplus.com/sms/browse/cataucs?catid=19',1),
// array('https://www.govdeals.com/index.cfm?fa=Main.AdvSearchResultsNew&searchPg=Category&additionalParams=true&sortOption=ad&timing=BySimple&timingType=&category=03',1),
// array('http://cla.aero/engines/',1),
// array('http://www.networkintl.com/BrowseLots.aspx?categoryid=448&pageindex=1&numrows=50',1),
// array('https://www.proxibid.com/asp/AuctionsByCompany.asp?ahid=743',1),
// array('https://www.proxibid.com/asp/SearchAdvanced_i.asp?searchTerm=aviation&category=all+categories#searchid=0&type=lot&search=aviation&sort=relevance&view=gallery&length=25&start=1&refine=',1),
// array('http://www.salvex.com/listings/index.cfm?catID=1280&regID=0&mmID=0&orderBy=1&order=0&filterWithin=&filterRegionID=&filterMfrID=&filterModelID=',1),
// array('http://www.salvex.com/listings/index.cfm?catID=1464&regID=0&mmID=0&orderBy=1&order=0&filterWithin=&filterRegionID=&filterMfrID=&filterModelID=',1));
//for ($row = 0; $row <10; $row++) {
echo $_POST['url']."&nbsp;".$_POST['depth']."<br>";
crawl_page($_POST['url'], $_POST['depth']);
$fileName = md5($_POST['url'].$_POST['depth']);
$oldContent = "";
$newContent=$currentContent;
// If a cached file exists
if(file_exists($savePath.$fileName)) {
  // Retrieve the old content
  $oldContent = file_get_contents($savePath.$fileName);
}
// Save new content
file_put_contents($savePath.$fileName,$newContent);
//}
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
echo '<b>Total Execution Time:</b> '.$execution_time.'<br>';
 ?>
