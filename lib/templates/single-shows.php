<?php
/*
 * Single show template.
 *
 */

$spektrix_id = get_post_meta($post->ID,'_spektrix_id',true);
$show = new Show($spektrix_id);
$api = new Spektrix();

$performances = $show->get_performances();
$is_in_future = !is_in_past($performances);

$now = new DateTime();
get_header();
?>

<div class="showcard">

	<div class="show-container">

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
					Running time: <?php //echo wpspx_convert_to_hours_minutes($show->duration); ?>
				</p>
				<?php endif; ?>
			</div>
			<div class="show-copy">
				<?php
				while (have_posts()) : the_post();
					the_content();
				endwhile;
				?>
			</div>
		</div>

		<?php
		if($is_in_future) {
			?>
			<div class="show-times">
				<h2 id="performance-information">Performance information</h2>

				<?php
				foreach($performances as $performance):
					if($performance->start_time > $now):
						$pricelists = $api->get_price_list($performance->id);
						$availabilities = $api->get_availability($performance->id);
						?>
						<div class="performance">
							<div class="date">
								<span>
									<?php echo $performance->start_time->format('D d M') ?>
								</span>
							</div>
							<div class="date">
								<span>
									<?php echo $performance->start_time->format('g:ia') ?>
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
								$price_range = array();
								foreach($pricelists->prices as $price):
									$price_range[] = $price->price;
								endforeach;

								if (count($price_range) === 1) {
									$price = current($price_range);
									if($price == 0):
										$price_string = 'free';
									else:
										$price_string = "&pound;" . number_format($price,2);
									endif;
									echo "<span>From ".$price_string."</span>";
								} else {

									echo "<span>From "."&pound;" . number_format(min($price_range),2) ."</span>";

								}
								?>
							</div>
							<div class="action">
								<span>
									<?php echo book_online_button($availabilities,$performance); ?>
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

</div>

<?php get_footer(); ?>
