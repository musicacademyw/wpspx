<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_action( 'admin_menu', 'wpspx_add_admin_menu' );
add_action( 'admin_init', 'wpspx_settings_init' );

function wpspx_add_admin_menu(  ) {

	add_menu_page( 'WPSPX', 'WPSPX', 'manage_options', 'wpspx', 'wpspx_options_page' );

	add_submenu_page(
		'wpspx',									// parent slug
		'Data Sync',								// page title
		'Data Sync',								// menu title
		'manage_options',							// capability
		'wpspx-shows',								// slug
		'wpspx_shows_options_page' 					// callback
	);

	add_submenu_page(
		'wpspx',									// parent slug
		'Cache',									// page title
		'Cache',									// menu title
		'manage_options',							// capability
		'wpspx-cache',								// slug
		'wpspx_cache_options_page' 					// callback
	);

	add_submenu_page(
		'wpspx',									// parent slug
		'License',									// page title
		'License',									// menu title
			'manage_options',						// capability
		'wpspx-license',							// slug
		'wpspx_license_options_page' 				// callback
	);

	if (is_plugin_active('wpspx-basket/wp-spektrix-basket.php')):
	add_submenu_page(
		'wpspx',									// parent slug
		'Basket',									// page title
		'Basket',									// menu title
		'manage_options',							// capability
		'wpspx-basket',								// slug
		'wpspx_basket_options_page' 				// callback
	);
	endif;

	if (is_plugin_active('wpspx-login/wp-spektrix-login.php')):
	add_submenu_page(
		'wpspx',									// parent slug
		'Login',									// page title
		'Login',									// menu title
		'manage_options',							// capability
		'wpspx-login',								// slug
		'wpspx_login_options_page' 					// callback
	);
	endif;

	add_submenu_page(
		'wpspx',									// parent slug
		'Support',									// page title
		'Support',									// menu title
		'manage_options',							// capability
		'wpspx-support',							// slug
		'wpspx_support_options_page' 				// callback
	);

}

function wpspx_settings_init(  ) {

	// API Settings

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

	// Cache Setting
	register_setting( 'wpspxPluginPageCache', 'wpspx_cache_settings' );

	add_settings_section(
		'wpspx_wpspxPluginPageCache_section',
		__( 'Spektrix API Cache', 'wpspx' ),
		'wpspx_settings_cache_section_callback',
		'wpspxPluginPageCache'
	);

	add_settings_field(
		'wpspx_expire_cache',
		__( 'WPSPX Cache Length', 'wpspx' ),
		'wpspx_cache_expires_render',
		'wpspxPluginPageCache',
		'wpspx_wpspxPluginPageCache_section'
	);

	// Posts Settings
	register_setting( 'wpspxPluginPagePosts', 'wpspx_posts_settings' );
	register_setting( 'wpspxPluginPageCheckShows', 'wpspx_check_posts_settings' );

	add_settings_section(
		'wpspx_wpspxPluginPageCheckShows_section',
		'',
		'',
		'wpspxPluginPageCheckShows'
	);

	// Support Settings
	register_setting( 'wpspxPluginPageSupport', 'wpspx_support_settings' );

	add_settings_section(
		'wpspx_wpspxPluginPageSupport_section',
		__( 'WPSPX Support', 'wpspx' ),
		'wpspx_support_section_callback',
		'wpspxPluginPageSupport'
	);

	add_settings_field(
		'wpspx_disable_styles',
		__( 'Disable WPSPX Styles', 'wpspx' ),
		'wpspx_disable_styles_render',
		'wpspxPluginPageSupport',
		'wpspx_wpspxPluginPageSupport_section'
	);

	// License Settings
	register_setting( 'wpspxPluginPageLicense', 'wpspx_licence_settings' );

	add_settings_section(
		'wpspx_wpspxPluginLicense_section',
		__( 'License', 'wpspx' ),
		'wpspx_settings_license_section_callback',
		'wpspxPluginPageLicense'
	);

	add_settings_field(
		'wpspx_license_key',
		__( 'WPSPX License', 'wpspx' ),
		'wpspx_license_key_render',
		'wpspxPluginPageLicense',
		'wpspx_wpspxPluginLicense_section'
	);

}

