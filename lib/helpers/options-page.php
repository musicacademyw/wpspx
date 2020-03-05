<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

add_action( 'admin_menu', 'wpspx_add_admin_menu' );
add_action( 'admin_init', 'wpspx_settings_init' );

function wpspx_add_admin_menu(  ) {

	add_menu_page( 'WPSPX', 'WPSPX', 'manage_options', 'wpspx', 'wpspx_options_page' );

	add_submenu_page(
		'wpspx',								// parent slug
		'WPSPX Pages',							// page title
		'WPSPX Pages',							// menu title
		'manage_options',						// capability
		'wpspx-pages',							// slug
		'wpspx_pages_options_page' 				// callback
	);

	add_submenu_page(
		'wpspx',								// parent slug
		'WPSPX Shows',							// page title
		'WPSPX Shows',							// menu title
		'manage_options',						// capability
		'wpspx-shows',							// slug
		'wpspx_shows_options_page' 				// callback
	);

	add_submenu_page(
		'wpspx',								// parent slug
		'WPSPX Cache',							// page title
		'WPSPX Cache',							// menu title
		'manage_options',						// capability
		'wpspx-cache',							// slug
		'wpspx_cache_options_page' 				// callback
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

	echo __( 'Please populate the below fields with your API settings from your Spektrix control panel. You will need a valid API key, your account name, a custom domain (if you have one setup) and the server path to your self hosted Spektrix signed CRT & KEY.', 'wpspx' );

}

function wpspx_settings_cache_section_callback(  ) {

	echo __( 'Clear you WPSPX Cache files. This will completely remove all cached data from the server. <strong>Proceed with caution</strong> as this will cause your site to run slowly untill the cache has been rebuilt. ', 'wpspx' );

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

			// Create post object
			$my_post = array(
				'post_type'		=> 'shows',
				'post_title'    => $spektrix_name,
				'post_content'  => $spektrix_short_description,
				'post_status'   => 'publish',
				'post_author'   => 1,
				'meta_input' 	=> array(
                    '_spektrix_id'	=> $spektrix_id
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
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-pages' ?>">Page Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Show Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
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
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-pages' ?>">Page Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Show Settings</a></li>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
						</ul>
					</nav>
				</header>

				<article>

					<?php
					if (isset($_GET['settings-updated'])):
						$cached_files = WPSPX_PLUGIN_DIR . '/wpspx-cache/*.json';
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

function wpspx_pages_options_page(  ) {

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
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-pages' ?>">Page Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Show Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
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
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-pages' ?>">Page Settings</a></li>
							<li><a class="active" href="<?php echo admin_url() . 'admin.php?page=wpspx-shows' ?>">Show Settings</a></li>
							<li><a href="<?php echo admin_url() . 'admin.php?page=wpspx-cache' ?>">Cache</a></li>
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
								?>
								<div class="loading">
									<div class="spin"></div>
								</div>
								<?php
								submit_button('Sync '.count($shows_in_spektrix).' Shows', 'primary', 'publishshows' );
								?>
							</section>
						</div>
					</div>

				</article>

			</div>

		</form>
		<?php

}
