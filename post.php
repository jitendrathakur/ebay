<?php

$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1?';  
$responseEncoding = 'XML';   
$version = '1.8.0';   // API version number (they're actually up to 1.11.0 at this point
$appID   = 'self56e11-173d-4020-8209-31afe61b5b6'; 
$itemSort  = "EndTimeSoonest";
$keywords = $_GET['keyword'];    // make sure this is a valid keyword or keywords

if ($_GET['category'] == 'ebay') {

	echo "<h2>Ebay Result</h2>";

	//find items advanced
	$apicalla  = "http://svcs.ebay.com/services/search/FindingService/v1?SECURITY-APPNAME=self56e11-173d-4020-8209-31afe61b5b6&OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=JSON&callback=_cb_findItemsByKeywords&REST-PAYLOAD&keywords=".$keywords."%203g&paginationInput.entriesPerPage=3";
	    

	$resp = curl($apicalla);

	$rx_range_sec = '(({"(.*)]}))Ui';
	preg_match($rx_range_sec, $resp, $range_sec);
	echo "<pre>";
	print_r($range_sec[2]);

}


function curl($url = null, $content= null, $headers = null) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1); // set POST method
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
	$return = curl_exec($ch);
	curl_close ($ch);
	return $return;

}//end curl