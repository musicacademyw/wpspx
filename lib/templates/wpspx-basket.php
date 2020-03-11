<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Template Name: WPSPX Basket
 */
get_header();

$request = wp_remote_get( SPEKTRIX_API_URL . 'basket' );
if( is_wp_error( $request ) ) {
	return false; // Bail early
}
$json = wp_remote_retrieve_body( $request );

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

<div class="showcard">

	<?php print_r($json); ?>

</div>

<?php get_footer(); ?>
