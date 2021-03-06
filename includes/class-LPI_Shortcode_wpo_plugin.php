<?php
/**
 * class-LPI_Shortcode_wpo_plugin.php
 *
 * @author      Sandro Lucifora
 * @copyright   (c) 2021, Kybernetik Services
 * @link        https://www.kybernetik-services.com
 * @package     ListPluginInformation
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

class LPI_Shortcode_wpo_plugin {

    /**
     * A dummy constructor to prevent from being loaded more than once.
     *
     * @since 1.0.0
     * @see LPI_Shortcode_wpo_plugin::instance()
     */
    private function __construct() { /* Do nothing here */ }

    /**
     * Main LPI_Shortcode Instance.
     *
     * Insures that only one instance of LPI_Shortcode exists in memory at any
     * one time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0.0
     *
     * @static object $instance
     *
     */
    public static function instance(): ?LPI_Shortcode_wpo_plugin {

        // Store the instance locally to avoid private static replication.
        static $instance = null;

        // Only run these methods if they haven't been run previously.
        if ( null === $instance ) {
            $instance = new LPI_Shortcode_wpo_plugin;
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

    public function shortcode_wpo_plugin_showcase( $atts ): string {
        $a = shortcode_atts( array(
                                 'foo' => 'something',
                                 'bar' => 'something else',
                             ), $atts );

        $data = new LPI_Retrieve_Data();
        ob_start();

        lpi_get_template( 'wpo-showcase.php', array( 'data' => $data ) );

        return ob_get_clean();

    }

}
