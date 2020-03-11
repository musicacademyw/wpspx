<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_action( 'admin_menu', 'wpspx_add_admin_menu' );
add_action( 'admin_init', 'wpspx_settings_init' );

function wpspx_add_admin_menu(  ) {

	add_menu_page( 'WPSPX', 'WPSPX', 'manage_options', 'wpspx', 'wpspx_options_page' );

	add_submenu_page(
		'wpspx',								// parent slug
		'Data Sync',							// page title
		'Data Sync',							// menu title
		'manage_options',						// capability
		'wpspx-shows',							// slug
		'wpspx_shows_options_page' 				// callback
	);

	add_submenu_page(
		'wpspx',								// parent slug
		'Cache',							// page title
		'Cache',							// menu title
		'manage_options',						// capability
		'wpspx-cache',							// slug
		'wpspx_cache_options_page' 				// callback
	);

	add_submenu_page(
		'wpspx',								// parent slug
		'License',							// page title
		'License',							// menu title
		'manage_options',						// capability
		'wpspx-license',							// slug
		'wpspx_license_options_page' 				// callback
	);

	add_submenu_page(
		'wpspx',								// parent slug
		'Support',						// page title
		'Support',						// menu title
		'manage_options',						// capability
		'wpspx-support',						// slug
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

	// Cache Setting
	register_setting( 'wpspxPluginPageCache', 'wpspx_cache_settings' );

	add_settings_section(
		'wpspx_wpspxPluginPageCache_section',
		__( 'Spektrix API Cache', 'wpspx' ),
		'wpspx_settings_cache_section_callback',
		'wpspxPluginPageCache'
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
		'',
		'',
		'wpspxPluginPageSupport'
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
	?>
	<input type='text' name='wpspx_settings[wpspx_account_name]' value='<?php echo $options['wpspx_account_name']; ?>'>
	<span>Your account name is the theatrename ius your admin interface url: https://system.spektrix.com/[your account name]/</span>
	<?php

}

// Client API Key
function wpspx_api_key_render(  ) {

	$options = get_option( 'wpspx_settings' );
	$key = $options['wpspx_api_key'];
	?>
	<input type='text' id='ssn' name='wpspx_settings[wpspx_api_key]' value='<?php if ($key): ?>&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;&bull;&bull;<?php echo substr($key, -5,5); ?><?php endif; ?>'>
	<span>This will look something like this abcd1e12-f4g5-6789-h01i-2345678j910k</span>
	<?php

}

// Custom URL
function wpspx_custom_domain_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_custom_domain]' value='<?php echo $options['wpspx_custom_domain']; ?>'>
	<span>A custom domain such as tickets.theatre.com <a target="_blank" href="https://integrate.spektrix.com/docs/customdomains">Instructions to set this up</a></span>
	<?php

}

// Path to CRT
function wpspx_path_to_crt_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_path_to_crt]' value='<?php echo $options['wpspx_path_to_crt']; ?>'>
	<span>This needs to be a server path. Your path is <?php echo $_SERVER['DOCUMENT_ROOT']; ?></span>
	<?php

}

// Path to KEY
function wpspx_path_to_key_render(  ) {

	$options = get_option( 'wpspx_settings' );
	?>
	<input type='text' name='wpspx_settings[wpspx_path_to_key]' value='<?php echo $options['wpspx_path_to_key']; ?>'>
	<span>This needs to be a server path. Your path is <?php echo $_SERVER['DOCUMENT_ROOT']; ?></span>
	<?php

}


function wpspx_settings_section_callback(  ) {

	echo __( '<p>Please populate the below fields with your API settings from your Spektrix control panel. You will need a valid API key, your account name, a custom domain (if you have one setup) and the server path to your self hosted Spektrix signed CRT & KEY.</p>', 'wpspx' );

}

function wpspx_settings_cache_section_callback(  ) {

	echo __( '<p>Clear you WPSPX Cache files. This will completely remove all cached data from the server. <strong>Proceed with caution</strong> as this will cause your site to run slowly untill the cache has been rebuilt. </p>', 'wpspx' );

}

function wpspx_settings_license_section_callback(  ) {

	echo __( '<p>You can find your license key <a href="https://wearebeard.com" target="_blank">within your account</a> or contained inside your payment confirmation email.</p>', 'wpspx' );

}

function wpspx_license_key_render(  ) {

	$options = get_option( 'wpspx_license_settings' );
	$key = $options['wpspx_license_key'];
	?>
	<input type='text' id='ssn' name='wpspx_license_settings[wpspx_license_key]' value='<?php if ($key): ?>&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;&bull;&bull;&bull;-&bull;&bull;&bull;<?php echo substr($key, -5,5); ?><?php endif; ?>'>
	<span>This will look something like this sdfrt-gyhu45-67sdfg-hyu45rt6</span>
	<?php

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
							array_map('unlink', glob($cached_files)); ?>
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
								?>
								<p>
									There are currnelty <?php echo count($cached_files); ?> cached files
								</p>
								<?php
								submit_button("Clear Cache");
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
								<h2>Support</h2>
								<ul>
									<li>Useful Links</li>
									<li><a href="#">Documentation</a></li>
								</ul>

								<h2>Debug Info</h2>
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
										<dd><?php echo home_url(); ?></dd>
										<dt>Shows in spektrix</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Shows in spektrix (In Furure)</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Shows in WordPress</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>Web Server</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>PHP</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WP Memory Limit</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>PHP Time Limit</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Blocked External HTTP Requests</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>fsockopen</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>OpenSSL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>cURL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Enable SSL verification setting</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Opcache Enabled</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>MySQL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>ext/mysqli</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WP Locale</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>DB Charset</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WPMDB_STRIP_INVALID_TEXT</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>Debug Mode</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Debug Log</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Script Debug</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>PHP Error Log</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>WP Max Upload Size</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>PHP Post Max Size</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>WP_HOME</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WP_SITEURL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_USER</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_CERT</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_KEY</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_API</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_CUSTOM_URL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_API_URL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_SECURE_WEB_URL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>SPEKTRIX_NON_SECURE_WEB_URL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WP_CONTENT_URL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WP_CONTENT_DIR</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WP_PLUGIN_DIR</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>WP_PLUGIN_URL</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>UPLOADS</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>UPLOADBLOGSDIR</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>Media Files:</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Number of Image Sizes</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>Theme & Plugin Files</dt>
										<dd></dd>
										<dt>Transfer Bottleneck</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Themes Permissions</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Plugins Permissions</dt>
										<dd><?php echo home_url(); ?></dd>
									</dl>
									<dl>
										<dt>Active Theme Name:</dt>
										<dd><?php echo wp_get_theme()->get( 'Name' ); ?></dd>
										<dt>Active Theme Folder</dt>
										<dd><?php echo home_url(); ?></dd>
										<dt>Parent Theme Folder</dt>
										<dd><?php echo home_url(); ?></dd>
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
						<p><strong>Shows / Events Cached.</strong></p>
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
								submit_button($synctext, 'primary', 'publishshows', true, $other_attributes );
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
