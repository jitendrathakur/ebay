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



  <style>
.custom-combobox {
position: relative;
display: inline-block;
}
.custom-combobox-toggle {
position: absolute;
top: 0;
bottom: 0;
margin-left: -1px;
padding: 0;
/* support: IE7 */
*height: 1.7em;
*top: 0.1em;
}
.custom-combobox-input {
margin: 0;
padding: 0.3em;
}
</style>
<script>
(function( $ ) {
$.widget( "custom.combobox", {
_create: function() {
this.wrapper = $( "<span>" )
.addClass( "custom-combobox" )
.insertAfter( this.element );
this.element.hide();
this._createAutocomplete();
this._createShowAllButton();
},
_createAutocomplete: function() {
var selected = this.element.children( ":selected" ),
value = selected.val() ? selected.text() : "";
this.input = $( "<input>" )
.appendTo( this.wrapper )
.val( value )
.attr( "title", "" )
.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
.autocomplete({
delay: 0,
minLength: 0,
source: $.proxy( this, "_source" )
})
.tooltip({
tooltipClass: "ui-state-highlight"
});
this._on( this.input, {
autocompleteselect: function( event, ui ) {
ui.item.option.selected = true;
this._trigger( "select", event, {
item: ui.item.option
});
},
autocompletechange: "_removeIfInvalid"
});
},
_createShowAllButton: function() {
var input = this.input,
wasOpen = false;
$( "<a>" )
.attr( "tabIndex", -1 )
.attr( "title", "Show All Items" )
.tooltip()
.appendTo( this.wrapper )
.button({
icons: {
primary: "ui-icon-triangle-1-s"
},
text: false
})
.removeClass( "ui-corner-all" )
.addClass( "custom-combobox-toggle ui-corner-right" )
.mousedown(function() {
wasOpen = input.autocomplete( "widget" ).is( ":visible" );
})
.click(function() {
input.focus();
// Close if already visible
if ( wasOpen ) {
return;
}
// Pass empty string as value to search for, displaying all results
input.autocomplete( "search", "" );
});
},
_source: function( request, response ) {
var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
response( this.element.children( "option" ).map(function() {
var text = $( this ).text();
if ( this.value && ( !request.term || matcher.test(text) ) )
return {
label: text,
value: text,
option: this
};
}) );
},
_removeIfInvalid: function( event, ui ) {
// Selected an item, nothing to do
if ( ui.item ) {
return;
}
// Search for a match (case-insensitive)
var value = this.input.val(),
valueLowerCase = value.toLowerCase(),
valid = false;
this.element.children( "option" ).each(function() {
if ( $( this ).text().toLowerCase() === valueLowerCase ) {
this.selected = valid = true;
return false;
}
});
// Found a match, nothing to do
if ( valid ) {
return;
}
// Remove invalid value
this.input
.val( "" )
.attr( "title", value + " didn't match any item" )
.tooltip( "open" );
this.element.val( "" );
this._delay(function() {
this.input.tooltip( "close" ).attr( "title", "" );
}, 2500 );
this.input.data( "ui-autocomplete" ).term = "";
},
_destroy: function() {
this.wrapper.remove();
this.element.show();
}
});
})( jQuery );
$(function() {
$( "#combobox" ).combobox();
$( "#toggle" ).click(function() {
$( "#combobox" ).toggle();
});

$( "#country" ).combobox();
$( "#toggle" ).click(function() {
$( "#country" ).toggle();
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
        <div class="title">Category:</div>
        <div class="ui-widget">  
          <?php
            $option = array('ebay' => 'Ebay', 'amazon' => 'Amazon');            
            
           ?>       
          <select id="combobox" name="category">
            <option value="">Select one...</option>
            <?php foreach($option as $key => $value) : 
              $selected = ($category == $key) ? "selected" : "";
            ?>
            <option value="<?php echo $key; ?>"  <?php echo $selected; ?>  ><?php echo $value; ?></option>                
            <?php endforeach; ?>    
          </select>
        </div>        
      </div>

      <div class="column">
        <div class="title">Country:</div>
        <div class="ui-widget">  
          <?php
          //echo $countryCode;
            $option = array('de' => 'Denmark', 'com' => 'United State', 'co.uk' => 'Great Britain',
             'ca' => 'Canada', 'fr' => 'Franse', 'co.jp' => 'Japan', 'cn' => 'China', 'it' => 'Italy');            
          ?>       
          <select id="country" name="country">
            <option value="">Select one...</option>
            <?php foreach($option as $key => $value) : 
              $selected = ($country == $key) ? "selected" : "";

            ?>
            <option value="<?php echo $key; ?>"  <?php echo $selected; ?>  ><?php echo $value; ?></option>                
            <?php endforeach; ?>    
          </select>
        </div>
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
          echo "<td><img class='shadow' style=' width:80px; height:70px; margin: 0px; margin-left: 10px; border: 1px solid black;' align='right' src='". $result['searchResult']['item'][$i]['galleryURL'] ."'></td>";
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
                    echo "<td><img class='shadow' style='width:80px; height:70px; margin: 0px; margin-left: 10px; border: 1px solid black;' align='right' src='". $row['SmallImage']['URL'] ."'></td>";
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