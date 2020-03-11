<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

	$classnames = array(
		'spektrix',
		'iframe',
		'cachedfile',
		'show',
		'performance',
		'pricelist',
		'plan',
		'availability',
		'memberships',
	);

	foreach($classnames as $classname) {
		$filename = plugin_dir_path( __FILE__ ) . "/spektrix/". $classname .".class.php";
		require $filename;
	}
