<?php
/**
 *
 * @package   ListPluginDetails
 * @author    Kybernetik Services <wordpress@kybernetik.com.de>
 * @license   GPLv3 or later
 * @link      https://www.kybernetik-services.com
 * @copyright Kybernetik Services
 *
 * @wordpress-plugin
 * Plugin Name:       List Plugin Details (from wordpress.org)
 * Plugin URI:        http://wordpress.org/plugins/list-plugin-details/
 * Description:       This tiny plugin lists selected plugin details which are provided on wordpress.org. It allows you to show a showcase of your developed or your preferred plugins, which are listed on wordpress.org.
 * Version:           1.0.1
 * Requires at least: 5.0
 * Requires PHP:      7.3
 * Author:            Kybernetik Services
 * Author URI:        https://www.kybernetik-services.com/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=list-plugin-details&utm_content=author
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       list-plugin-details
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class autoload for plugin classes which contains LPI in the class name
 *
 * @param $class_name
 */
function lpd_autoloader( $class_name ) {
    if ( false !== strpos( $class_name, 'LPD' ) ) {
        include plugin_dir_path( __FILE__ ) . '/includes/class-' . $class_name . '.php';
    }
}
spl_autoload_register('lpd_autoloader');


/**
 * Setup plugin constants.
 */
define( 'LPD_VERSION', LPD_Generic::get_plugin_version() );
define( 'LPD_DOMAIN', 'list-plugin-details' );
define( 'LPD_PLUGIN_DIR', untrailingslashit( plugin_dir_path(__FILE__ ) ) );
define( 'LPD_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'LPD_ASSETS_URI', LPD_PLUGIN_URL . 'assets' );
define( 'LPD_PLUGIN_BASE', plugin_basename( __FILE__ ) );

$lpi = new LPD_Generic();

if ( is_admin() ) {

    register_activation_hook(__FILE__, ['LPD_Generic', 'activation']);
    register_deactivation_hook(__FILE__, ['LPD_Generic', 'deactivation']);

    $my_settings_page = new LPD_Settings_Page();

}

