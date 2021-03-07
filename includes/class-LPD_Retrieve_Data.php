<?php
/**
 * class-LPD_Retrieve_Data.php
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

class LPD_Retrieve_Data {

    public $downloads;

    public function __construct() {

        self::get_wpo_data();

    }

    private function get_wpo_data() {

        $api_url              = 'http://api.wordpress.org/plugins/info/1.0/';
        $downloads[ 'total' ] = 0;
        $position             = 0;

        foreach ( self::get_wpo_plugins() as $id => $slug ) {

            $position++;

            $args = (object) array( 'slug' => $slug );

            $request = array( 'action' => 'plugin_information', 'timeout' => 15, 'request' => serialize( $args) );

            $response = wp_remote_post( $api_url, array( 'body' => $request ) );

            $plugin_details = unserialize( $response['body'] );

            $downloads[ 'total' ] = $downloads[ 'total' ] + $plugin_details->downloaded;

            $downloads[ $plugin_details->name ] = array( 'post_id'       => $id,
                                                         'slug'          => $plugin_details->slug,
                                                         'downloads'     => $plugin_details->downloaded,
                                                         'download_link' => $plugin_details->download_link,
                                                         'rating'        => $plugin_details->rating,
                                                         'num_ratings'   => $plugin_details->num_ratings,
                                                         'homepage'      => $plugin_details->homepage
            );

        }

        $this->downloads = $downloads;

    }

    /**
     * @return array
     */
    private function get_wpo_plugins(): array {

        $args = array(
            'numberposts'      => 0,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => array(),
            'exclude'          => array(),
            'post_type'        => 'wpo_plugin',
            'suppress_filters' => true,
        );

        $result = get_posts( $args );

        return wp_list_pluck( $result, 'post_title', 'ID');

    }
}