// Client Account Name
function wpspx_account_name_render(  ) {

	$options = get_option( 'wpspx_settings' );

	$is_disabled = true;
	$license = get_option( 'wpspx_licence_settings' );
	$lkey = $license['wpspx_license_key'];
	if ($lkey) {
		$validation = wpspx_callback_validate($lkey);
		if ($validation->success == 1):
			$is_disabled = false;
		endif;
	}
	?>
	<input type='text' name='wpspx_settings[wpspx_account_name]' value='<?php echo $options['wpspx_account_name']; ?>'<?php disabled( $is_disabled ) ?>>
	<span>Your account name is the theatrename ius your admin interface url: https://system.spektrix.com/[your account name]/</span>
	<?php

}

// Client API Key
function wpspx_api_key_render(  ) {

	$options = get_option( 'wpspx_settings' );
	$key = $options['wpspx_api_key'];

	$is_disabled = true;
	$license = get_option( 'wpspx_licence_settings' );
	$lkey = $license['wpspx_license_key'];
	if ($lkey) {
		$validation = wpspx_callback_validate($lkey);
		if ($validation->success == 1):
			$is_disabled = false;
		endif;
	}
	?>
	<input type='text' id='ssn' name='wpspx_settings[wpspx_api_key]' value='<?php if ($key): ?>&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;&bull;&bull;<?php echo substr($key, -5,5); ?><?php endif; ?>' <?php disabled( $is_disabled ) ?>>
	<span>This will look something like this abcd1e12-f4g5-6789-h01i-2345678j910k</span>
	<?php

}

// Custom URL
function wpspx_custom_domain_render(  ) {

	$options = get_option( 'wpspx_settings' );

	$is_disabled = true;
	$license = get_option( 'wpspx_licence_settings' );
	$lkey = $license['wpspx_license_key'];
	if ($lkey) {
		$validation = wpspx_callback_validate($lkey);
		if ($validation->success == 1):
			$is_disabled = false;
		endif;
	}
	?>
	<input type='text' name='wpspx_settings[wpspx_custom_domain]' value='<?php echo $options['wpspx_custom_domain']; ?>' <?php disabled( $is_disabled ) ?>>
	<span>A custom domain such as tickets.theatre.com <a target="_blank" href="https://integrate.spektrix.com/docs/customdomains">Instructions to set this up</a></span>
	<?php

}

// Disbale WPSPX Styles
function wpspx_disable_styles_render(  ) {

	$options = get_option( 'wpspx_support_settings' );
	?>
	<input type='checkbox' class="checkbox" value="1" name='wpspx_support_settings[wpspx_disable_styles]' <?php checked( 1 == isset($options['wpspx_disable_styles'] )); ?>>
	<?php

}

// Disbale WPSPX Styles
function wpspx_cache_expires_render(  ) {

	$options = get_option( 'wpspx_cache_settings' );
	?>
	<select class="wpspx_expire_cache" name="wpspx_support_settings[wpspx_expire_cache]">
		<option value="3600">1 Hour</option>
		<option value="21600">6 Hours</option>
		<option value="43200">12 Hours</option>
		<option value="86400">1 Day</option>
		<option value="604800">1 Week</option>
	</select>
	<?php

}


function wpspx_support_section_callback(  ) {

	echo __( '<p>Please review the documentation first. If you still can\'t find the answer open a support ticket and we will be happy to answer 1your questions and assist you with any problems. Please note: If you have not purchased a license from us, you will not have access to these help resources.</p>', 'wpspx' );

}

function wpspx_settings_section_callback(  ) {

	echo __( '<p>Please populate the below fields with your API settings from your Spektrix control panel. You will need a valid API key, your account name, a custom domain (if you have one setup) and the server path to your self hosted Spektrix signed CRT & KEY.</p>', 'wpspx' );

}

function wpspx_settings_cache_section_callback(  ) {

	echo __( '<p>Clear you WPSPX Cache files. This will completely remove all cached data from the server. <strong>Proceed with caution</strong> as this will cause your site to run slowly untill the cache has been rebuilt. </p>', 'wpspx' );

}

function wpspx_settings_license_section_callback(  ) {

	echo __( '<p>You can find your license key contained inside your payment confirmation email.</p>', 'wpspx' );

}

