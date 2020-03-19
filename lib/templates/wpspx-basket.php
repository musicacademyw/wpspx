<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

$api = New Spektrix();
$basket = $api->get_basket();

/**
 * Template Name: WPSPX Basket
 */
get_header();



// totalDiscount
// defaultCardPaymentFee
// promoCode
// id
// basketId
// basketId_Comment
// transactionCommission
	// waived
	// amount
// disableWebComponents
// hash
// customer
// merchandiseItems
// donations
// notes
// tickets
// membershipSubscriptions
// giftVouchers
// total

?>

<div class="wpspxbasket">

	<pre>
		<?php print_r($basket) ?>
	</pre>

</div>

<?php get_footer(); ?>
