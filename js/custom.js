$(function() {


	$( "#combobox" ).combobox({
	  select: function (event, ui) {       
      showCountry($(this).val());
    } 
	});
	$( "#toggle" ).click(function() {
		$( "#combobox" ).toggle();
	});

	$( "#ebay_country" ).combobox();
	$( "#toggle" ).click(function() {
		$( "#ebay_country" ).toggle();
	});

	$( "#amazon_country" ).combobox();
	$( "#toggle" ).click(function() {
		$( "#amazon_country" ).toggle();
	});

});


function showCountry(product = null) {

	$(".amazon-country").hide();
	$(".ebay-country").hide();
	
	$("."+product+"-country").show();
	//$("."+product+"-country").addClass('show');

}//end showCountry()