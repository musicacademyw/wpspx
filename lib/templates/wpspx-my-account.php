<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Template Name: WPSPX Basket
 */
get_header();
?>

<div class="showcard">

	<?php
	$spektrix_iframe_url = new iFrame('MyAccount',NULL,true);
	echo $spektrix_iframe_url->render_iframe();
	?>

</div>

<?php get_footer(); ?>
