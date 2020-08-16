<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_action( 'admin_menu', 'wpspx_add_admin_menu' );
add_action( 'admin_init', 'wpspx_settings_init' );

function wpspx_add_admin_menu(  ) {

	add_menu_page(
		'WPSPX',
		'WPSPX',
		'manage_options',
		'wpspx',
		'wpspx_options_page',
		plugins_url( 'wpspx/lib/assets/icon.svg' )
	);

	add_submenu_page(
		'wpspx',									// parent slug
		'Data Sync',								// page title
		'Data Sync',								// menu title
		'manage_options',							// capability
		'wpspx-shows',								// slug
		'wpspx_shows_options_page', 				// callback
		null
	);

	add_submenu_page(
		'wpspx',									// parent slug
		'Cache',									// page title
		'Cache',									// menu title
		'manage_options',							// capability
		'wpspx-cache',								// slug
		'wpspx_cache_options_page', 				// callback
		null
	);

	if (is_plugin_active('wpspx-basket/wp-spektrix-basket.php')):
	add_submenu_page(
		'wpspx',									// parent slug
		'Basket',									// page title
		'Basket',									// menu title
		'manage_options',							// capability
		'wpspx-basket',								// slug
		'wpspx_basket_options_page', 				// callback
		null
	);
	endif;

	if (is_plugin_active('wpspx-login/wp-spektrix-login.php')):
	add_submenu_page(
		'wpspx',									// parent slug
		'Login',									// page title
		'Login',									// menu title
		'manage_options',							// capability
		'wpspx-login',								// slug
		'wpspx_login_options_page', 				// callback
		null
	);
	endif;

	add_submenu_page(
		'wpspx',									// parent slug
		'Support',									// page title
		'Support',									// menu title
		'manage_options',							// capability
		'wpspx-support',							// slug
		'wpspx_support_options_page', 				// callback
		null
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

	// add_settings_field(
	// 	'wpspx_api_useername',
	// 	__( 'Spektrix API Username', 'wpspx' ),
	// 	'wpspx_api_username_render',
	// 	'wpspxPluginPage',
	// 	'wpspx_wpspxPluginPage_section'
	// );

	// add_settings_field(
	// 	'wpspx_api_key',
	// 	__( 'Spektrix API', 'wpspx' ),
	// 	'wpspx_api_key_render',
	// 	'wpspxPluginPage',
	// 	'wpspx_wpspxPluginPage_section'
	// );

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
}

// Client Account Name
function wpspx_account_name_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_account_name]' value='<?php echo $options['wpspx_account_name']; ?>'>
	<?php
	echo __( '<span>Your account name is the theatre name in your admin interface url: https://system.spektrix.com/[your account name]/</span>', 'wpspx' );
}

// Client API Key
function wpspx_api_username_render(  ) {

	$options = get_option( 'wpspx_settings' );
	$username = $options['wpspx_api_username'];
	?>
	<input type='text' id='ssn' name='wpspx_settings[wpspx_api_username]' value='<?php if ($username): echo $username; endif; ?>'>
	<?php
	echo __( '<span>You will need to request an API Username though your spektrix account manager.</span>', 'wpspx' );

}

// Client API Key
function wpspx_api_key_render(  ) {

	$options = get_option( 'wpspx_settings' );
	$key = $options['wpspx_api_key'];
	?>
	<textarea rows='7' cols='50' type='textarea' id='ssn' style="width: 100%;margin-bottom: 10px;" name='wpspx_settings[wpspx_api_key]' value=''><?php if ($key): echo $key;  endif; ?></textarea>
	<?php
	echo __( '<span>You will need to request an API key though your spektrix account manager.</span>', 'wpspx' );
}

// Custom URL
function wpspx_custom_domain_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_custom_domain]' value='<?php echo $options['wpspx_custom_domain']; ?>'>
	<?php
	echo __( '<span>A custom domain such as tickets.theatre.com <a target="_blank" href="https://integrate.spektrix.com/docs/customdomains">Follow these instructions to set this up</a></span>', 'wpspx' );
}

