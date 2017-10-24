<?php
include_once('simple_html_dom.php');

function scraping_for_text($iUrl,$iText)
{
echo "iUrl=".$iUrl."<br />";
echo "iText=".$iText."<br />";

    // create HTML DOM
    $html = file_get_html($iUrl);

    // get text elements
    $aObj = $html->find('text');

    if (count($aObj) > 0)
    {
       echo "<h4>Found ".$iText."</h4>";
    }
    else
    {
       echo "<h4>No ".$iText." found"."</h4>";
    }
    foreach ($aObj as $key=>$oLove)
    {
      $plaintext = $oLove->plaintext;
      if (stripos($plaintext,$iText) !== FALSE)
      {
         echo $key.": text=".$plaintext."<br />"
              ."--- parent tag=".$oLove->parent()->tag."<br />"
              ."--- parent id=".$oLove->parent()->id."<br />";
      }
    }

    // clean up memory
    $html->clear();
    unset($html);

    return;
}

// -------------------------------------------------------------
// test it!

// user_agent header...
ini_set('user_agent', 'My-Application/2.5');

scraping_for_text("test_text.html","we are looking for");
?>
