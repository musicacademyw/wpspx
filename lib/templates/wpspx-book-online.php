<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Spektrix booking page tempkate
 *
 * To overwrite this template copy this file to your theme under /wpspx/wpspx-book-online.php
 *
 */
get_header();
?>

<div class="book-online">

	<div class="wpspx-container">

		<div class="wpspx-row">

			<?php
			$performance = get_query_var('performance');
			if(!$performance):
				?>
				<div class="wpspx-column">
					<!-- Enter a message for those trying to access directly. -->
					<h1>Opps.</h1>
					<p>Seems you tried to access the booking page without choosing a performance.</p>
					<a class="button btn" href="<?php echo home_url(); ?>">Browse all available shows</a>
				</div>
			<?php

			else:
				$spektrix_iframe_url = new iFrame('ChooseSeats',array('EventInstanceId' => $performance));
				echo $spektrix_iframe_url->render_iframe();
			endif;
			?>

		</div>

	</div>

</div>

<?php get_footer(); ?>
