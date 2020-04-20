<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix basket template
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-basket.php
 *
 */

get_header();

?>

<div class="wpspxbasket">

	<div class="container is-flex">

	<?php
	$spektrix_iframe_url = new iFrame('Basket2',NULL,false);
	echo $spektrix_iframe_url->render_iframe();
	?>

	</div>

</div>

<?php get_footer(); ?>
