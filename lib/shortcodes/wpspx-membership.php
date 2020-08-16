<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix giftcard / voucher shoertcode
 *
 *
 */

function wpspxmembership($atts)
{
	ob_start();
	$atts = shortcode_atts(array(
		'id' => '',
		'name' => '',
		'description' => '',
		'price' => '',
	), $atts);

	?>
	<spektrix-memberships
		class="wpspx-membership-box"
		client-name="<?php echo WPSPX_SPEKTRIX_USER; ?>"
		custom-domain="<?php echo WPSPX_SPEKTRIX_CUSTOM_URL; ?>"
		membership-id="<?php echo $atts['id'] ?>">

		<div class="wpspx-row wpspx-membership-heading-row">
			<div class="wpspx-membership-header">
				<h2><?php echo $atts['name'] ?></h2>
			</div>
			<div class="wpspx-membership-price">
				<h4><?php echo $atts['price'] ?></h4>
			</div>
		</div>

		<div class="wpspx-row">
			<div class="wpspx-membership-content">
				<?php echo wpautop($atts['description']); ?>
			</div>
		</div>

		<div class="wpspx-row">
			<div class="wpspx-membership-field">
				<div class="wpspx-membership-control wpspx-add-to-basket">
					<button class="button btn button-primary" data-submit-membership>Buy membership</button>
					<div class="autorenew">
						<input class="" type="checkbox" name="autorenew" data-set-autorenew checked>
						<label class="checkbox">Enable Auto Renew</label>
					</div>
				</div>
			</div>
		</div>

		<div class="alert success" data-success-container style="display: none;">
			<div class="wpspx-row">
				<div class="message-body">
					Sucessfully added to basket
				</div>
			</div>
		</div>
		<div class="alert warn" data-fail-container style="display: none;">
			<div class="wpspx-row">
				<div class="message-body">
				There seems to be an issue, please try again or call the box office.
				</div>
			</div>
		</div>

	</spektrix-memberships>
	<?php
	return ob_get_clean();
}
add_shortcode( 'wpspx-membership', 'wpspxmembership' );
