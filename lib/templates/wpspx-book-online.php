<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix booking page tempkate
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-book-online.php
 *
 */
get_header();
?>

<div class="book-online">

	<div class="container is-flex">

	<?php
	$performance = get_query_var('performance');

	if(strpos($performance,'event') === 0):
		$pieces = explode('-',$performance);
		$spektrix_iframe_url = new iFrame('EventDetails',array('EventId' => $pieces[1]));
	else:
		$spektrix_iframe_url = new iFrame('ChooseSeats',array('EventInstanceId' => $performance));
	endif;

	echo $spektrix_iframe_url->render_iframe();
	?>

	</div>

</div>

<?php get_footer(); ?>
