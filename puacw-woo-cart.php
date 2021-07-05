<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              
 * @since             1.0.0
 * @package           Puacw_Woo_Cart
 *
 * @wordpress-plugin
 * Plugin Name:       Pop-Up Ajax Cart for Woocommerce
 * Plugin URI:        https://github.com/markusweblance/puacw
 * Description:       Ajax Shopping Cart for Woocommerce in pop-up.
 * Version:           1.0.0
 * Author:            Dmitry Tihonov
 * Author URI:        https://github.com/markusweblance
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       puacw-woo-cart
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PUACW_WOO_CART_VERSION', '1.0.0');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-puacw-woo-cart.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_puacw_woo_cart()
{

    $plugin = new Puacw_Woo_Cart();
    $plugin->run();

}

if (in_array(
    'woocommerce/woocommerce.php',
    apply_filters('active_plugins',
        get_option('active_plugins')))) {
    run_puacw_woo_cart();
}
