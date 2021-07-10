<?php
/**
 * class-LPD_Shortcode_wpo_plugin.php
 *
 * @author      Sandro Lucifora
 * @copyright   (c) 2021, Kybernetik Services
 * @link        https://www.kybernetik-services.com
 * @package     ListPluginDetails
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

class LPD_Shortcode_wpo_plugin {

    /**
     * A dummy constructor to prevent from being loaded more than once.
     *
     * @since 1.0.0
     * @see LPD_Shortcode_wpo_plugin::instance()
     */
    private function __construct() { /* Do nothing here */ }

    /**
     * Main LPD_Shortcode Instance.
     *
     * Insures that only one instance of LPD_Shortcode exists in memory at any
     * one time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0.0
     *
     * @static object $instance
     *
     */
    public static function instance(): ?LPD_Shortcode_wpo_plugin {

        // Store the instance locally to avoid private static replication.
        static $instance = null;

        // Only run these methods if they haven't been run previously.
        if ( null === $instance ) {
            $instance = new LPD_Shortcode_wpo_plugin;
            $instance->init();
        }

        // return the instance.
        return $instance;
    }

    /**
     * Initialization
     *
     * @since 1.0.0
     */
    private function init() {

    }

    public static function shortcode_wpo_plugin_showcase( $atts ): string {

        $data = new LPD_Retrieve_Data();
        ob_start();

        lpd_get_template( 'wpo-showcase.php', array( 'data' => $data ) );

        return ob_get_clean();

    }

}
