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



  $url = json_decode(file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=<your_api_key>&ip=".$_SERVER['REMOTE_ADDR']."&format=json"));
  $countryCode = !empty($url->countryCode) ? $url->countryCode : 'com';


  $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
  $category = isset($_GET['category']) ? $_GET['category'] : '';

  $country = isset($_GET['country']) ? $_GET['country'] : $countryCode;


  if (isset($_GET['keyword'])) :

  //Ebay

    $keywords = $_GET['keyword'];    // make sure this is a valid keyword or keywords

    if ($_GET['category'] == 'ebay') {   
      //find items advanced      
      $url = 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.12.0&SECURITY-APPNAME=self56e11-173d-4020-8209-31afe61b5b6&RESPONSE-DATA-FORMAT=XML&REST-PAYLOAD&country=IN&keywords='.$keywords.'%203g&paginationInput.entriesPerPage=10';
      $resp = curl($url);

      $xml   = simplexml_load_string($resp);
      $result = XML2Array($xml);
      

      $result['service'] = 'ebay';
       

    } elseif ($_GET['category'] == 'amazon') {

      require 'lib/AmazonECS.class.php';
         
      $client = new AmazonECS('AKIAIXCLOJMP4L2CCGEA', 'NfISxraD61uDaZKmm8JwNfidHClJElNKApVd+a/6', 'com', 'ASSOCIATE TAG');
      $client->setReturnType(AmazonECS::RETURN_TYPE_ARRAY);
      $response = $client->country($_GET['country']);
      $response = $client->responseGroup('Small,Images');
      $response  = $client->category("All")->search($keywords);

      
      $result = $response;
      $result['service'] = 'amazon';
    

    }

  endif;