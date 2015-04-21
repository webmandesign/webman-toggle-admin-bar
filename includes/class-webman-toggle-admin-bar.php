<?php
/**
 * WebMan Toggle Admin Bar
 *
 * @package  WebMan Toggle Admin Bar
 *
 * @link  http://www.webmandesign.eu
 *
 * @since    1.0
 * @version  1.2
 */



//Exit if accessed directly
if ( ! defined( 'WPINC' ) ) exit;





/**
 * Main WM Toggle Admin Bar class
 *
 * Contains the main functions for WebMan Toggle Admin Bar.
 *
 * @since    1.0
 * @version	 1.0
 */
if ( ! class_exists( 'WM_Toggle_Admin_Bar' ) ) {

	class WM_Toggle_Admin_Bar {

		/**
		 * Run the plugin
		 */

			/**
			 * Constructor
			 *
			 * A dummy constructor to prevent class from being loaded more than once.
			 *
			 * @since    1.0
			 * @version  1.0
			 */
			public function __construct() {

				/* Do nothing here */

			} // /__construct



			/**
			 * Run the loader to execute all of the hooks with WordPress.
			 *
			 * @since    1.0
			 * @version  1.0
			 */
			public function run() {

				//Store the instance locally to avoid private static replication
					static $instance = null;

				//Only run these methods if they haven't been ran previously
					if ( null === $instance ) {
						$instance = new WM_Toggle_Admin_Bar;

						$instance->setup_actions();
					}

				//Always return the instance
					return $instance;

			} // /run





		/**
		 * Methods
		 */

			/**
			 * Setup the default action hooks
			 *
			 * @since    1.0
			 * @version  1.0
			 */
			public function setup_actions() {

				add_action( 'plugins_loaded',     array( $this, 'load_textdomain'  ) );
				add_action( 'after_setup_theme',  array( $this, 'toggle_admin_bar' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets'   ) );
				add_action( 'wp_footer',          array( $this, 'toggle_button'    ), 9998 );

			} // /setup_actions



			/**
			 * Can the button be displayed?
			 *
			 * @since    1.0
			 * @version  1.1
			 */
			public function can_display_button() {

				if (
						! is_user_logged_in()
						|| is_admin()
						|| isset( $GLOBALS['wp_customize'] )
					) {
					return false;
				}

				return true;

			} // /can_display_button



			/**
			 * Enqueue styles and scripts
			 *
			 * @since    1.0
			 * @version  1.1
			 */
			public function enqueue_assets() {

				//Requirements check
					if ( ! $this->can_display_button() ) {
						return;
					}

				//Helper variables
					$plugin_data = ( function_exists( 'get_plugin_data' ) ) ? ( get_plugin_data( WMTAB_PLUGIN_FILE, false ) ) : ( array( 'Version' => $GLOBALS['wp_version'] ) );

				//Enqueue assets
					wp_enqueue_style(
							'wmtab-styles',
							WMTAB_PLUGIN_URL . 'assets/css/style.css',
							false,
							$plugin_data['Version'],
							'screen'
						);

			} // /enqueue_assets



			/**
			 * Toggle button
			 *
			 * @link  https://kovshenin.com/2012/current-url-in-wordpress/
			 *
			 * @since    1.0
			 * @version  1.2
			 */
			public function toggle_button() {

				//Requirements check
					if ( ! $this->can_display_button() ) {
						return;
					}

				//Helper variables
					global $wp;

					$url = ( isset( $wp->request ) ) ? ( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) ) : ( false );

					//Requirements check
						if ( ! $url ) {
							return;
						}

						$url = esc_url( remove_query_arg( array( 'wmtab-on', 'wmtab-off' ), $url ) );
						$url = esc_url( add_query_arg( 'wmtab-on', '', $url ) );

				//Processing
					if ( ! is_admin_bar_showing() ) {

						$url = str_replace( 'wmtab-off', 'wmtab-on', $url );

						$button_text = _x( 'On', 'Button label. Enable admin bar.', 'wm_domain' );

					} else {

						$url = str_replace( 'wmtab-on', 'wmtab-off', $url );

						$button_text = _x( 'Off', 'Button label. Disable admin bar.', 'wm_domain' );

					}

				//Output
					echo '<div class="wm-toggle-admin-bar-container"><a href="' . esc_url( $url ) . '" title="' . __( 'Toggle admin bar', 'wm_domain' ) . '" class="wm-toggle-admin-bar">' . $button_text . '</a></div>';

			} // /toggle_button



			/**
			 * Toggle admin bar
			 *
			 * @since    1.0
			 * @version  1.0
			 */
			public function toggle_admin_bar() {

				//Requirements check
					if ( ! $this->can_display_button() ) {
						return;
					}

				//Toggle admin bar
					if (
							isset( $_GET['wmtab-on'] )
							&& ! is_admin_bar_showing()
						) {

						add_filter( 'show_admin_bar', '__return_true' );

					} elseif (
							isset( $_GET['wmtab-off'] )
							&& is_admin_bar_showing()
						) {

						add_filter( 'show_admin_bar', '__return_false' );

					}

			} // /toggle_admin_bar





		/**
		 * Localization
		 */

			/**
			 * Localization
			 *
			 * Load the translation file for the current language.
			 *
			 * Checks the languages folder inside the plugin first,
			 * and then the default WordPress languages folder.
			 *
			 * Note: the first-loaded translation file overrides any
			 * following ones if the same translation is present.
			 *
			 * @since    1.0
			 * @version  1.0
			 */
			public function load_textdomain() {

				//Traditional WordPress plugin locale filter
					$locale = apply_filters( 'plugin_locale', get_locale(), 'wm_domain' );
					$mofile = $locale . '.mo';

				//Look in local /wp-content/plugins/webman-toggle-admin-bar/languages/ folder
					load_textdomain( 'wm_domain', trailingslashit( WMTAB_PLUGIN_DIR ) . 'languages/' . $mofile );

				//Look in global /wp-content/languages/webman-toggle-admin-bar folder
					load_textdomain( 'wm_domain', trailingslashit( WP_LANG_DIR ) . 'plugins/webman-toggle-admin-bar/' . $mofile );

			} // /load_textdomain

	} // /WM_Toggle_Admin_Bar

} // /class WM_Toggle_Admin_Bar check
