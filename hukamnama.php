<?php
/*
Plugin Name: Hukamnama
Plugin URI: https://wordpress.org/plugins/hukamnama/
Description: Display today's hukamnama using Sikher API
Version: 0.5.1
Author: Inderpreet Singh
Author URI: https://inderpreetsingh.com
*/

define( 'HUKAMNAMA_VERSION', '0.5.1' );

$GLOBALS['hukamnama'] = new Hukamnama();

add_action( 'admin_init', array( 'Hukamnama', 'register_settings' ) );
add_action( 'admin_menu', array( 'Hukamnama', 'setup_menu' ) );
add_action( 'admin_enqueue_scripts', array( 'Hukamnama', 'admin_enqueue_scripts' ) );
add_action( 'wp_enqueue_scripts', array( 'Hukamnama', 'wp_enqueue_scripts' ) );

add_action( 'wp_ajax_hukamnama_finder', array( 'Hukamnama', 'finder_json' ) );
add_action( 'wp_ajax_nopriv_hukamnama_finder', array( 'Hukamnama', 'finder_json' ) );

class Hukamnama {
	public static $post_type = 'hukam';
	public $default_api = 'http://api.sikher.com';
	public $cache_ttl;

	function __construct() {

		$this->cache_ttl = 24 * HOUR_IN_SECONDS;

		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

		add_shortcode( 'hukamnama', array( $this, 'shortcode' ) );

		add_action( 'wp_ajax_hukamnama_api', array( $this, 'api' ) );
		add_action( 'wp_ajax_nopriv_hukamnama_api', array( $this, 'api' ) );
	}

	function plugin_activation() {

		if( ! get_role( 'ragi' ) ) {
			add_role( 'ragi', 'Ragi', array(
				'read'		 => true,  // true allows this capability
			) );
		}

		$supported_roles = array( 'administrator', 'author', 'editor', 'ragi' );
		foreach( $supported_roles as $role_name ) {
			$role = get_role( $role_name );
			$role->add_cap( 'hukamnama' );
		}

		update_option( 'hukamnama_api', $this->$default_api );
	}

	static function setup_menu() {
		add_menu_page( 'Hukamnama', 'Hukamnama', 'hukamnama', 'hukamnama', array( 'Hukamnama', 'select_page' ) );
		add_submenu_page( 'hukamnama', 'Settings', 'Settings', 'hukamnama', 'hukamnama-settings', array( 'Hukamnama', 'settings_page' ) );
	}

	static function wp_enqueue_scripts() {
		global $post;

		if ( ! get_option( 'hukamnama_post_id' ) || ( get_option( 'hukamnama_post_id' ) && get_the_ID() == get_option( 'hukamnama_post_id' ) ) ) {
			wp_enqueue_script( 'hukamnama-frontend.js', plugins_url( 'js/frontend.js', __FILE__ ), array( 'jquery', ), HUKAMNAMA_VERSION, false );

			$date = isset( $_GET['date'] ) ? $_GET['date'] : get_option( 'hukamnama_latest' );
			$finder_data = self::finder_data( $date );
			wp_localize_script( 'hukamnama-frontend.js', 'HukamnamaFinder', $finder_data );

			wp_register_style( 'hukamnama-display.css', plugins_url( 'css/display.css', __FILE__ ), array(), HUKAMNAMA_VERSION );
			wp_enqueue_style( 'hukamnama-display.css');
		}
	}

	static function admin_enqueue_scripts() {
		global $hook_suffix;

		if ( in_array( $hook_suffix, array(
			'toplevel_page_hukamnama',
		) ) ) {
			wp_register_style( 'hukamnama-finder.css', plugins_url( 'css/finder.css', __FILE__ ), array(), HUKAMNAMA_VERSION );
			wp_enqueue_style( 'hukamnama-finder.css');

			wp_register_script( 'hukamnama-finder.js', plugins_url( 'js/finder.js', __FILE__ ), array( 'jquery', /*'jquery-ui-core', 'jquery-ui-datepicker',*/), HUKAMNAMA_VERSION );
			wp_enqueue_script( 'hukamnama-finder.js' );
			wp_localize_script( 'hukamnama-finder.js', 'HukamnamaFinder', self::finder_data( date( 'Y-m-d', current_time( 'timestamp' ) ) ) );

			wp_enqueue_style( 'jquery-ui-datepicker' );
		}
	}

	static function finder_data( $date ) {
		return array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'hymn' => get_option( 'hukamnama_hymn_' . $date ),
			'page' => get_option( 'hukamnama_page_' . $date ),
			'api' => get_option( 'hukamnama_api' ),
			'date' => $date,
			'display' => self::get_display_url( $date ),
			'date_nice' => date( 'l jS \of F Y', strtotime( $date ) ),
			'latest' => get_option( 'hukamnama_latest' ),
		);
	}

	static function get_display_url( $date ) {
		return sprintf( '%s?date=%s', get_permalink( get_option( 'hukamnama_post_id' ) ), $date );
	}

	static function finder_json() {
		$date = $_GET['date'];
		echo json_encode( self::finder_data( $date ) );
		die;
	}

	static function register_settings() {
		global $hook_suffix;
		register_setting( 'hukamnama', 'hukamnama_date', array( 'Hukamnama', 'save_hukamnama' ) );
		register_setting( 'hukamnama-settings', 'hukamnama_api' );
		register_setting( 'hukamnama-settings', 'hukamnama_post_id' );
		add_filter('option_page_capability_hukamnama-settings', function() { return "hukamnama"; });
		add_filter('option_page_capability_hukamnama', function() { return "hukamnama"; });
	}

	static function save_hukamnama( $input ) {
		$date = $_POST['hukamnama_date'];
		$page = $_POST['hukamnama_page'];
		$hymn = $_POST['hukamnama_hymn'];
		if( $page && $hymn ) {
			update_option( 'hukamnama_page_' . $date, $page );
			update_option( 'hukamnama_hymn_' . $date, $hymn );

			update_option( 'hukamnama_update_' . $date, current_time( 'timestamp' ) );
			update_option( 'hukamnama_user_' . $date, get_current_user_id() );

			// save the latest date that we have hukamnama for
			if( strtotime( get_option( 'hukamnama_latest' ) ) < strtotime( $date ) ) {
				update_option( 'hukamnama_latest', $date );
			}
		}

		return $input;
	}

	function select_page() {
		$current_date = date( 'Y-m-d', current_time( 'timestamp' ) );
		$max_date = date( 'Y-m-d', current_time( 'timestamp' ) );
		$min_date = date( 'Y-m-d', current_time( 'timestamp' ) - (6 * DAY_IN_SECONDS) );
		include_once dirname(__FILE__) . '/interface/select-page.php';
	}

	function settings_page() {
		$hukamnama_post_id = get_option( 'hukamnama_post_id' );
		include_once dirname(__FILE__) . '/interface/settings-page.php';
	}

	function api() {
		$api   = $this->default_api;
		$route = filter_input( INPUT_GET, 'route', FILTER_SANITIZE_URL );
		$url   = $api . $route;
		$key   = md5( $url );

		header( 'Content-type: application/json' );

		if ( $value = get_transient( $key ) ) {
			echo $value;
			die;
		}

		$response = wp_remote_get( $url );
		$value = wp_remote_retrieve_body( $response );
		echo $value;

		set_transient( $key, $value, $this->cache_ttl );

		die;
	}

	function shortcode() {
		ob_start();
		include_once __DIR__ . '/interface/shortcode.php';
		return ob_get_clean();
	}
}
