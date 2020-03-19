<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*==========================================
=            QUERY VAR - CUSTOM            =
==========================================*/

add_filter('query_vars', 'wpspx_dd_my_var');
function wpspx_dd_my_var($public_query_vars)
{
	$public_query_vars[] = 'performance';
	$public_query_vars[] = 'lid';
	return $public_query_vars;
}

/*==================================
=            DO REWRITE            =
==================================*/

add_action('init', 'wpspx_do_rewrite');
function wpspx_do_rewrite()
{
	add_rewrite_rule('book-online/([^/]+)/?$', 'index.php?pagename=book-online&performance=$matches[1]','top');
}

/*===========================================
=            GET FIRST PARAGRAPH            =
===========================================*/

function wpspx_get_first_paragraph($post_content)
{
	$str = wpautop($post_content);
	$str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
	$str = strip_tags($str, '<a><strong><em>');
	return '<p>' . $str . '</p>';
}

/*=======================================
=            CLEAR THE CACHE            =
=======================================*/
function wpspx_bust_cache()
{
	$cached_files = WPSPX_PLUGIN_DIR . '/wpspx-cache/*.json';
	try
	{
		array_map('unlink', glob($cached_files)); ?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'WPSPX cache has been cleared!', 'wpspx' ); ?></p>
		</div>
		<?php
	} catch (Exception $e)
	{
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo  $e->getMessage() . '\n'; ?></p>
		</div>
		<?php
	}
}

/*=============================================
=            CONVERT HOURS TO MINS            =
=============================================*/

function wpspx_convert_to_hours_minutes($minutes)
{
	$return_string = '';

	$hours = floor($minutes / 60);
	if($hours)
	{
		$return_string .= $hours . ' hours';
	}

	$minutes = $minutes % 60;
	if($minutes) {
		$return_string .= ' ' . $minutes . ' minutes';
	}

	return $return_string;
}

/*============================================
=            CONVERT MINS TO SECS            =
============================================*/
function wpspx_convert_to_seconds($minutes)
{
	$seconds = $minutes * 60;
	return $seconds;
}

/*===========================================
=            CREATE POSTER IMAGE            =
===========================================*/

add_image_size( 'poster', 800, 1200, false );

/*===========================================
=            CUSTOM ADMIN COLUMS            =
// ===========================================*/

add_filter('manage_edit-shows_columns', 'wpspx_columns');
function wpspx_columns($columns) {
    $columns['spetrixid'] = 'Spektrix ID';
    $columns['starts'] = 'Starts';
    $columns['ends'] = 'Ends';
    return $columns;
}

add_action('manage_posts_custom_column',  'wpspx_show_columns');
function wpspx_show_columns($name) {
    global $post;
    $spektrix_id = get_post_meta($post->ID, '_spektrix_id', true);
    $firstdate = get_post_meta($post->ID, '_spektrix_start', true);;
    $lastdate = get_post_meta($post->ID, '_spektrix_end', true);;


    switch ($name) {
        case 'spetrixid':
            echo (integer) $spektrix_id;
            break;
        case 'starts':
        	echo date("d/m/Y", $firstdate);
			// echo '<span title="'.$firstdate->start_time->format("Y/m/d h:i:s a").'">'.$firstdate->start_time->format("d/m/Y").'</span>';
            break;
        case 'ends':
        	echo date("d/m/Y", $lastdate);
        	// echo '<span title="'.$lastdate->start_time->format("Y/m/d h:i:s a").'">'.$lastdate->start_time->format("d/m/Y").'</span>';
            break;
    }
}

