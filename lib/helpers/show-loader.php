<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// loads shows
$shows = Show::find_all_in_future();
$wp_shows = get_wp_shows_from_spektrix_shows($shows);
$shows = filter_published($shows,$wp_shows);
