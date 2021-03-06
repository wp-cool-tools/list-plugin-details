<?php
/**
 *
 * @package   ListPluginInformation
 * @author    Kybernetik Services <wordpress@kybernetik.com.de>
 * @license   GPLv3 or later
 * @link      https://www.kybernetik-services.com
 * @copyright Kybernetik Services
 *
 * @wordpress-plugin
 * Plugin Name:       List Plugin Information (from wordpress.org)
 * Plugin URI:        http://wordpress.org/plugins/list-plugin-information/
 * Description:       This tiny plugin lists selected plugin information which are provided on wordpress.org. It allows you to show a showcase of your developed or your preferred plugins, which are listed on wordpress.org.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.3
 * Author:            Kybernetik Services
 * Author URI:        https://www.kybernetik-services.com/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=list-plugin-information&utm_content=author
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       list-plugin-information
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Setup plugin constants.
 */
define( 'LPI_VERSION', '1.2.6' );
define( 'LPI_DOMAIN', 'list-plugin-information' );
define( 'LPI_PLUGIN_DIR', untrailingslashit( plugin_dir_path(__FILE__ ) ) );
define( 'LPI_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'LPI_ASSETS_URI', LPI_PLUGIN_URL . 'assets' );
define( 'LPI_PLUGIN_BASE', plugin_basename( __FILE__ ) );

/**
 * Class autoload for plugin classes which contains LPI in the class name
 *
 * @param $class_name
 */
function lpi_autoloader( $class_name ) {
    if ( false !== strpos( $class_name, 'LPI' ) ) {
        include plugin_dir_path( __FILE__ ) . '/includes/class-' . $class_name . '.php';
    }
}
spl_autoload_register('lpi_autoloader');

$lpi = new LPI_Generic();

if ( is_admin() ) {

    register_activation_hook(__FILE__, ['LPI_Generic', 'activation']);
    register_deactivation_hook(__FILE__, ['LPI_Generic', 'deactivation']);

    $my_settings_page = new LPI_Settings_Page();

}

