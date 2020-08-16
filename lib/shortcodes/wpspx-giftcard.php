<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix giftcard / voucher shoertcode
 *
 *
 */

function wpspxgiftcards()
{
	ob_start();
	?>
	<spektrix-gift-vouchers client-name="<?php echo WPSPX_SPEKTRIX_USER; ?>" custom-domain="<?php echo WPSPX_SPEKTRIX_CUSTOM_URL; ?>">

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

		<div class="wpspx-row wpspx-row-wrap">

			<div class="wpspx-membership-field wpspx-column wpspx-column-50">
				<label class="label">Amount</label>
				<div class="control">
					<input class="input" type="text" name="amount" placeholder="£20" data-amount>
				</div>
			</div>
			<div class="wpspx-membership-field wpspx-column wpspx-column-50">
				<label class="label">Send Date</label>
				<div class="control">
					<input class="input" type="date" name="sendDate" data-send-date>
				</div>
			</div>
			<div class="wpspx-membership-field wpspx-column wpspx-column-50">
				<label class="label">To</label>
				<div class="control">
					<input class="input" type="text" name="toname" data-to-name>
				</div>
			</div>
			<div class="wpspx-membership-field wpspx-column wpspx-column-50">
				<label class="label">From</label>
				<div class="control">
					<input class="input" type="text" name="fromname" data-from-name>
				</div>
			</div>
			<div class="wpspx-membership-field wpspx-column">
				<label class="label">Message</label>
				<div class="control">
					<textarea class="textarea" placeholder="e.g. Hello world" name="message" data-message></textarea>
				</div>
			</div>
			<div class="wpspx-membership-field ext-email wpspx-column wpspx-column-50">
				<label class="label">Delivery Type</label>
				<div class="select">
					<select name="deliveryType" data-delivery-type>
						<option value="CustomerEmail" selected>Customer Email</option>
						<option value="OtherEmail">Other Email</option>
					</select>
				</div>
			</div>
			<div class="wpspx-membership-field wpspx-column wpspx-column-50">
				<label class="label">Delivery Email Address</label>
				<div class="control">
					<input disabled class="input" type="text" name="deliveryEmail" data-delivery-email-address>
				</div>
			</div>

		</div>

		<div class="control wpspx-row">
			<div class="wpspx-column">
				<button class="button btn button-primary" data-submit-gift-voucher>Buy Gift Voucher</button>
			</div>
		</div>

	</spektrix-gift-vouchers>
	<?php
	return ob_get_clean();
}
add_shortcode( 'wpspx-giftcard', 'wpspxgiftcards' );
