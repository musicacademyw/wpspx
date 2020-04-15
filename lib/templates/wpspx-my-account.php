<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix my account template
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-my-account.php
 *
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
