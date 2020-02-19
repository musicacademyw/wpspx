<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

$spektrix_id = get_post_meta($post->ID,'_spektrix_id',true);

$api = new Spektrix();
$show = new Show($spektrix_id);
$performances = $show->get_performances();
$is_in_future = !is_in_past($performances);
$pricelists = $show->get_price_lists();

$availabilities = $api->get_availabilities();
$extremes = availability_extremes($performances,$availabilities);
$av_helper = availability_helper($availabilities[$performances[0]->id],$extremes);
$count = count($performances);

get_header();
?>

<div class="showcard">

	<div class="show-image">
	<?php
	if(has_post_thumbnail()):
		the_post_thumbnail('poster');
	endif;
	?>
	</div>

	<div class="show-content">
		<h1><?php the_title(); ?></h1>
		<h3 class="date-range">
			<?php echo $performances ? get_performance_range($performances) : "No performance info available"; ?>
		</h3>
		<?php if($show->duration): ?>
		<p class="runtime">
			Running time: <?php echo wpspx_convert_to_hours_minutes($show->duration) ?>
		</p>
		<?php endif; ?>
		<?php the_content(); ?>
	</div>

	<?php
	if($is_in_future) {
		?>
		<div class="show-times">
			<h2 id="performance-information">Performance information</h2>
			<p class="performance-count">
			<?php if($count > 1): ?>
				When can I see one of the <?php echo wpspx_convert_number_to_words($count); ?> performances of <?php the_title(); ?>?
			<?php else: ?>
				When can I see the only performance of <?php the_title(); ?>?
			<?php endif; ?>
			</p>

		  	<table class="table">
				<tr>
					<th width="25%"><strong>Date &amp; Details<strong></th>
						<th><strong>Time &amp; Place</strong></th>
						<th class="hidden-phone"><strong>Tickets &amp; Pricing</strong></th>
					</tr>
					<tr class="no-best-availability">
						<td colspan="3">
							<h3>Sorry!<br>All performances have sold out well.<br>Contact us and we'll do our best to squeeze you in.</h3>
						</td>
					</tr>
					<?php
					$now = new DateTime();
					foreach($performances as $performance):
						if($performance->start_time > $now) {
							$is_sold_out = ($availabilities[$performance->id]->available === 0);
							$av_helper = availability_helper($availabilities[$performance->id],$extremes);
							$tr_class = 'performance ';
							$tr_class.= $av_helper->best_availability ? "best-availability " : "";
							$tr_class.= $performance->is_accessible() ? "accessible" : "";
							?>
							<tr class="<?php echo $tr_class; ?>">
							  <td colspan="3">
							    <br>
							  </td>
							</tr>
							<?php if($is_sold_out): ?>
							<tr class="<?php echo $tr_class; ?> sold-out-container">
								<td colspan="2">
							    	<span class="day-name"><?php echo $performance->start_time->format('l') ?></span>
									<span class="day-number"><?php echo $performance->start_time->format('d') ?></span>
									<span class="month-year"><?php echo $performance->start_time->format('M Y') ?></span>
								</td>
								<td colspan="2">
									<h3>Sorry, this date is sold out</h3>
									<p>
										<?php echo book_online_button($availabilities[$performance->id],$av_helper,$performance,$is_blockbuster) ?>
									</p>
								</td>
							</tr>
							<?php else: ?>
							<tr class="<?php echo $tr_class; ?>">
								<td>
									<span class="day-name"><?php echo $performance->start_time->format('l') ?></span>
									<span class="day-number"><?php echo $performance->start_time->format('d') ?></span>
									<span class="month-year"><?php echo $performance->start_time->format('M Y') ?></span>
									<p>
										<?php echo book_online_button($availabilities[$performance->id],$av_helper,$performance,$is_blockbuster) ?>
									</p>
								</td>
								<td>
									<time datetime="<?php echo $performance->start_time->format('Y-m-d H:i:s') ?>"></time>
									<span class="day-time"><small>Starts</small><br><?php echo $performance->start_time->format('H.i') ?></span>
									<?php if($show->duration): ?>
										<span class="day-time ends"><small>Ends</small><br> <?php echo $performance->end_time($show->duration,'H.i'); ?><small class="ish">ish</small></span>
									<?php endif; ?>
									<p>
										<a href="https://maps.google.co.uk/maps?q=<?php echo $show->venue ?>" target="_blank"><?php echo str_replace(', ','<br>',$show->venue) ?></a>
									</p>
								</td>
								<td class="show-prices hidden-phone" rowspan="2">
									<div class="<?php if($is_sold_out) echo 'sold-out-container' ?>">
										<table class="table table-hover">
											<?php
											$pricelist = get_price_list_for_performance($pricelists,$performance);
											$price_table = array();
											foreach($pricelist->prices as $price):
												if($price->ticket_type_name && $price->band_name):
													$price_table[$price->ticket_type_name][$price->band_name] = $price->price;
												endif;
											endforeach;
											$i = 0;
											foreach($price_table as $ticket_type => $bands):
												//If it's the first one, print the band names
												if($i == 0):
													echo '<tr class="bands"><td>&nbsp;</td>';
													foreach($bands as $band_name => $price):
														echo '<td><strong>'.$band_name.'</strong></td>';
													endforeach;
													echo '</tr>';
												endif;
												//Now just cycle through each ticket type
												echo '<tr>';
												echo '<td>'.$ticket_type.'</td>';
												foreach($bands as $band_name => $price):
													if($price == 0):
														echo '<td>Free</td>';
													else:
														echo '<td> &pound;'.number_format($price,2).'</td>';
													endif;
												endforeach;
												echo '</tr>';
												$i++;
											endforeach;
											?>
										</table>
									</div>
								</td>
							</tr>
							<?php
							endif; // end if sold out
						}
		    		endforeach;
					?>
			</table>
		</div>
		<?php
	}
	?>

</div>

<?php get_footer(); ?>
