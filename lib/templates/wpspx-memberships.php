<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix memberships template
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-memberships.php
 *
 */

 get_header();

 $api = New Spektrix();
 $memberships = $api->get_memberships();

?>

<div class="all-memberships">

	<div class="columns is-multiline">
	<?php foreach ($memberships as $membership): ?>

		<spektrix-memberships
			class="column is-one-third"
			client-name="<?php echo SPEKTRIX_USER; ?>"
			custom-domain="<?php echo SPEKTRIX_CUSTOM_URL; ?>"
			membership-id="<?php echo $membership->id ?>">

			<div class="header">
				<h2><?php echo $membership->name ?></h2>
			</div>
			<div class="content">
				<?php echo wpautop($membership->htmlDescription); ?>
			</div>


			<div class="field">
				<div class="control">
					<button class="button is-primary" data-submit-membership>Buy membership</button>
				</div>
			</div>
			<div class="field">
				<label class="checkbox">
					<input class="" type="checkbox" name="autorenew" data-set-autorenew checked>
					Auto Renew Membership
				</label>
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

		</spektrix-memberships>

	<?php endforeach; ?>
	</div>

</div>

<?php get_footer(); ?>
