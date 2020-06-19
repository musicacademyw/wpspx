<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix memberships shoertcode
 *
 */

function wpspxmembershis($atts)
{
	ob_start();
	$api = New Spektrix();
    $memberships = $api->get_memberships();
	?>
	<div class="wpspx-memberships">

		<div class="wpspx-row wpspx-row-wrap">
		<?php foreach ($memberships as $membership): ?>

			<spektrix-memberships
				class="wpspx-membership-box wpspx-column wpspx-column-25"
				client-name="<?php echo SPEKTRIX_USER; ?>"
				custom-domain="<?php echo SPEKTRIX_CUSTOM_URL; ?>"
				membership-id="<?php echo $membership->id ?>">

				<div class="wpspx-membership-header">
					<?php if ($membership->imageUrl): ?>
						<img src="<?php echo $membership->imageUrl ?>" alt="">
					<?php endif; ?>
					<h2><?php echo $membership->name ?></h2>
				</div>
				<div class="wpspx-membership-content">
					<?php echo wpautop($membership->description); ?>
					<?php echo wpautop($membership->htmlDescription); ?>
				</div>

				<div class="wpspx-membership-field">
					<div class="wpspx-membership-control wpspx-add-to-basket">
						<button class="button btn button-primary" data-submit-membership>Buy membership</button>
						<div class="autorenew">
							<input class="" type="checkbox" name="autorenew" data-set-autorenew checked>
							<label class="checkbox">Enable Auto Renew</label>
						</div>
					</div>
				</div>

				<div class="alert success" data-success-container style="display: none;">
					<div class="message-body">
						Sucessfully added to basket
					</div>
				</div>
				<div class="alert warn" data-fail-container style="display: none;">
					<div class="message-body">
						There seems to be an issue, please try again or call the box office.
					</div>
				</div>

			</spektrix-memberships>

		<?php endforeach; ?>

		</div>

	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'wpspx-memberships', 'wpspxmembershis' );
