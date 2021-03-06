<?php
/**
 * uninstall.php
 *
 * @author      Sandro Lucifora
 * @copyright   (c) 2021, Kybernetik Services
 * @link        https://www.kybernetik-services.com
 * @package     ListPluginInformation
 * @since       1.0.0
 */

if ( !defined('ABSPATH' ) ) exit;
/**
 * https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
 */
if (!defined('WP_UNINSTALL_PLUGIN')) { exit(); }

// Does function not exist?
if ( !function_exists('lpi_uninstall' ) ) {

    /**
     * Uninstall
     * @return void
     */
    function lpi_uninstall() {

        // Check Admin
        if ( is_admin() ) {

            if ( !current_user_can('delete_plugins' ) ) {
                return;
            }

            /**
             * Unregister settings
             * https://codex.wordpress.org/Function_Reference/unregister_setting
             */
            unregister_setting('lpi_settings', 'wc_rma_settings', '');
            delete_option('lpi_settings');
        }
    }

    lpi_uninstall();
}