<?php
/**
 * Memberships template
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



			<div data-success-container style="display: none;">Insert success content/markup here</div>
			<div data-fail-container style="display: none;">Insert failure content/markup here</div>

		</spektrix-memberships>

	<?php endforeach; ?>
	</div>

</div>

<?php get_footer(); ?>
