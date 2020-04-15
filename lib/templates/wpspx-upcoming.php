<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix upcomming shows template
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-upcomming.php
 *
 */

 get_header();

 $shows = Show::find_all_in_future_with_instances();
 $wp_shows = get_wp_shows_from_spektrix_shows($shows);
 $shows = filter_published($shows,$wp_shows);

 function wpspx_date_compare($a, $b)
 {
     $t1 = strtotime($a->instances[0]->start);
     $t2 = strtotime($b->instances[0]->start);
     return $t1 - $t2;
 }
 usort($shows, 'wpspx_date_compare');

?>

<header>
	<h1>Upcoming Shows</h1>
</header>

<div class="all-upcoming-shows columns is-multiline">

<?php
foreach($shows as $show) {
	$show_id = $wp_shows[$show->id];
	$show_poster = $show->image_url;
	$poster = get_the_post_thumbnail($show_id, 'poster');
	?>
	<div class="show column is-one-quarter">

		<a href="<?php echo get_permalink($show_id); ?>">
			<?php
			if($show_poster):
				echo '<img src="'.$show_poster.'">';
			elseif($poster):
				echo $poster;
			else:
				echo '<img src="'.WPSPX_PACEHOLDER . '">';
			endif;
			?>
		</a>
		<div class="info">
			<h3 class="subtitle">
				<a href="<?php echo get_permalink($show_id); ?>"><?php echo $show->name ?></a>
			</h3>
			<p><?php echo $show->instance_dates; ?></p>
		</div>
	</div>
	<?php
}
?>
</div>

<?php get_footer(); ?>