// Disbale WPSPX Styles
function wpspx_disable_styles_render(  ) {

	$options = get_option( 'wpspx_support_settings' );
	echo __( '<p>WPSPX uses the Milligram CSS framework. If you experience issues with your theme, please disable the plugin\'s CSS and compose your own style.</p>', 'wpspx' );
	?>
	<input type='checkbox' class="checkbox" value="1" name='wpspx_support_settings[wpspx_disable_styles]' <?php checked( 1 == isset($options['wpspx_disable_styles'] )); ?>>
	<?php

}

// Disbale WPSPX Styles
function wpspx_cache_expires_render(  ) {

	?>
	<select class="wpspx_expire_cache" name="wpspx_cache_settings[wpspx_expire_cache]">
		<option value="3600" <?php if (selected( WPSPXCACHEVALIDFOR, 3600 )); ?>>1 Hour</option>
		<option value="21600 <?php if (selected( WPSPXCACHEVALIDFOR, 21600 )); ?>">6 Hours</option>
		<option value="43200" <?php if (selected( WPSPXCACHEVALIDFOR, 43200 )); ?>>12 Hours</option>
		<option value="86400" <?php if (selected( WPSPXCACHEVALIDFOR, 86400 )); ?>>1 Day</option>
		<option value="604800" <?php if (selected( WPSPXCACHEVALIDFOR, 604800 )); ?>>1 Week</option>
	</select>
	<?php

}


function wpspx_support_section_callback(  ) {

	echo __( '<p>Please review the <a href="https://docs.wpspx.io" target="_blank" >documentation</a> first. If you can\'t find the answer open a support ticket and we will be happy to answer your questions or assist you with any problems.</p>', 'wpspx' );

}

function wpspx_settings_section_callback(  ) {

	echo __( '<p>Please populate the below fields with your API credentials from your Spektrix control panel. You will need a valid account name &amp; a custom domain.</p>', 'wpspx' );

}

function wpspx_settings_cache_section_callback(  ) {

	echo __( '<p>Please select how long you would like your Spektrix API data to be cached locally on your server.</p>', 'wpspx' );
	echo __( '<p><strong>Cache currently set for: '.secondsToWords(WPSPXCACHEVALIDFOR).'</strong></p>', 'wpspx' );

}

