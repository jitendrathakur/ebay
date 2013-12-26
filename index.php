<!DOCTYPE html>
  <?php
    require 'marketplace.php';
  ?>
<html>

<head>

  <meta charset="UTF-8">

  <title>CodePen - Pen</title>

  <link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
  <link href="css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  
  <script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

  <script type="text/javascript">
    $(function() {
      var availableTags = [
        "ebay",
        "amazon",          
      ];
      $( "#category" ).autocomplete({
      source: availableTags
      });

      var availableTags = [
       "de", "com", "co.uk", "ca", "fr", "co.jp", "it", "cn", "es"                 
      ];
      $( "#country" ).autocomplete({
        source: availableTags
      });

    });
  </script>

</head>

<body>

  <body>
  <div class="wrapper">    


    <form method="get" action="index.php" >

      <div class="column">
        <div class="title">Search For:</div>
        <input type="text" class="main" placeholder="Start here" name="keyword" value="<?php echo $keyword; ?>" />
      </div>

      <div class="column">
        <div class="title">Sort:</div>
        <input type="text" id="category" data-provide="typeahead" name="category"  value="<?php echo $category ?>" />      
      </div>
     
      <div class="column">
        <div class="title">Country:</div>
        <input type="text" id="country" placeholder="* Optional" name="country" value="<?php echo $countryCode;  ?>"  />
      </div>

      <div class="clear"></div> 

      <input type="submit" value="Search" />

    </form>  
  </div> 

  <div class="container">

  <?php

  if (isset($result['service'])) {

    if ($result['service'] == 'ebay') {
      ?> <h1>Ebay result</h1> <?php        
         
        echo '<table class="table table-bordered table-striped table-hover">';
        for ($i=0;$i<=8;$i++) {
       
          echo "<tr>";
          echo "<td><img class='shadow' style=' margin: 0px; margin-left: 10px; border: 1px solid black; max-height: 55px;' align='right' src='". $result['searchResult']['item'][$i]['galleryURL'] ."'></td>";
          echo "<td><a target='_Blank' href='" . $result['searchResult']['item'][$i]['viewItemURL'] ."'>". $result['searchResult']['item'][$i]['title'] . "</a></td></tr>";
          echo "</tr>";
        }
        echo "</table>";

    } elseif($result['service'] == 'amazon') {

      ?> <h1>Amazon result</h1> <?php

        if (isset($result['Items']['Item']) ) {
       
           //loop through each item
          echo '<table class="table table-bordered table-striped table-hover"><tr>';
          foreach ($result['Items']['Item'] as $row) {
            //check that there is a ASIN code - for some reason, some items are not
            //correctly listed. Im sure there is a reason for it, need to check.
            if (isset($row['ASIN'])) {

                //store the ASIN code in case we need it
              $asin = $row['ASIN'];
                //check that there is a URL. If not - no need to bother showing
                //this one as we only want linkable items
              if (isset($row['DetailPageURL'])) {             
                //set up a container for the details - this could be a DIV   
                //if there is a small image - show it
                if (isset($row['SmallImage']['URL'] )) {
                    echo "<td><img class='shadow' style=' margin: 0px; margin-left: 10px; border: 1px solid black; max-height: 55px;' align='right' src='". $row['SmallImage']['URL'] ."'></td>";
                }
                // if there is a title - show it
                if (isset($row['ItemAttributes']['Title'])) {
                    echo "<td><a target='_Blank' href='" . $row['DetailPageURL'] ."'>". $row['ItemAttributes']['Title'] . "</a></td></tr>";
                }            

              }
            }
          }
          echo "</table>";
        } 
    }

  }

  ?>

  </div>  


</body>

</html>