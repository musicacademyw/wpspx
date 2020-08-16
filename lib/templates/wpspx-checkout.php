<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix checkout template
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-checkout.php
 *
 */

 get_header();

?>

<div class="showcard">

	<div class="wpspx-container">

		<div class="wpspx-row">

			<?php
			$spektrix_iframe_url = new WPSPX_iFrame('Checkout',NULL,true);
			echo $spektrix_iframe_url->render_iframe();
			?>

		</div>

	</div>

</div>

<?php get_footer(); ?>
