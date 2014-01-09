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

	$("input[type='submit']").click(function() {
		if ($(".custom-combobox-input").val() == '') {
			alert("please select category");
			return false;
		}
	});



});


function showCountry(product = null) {

	$(".amazon-country").hide();
	$(".ebay-country").hide();
	
	$("."+product+"-country").show();
	//$("."+product+"-country").addClass('show');

}//end showCountry()