<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_action( 'admin_menu', 'wpspx_add_admin_menu' );
add_action( 'admin_init', 'wpspx_settings_init' );

function wpspx_add_admin_menu(  ) {

	add_options_page( 'WPSPX', 'WPSPX', 'manage_options', 'wpspx', 'wpspx_options_page' );

}

function wpspx_settings_init(  ) {

	register_setting( 'wpspxPluginPage', 'wpspx_settings' );

	add_settings_section(
		'wpspx_wpspxPluginPage_section',
		__( 'Settings for your Spektrix API', 'wpspx' ),
		'wpspx_settings_section_callback',
		'wpspxPluginPage'
	);

	add_settings_field(
		'wpspx_account_name',
		__( 'Spektrix Account Name', 'wpspx' ),
		'wpspx_account_name_render',
		'wpspxPluginPage',
		'wpspx_wpspxPluginPage_section'
	);

	add_settings_field(
		'wpspx_api_key',
		__( 'Spektrix API', 'wpspx' ),
		'wpspx_api_key_render',
		'wpspxPluginPage',
		'wpspx_wpspxPluginPage_section'
	);

	add_settings_field(
		'wpspx_custom_domain',
		__( 'Spektrix Custom Domain', 'wpspx' ),
		'wpspx_custom_domain_render',
		'wpspxPluginPage',
		'wpspx_wpspxPluginPage_section'
	);

	add_settings_field(
		'wpspx_path_to_crt',
		__( 'Path to Spektrix CRT', 'wpspx' ),
		'wpspx_path_to_crt_render',
		'wpspxPluginPage',
		'wpspx_wpspxPluginPage_section'
	);

	add_settings_field(
		'wpspx_path_to_key',
		__( 'Parh to Spektrix KEY', 'wpspx' ),
		'wpspx_path_to_key_render',
		'wpspxPluginPage',
		'wpspx_wpspxPluginPage_section'
	);


}

// Client Account Name
function wpspx_account_name_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_account_name]' value='<?php echo $options['wpspx_account_name']; ?>'><br />
	<span>Your account name is the theatrename ius your admin interface url: https://system.spektrix.com/[your account name]/</span>
	<?php

}

// Client API Key
function wpspx_api_key_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' id='ssn' name='wpspx_settings[wpspx_api_key]' value='<?php echo $options['wpspx_api_key']; ?>'><br />
	<span>This will look something like this abcd1e12-f4g5-6789-h01i-2345678j910k</span>
	<?php

}

// Custom URL
function wpspx_custom_domain_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_custom_domain]' value='<?php echo $options['wpspx_custom_domain']; ?>'><br />
	<span>A custom domain such as tickets.theatre.com <a target="_blank" href="https://integrate.spektrix.com/docs/customdomains">Instructions to set this up</a></span>
	<?php

}

// Path to CRT
function wpspx_path_to_crt_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_path_to_crt]' value='<?php echo $options['wpspx_path_to_crt']; ?>'><br />
	<span>This needs to be a server path. Your path is <?php echo $_SERVER['DOCUMENT_ROOT']; ?></span>
	<?php

}

// Path to KEY
function wpspx_path_to_key_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_path_to_key]' value='<?php echo $options['wpspx_path_to_key']; ?>'><br />
	<span>This needs to be a server path. Your path is <?php echo $_SERVER['DOCUMENT_ROOT']; ?></span>
	<?php

}


function wpspx_settings_section_callback(  ) {

	echo __( 'Please populate the below fields with your API settings from your spektrix control panel. You will need a valid API key, your account name, a custom domain (if you have one setup) and the server path to your self hosted spektrix signed CRT & KEY.', 'wpspx' );

}


function wpspx_options_page(  ) {

		?>
		<form action='options.php' method='post' autocomplete="off">

			<div class="wpspx-wrapper">

				<?php
				if ( isset($_POST['cachebuster']) )
				{
					wpspk_bust_cache();
				}
				?>

				<header>
					<div class="logo">
						<img src="<?php echo WPSPX_PLUGIN_URL; ?>/lib/assets/logo.svg" alt="" width="160px">
					</div>
				</header>

				<article>

					<div class="tabs">

						<input type="radio" id="tab1" name="tab-control" checked>
						<input type="radio" id="tab2" name="tab-control">
						<input type="radio" id="tab3" name="tab-control">

						<ul>
							<li title="API Settings">
								<label for="tab1" role="button">
									<span>API Settings</span>
								</label>
							</li>
							<li title="Shipping">
								<label for="tab2" role="button">
									<span>Shipping</span>
								</label>
							</li>
							<li title="Support">
								<label for="tab3" role="button">
									<span>Support</span>
								</label>
							</li>
						</ul>

						<div class="slider"><div class="indicator"></div></div>

						<div class="content">
							<section>
								<h2>API Settings</h2>
								<?php
								settings_fields( 'wpspxPluginPage' );
								do_settings_sections( 'wpspxPluginPage' );
								submit_button();
								?>
							</section>
							<section>
								<h2>Delivery Contents</h2>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem quas adipisci a accusantium eius ut voluptatibus ad impedit nulla, ipsa qui. Quasi temporibus eos commodi aliquid impedit amet, similique nulla.
							</section>
							<section>
								<h2>Support</h2>
								<p>This software is provided for free and requires an basic undersatanding of WordPress &amp; Spektrix functionality. Spektrix <strong>WILL NOT</strong> provide any support for websites using this plugin. Instead you will need to raise a support ticket via the plugin support tab on wordpress.org.</p>

								<h3>WPSPX Pages</h3>
								<div class="pages">
								<?php
								$page_slugs = array(
									'basket',
									'checkout',
									'my-account',
									'book-online',
									'upcoming',
								);
								foreach ($page_slugs as $page_slug) {
									$page = get_page_by_path( $page_slug , OBJECT );
									if ( isset($page) )
										echo '<div class="found"><p>' . $page->post_title . '</p><svg id="Capa_1" enable-background="new 0 0 515.556 515.556" height="512" viewBox="0 0 515.556 515.556" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m0 274.226 176.549 176.886 339.007-338.672-48.67-47.997-290.337 290-128.553-128.552z"/></svg>
										</div>';
									else
										echo '<div class="not-found"><p>' . ucwords(str_replace("-"," ", $page_slug)) .'</p><svg id="Capa_1" enable-background="new 0 0 386.667 386.667" height="512" viewBox="0 0 386.667 386.667" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m386.667 45.564-45.564-45.564-147.77 147.769-147.769-147.769-45.564 45.564 147.769 147.769-147.769 147.77 45.564 45.564 147.769-147.769 147.769 147.769 45.564-45.564-147.768-147.77z"/></svg>
										</div>';
								}
								?>
								</div>

								<h3>Cache</h3>
								<p>Firstly, before troubleshooting anything, hit the clear cache button below to clear out the spektrix cached files.</p>
								<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
									<input type="submit" name="cachebuster" id="cachebuster" class="button" value="Clear Spektrix Cache">
								</form>
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}
