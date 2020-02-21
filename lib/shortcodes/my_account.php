<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_shortcode( 'wpspx_my_account', 'wpspx_load_my_account' );
function wpspx_load_my_account()
{
	$spektrix_iframe_url = new iFrame('MyAccount',NULL,true);
	echo $spektrix_iframe_url->render_iframe();
}
