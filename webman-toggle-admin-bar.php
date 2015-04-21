<?php

/**
 * WebMan Toggle Admin Bar
 *
 * A plugin for easy testing WordPress themes with and without admin bar.
 * The admin bar needs to be enabled.
 *
 * @package    WebMan Toggle Admin Bar
 * @copyright  2015 WebMan - Oliver Juhas
 * @license    GPL-2.0+, http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @wordpress-plugin
 * Plugin Name:        WebMan Toggle Admin Bar
 * Plugin URI:         http://www.webmandesign.eu/
 * Description:        A plugin for disabling and enabling WordPress admin bar on front end. The admin bar needs to be enabled in WordPress admin first.
 * Version:            1.2
 * Author:             WebMan - Oliver Juhas
 * Author URI:         http://www.webmandesign.eu/
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:        wm_domain
 * Domain Path:        /languages
 * Requires at least:  4.1
 * Tested up to:       4.2
 */



//Exit if accessed directly
if ( ! defined( 'WPINC' ) ) exit;





/**
 * Constants
 */

	//Define global constants

		//Paths
			if ( ! defined( 'WMTAB_PLUGIN_FILE' ) ) define( 'WMTAB_PLUGIN_FILE', __FILE__ );
			if ( ! defined( 'WMTAB_PLUGIN_DIR' ) )  define( 'WMTAB_PLUGIN_DIR',  trailingslashit( plugin_dir_path( __FILE__ ) ) );
			if ( ! defined( 'WMTAB_PLUGIN_URL' ) )  define( 'WMTAB_PLUGIN_URL',  trailingslashit( plugin_dir_url( __FILE__ ) ) );





/**
 * Plugin setup
 */

	/**
	 * Plugin activation
	 *
	 * @since    1.0
	 * @version  1.0
	 */
	function wmtab_activate() {
		do_action( 'wmhook_wmtab_plugin_activation' );
	} // /wmtab_activate

	register_activation_hook( WMTAB_PLUGIN_FILE, 'wmtab_activate' );



	/**
	 * Plugin deactivation
	 *
	 * @since    1.0
	 * @version  1.0
	 */
	function wmtab_deactivate() {
		do_action( 'wmhook_wmtab_plugin_deactivation' );
	} // /wmtab_deactivate

	register_deactivation_hook( WMTAB_PLUGIN_FILE, 'wmtab_deactivate' );



	/**
	 * Load the plugin
	 */

		/**
		 * Load the plugin main class
		 */
		require_once( WMTAB_PLUGIN_DIR . 'includes/class-webman-toggle-admin-bar.php' );



		/**
		 * Execute the plugin
		 *
		 * @since    1.0
		 * @version  1.0
		 *
		 * @return  WebMan Toggle Admin Bar instance
		 */
		function wm_toggle_admin_bar() {
			$plugin = new WM_Toggle_Admin_Bar();
			$plugin->run();
		} // /wm_toggle_admin_bar

		wm_toggle_admin_bar();
