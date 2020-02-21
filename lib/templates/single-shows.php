<?php

if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

$spektrix_id = get_post_meta($post->ID,'_spektrix_id',true);
$show = new Show($spektrix_id);

$performances = $show->get_performances();
$pricelists = $show->get_price_lists();
$is_in_future = !is_in_past($performances);

$api = new Spektrix();
$availabilities = $api->get_availabilities();

get_header();
?>

<div class="showcard">

	<div class="show-image">
	<?php
	if(has_post_thumbnail()):
		the_post_thumbnail('poster');
	elseif($show->image_url):
		echo "<img src='".$show->image_url."'>";
	else:
		echo "<img src='".WPSPX_PLUGIN_URL."lib/assets/wpspx-image-portrait.jpg'>";
	endif;
	?>
	</div>

	<div class="show-content">
		<div class="show-title">
			<h1><?php the_title(); ?></h1>
			<h3 class="date-range">
				<?php echo $performances ? get_performance_range($performances) : "No performance info available"; ?>
			</h3>
			<?php if($show->duration): ?>
			<p class="runtime">
				Running time: <?php echo wpspx_convert_to_hours_minutes($show->duration); ?>
			</p>
			<?php endif; ?>
		</div>
		<div class="show-copy">
			<?php while (have_posts()) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; ?>
		</div>
	</div>

	<?php
	if($is_in_future) {
		?>
		<div class="show-times">
			<h2 id="performance-information">Performance information</h2>

			<?php
			$now = new DateTime();
			foreach($performances as $performance):
				if($performance->start_time > $now):
					$pricelist = get_price_list_for_performance($pricelists,$performance);
					?>
					<div class="performance">
						<div class="date">
							<span>
								<?php echo $performance->start_time->format('D d M') ?>
							</span>
						</div>
						<div class="date">
							<span>
								<?php echo $performance->start_time->format('h:ia') ?>
							</span>
						</div>
						<div class="attr">
							<span>
								<?php if ($performance->start_time->format('h') < 5): ?>
									Matinee
								<?php endif; ?>
							</span>
						</div>
						<div class="prices">
							<?php
							$price_table = array();
							foreach($pricelist->prices as $price):
								$price_table[$price->band_name] = $price->price;
							endforeach;

							if (count($price_table) === 1) {
								$price = current($price_table);
								if($price == 0):
									$price_string = 'free';
								else:
									$price_string = "&pound;" . number_format($price,2);
								endif;
								echo "<span>From ".$price_string."</span>";
							} else {

								echo "<span>From "."&pound;" . number_format(min($price_table),2) ."</span>";

							}
							?>
						</div>
						<div class="action">
							<span>
								<?php echo book_online_button($availabilities[$performance->id],$performance); ?>
							</span>
						</div>
					</div>
					<?php
				endif;
		    endforeach;
			?>
		</div>
		<?php
	}
	?>

</div>

<?php
if (is_user_logged_in() && user_can(get_current_user_id(), 'edit_post' )) {
	?>
	<div class="cpanel">
		<h4>Show Data (admin only)</h4>
		<p>Show ID: <?php echo $show->id ?></p>
	</div>
	<?php
}
?>

<?php get_footer(); ?>
