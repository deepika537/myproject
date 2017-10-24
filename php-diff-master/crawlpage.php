<?php
//require_once './class.Diff.php';
require_once dirname(__FILE__).'/lib/Diff.php';
require_once dirname(__FILE__).'/lib/Diff/Renderer/Html/SideBySide.php';
$start="http://dnyavanandi.pl.internal/my-first-crawler/page1.html";
$depth=0;
$already_crawled=array();
$crawling=array();
$savePath = "cache-pages/";
function get_details($url)
{
  $options=array('http'=>array('method'=>"GET",'headers'=>"User-Agent: howBot/0.1\n"));
  $context=stream_context_create($options);
  $doc=new DOMDocument();
  @$doc->loadHTML(@file_get_contents($url,false,$context));
  $body = $doc->getElementsByTagName('body')->item(0);
// perform innerhtml on $body by enumerating child nodes
// and saving them individually
$savePath = "cache-pages/";
$fileName = md5($url);
$oldContent = "";
if(file_exists($savePath.$fileName)) {
  // Retrieve the old content
  $oldContent = file_get_contents($savePath.$fileName);
}
$newContent="";
foreach ($body->childNodes as $childNode) {
  $newContent.=$doc->saveHTML($childNode);
}
//echo $oldContent;
file_put_contents($savePath.$fileName,$newContent);
if($oldContent && $newContent != $oldContent) {
  // Options for generating the diff
  $options = array(
    //'ignoreWhitespace' => true,
    //'ignoreCase' => true,
  );
  $a = explode("\n", file_get_contents($oldContent));
  $b = explode("\n", file_get_contents($newContent));

$diff=new diff($a, $b,$options);
$renderer = new Diff_Renderer_Html_SideBySide;
echo $diff->Render($renderer);
}
  /*$title=$doc->getElementsByTagName("title");
  $title=$title->item(0)->nodeValue;
  $description="";
  $keywords="";
  $metas=$doc->getElementsByTagName("meta");
  for($i=0;$i<$metas->length;$i++)
  {
   $meta=$metas->item($i);
   if($meta->getAttribute("name")==strtolower("description"))
   {$description=$meta->getAttribute("content");}
   if($meta->getAttribute("name")==strtolower("keywords"))
   {$keywords=$meta->getAttribute("content");}
  }
return '{"Title":"'.str_replace("\n","",$title).'","Description": "'.str_replace("\n","",$description).'","Keywords": "'.str_replace("\n","",$keywords).'","URL": "'.$url.'"},';*/
}
function follow_links($url,$depth)
{
  global $already_crawled;
  global $crawling;
  echo get_details($url)."<br>";
  $options=array('http'=>array('method'=>"GET",'headers'=>"User-Agent: howBot/0.1\n"));
  $context=stream_context_create($options);
  $doc=new DOMDocument();
  @$doc->loadHTML(@file_get_contents($url,false,$context));
  $linklist=$doc->getElementsByTagName("a");
  foreach($linklist as $link)
  {
    $l= $link->getAttribute("href");
    if(substr($l,0,1)=="/"&&substr($l,0,2)!="//")
    {
      $l=parse_url($url)["scheme"]."://".parse_url($url)["host"].$l;
    }
    else if(substr($l,0,2)=="//")
    {
      $l=parse_url($url)["scheme"].":".$l;
    }
    else if(substr($l,0,2)=="./")
    {
      $l=parse_url($url)["scheme"]."//".parse_url($url)["host"].dirname(parse_url($url)["path"]).substr($l,1);
    }
    else if(substr($l,0,1)=="#")
    {
      $l=parse_url($url)["scheme"]."//".parse_url($url)["host"].parse_url($url)["path"].$l;
    }
    else if(substr($l,0,3)=="../")
    {
      $l=parse_url($url)["scheme"]."//".parse_url($url)["host"]."/".$l;
    }
    else if(substr($l,0,11)=="javascript:")
    {
      continue;
    }
    else if(substr($l,0,5)!="https"&&substr($l,0,4)!="http")
    {
      $l=parse_url($url)["scheme"]."://".parse_url($url)["host"]."/".$l;
    }
    if($depth !== 0&&!in_array($l,$already_crawled))
    {
      $already_crawled[]=$l."<br>";
      $crawling[]=$l;
      follow_links($l,$depth-1);
    }
    else {
      return;
    }
  }
  array_shift($crawling);
 /*foreach($crawling as $site)
 {
   follow_links($site);
 }*/
}
follow_links($start,$depth);
print_r($already_crawled);

 ?>
