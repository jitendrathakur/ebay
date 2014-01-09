<?php

  function curl($url = null, $content= null, $headers = null) {
    // create a new cURL resource
    $ch = curl_init($url);           
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;

  }//end curl

  function XML2Array(SimpleXMLElement $parent)
  {
      $array = array();

      foreach ($parent as $name => $element) {
          ($node = & $array[$name])
              && (1 === count($node) ? $node = array($node) : 1)
              && $node = & $node[];

          $node = $element->count() ? XML2Array($element) : trim($element);
      }

      return $array;
  }

  $ebay_country = array(
    'US' => 'United States',
    'AT' => 'Austria', 
    'AU' => 'Australia', 
    'CH' => 'Switzerland',
    'DE' => 'Germany', 
    'ENCA' => 'Canada (English)', 
    'ES' => 'Spain', 
    'FR' => 'France', 
    'FRBE' => 'Belgium (French)',
    'FRCA' => 'Canada (French)', 
    'GB' => 'Great Britain', 
    'HK' => 'Honk Kong',        
    'IE' => 'Ireland', 
    'IN' => 'India', 
    'IT' => 'Italy', 
    'MOTOR' => 'Motors', 
    'MY' => 'Malaysia',
    'NL' => 'Netherlands', 
    'NLBE' => 'Belgium (Dutch)', 
    'PH' => 'Philippines',
    'PL' => 'Poland', 
    'SG' => 'Singapore'    
  );

  $amazon_country = array('com' => 'United States', 'de' => 'Denmark', 'co.uk' => 'Great Britain',
             'ca' => 'Canada', 'fr' => 'Franse', 'co.jp' => 'Japan', 'cn' => 'China', 'it' => 'Italy');
  
  $ip = $_SERVER['REMOTE_ADDR'];
  $result = curl("freegeoip.net/json/".$ip);

  $result = json_decode($result, 1);

  $ipCountryCode = $result['country_code'];

  $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
  $category = isset($_GET['category']) ? $_GET['category'] : '';

  if (!empty($_GET['ebay_country']) && ($_GET['category'] == 'ebay')) {
    $open_country = 'ebay';
    $country = $_GET['ebay_country'];
  } else if (!empty($_GET['amazon_country']) && ($_GET['category'] == 'amazon')) {
    $open_country = 'amazon';
    $country = $_GET['amazon_country'];
  } else {
    $open_country = 'start';
    foreach($ebay_country as $code => $name) {
      if ($code == $ipCountryCode) {
        $country = $code;
        break;
      } else {
        $country = 'US';
      }
    }   
     
  }

  if (isset($_GET['keyword'])) :

  //Ebay   

    if ($_GET['category'] == 'ebay') {

 
      //find items advanced      
      $url = 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.12.0&SECURITY-APPNAME=self56e11-173d-4020-8209-31afe61b5b6&RESPONSE-DATA-FORMAT=XML&REST-PAYLOAD&global-id=EBAY-'.$country.'&keywords='.$keyword.'%203g&paginationInput.entriesPerPage=20';
      $resp = curl($url);

      $xml   = simplexml_load_string($resp);
      $result = XML2Array($xml);
      

      $result['service'] = 'ebay';
       

    } elseif ($_GET['category'] == 'amazon') {

      require 'lib/AmazonECS.class.php';
         
      $client = new AmazonECS('AKIAIXCLOJMP4L2CCGEA', 'NfISxraD61uDaZKmm8JwNfidHClJElNKApVd+a/6', 'com', 'ASSOCIATE TAG');
      $client->setReturnType(AmazonECS::RETURN_TYPE_ARRAY);
      $response = $client->country($country);
      $response = $client->responseGroup('Small,Images');
      $response  = $client->category("All")->search($keyword);

      
      $result = $response;
      $result['service'] = 'amazon';

    }

  endif;