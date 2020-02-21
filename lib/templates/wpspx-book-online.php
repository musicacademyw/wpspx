<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Template Name: WPSPX Basket
 */
get_header();
?>

<div class="book-online">

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

<?php get_footer(); ?>
