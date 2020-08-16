<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix donate template
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-donate.php
 *
 */

 get_header();

?>

<div class="wpspx-donation">

	<div class="wpspx-container">

		<spektrix-donate client-name="<?php echo WPSPX_SPEKTRIX_USER; ?>" custom-domain="<?php echo WPSPX_SPEKTRIX_CUSTOM_URL; ?>" fund-id="" id="selectfundcomponent">

			<div class="wpspx-row header">
				<div class="wpspx-column">
					<h3>Choose a fund to donate to:</h3>
				</div>
			</div>
			<div class="wpspx-row">
				<?php
				// $api = New Spektrix();
				$funds = $api->get_data('funds');

				foreach ($funds as $fund):
					?>
					<div class="wpspx-column">
						<input type="radio" id="<?php echo str_replace(" ","-", $fund->name) ?>" name="fundid" value="<?php echo $fund->id ?>">
						<label for="<?php echo str_replace(" ","-", $fund->name) ?>"><h3><?php echo str_replace("-"," ", $fund->name) ?></h3>
							<p><?php echo $fund->description ?></p>
						</label>
					</div>
					<?php
				endforeach;
				?>
			</div>


			<div class="wpspx-row header">
				<div class="wpspx-column">
					<h3>Choose an amount to donate:</h3>
				</div>
			</div>
			<div class="wpspx-row">
				<div class="wpspx-column">
					<div class="wpspx-column">
						<button class="button btn button-primary" data-donate-amount="10">£10</button>
					</div>
					<div class="wpspx-column">
						<button class="button btn button-primary" data-donate-amount="20">£20</button>
					</div>
					<div class="wpspx-column">
						<button class="button btn button-primary" data-donate-amount="30">£30</button>
					</div>
					<div class="wpspx-column">
						<input type="text" data-custom-donation-input placeholder="Enter Custom Amount">
					</div>
				</div>
			</div>


			<div class="wpspx-row">
				<div class="wpspx-column">
					<h3><span>Donation Total £</span> <span data-display-donation-amount>0</span></h3>
				</div>
			</div>

			<div class="wpspx-row">
				<div class="wpspx-column">
					<button class="button btn button-primary" data-submit-donation>Donate</button>
				</div>
				<div class="wpspx-column">
					<button class="button btn button-secondary" data-clear-donation>Clear Donation</button>
				</div>
			</div>

			<div class="wpspx-row" data-success-container style="display: none;">
				<div class="wpspx-column">
					<div class="message-body alert success">
						Sucessfully added to basket
					</div>
				</div>
			</div>
			<div class="wpspx-row" data-fail-container style="display: none;">
				<div class="wpspx-column">
					<div class="message-body alert warn">
						There seems to be an issue, please try again or call the box office.
					</div>
				</div>
			</div>

			<script>
				jQuery('#selectfundcomponent input').on('change', function() {
					jQuery("#selectfundcomponent").attr('fund-id', jQuery('input[name="fundid"]:checked', '#selectfundcomponent').val());
				});
			</script>
		</spektrix-donate>

	</div>

</div>

<?php get_footer(); ?>
