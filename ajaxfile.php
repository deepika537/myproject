<?php  ?>
<html><head><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script></head><body><div id="res"></div><div id="start"></div><div id="end"></div><div id="diff">Time:</div></body></html>
<script>
$(document).ready(function(){
  var urls=[['http://gsaauctions.gov/gsaauctions/gsaauctions/',1],
  ['http://www.govliquidation.com/events',1],
  ['http://www.publicsurplus.com/sms/browse/cataucs?catid=19',1],
  ['https://www.govdeals.com/index.cfm?fa=Main.AdvSearchResultsNew&searchPg=Category&additionalParams=true&sortOption=ad&timing=BySimple&timingType=&category=03',1],
  ['http://cla.aero/engines/',1],
  ['http://www.networkintl.com/BrowseLots.aspx?categoryid=448&pageindex=1&numrows=50',1],
  ['https://www.proxibid.com/asp/AuctionsByCompany.asp?ahid=743',1],
  ['https://www.proxibid.com/asp/SearchAdvanced_i.asp?searchTerm=aviation&category=all+categories#searchid=0&type=lot&search=aviation&sort=relevance&view=gallery&length=25&start=1&refine=',1],
  ['http://www.salvex.com/listings/index.cfm?catID=1280&regID=0&mmID=0&orderBy=1&order=0&filterWithin=&filterRegionID=&filterMfrID=&filterModelID=',1],
  ['http://www.salvex.com/listings/index.cfm?catID=1464&regID=0&mmID=0&orderBy=1&order=0&filterWithin=&filterRegionID=&filterMfrID=&filterModelID=',1]];
  var start = performance.now();
  $('#start').append(start);
  for (i = 0; i < urls.length; i++) {
    $.ajax({
    url:"test50.php",
    method:"POST",
    data:{url:urls[i][0],depth:urls[i][1]},
    dd:i,
    async:false,
    dataType:"text",
    success:function(response)
    {
     $('#res').append(this.dd+" "+response);
   }
 })
 }
 var end = performance.now();
 $('#end').append(end);
 $('#diff').append(end-start);
 });
</script>