add_action( 'pre_get_posts', 'my_slice_orderby' );
function my_slice_orderby( $query ) {
    if( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');

    if( 'starts' == $orderby ) {
        $query->set('meta_key','_spektrix_start');
        $query->set('orderby','meta_value_num');
    }
    if( 'ends' == $orderby ) {
        $query->set('meta_key','_spektrix_end');
        $query->set('orderby','meta_value_num');
    }
}

function wpspx_reorder_columns($columns) {
	$wpspx_columns = array();
	$comments = 'comments';
	foreach($columns as $key => $value) {
		if ($key==$comments){
			$wpspx_columns['spetrixid'] = ''; 	// Move author column before title column
			$wpspx_columns['starts'] = ''; 		// Move date column before title column
			$wpspx_columns['ends'] = '';		// Move author column before title column
		}
		$wpspx_columns[$key] = $value;
	}
	return $wpspx_columns;
}
add_filter('manage_posts_columns', 'wpspx_reorder_columns');

add_filter( 'manage_edit-shows_sortable_columns', 'wpspx_sortable_date_column' );
function wpspx_sortable_date_column( $columns ) {
    $columns['starts'] = 'starts';
    $columns['ends'] = 'ends';

    return $columns;
}

/*===========================================
=            CUSTOM ADMIN COLUMS            =
===========================================*/

function wpspx_basket() {
?>
<div class="wpspxbasket is-fixed">
	<spektrix-basket-summary
		class="is-primary"
		custom-domain="<?php echo SPEKTRIX_CUSTOM_URL ?>"
		client-name="<?php echo SPEKTRIX_USER ?>">
		<div class="basket-icon">
			<span class="count" data-basket-item-count></span>
			<a href="<?php echo home_url('basket'); ?>"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="shopping-basket" class="svg-inline--fa fa-shopping-basket fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M564 192h-72.902L362.286 40.457c-8.583-10.099-23.729-11.327-33.83-2.743-10.099 8.584-11.327 23.731-2.742 33.83L428.102 192H147.899L250.287 71.543c8.584-10.099 7.356-25.246-2.743-33.83s-25.246-7.355-33.83 2.743L84.901 192H12c-6.627 0-12 5.373-12 12v24c0 6.627 5.373 12 12 12h18.667l27.584 198.603C61.546 462.334 81.836 480 105.794 480h364.412c23.958 0 44.248-17.666 47.544-41.397L545.333 240H564c6.627 0 12-5.373 12-12v-24c0-6.627-5.373-12-12-12zm-93.794 240H105.794L79.127 240h417.745l-26.666 192zM312 296v80c0 13.255-10.745 24-24 24s-24-10.745-24-24v-80c0-13.255 10.745-24 24-24s24 10.745 24 24zm112 0v80c0 13.255-10.745 24-24 24s-24-10.745-24-24v-80c0-13.255 10.745-24 24-24s24 10.745 24 24zm-224 0v80c0 13.255-10.745 24-24 24s-24-10.745-24-24v-80c0-13.255 10.745-24 24-24s24 10.745 24 24z"></path></svg></a>
			</div>
		</div>

	</spektrix-basket-summary>
</div>
<?php
}
add_action( 'wp_footer', 'wpspx_basket', 99 );


function wpspx_login() {
?>

<div class="accountbox modal">
	<div class="modal-background"></div>
	<div class="modal-card">
		<header class="modal-card-head">
			<p class="modal-card-title">Login to your account</p>
			<button class="delete" aria-label="close"></button>
		</header>
		<section class="modal-card-body">
			<div class="formcontent"></div>
		</section>
		<footer class="modal-card-foot">
		</footer>
	</div>
</div>
<script>
function wpspxLogout() {
	var settings = {
		"async": true,
		"crossDomain": true,
		"url": "<?php echo SPEKTRIX_API_URL ?>customer/deauthenticate",
		"method": "POST",
		"xhrFields": {
			"withCredentials": true
		},
		"headers": {
			"Content-Type": "application/json",
			"cache-control": "no-cache"
		},
		"processData": false
	}
	jQuery.ajax(settings).done(function (response) {
		location.reload();
	});
}
function wpspxLogin(message) {

	jQuery('.accountbox').addClass('is-active');
	jQuery('.delete').on("click", function(){
		jQuery('.accountbox').removeClass('is-active');
	});

	var lostpass = '<p>Please enter your email to reset your password.</p><div class="fields"><div class="field"><label class="label">Email Address</label><input id="passwordresetemail" class="input" type="text" placeholder="your email" name="forgot-password" required></div><div class="field"><div class="message" style="display:none;"><div class="message-body"></div></div></div></div>';
	var logpaaslinks = '<button id="sendpassword" class="button is-primary">Reset Password</button><button id="forgotpassword" class="button is-info">Forgot password?</button>';

	var loginform = '<p>Login to your account. Don\'t have one yet? You can <a class="registerlink" href="#">register here</a></p><div class="field"><label class="label">Email Address</label><input class="input" autocomplete="username" type="text" placeholder="Email" name="login-username" required></div><div class="field"><label class="label">Password</label><input class="input" autocomplete="current-password" type="password" placeholder="Password" name="login-password" required></div><div class="field"><div class="message" style="display:none;"><div class="message-body"></div></div></div></div>';
	var loginformlinks = '<button id="loginbutton" class="button is-primary">Login</button><button id="forgotpassword" class="button is-info">Forgot password?</button>';

	var registerform = '<p>Please complete the below form to register for an account.</p><div class="field"><div class="label"><label class="label">Name</label></div><div class="field-body is-horizontal"><div class="field"><input class="input" type="text" placeholder="First Name" id="signup-firstname"></div><div class="field"><input class="input" type="text" placeholder="Last Name" id="signup-lastname"></div></div></div><div class="field"><label class="label">Date of Birth</label><div class="control"><input class="input" type="date" id="signup-birthdate"></div></div><div class="field"><label class="label">Phone Number</label><div class="control"><input class="input" type="text" id="signup-phone"></div></div><div class="field"><label class="label">Email Address</label><div class="control"><input class="input" type="email" id="signup-email"></div></div><div class="field"><div class="message" style="display:none;"><div class="message-body"></div></div></div></div>';
	var registerformlinks = '<button id="register" class="button is-primary">Register</button>';


	jQuery('.modal-card-title').html('Login to your account');
	jQuery('.formcontent').html(loginform);
	jQuery('.modal-card-foot').html(loginformlinks);

	//login button
	jQuery('#loginbutton').on("click", function(){
		var settings = {
			"async": true,
			"crossDomain": true,
			"url": "<?php echo SPEKTRIX_API_URL ?>customer/authenticate",
			"method": "POST",
			"xhrFields": {
				"withCredentials": true
			},
			"headers": {
				"Content-Type": "application/json",
				"cache-control": "no-cache"
			},
			"processData": false,
			"data": "{\r\n  \"email\": \"" + jQuery('#spektrix-username').val() + "\",\r\n  \"password\": \"" + jQuery('#spektrix-password').val() + "\"\r\n}"
		}
		jQuery.ajax(settings).done(function (response) {
			if(response.id !== '') {
				sessionStorage.setItem("spektrix_auth", "success");
				jQuery('.message').addClass('is-success');
				jQuery('.message .message-body').text('Sucessfully loggin in.');
				jQuery('.message').show();
				setTimeout(function() {
					jQuery('.accountbox').removeClass('is-active');
				},3000);
			}
			else {
				jQuery('.message').addClass('is-danger');
				jQuery('.message .message-body').text('There was an error.');
				jQuery('.message').show();
			}
		}).fail(function(xhr, status, error) {
			switch(xhr.status) {
				case 404:
					jQuery('.message').addClass('is-danger');
					jQuery('.message .message-body').text('Invalid username or password');
					setTimeout(function() {
						jQuery('.message').show();
					},3000);
					break;
				case 401:
					jQuery('.message').addClass('is-danger');
					jQuery('.message .message-body').text('There was an error trying to log you in.');
					setTimeout(function() {
						jQuery('.message').show();
					},3000);
					break;
				default:
					jQuery('.message').addClass('is-danger');
					jQuery('.message .message-body').text('There was an error trying to log you in.');
					setTimeout(function() {
						jQuery('.message').show();
					},3000);
					break;
			}
		});
	});

	// forget password link
	jQuery('#forgotpassword').on("click", function(e) {
		jQuery('.modal-card-title').html('Forgot your password');
		jQuery('.formcontent').html(lostpass);
		jQuery('.modal-card-foot').html(logpaaslinks)
		jQuery('#sendpassword').on("click", function(e){
			var emailaddress = jQuery('#passwordresetemail').val();
			if(emailaddress !== '') {
				var settings = {
					"async": true,
					"crossDomain": true,
					"url": "<?php echo SPEKTRIX_API_URL ?>customer/forgot-password?emailAddress=" + emailaddress,
					"method": "POST",
					"headers": {
						"Content-Type": "application/x-www-form-urlencoded",
						"cache-control": "no-cache"
					}
				}

				jQuery.ajax(settings).done(function (response) {
					jQuery('.message').addClass('is-success');
					jQuery('.message .message-body').text('Password reset email sent.');
					setTimeout(function() {
						jQuery('.message').show();
					},8000);
				}).fail(function(xhr, status, error) {
					switch(xhr.status) {
						case '404':
							jQuery('.message').addClass('is-danger');
							jQuery('.message .message-body').text('No account found with this email address.');
							jQuery('.message').show();
							break;
						default:
							jQuery('.message').addClass('is-danger');
							jQuery('.message .message-body').text('Sorry there was an error - please call the box office to reset your password.');
							jQuery('.message').show();
							break;
					}
				});
			}
			else {
				jQuery('.message .message-body').text('You must enter an email address');
				jQuery('.message').show();
			}
		});
		// back to login
		jQuery('#backtologin').on("click", function(e) {
			jQuery('.modal-card-title').html('Login to your account');
			jQuery('.formcontent').html(loginform);
			jQuery('.modal-card-foot').html(loginformlinks);
		});
	});


	// register button
	jQuery('.registerlink').on("click", function(e){
		jQuery('.modal-card-title').html('Sign uo for an account');
		jQuery('.formcontent').html(registerform);
		jQuery('.modal-card-foot').html(registerformlinks);
		jQuery('#register').on("click", function(e){
			var settings = {
				"async": true,
				"crossDomain": true,
				"url": "<?php echo SPEKTRIX_API_URL ?>customer",
				"method": "POST",
				"xhrFields": {
					"withCredentials": true
				},
				"headers": {
					"Content-Type": "application/json",
					"cache-control": "no-cache"
				},
				"processData": false,
				"data": "{\r\n  \"birthDate\": \"" + jQuery('#signup-birthdate').val() + "\",\r\n  \"email\": \"" + jQuery('#signup-email').val() + "\",\r\n  \"firstName\": \"" + jQuery('#signup-firstname').val() + "\",\r\n  \"lastName\": \"" + jQuery('#signup-lastname').val() + "\",\r\n  \"mobile\": \"" + jQuery('#signup-phone').val() + "\",\r\n  \"password\": \"" + jQuery('#signup-password').val() + "\"\r\n}"
			}

			jQuery.ajax(settings).done(function (response) {
				if(response.id !== '') {
					jQuery('.message').addClass('is-success');
					jQuery('.message .message-body').text('Account sucessfully created, logging you in....');
					jQuery('.message').show();
					setTimeout(function() {
						location.reload();
					},3000);
				}
				else {
					jQuery('.message').addClass('is-success');
					jQuery('.message .message-body').text('There was an error.');
					setTimeout(function() {
						jQuery('.message').show();
					},8000);
				}
			}).fail(function(xhr, status, error) {
				switch(xhr.status) {
					default:
						jQuery('.message').addClass('is-success');
						jQuery('.message .message-body').text('There was an error.');
						setTimeout(function() {
							jQuery('.message').show();
						},8000);
						break;
				}
			});
		});
	});
}
if (sessionStorage.getItem('spektrix_auth') == 'success') {
	var spektrixuserid = null;
	var userdetailssettings = {
		"async": true,
		"crossDomain": true,
		"url": "<?php echo SPEKTRIX_API_URL ?>customer",
		"method": "GET",
		"xhrFields": {
			"withCredentials": true
		},
		"headers": {
			"Content-Type": "application/json",
			"cache-control": "no-cache"
		},
		"statusCode": {
        	401: function (error) {
            	// simply ignore this error
        	}
		},
		"processData": false
	}
	jQuery.ajax(userdetailssettings).done(function (userdetailsresponse) {
		if(typeof userdetailsresponse.id !== 'undefined' && userdetailsresponse.id !== '') {
			spektrixuserid = userdetailsresponse.id;
			jQuery('.accountlink').html('<div class="greeting">Hello '+ userdetailsresponse.firstName +'</div><a class="button is-info" href="<?php echo home_url('my-account'); ?>">View Account</a><a class="logoutlink" onclick="wpspxLogout();">Log Out</a>'
			);

		}
	}).fail(function(xhr, status, error) {
		jQuery('.accountlink').html('<div class="greeting">Hello '+ userdetailsresponse.firstName +'</div><a class="button is-info" href="<?php echo home_url('my-account'); ?>">View Account</a><a class="logoutlink" onclick="wpspxLogin();">Log In</a>'
		);
	});


}
</script>
<?php
}
add_action( 'wp_footer', 'wpspx_login', 99);
