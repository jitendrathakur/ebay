<?php
require 'lib/AmazonECS.class.php';
$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1?';  
$responseEncoding = 'XML';   
$version = '1.8.0';   // API version number (they're actually up to 1.11.0 at this point
$appID   = 'self56e11-173d-4020-8209-31afe61b5b6'; 
$itemSort  = "EndTimeSoonest";?>
<a href= "<?php echo "http://".$_SERVER['HTTP_HOST'].'/ebay/';?>">Home</a>
<?php $keywords = $_GET['keyword'];    // make sure this is a valid keyword or keywords
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
elseif($_GET['category'] == 'amazon')
{
    echo "<h2>Amazon Result</h2>";
    $client = new AmazonECS('AKIAIXCLOJMP4L2CCGEA', 'NfISxraD61uDaZKmm8JwNfidHClJElNKApVd+a/6', 'com', 'ASSOCIATE TAG');
    $client->setReturnType(AmazonECS::RETURN_TYPE_ARRAY);
    $response  = $client->category("All")->search($keywords);

    if (isset($response['Items']['Item']) ) { ?>

        <table class="table table-condensed table-hover table-bordered">


        <?php

        //loop through each item
        foreach ($response['Items']['Item'] as $result) {

            //check that there is a ASIN code - for some reason, some items are not
            //correctly listed. Im sure there is a reason for it, need to check.
            if (isset($result['ASIN'])) {

                //store the ASIN code in case we need it
                $asin = $result['ASIN'];

                //check that there is a URL. If not - no need to bother showing
                //this one as we only want linkable items
                if (isset($result['DetailPageURL'])) { ?>

                <tr>
                  <td class="active">
                       <?php

                        //set up a container for the details - this could be a DIV
                        echo "<p style='min-height: 60px; font-size: 90%;'>";

                        //create the URL link
                        echo "<a target='_Blank' href='" . $result['DetailPageURL'] ."'>";

                        //if there is a small image - show it
                        if (isset($result['SmallImage']['URL'] )) {
                            echo "<img class='shadow' style=' margin: 0px; margin-left: 10px; border: 1px solid black; max-height: 55px;' align='right' src='". $result['SmallImage']['URL'] ."'>";
                        }

                        // if there is a title - show it
                        if (isset($result['ItemAttributes']['Title'])) {
                            echo $result['ItemAttributes']['Title'] . "<br/>";
                        }

                        //close the paragraph
                        echo "</p>";

                        ?>
                  
                  </td>
                 
                </tr>            

                    <?php
                }
            }
        } ?>
        </table>


        <?php

    } 

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