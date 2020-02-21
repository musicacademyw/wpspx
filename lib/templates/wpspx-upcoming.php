<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

$shows = Show::find_all_in_future();
$wp_shows = get_wp_shows_from_spektrix_shows($shows);
$shows = filter_published($shows,$wp_shows);
$all_performances = Performance::find_all_in_future(true);

$api = new Spektrix();
$availabilities = $api->get_availabilities();

get_header();

uasort($all_performances, function($a, $b) {
	return $a[0]->start_time->format('U') - $b[0]->start_time->format('U');
});
$performance_months = array();
foreach($all_performances as $show_id => $ps):
	if(!is_in_past($ps)){
		$months = get_performance_months($ps);
		foreach($months as $month):
			if(array_key_exists($show_id,$shows)){
				$month = strtotime($month);
				$performance_months[$month][] = array($shows[$show_id],get_performance_range($ps,""));
			}
		endforeach;
	}
endforeach;
ksort($performance_months);

?>

	<div class="all-upcoming-shows">
	<?php foreach($performance_months as $month => $shows): $month = date("F Y",$month); ?>

		<h2 class="month"><?php echo $month ?></h2>
		<div class="month-shows">
		<?php
		$i = 0;
		foreach($shows as $show):
			$performances = $show[1];
			$show = $show[0];
			$show_id = $wp_shows[$show->id];
			$is_sold_out = false;

			$this_show_performances = $show->get_performances();
			$now = new DateTime();
			$number_tikets = array();

			foreach($this_show_performances as $this_show_performance):
				if($this_show_performance->start_time > $now):
					$number_tikets[] = $availabilities[$this_show_performance->id]->available;
				endif;
			endforeach;

			if(array_sum($number_tikets) === 0) {
				$is_sold_out = true;
			}
			?>
			<div data-tickets-left="<?php echo array_sum($number_tikets); ?>" class="show <?php if($is_sold_out): ?>sold-out<?php endif; ?>">
				<a href="<?php echo get_permalink($show_id); ?>">
					<?php
					if(has_post_thumbnail()):
						the_post_thumbnail('poster');
					elseif($show->image_url):
						echo "<img src='".$show->image_url."'>";
					else:
						echo "<img src='".WPSPX_PLUGIN_URL."lib/assets/wpspx-image-portrait.jpg'>";
					endif;
					?>
				</a>
				<div class="info">
					<h3><a href="<?php echo get_permalink($show_id); ?>"><?php echo $show->name ?></a></h3>
					<p><?php echo $performances; ?></p>
				</div>
			</div>
			<?php
		endforeach;
		?>
		</div>
		<?php
	endforeach;
	?>
</div>

<?php get_footer(); ?>
