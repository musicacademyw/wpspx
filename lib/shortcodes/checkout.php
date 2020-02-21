<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_shortcode( 'wpspx_checkout', 'wpspx_load_checkout' );
function wpspx_load_checkout()
{
	$spektrix_iframe_url = new iFrame('Checkout',NULL,true);
	echo $spektrix_iframe_url->render_iframe();
}
