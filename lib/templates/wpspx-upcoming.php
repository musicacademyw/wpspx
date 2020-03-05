<?php
/**
 * Upcoming shows template
 */

get_header();

$shows = Show::find_all_in_future_with_instances();
$wp_shows = get_wp_shows_from_spektrix_shows($shows);
$shows = filter_published($shows,$wp_shows);

function date_compare($a, $b)
{
    $t1 = strtotime($a->instances[0]->start);
    $t2 = strtotime($b->instances[0]->start);
    return $t1 - $t2;
}
usort($shows, 'date_compare');

?>

<div class="all-upcoming-shows">

	<div class="shows-container">

	<?php
	foreach($shows as $show) {
		$show_id = $wp_shows[$show->id];
		$show_poster = $show->image_url;
		$poster = get_the_post_thumbnail($show_id, 'poster');

		?>
		<div class="show">

			<a href="<?php echo get_permalink($show_id); ?>">
				<?php
				if($show_poster):
					echo '<img src="'.$show_poster.'">';
				elseif($poster):
					echo $poster;
				else:
					echo '<img src="'.plugin_dir_url( __DIR__ ).'/assets/wpspx-image-portrait.jpg">';
				endif;
				?>
			</a>
			<div class="info">
				<h5><a href="<?php echo get_permalink($show_id); ?>"><?php echo $show->name ?></a></h5>
				<p><?php echo $show->instance_dates; ?></p>
			</div>
		</div>
		<?php
	}
	?>
	</div>
</div>

<?php get_footer(); ?>
