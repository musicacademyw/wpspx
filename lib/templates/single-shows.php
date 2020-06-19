<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Single show template.
 *
 * To overwrite this template copy this file to your theme under /wpspx/single-shows.php
 *
 */

 $spektrix_id = get_post_meta($post->ID,'_spektrix_id',true);
 $show = new Show($spektrix_id);

 // is_on_sale
 // short_description
 // long_description
 // image_url
 // image_thumb
 // duration
 // instance_dates
 // instances

 $performances = $show->get_performances();
 $is_in_future = !is_in_past($performances);

 $now = new DateTime();
 get_header();

?>

<?php if (!$show->is_on_sale): ?>
<div class="notification is-info">
  	<button class="delete"></button>
  	<h4>This show is not currently selling online.</h4>
</div>
<?php endif; ?>

<div class="wpspx-single-show">

	<div class="wpspx-container">

		<div class="wpspx-show-info wpspx-row wpspx-row-wrap">

			<div class="wpspx-show-image wpspx-column wpspx-column-40">
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

			<div class="wpspx-show-content wpspx-column wpspx-column-60">
				<div class="wpspx-show-title">
					<h1><?php the_title(); ?></h1>
				</div>
				<div class="wpspx-date-range">
					<p class="date-range"><?php echo $performances ? get_performance_range($performances) : "No performance info available"; ?></p>
				</div>
				<?php if($show->duration): ?>
				<div class="wpspx-runtime">
					<p>Running time: <?php echo wpspx_convert_to_hours_minutes($show->duration); ?></p>
				</div>
				<?php endif; ?>
				<div class="wpspx-show-copy">
					<?php
					if ($show->long_description):
						echo $show->long_description;
					else:
						while (have_posts()) : the_post();
							the_content();
						endwhile;
					endif;
					?>
				</div>
			</div>

		</div>


		<?php if($is_in_future): ?>
		<div class="wpspx-performaces">

			<h2 class="wpspx-performaces-title">Performances</h2>

			<div class="wpspx-performaces-list">

			<?php
			foreach($performances as $performance):
				if($performance->start_time > $now):
					$pricelists = $api->get_price_list($performance->id);
					$availabilities = $api->get_availability($performance->id);
					?>
					<div class="wpspx-performance wpspx-row">
						<div class="wpspx-performance-date wpspx-column wpspx-column-20">
							<i class="far fa-calendar-alt"></i> <?php echo $performance->start_time->format('D d M') ?>
						</div>
						<div class="wpspx-performance-time wpspx-column wpspx-column-20">
							<i class="far fa-clock"></i> <?php echo $performance->start_time->format('g:ia') ?>
						</div>
						<div class="wpspx-performance-attr wpspx-column wpspx-column-20">
							<?php if ($performance->start_time->format('h') < 5): ?>
								Matinee
							<?php endif; ?>
						</div>
						<div class="wpspx-performance-prices wpspx-column wpspx-column-20">
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
								echo "From ".$price_string;
							} else {

								echo "From "."&pound;" . number_format(min($price_range),2);

							}
							?>
						</div>
						<div class="wpspx-performance-action wpspx-column wpspx-column-20">
							<?php echo book_online_button($availabilities,$performance); ?>
						</div>
					</div>
					<?php
				endif;
		    endforeach;
			?>
			</div>

		</div>
		<?php else: ?>
		<div class="wpspx-no-performaces wpspx-row">
			<div class="alert warn">
				<!-- <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> -->
				This is an alert box.
			</div>
		</div>
		<?php endif //is in future ?>

	</div>

</div>

<?php get_footer(); ?>
