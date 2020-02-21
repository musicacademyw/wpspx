<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_shortcode( 'wpspx_basket', 'wpspx_load_basket' );
function wpspx_load_basket()
{
	$spektrix_iframe_url = new iFrame('Basket2');
	echo $spektrix_iframe_url->render_iframe();
}
