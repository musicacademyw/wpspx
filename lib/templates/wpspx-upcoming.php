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

<div class="wpspx-upcoming-shows">

	<div class="wpspx-container container">

		<div class="wpspx-row row">
		<?php
		foreach($shows as $show) {
			$show_id = $wp_shows[$show->id];
			$show_poster = $show->image_url;
			$poster = get_the_post_thumbnail($show_id, 'poster');
			?>
			<div class="wpspx-show column column-25">

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
				<div class="wpspx-show-info">
					<h3 class="wpspx-show-subtitle">
						<a href="<?php echo get_permalink($show_id); ?>"><?php echo $show->name ?></a>
					</h3>
					<p><?php echo $show->instance_dates; ?></p>
					<a class="button btn button-primary" href="<?php echo get_permalink($show_id); ?>">View Performances</a>
				</div>
			</div>
			<?php
		}
		?>
		</div>

	</div>

</div>

<?php get_footer(); ?>
