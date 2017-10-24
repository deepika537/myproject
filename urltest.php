<?php
header("Content-type: text/plain");
$url = array();
$url[] = "http://www.govliquidation.com/";
$url[] = "https://gsaauctions.gov/gsaauctions/gsaauctions/";
$url[] = "http://www.publicsurplus.com/sms/browse/cataucs?catid=19";
$url[] = "https://www.govdeals.com/index.cfm?fa=Main.AdvSearchResultsNew&searchPg=Category&additionalParams=true&sortOption=ad&timing=BySimple&timingType=&category=03";
foreach ($url as $u){
    echo "\n\n". $u ."\n\n";
    print_r(get_headers($u));
}
