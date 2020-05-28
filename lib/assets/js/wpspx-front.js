/**
 * Front Emnd JS
 */

jQuery(document).ready(function($) {

	$('select[name=deliveryType]').change(function(){
		if($(this).val() == 'CustomerEmail'){
			$('input[name=deliveryEmail]').prop("disabled", true);
		}
		if($(this).val() == 'OtherEmail'){
			$('input[name=deliveryEmail]').prop("disabled", false);
		}
	});

});