function wpspx_license_key_render() {


	$message = "";
	$validation = null;
	$license = get_option( 'wpspx_licence_settings' );
	$key = $license['wpspx_license_key'];
	if ($key) {
		$validation = wpspx_callback_validate($key);
		if ($validation->success == 1):
			if ($validation->data->remainingActivations > 1) {
				$activate = wpspx_callback_activate($key);
			}
			$licenseinfo = wpspx_callback_retrieve($key);
			$message = "<span class='active'>Licence active</span><br>
			<span>Remaining ctivations: ".$validation->data->remainingActivations."</span><br /><span>Valid for: ".$licenseinfo->data->validFor." days</span>";
		else:
			delete_site_option( 'wpspx_licence_settings' );
			$message = "<span class='error'>Invalid License entered.</span>";
		endif;
	}
	?>
	<input type='text' id='ssn' name='wpspx_licence_settings[wpspx_license_key]' value='<?php if ($key): ?>&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;<?php echo substr($key, -3,3); ?><?php endif; ?>'>
	<?php
	echo $message;


}


function wpspx_settings_posts_section_callback(  ) {

	// Grab all future shows in spektrix and all shows in WordPress
	$shows_in_spektrix = Show::find_all_in_future_with_instances();
	$shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1));

	$wp_shows = array();
	foreach($shows_in_wordpress as $siw){
		$wp_shows[] = get_post_meta($siw->ID,'_spektrix_id',true);
	}

	foreach($shows_in_spektrix as $show):
		if(!in_array($show->id,$wp_shows)){
			$spektrix_id = $show->id;
			$spektrix_name = $show->name;
			$spektrix_short_description = $show->short_description;
			$api = new Spektrix();
			$performances = $show->get_performances();
			foreach ($performances as $performance) {
				$pricelists = $api->get_price_list($performance->id);
				$availabilities = $api->get_availability($performance->id);
			}
			$firstdate = reset($performances);
		    $lastdate = end($performances);

			// Create post object
			$my_post = array(
				'post_type'		=> 'shows',
				'post_title'    => $spektrix_name,
				'post_content'  => $spektrix_short_description,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'meta_input' 	=> array(
                    '_spektrix_id'	=> $spektrix_id,
                    '_spektrix_start'	=> $firstdate->start_time->format('U'),
                    '_spektrix_end'	=> $lastdate->start_time->format('U'),
                ),
			);

			// Insert the post into the database
			wp_insert_post( $my_post );
		}
	endforeach;
	flush_rewrite_rules( );

}

function wpspx_settings_initial_posts_section_callback(  ) {

	// Grab all future shows in spektrix and all shows in WordPress
	$shows_in_spektrix = Show::find_all_in_future();

}


