<?php
/*
Plugin Name: Admin Tailor - Customizer
Plugin URI: https://wordpress.org/plugins/admin-tailor
Description: Personalize your admin login and dashboard.
Version: 1.1.2
Author: Jahidur Nadim
Author URI: https://github.com/nadim1992
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: jn-admin-tailor
Domain Path: /languages
*/

defined( 'ABSPATH' ) || exit;


/**
 * Define constants.
 */
define( 'JN_ADMIN_TAILOR_FILE', __FILE__ );
define( 'JN_ADMIN_TAILOR_VERSION', '1.1.2' );
define( 'JN_ADMIN_TAILOR_PATH', dirname( JN_ADMIN_TAILOR_FILE ) );
define( 'JN_ADMIN_TAILOR_URL', plugins_url( '/assets', JN_ADMIN_TAILOR_FILE ) );


/**
 * Load global files.
 */
add_action( 'plugins_loaded', function() {
	require_once JN_ADMIN_TAILOR_PATH . '/includes/utility.php';
} );


/**
 * Activation hook.
 */
register_activation_hook( JN_ADMIN_TAILOR_FILE, function() {
	$installed = get_option( 'jn_admin_tailor_installed' );

	if ( ! $installed ) {
		update_option( 'jn_admin_tailor_installed', time() );
	}

	update_option( 'jn_admin_tailor_version', JN_ADMIN_TAILOR_VERSION );
} );

/**
 * Add settings link in under plugin name
 */
add_filter( 'plugin_action_links_admin-tailor/admin-tailor.php', function( $links ) {
	$link = '<a href="' . esc_url( get_admin_url() . 'options-general.php?page=admin-tailor' ) . '">Settings</a>';

	array_unshift( $links, $link );

	return $links;
} );


/**
 * Load files.
 */
function jn_admin_tailor_init_hook() {
	require_once JN_ADMIN_TAILOR_PATH . '/includes/admin.php';


	add_action( 'admin_enqueue_scripts', function() {
		// Media.
		if ( ! did_action( 'wp_enqueue_media' ) ) wp_enqueue_media();

		// Styles.
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'jn-admin-tailor-style', plugins_url( '/assets/css/style.css' , JN_ADMIN_TAILOR_FILE ) );

		// Script.
		wp_enqueue_script(
			'jn-admin-tailor-script',
			plugins_url( '/assets/js/script.js' , JN_ADMIN_TAILOR_FILE ),
			array( 'jquery', 'wp-color-picker' )
		);
	} );
}


/**
 * Settings page.
 */
add_action( 'admin_menu', function() {
	$hook = add_options_page(
		'Admin Tailor Settings',
		'Admin Tailor',
		'administrator',
		'admin-tailor',
		'jn_admin_tailor_add_settings_page_content',
		6
	);

	add_action( 'load-' . $hook, 'jn_admin_tailor_init_hook' );
} );


/**
 * Utility
 */
function jn_admin_tailor_hex2rgba( $color, $opacity ) {
    list( $r, $g, $b ) = sscanf( $color, '#%02x%02x%02x' );

    return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')'; // double-quotes look messy :D
}


/**
 * Set login page styles.
 */
add_action( 'login_enqueue_scripts', function() {
	$styles              = '';
	$login_color_palette = get_option( 'jn_admin_tailor_login_color' );
	$login_footer_color  = get_option( 'jn_admin_tailor_login_footer_color' );
	$login_pattern_url   = get_option( 'jn_admin_tailor_login_pattern_url' );
	$image_id            = get_option( 'jn_admin_tailor_login_logo_id' );
	$image               = wp_get_attachment_image_url( $image_id );

	if ( $image ) {
		$styles .= 'body.login div#login h1 a {
			background-image: url(' . esc_url( $image ) . ');
		}';
	}

	if ( $login_color_palette ) {
		$color = esc_attr( $login_color_palette );

		$styles .= 'body.login div#login .message {
			border-color: ' . jn_admin_tailor_hex2rgba( $color, .5 ) . ';
		}';

		$styles .= 'body.login div#login #loginform input:focus {
			border-color: ' . $color . ';
			box-shadow: 0 0 0 1px ' . $color . ';
		}';

		$styles .= 'body.login div#login #loginform .wp-pwd .button-secondary {
			color: ' . $color . ';
		}';

		$styles .= 'body.login div#login #loginform #rememberme:focus {
			border-color: ' . $color . ';
		}';

		$styles .= 'body.login div#login #loginform #wp-submit {
			background: ' . $color . ';
			border-color: ' . $color . ';
		}';
	}

	if ( $login_footer_color ) {
		$styles .= 'body.login #backtoblog a, body.login #nav a {
			color: ' . esc_attr( $login_footer_color ) . ';
		}';
	}

	if ( $login_pattern_url && jn_admin_tailor_get_pattern_url( 'default.png' ) !== $login_pattern_url ) {
		$size = ( 'svg' === pathinfo( $login_pattern_url, PATHINFO_EXTENSION ) ) ? 'cover' : 'auto';

		$styles .= 'body.login {
			background-image: url(' . esc_url( $login_pattern_url ) . ');
			background-position: center;
			background-size: ' . esc_html( $size ) . ';
		}';
	}
	?>
	<style><?php echo esc_html( $styles ) ?></style>
	<?php
} );

// All done, have fun!