function wpspx_settings_initial_posts_section_callback(  ) {

	// Grab all future shows in spektrix and all shows in WordPress
	$shows_in_spektrix = WPSPX_Show::find_all_in_future();

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
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php if (isset($_GET['settings-updated'])): ?>
					<div class="notice notice-success is-dismissible">
						<p><strong><?php echo __( 'Settings Saved', 'wpspx' ); ?></strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text"><?php echo __( 'Dismiss', 'wpspx' ); ?></span>
						</button>
					</div>
					<?php endif; ?>

					<div class="tab">

						<div class="content">
							<section>
								<?php
								settings_fields( 'wpspxPluginPage' );
								do_settings_sections( 'wpspxPluginPage' );
								submit_button();
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
							array_map('unlink', glob($cached_files)); ?>
							<div class="notice notice-success is-dismissible">
								<p><strong><?php echo __( 'Settings Saved', 'wpspx' ); ?></strong></p>
								<button type="button" class="notice-dismiss">
									<span class="screen-reader-text"><?php echo __( 'Dismiss', 'wpspx' ); ?></span>
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
								submit_button("Save Cache Settings");
								?>
								<p>
									<?php echo __( 'Clearing this cache completely remove all cached API data data from the server. <strong>Proceed with caution</strong> as this will cause your site to run slowly untill the cache has been rebuilt.', 'wpspx' ); ?>
								</p>
								<h4>
									<?php echo __( 'There are currnelty '. count($cached_files) .' cached files', 'wpspx' ); ?>
								</h4>
								<?php
									submit_button("Clear Cache", "large", "clear-cache");
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
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php if (isset($_GET['settings-updated'])): ?>
					<div class="notice notice-success is-dismissible">
						<p><strong><?php echo __( 'Settings Saved', 'wpspx' ); ?></strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text"><?php echo __( 'Dismiss', 'wpspx' ); ?></span>
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
								submit_button('Save Settings');
								?>
								<h2><?php echo __( 'API Data', 'wpspx' ); ?></h2>
								<h3><?php echo __( 'Memberships', 'wpspx' ); ?></h3>
								<?php
								$api = New WPSPX_Spektrix();
								$memberships = $api->get_memberships();
								?>
								<dl>
									<dt><?php echo __( 'Name', 'wpspx' ); ?></dt>
									<dd><?php echo __( 'ID', 'wpspx' ); ?></dd>
									<?php foreach ($memberships as $membership): ?>
										<dt><?php echo $membership->name ?></dt>
										<dd><?php echo $membership->id ?></dd>
									<?php endforeach; ?>
								</dl>

								<h3><?php echo __( 'Shows', 'wpspx' ); ?></h3>
								<?php
								$shows = WPSPX_Show::find_all_in_future_with_instances();
							    $wp_shows = get_wp_shows_from_spektrix_shows($shows);
							    $shows = filter_published($shows,$wp_shows);
								?>
								<dl>
									<dt><?php echo __( 'Name', 'wpspx' ); ?></dt>
									<dd><?php echo __( 'ID', 'wpspx' ); ?></dd>
									<?php foreach($shows as $show): ?>
										<dt><?php echo $show->name ?></dt>
										<dd><?php echo $show->id ?></dd>
									<?php endforeach; ?>
								</dl>


								<h2><?php echo __( 'Dubug Info', 'wpspx' ); ?></h2>
								<?php
									// plugin info
									$wpspx_info = get_plugin_data( WPSPX_PLUGIN_DIR . '/wp-spektrix.php' );

									// Show info
									$shows_in_spektrix = WPSPX_Show::find_all_in_future();
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
										<dt><?php echo __( 'Database Name', 'wpspx' ); ?></dt>
										<dd><?php echo DB_NAME ?></dd>
										<dt><?php echo __( 'Table Prefix', 'wpspx' ); ?></dt>
										<dd><?php echo $wpdb->prefix; ?></dd>
									</dl>
									<dl>
										<dt><?php echo __( 'WordPress Version', 'wpspx' ); ?></dt>
										<dd><?php bloginfo('version'); ?></dd>
									</dl>
									<dl>
										<dt><?php echo __( 'WPSPX Version', 'wpspx' ); ?></dt>
										<dd><?php echo $wpspx_info['Version']; ?></dd>
										<dt><?php echo __( 'Shows in Spektrix (in the future)', 'wpspx' ); ?></dt>
										<dd><?php echo count($shows_in_spektrix); ?></dd>
										<dt><?php echo __( 'Shows in Spektrix', 'wpspx' ); ?></dt>
										<dd><?php echo count($wp_shows); ?></dd>
									</dl>
									<dl>
										<dt><?php echo __( 'Web Server', 'wpspx' ); ?></dt>
										<dd><?php echo $_SERVER['SERVER_SOFTWARE']; ?></dd>
										<dt><?php echo __( 'PHP Version', 'wpspx' ); ?></dt>
										<dd><?php echo phpversion(); ?></dd>
										<dt><?php echo __( 'WP Memory Limit', 'wpspx' ); ?></dt>
										<dd><?php echo WP_MEMORY_LIMIT; ?></dd>
										<dt><?php echo __( 'Max Execution Time', 'wpspx' ); ?></dt>
										<dd><?php echo ini_get('max_execution_time'); ?></dd>
										<dt><?php echo __( 'SSL', 'wpspx' ); ?></dt>
										<dd><?php echo $_SERVER['HTTPS']; ?></dd>
									</dl>
									<dl>
										<dt><?php echo __( 'Debug Mode', 'wpspx' ); ?></dt>
										<dd><?php if (defined('WP_DEBUG') && true === WP_DEBUG) {
											echo 'Enabled';
										} else {
											echo 'Disabled';
										}; ?></dd>
										<dt><?php echo __( 'Debug Log', 'wpspx' ); ?></dt>
										<dd><?php if (defined('WP_DEBUG_LOG') && true === WP_DEBUG) {
											echo 'Enabled';
										} else {
											echo 'Disabled';
										}; ?></dd>
										<dt><?php echo __( 'Script Debug', 'wpspx' ); ?></dt>
										<dd><?php if (defined('SCRIPT_DEBUG') && true === WP_DEBUG) {
											echo 'Enabled';
										} else {
											echo 'Disabled';
										}; ?></dd>
									</dl>
									<dl>
										<dt><?php echo __( 'Max Upload Size', 'wpspx' ); ?></dt>
										<dd><?php echo ini_get('upload_max_size'); ?></dd>
										<dt><?php echo __( 'Post Max Size', 'wpspx' ); ?></dt>
										<dd><?php echo ini_get('post_max_size'); ?></dd>
									</dl>
									<dl>
										<dt>WPSPX_SPEKTRIX_USER</dt>
										<dd><?php echo WPSPX_SPEKTRIX_USER; ?></dd>
										<dt>WPSPX_SPEKTRIX_CUSTOM_URL</dt>
										<dd><?php echo WPSPX_SPEKTRIX_CUSTOM_URL; ?></dd>
										<dt>WPSPX_SPEKTRIX_API_URL</dt>
										<dd><?php echo WPSPX_SPEKTRIX_API_URL; ?></dd>
										<dt>WPSPX_SPEKTRIX_SECURE_WEB_URL</dt>
										<dd><?php echo WPSPX_SPEKTRIX_SECURE_WEB_URL; ?></dd>
										<dt>WPSPX_SPEKTRIX_NON_SECURE_WEB_URL</dt>
										<dd><?php echo WPSPX_SPEKTRIX_NON_SECURE_WEB_URL; ?></dd>
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
										<dt><?php echo __( 'Active Theme Name', 'wpspx' ); ?></dt>
										<dd><?php echo wp_get_theme(); ?></dd>
									</dl>
									<dl>
										<dt><?php echo __( 'Active Plugins', 'wpspx' ); ?></dt>
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

				<div class="done">
					<ul class="list">
						<li><?php echo __( 'Fetching Spektrix Data', 'wpspx' ); ?></li>
					</ul>
					<a class="close-cacher button button-primary" href="<?php echo get_admin_url(); ?>/admin.php?page=wpspx-shows">Close</a>
					<div id="stopwatch">
						<?php echo __( 'Time elapsed:', 'wpspx' ); ?>
						<span id="sw_h">00</span>:
    					<span id="sw_m">00</span>:
    					<span id="sw_s">00</span>:
    					<span id="sw_ms">00</span>
					</div>
				</div>

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
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-support' ?>">Support</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php
					if (isset($_GET['settings-updated'])):
					?>
					<div class="notice notice-success is-dismissible">
						<p><strong><?php echo __( 'Shows Synced', 'wpspx' ); ?></strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text"><?php echo __( 'Dismiss', 'wpspx' ); ?></span>
						</button>
					</div>
					<?php endif; ?>

					<div class="tab">

						<div class="content">
							<input type="hidden" name="cache_url" value="<?php echo WP_CONTENT_DIR; ?>/wpspx-cache/">
							<input type="hidden" name="fetch_url" value="<?php echo home_url(); ?>">
							<input type="hidden" name="spektrix_url" value="<?php echo WPSPX_SPEKTRIX_API_URL; ?>">

							<section>
								<h2><?php echo __( 'Data Sync', 'wpspx' ); ?></h2>
								<p>
									<?php echo __( 'Below you will see how many shows are available to sync from Spektrix. By default this is set to all shows in the future.', 'wpspx' ); ?>
								</p>
								<?php
								$shows_in_spektrix = WPSPX_Show::find_all_in_future();
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
										<p><?php echo __( 'Shows Available in Spektrix', 'wpspx' ); ?></p>
										<span><?php echo count($shows_in_spektrix); ?></span>
									</div>
									<div class="showsinwp half">
										<p><?php echo __( 'Shows already synced', 'wpspx' ); ?></p>
										<span><?php echo count($wp_shows); ?></span>
									</div>
								</div>
								<p>
									<strong>
										<?php echo __( 'This can take upto 5 minutes to complete', 'wpspx' ); ?>
									</strong>
								</p>
								<?php
								settings_fields( 'wpspxPluginPagePosts' );
								do_settings_sections( 'wpspxPluginPagePosts' );
								?>
								<div class="loading">
									<div class="spin"></div>
								</div>
								<a href="#" onclick="wpspx_cache()" class="button button-primary"><?php echo __( 'Fetch Data', 'wpspx' ); ?></a>

							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}