function wpspx_options_page(  ) {

		?>
		<form action='options.php' method='post' autocomplete="off">

			<div class="wpspx-wrapper">

				<header>
					<div class="logo">
						<img src="<?php echo WPSPX_PLUGIN_URL; ?>/lib/assets/logo.svg" alt="" width="160px">
					</div>
					<nav>
						<ul>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx' ?>">API Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Data Sync</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
							<?php if (is_plugin_active('wpspx-basket/wp-spektrix-basket.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-basket' ?>">Basket</a></li>
							<?php endif; ?>
							<?php if (is_plugin_active('wpspx-login/wp-spektrix-login.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-login' ?>">Login</a></li>
							<?php endif; ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-license' ?>">License</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php if (isset($_GET['settings-updated'])):
						wpspx_settings_initial_posts_section_callback();
						?>
					<div class="notice notice-success is-dismissible">
						<p><strong>Settings saved.</strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>
					<?php endif; ?>

					<div class="tab">

						<div class="content">
							<section>
								<?php
								settings_fields( 'wpspxPluginPage' );
								do_settings_sections( 'wpspxPluginPage' );
								$license = get_option( 'wpspx_licence_settings' );
								$key = $license['wpspx_license_key'];
								if ($key) {
									$validation = wpspx_callback_validate($key);
									if ($validation->success == 1):
										submit_button();
									else:
										echo '<input disabled type="submit" name="disbaled" id="disbaled" class="button button-large" value="Please Register WPSPX to Update">';
									endif;
								}
								?>
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}

function wpspx_cache_options_page(  ) {

		?>
		<form action='options.php' method='post' autocomplete="off">

			<div class="wpspx-wrapper">

				<header>
					<div class="logo">
						<img src="<?php echo WPSPX_PLUGIN_URL; ?>/lib/assets/logo.svg" alt="" width="160px">
					</div>
					<nav>
						<ul>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx' ?>">API Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Data Sync</a></li>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
							<?php if (is_plugin_active('wpspx-basket/wp-spektrix-basket.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-basket' ?>">Basket</a></li>
							<?php endif; ?>
							<?php if (is_plugin_active('wpspx-login/wp-spektrix-login.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-login' ?>">Login</a></li>
							<?php endif; ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-license' ?>">License</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php
					if (isset($_GET['settings-updated'])):
						$cached_files = WP_CONTENT_DIR . '/wpspx-cache/*.json';
						try
						{
							// array_map('unlink', glob($cached_files)); ?>
							<div class="notice notice-success is-dismissible">
								<p><strong>Settings saved.</strong></p>
								<button type="button" class="notice-dismiss">
									<span class="screen-reader-text">Dismiss this notice.</span>
								</button>
							</div>
							<?php
						} catch (Exception $e)
						{
							?>
							<div class="notice notice-warning">
								<p><strong><?php echo  $e->getMessage() . '\n'; ?>.</strong></p>
							</div>
							<?php
						}
					endif;
					?>

					<div class="tab">

						<div class="content">
							<section>
								<?php
								settings_fields( 'wpspxPluginPageCache' );
								do_settings_sections( 'wpspxPluginPageCache' );
								$cached_dir = WP_CONTENT_DIR . '/wpspx-cache/';
								$cached_files = glob($cached_dir . "*.json");

								$license = get_option( 'wpspx_licence_settings' );
								$key = $license['wpspx_license_key'];
								if ($key) {
									$validation = wpspx_callback_validate($key);
									if ($validation->success == 1):
										submit_button("Save Cache Settings");
									else:
										echo '<input disabled type="submit" name="disbaled" id="disbaled" class="button button-large" value="Please Register WPSPX to Update">';
									endif;
								}
								?>
								<h4>
									There are currnelty <?php echo count($cached_files); ?> cached files
								</h4>
								<?php
								if ($key) {
									$validation = wpspx_callback_validate($key);
									if ($validation->success == 1):
										submit_button("Clear Cache", "large", "clear-cache");
									else:
										echo '<input disabled type="submit" name="disbaled" id="disbaled" class="button button-large" value="Please Register WPSPX to Update">';
									endif;
								}
								?>
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}

function wpspx_support_options_page(  ) {

		?>
		<form action='options.php' method='post' autocomplete="off">

			<div class="wpspx-wrapper">

				<header>
					<div class="logo">
						<img src="<?php echo WPSPX_PLUGIN_URL; ?>/lib/assets/logo.svg" alt="" width="160px">
					</div>
					<nav>
						<ul>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx' ?>">API Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Data Sync</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
							<?php if (is_plugin_active('wpspx-basket/wp-spektrix-basket.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-basket' ?>">Basket</a></li>
							<?php endif; ?>
							<?php if (is_plugin_active('wpspx-login/wp-spektrix-login.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-login' ?>">Login</a></li>
							<?php endif; ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-license' ?>">License</a></li>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php if (isset($_GET['settings-updated'])): ?>
					<div class="notice notice-success is-dismissible">
						<p><strong>Settings saved.</strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>
					<?php endif; ?>

					<div class="tab">

						<div class="content">
							<section>
								<?php
								settings_fields( 'wpspxPluginPageSupport' );
								do_settings_sections( 'wpspxPluginPageSupport' );
								?>
								<br /><br /><?php
								$license = get_option( 'wpspx_licence_settings' );
								$key = $license['wpspx_license_key'];
								if ($key) {
									$validation = wpspx_callback_validate($key);
									if ($validation->success == 1):
										submit_button('Save Settings');
									else:
										echo '<input disabled type="submit" name="disbaled" id="disbaled" class="button button-large" value="Please Register WPSPX to Update">';
									endif;
								}
								?>

								<h2>Links</h2>
								<ul>
									<li><a href="#">Documentation</a></li>
								</ul>

								<h2>Debug Info</h2>
								<?php
									// plugin info
									$wpspx_info = get_plugin_data( WPSPX_PLUGIN_DIR . '/wp-spektrix.php' );

									// Show info
									$shows_in_spektrix = Show::find_all_in_future();
									$shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1));
									$wp_shows = array();
									foreach($shows_in_wordpress as $siw){
										$wp_shows[] = get_post_meta($siw->ID,'_spektrix_id',true);
									}


								?>
								<div class="debug_log">
								<?php global $wpdb; ?>
									<dl>
										<dt>site_url():</dt>
										<dd><?php echo site_url(); ?></dd>
										<dt>home_url():</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>Database Name</dt>
										<dd><?php echo DB_NAME ?></dd>
										<dt>Table Prefix</dt>
										<dd><?php echo $wpdb->prefix; ?></dd>
									</dl>
									<dl>
										<dt>WordPress Version</dt>
										<dd><?php bloginfo('version'); ?></dd>
									</dl>
									<dl>
										<dt>WPSPX Version</dt>
										<dd><?php echo $wpspx_info['Version']; ?></dd>
										<dt>Shows in Spektrix (in the future)</dt>
										<dd><?php echo count($shows_in_spektrix); ?></dd>
										<dt>Shows in WordPress</dt>
										<dd><?php echo count($wp_shows); ?></dd>
									</dl>
									<dl>
										<dt>Web Server</dt>
										<dd><?php echo $_SERVER['SERVER_SOFTWARE']; ?></dd>
										<dt>PHP</dt>
										<dd><?php echo phpversion(); ?></dd>
										<dt>WP Memory Limit</dt>
										<dd><?php echo WP_MEMORY_LIMIT; ?></dd>
										<dt>PHP Time Limit</dt>
										<dd><?php echo ini_get('max_execution_time'); ?></dd>
										<dt>SSL</dt>
										<dd><?php echo $_SERVER['HTTPS']; ?></dd>
									</dl>
									<dl>
										<dt>Debug Mode</dt>
										<dd><?php if (defined('WP_DEBUG') && true === WP_DEBUG) {
											echo 'Enabled';
										} else {
											echo 'Disabled';
										}; ?></dd>
										<dt>Debug Log</dt>
										<dd><?php if (defined('WP_DEBUG_LOG') && true === WP_DEBUG) {
											echo 'Enabled';
										} else {
											echo 'Disabled';
										}; ?></dd>
										<dt>Script Debug</dt>
										<dd><?php if (defined('SCRIPT_DEBUG') && true === WP_DEBUG) {
											echo 'Enabled';
										} else {
											echo 'Disabled';
										}; ?></dd>
									</dl>
									<dl>
										<dt>WP Max Upload Size</dt>
										<dd><?php echo ini_get('upload_max_size'); ?></dd>
										<dt>PHP Post Max Size</dt>
										<dd><?php echo ini_get('post_max_size'); ?></dd>
									</dl>
									<dl>
										<dt>SPEKTRIX_USER</dt>
										<dd><?php echo SPEKTRIX_USER; ?></dd>
										<dt>SPEKTRIX_CERT</dt>
										<dd><?php echo SPEKTRIX_CERT; ?></dd>
										<dt>SPEKTRIX_KEY</dt>
										<dd><?php echo SPEKTRIX_KEY; ?></dd>
										<dt>SPEKTRIX_API</dt>
										<dd><?php echo SPEKTRIX_API; ?></dd>
										<dt>SPEKTRIX_CUSTOM_URL</dt>
										<dd><?php echo SPEKTRIX_CUSTOM_URL; ?></dd>
										<dt>SPEKTRIX_API_URL</dt>
										<dd><?php echo SPEKTRIX_API_URL; ?></dd>
										<dt>SPEKTRIX_SECURE_WEB_URL</dt>
										<dd><?php echo SPEKTRIX_SECURE_WEB_URL; ?></dd>
										<dt>SPEKTRIX_NON_SECURE_WEB_URL</dt>
										<dd><?php echo SPEKTRIX_NON_SECURE_WEB_URL; ?></dd>
										<dt>WP_CONTENT_URL</dt>
										<dd><?php echo WP_CONTENT_URL; ?></dd>
										<dt>WP_CONTENT_DIR</dt>
										<dd><?php echo WP_CONTENT_DIR; ?></dd>
										<dt>WP_PLUGIN_DIR</dt>
										<dd><?php echo WP_PLUGIN_DIR; ?></dd>
										<dt>WP_PLUGIN_URL</dt>
										<dd><?php echo WP_PLUGIN_URL; ?></dd>
									</dl>
									<dl>
										<dt>Active Theme Name:</dt>
										<dd><?php echo wp_get_theme(); ?></dd>
									</dl>
									<dl>
										<dt>Active Plugins</dt>
										<dd>
											<?php
											$apl = get_option('active_plugins');
											$plugins = get_plugins();
											$activated_plugins = array();
											foreach ($apl as $p){
												if(isset($plugins[$p])){
													array_push($activated_plugins, $plugins[$p]);
												}
											}
											?>
											<ul>
											<?php
											foreach ($activated_plugins as $activated_plugin) {
												echo '<li>' . $activated_plugin['Name'] . '</li>';
											}
											?>
											</ul>
										</dd>
									</dl>

								</div>

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
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}

function wpspx_shows_options_page(  ) {
		?>
		<form action='options.php' method='post' autocomplete="off">

			<div class="wpspx-wrapper">

				<header>
					<div class="logo">
						<img src="<?php echo WPSPX_PLUGIN_URL; ?>/lib/assets/logo.svg" alt="" width="160px">
					</div>
					<nav>
						<ul>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx' ?>">API Settings</a></li>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Data Sync</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
							<?php if (is_plugin_active('wpspx-basket/wp-spektrix-basket.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-basket' ?>">Basket</a></li>
							<?php endif; ?>
							<?php if (is_plugin_active('wpspx-login/wp-spektrix-login.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-login' ?>">Login</a></li>
							<?php endif; ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-license' ?>">License</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php
					if (isset($_GET['settings-updated'])):
					wpspx_settings_posts_section_callback();
					?>
					<div class="notice notice-success is-dismissible">
						<p><strong>Shows Synced.</strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>
					<?php endif; ?>

					<div class="tab">

						<div class="content">
							<section>
								<h2>Data Sync</h2>
								<p>
									Below you will see how many shows are available to sync from Spektrix. By default this is set to all shows in the future.
								</p>
								<?php
								$shows_in_spektrix = Show::find_all_in_future();
								$shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1));

						    	// Create an array of IDs of shows in WP.
								// (We use this to ensure we don't ask the user to choose a shows in Spectrix that has already been added to WP)
								$wp_shows = array();
								foreach($shows_in_wordpress as $siw){
									$wp_shows[] = get_post_meta($siw->ID,'_spektrix_id',true);
								}
								?>
								<div class="header">
									<div class="showsinspk half">
										<p>Shows Available in Spektrix</p>
										<span><?php echo count($shows_in_spektrix); ?></span>
									</div>
									<div class="showsinwp half">
										<p>Shows synned already</p>
										<span><?php echo count($wp_shows); ?></span>
									</div>
								</div>
								<p>
									<strong>
										Please note this can take up to 5 minutes to complete.
									</strong>
								</p>
								<?php
								settings_fields( 'wpspxPluginPagePosts' );
								do_settings_sections( 'wpspxPluginPagePosts' );
								$showstosync = count($shows_in_spektrix) - count($wp_shows);
								$synctext =  'Sync '.$showstosync.' Shows';
								$other_attributes = array();
								if ($showstosync < 1) {
									$synctext =  'All Available Shows Synced';
									$other_attributes = array( 'disabled' => 'disabled' );
								}

								?>
								<div class="loading">
									<div class="spin"></div>
								</div>
								<?php
								$license = get_option( 'wpspx_licence_settings' );
								$key = $license['wpspx_license_key'];
								if ($key) {
									$validation = wpspx_callback_validate($key);
									if ($validation->success == 1):
										submit_button($synctext, 'primary', 'publishshows', true );
									else:
										echo '<input disabled type="submit" name="disbaled" id="disbaled" class="button button-large" value="Please Register WPSPX to Update">';
									endif;
								}
								?>
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}

function wpspx_license_options_page(  ) {
		?>
		<form action='options.php' method='post' autocomplete="off">

			<div class="wpspx-wrapper">

				<header>
					<div class="logo">
						<img src="<?php echo WPSPX_PLUGIN_URL; ?>/lib/assets/logo.svg" alt="" width="160px">
					</div>
					<nav>
						<ul>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx' ?>">API Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Data Sync</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
							<?php if (is_plugin_active('wpspx-basket/wp-spektrix-basket.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-basket' ?>">Basket</a></li>
							<?php endif; ?>
							<?php if (is_plugin_active('wpspx-login/wp-spektrix-login.php')): ?>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-login' ?>">Login</a></li>
							<?php endif; ?>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-license' ?>">License</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php if (isset($_GET['settings-updated'])): ?>
					<div class="notice notice-success is-dismissible">
						<p><strong>Licenxe Saved.</strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>
					<?php endif; ?>

					<div class="tab">

						<div class="content">
							<section>
								<?php
								settings_fields( 'wpspxPluginPageLicense' );
								do_settings_sections( 'wpspxPluginPageLicense' );
								?>
								<br /><br /><?php
								submit_button('Activate Licence');
								?>
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}
