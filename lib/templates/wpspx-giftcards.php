<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix giftcards template
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-giftcards.php
 *
 */

 get_header();

?>

<div class="all-memberships">

	<div class="container">

		<spektrix-gift-vouchers client-name="<?php echo SPEKTRIX_USER; ?>" custom-domain="<?php echo SPEKTRIX_CUSTOM_URL; ?>" forward-to="<?php echo home_url( 'basket' ) ?>">

			<div class="fields">

				<div class="field">
					<label class="label">Amount</label>
					<div class="control">
						<input class="input" type="text" name="amount" placeholder="amount" data-amount>
					</div>
				</div>
				<div class="field">
					<label class="label">Send Date</label>
					<div class="control">
						<input class="input" type="date" name="sendDate" data-send-date>
					</div>
				</div>
				<div class="field">
					<label class="label">To</label>
					<div class="control">
						<input class="input" type="text" name="toname" data-to-name>
					</div>
				</div>
				<div class="field">
					<label class="label">From</label>
					<div class="control">
						<input class="input" type="text" name="fromname" data-from-name>
					</div>
				</div>
				<div class="field">
					<label class="label">Message</label>
					<div class="control">
						<textarea class="textarea" placeholder="e.g. Hello world" name="message" data-message></textarea>
					</div>
				</div>
				<div class="field">
					<label class="label">Delivery Type</label>
					<div class="select">
						<select name="deliveryType" data-delivery-type>
							<option value="CustomerEmail" selected>Customer Email</option>
							<option value="OtherEmail">Other Email</option>
						</select>
					</div>
				</div>
				<div class="field">
					<label class="label">Delivery Email Address</label>
					<div class="control">
						<input class="input" type="text" name="deliveryEmail" data-delivery-email-address>
					</div>
				</div>
			</div>

			<div class="control">
				<button class="button is-primary" data-submit-gift-voucher>Buy Gift Voucher</button>
			</div>

			<div class="message is-success" data-success-container style="display: none;">
				<div class="message-body">
					Sucessfully added to basket
				</div>
			</div>
			<div class="message is-warning" data-fail-container style="display: none;">
				<div class="message-body">
					There seems to be an issue, please try again or call the box office.
				</div>
			</div>

		</spektrix-gift-vouchers>

	</div>

</div>

<?php get_footer(); ?>
