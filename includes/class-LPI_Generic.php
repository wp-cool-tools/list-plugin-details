<?php
/**
 * class-LPI_Generic.php
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

class LPI_Generic {

    /**
     * A class constructor
     *
     * @since 1.0.0
     *
     */
    public function __construct() {

        // load required files
        self::load_files();

        // init plugin
        self::init();

        // add custom post types
        LPI_Custom_Post_Types::instance();

    }

    private function load_files() {

        require_once ( LPI_PLUGIN_DIR. '/functions/lpi-template.php' );

    }

    private function init() {

        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        add_shortcode( 'wpo_plugin_showcase', array('LPI_Shortcode_wpo_plugin', 'shortcode_wpo_plugin_showcase' ) );

        if( is_admin() ) {

            // Add an action link pointing to the options page.
            $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . LPI_DOMAIN . '.php' );
            add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

        }

    }

    /**
     * Returns plugin version or timestamp, depending on environment type
     * Needed for enqueueing
     *
     * @return int|string
     *
     * @since 1.0.0
     *
     */
    public static function get_enqueue_version() {

        switch ( wp_get_environment_type() ) {
            case 'development':
            case 'staging':
                $version = time();
                break;

            default:
                $version = self::get_plugin_version();

                break;
        }

        return $version;

    }

    /**
     * Returns current plugin version
     *
     * @return int|string
     *
     * @since 1.0.0
     *
     */
    public static function get_plugin_version() {

        $plugin_data = get_plugin_data( __FILE__ );

        return $plugin_data[ 'Version' ];

    }

    /**
     *	load_plugin_textdomain()
     *
     *	Set up localization for this plugin
     *	loading the text domain.
     *
     *	@uses	load_plugin_textdomain()
     *
     *	@since	1.0.0
     */
    public static function load_plugin_textdomain() {

        load_plugin_textdomain( LPI_DOMAIN, false, '/list-plugin-information/languages/' );

    }

    /**
     * Add settings action link to the plugins page.
     *
     * @param $links
     *
     * @return array
     * @since 1.0.0
     */
    public function add_action_links( $links ): array {
        $url = 'edit.php?post_type=wpo_plugin&page=lpi';
        return array_merge(
            array(
                'settings' => sprintf( '<a href="%s">%s</a>', esc_url( admin_url( $url ) ), __( 'Settings', LPI_DOMAIN ) )
            ),
            $links
        );

    }

    /**
     * Stuff to do on plugin activation
     *
     * @since 1.0.0
     */
    public static function activation() {

    }

    /**
     * Stuff to do on plugin deactivation
     *
     * @since 1.0.0
     */
    public static function deactivation() {

    }

}