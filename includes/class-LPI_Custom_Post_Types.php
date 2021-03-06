<?php
/**
 * class-LPI_Custom_Post_Types.php
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

class LPI_Custom_Post_Types {

    /**
     * A dummy constructor to prevent from being loaded more than once.
     *
     * @since 1.0.0
     * @see LPI_Custom_Post_Types::instance()
     */
    private function __construct() { /* Do nothing here */ }

    /**
     * Main LPI_Custom_Post_Types Instance.
     *
     * Insures that only one instance of LPI_Custom_Post_Types exists in memory at any
     * one time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0.0
     *
     * @static object $instance
     *
     */
    public static function instance() {

        // Store the instance locally to avoid private static replication.
        static $instance = null;

        // Only run these methods if they haven't been run previously.
        if ( null === $instance ) {
            $instance = new LPI_Custom_Post_Types;
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

        add_action( 'init', array( $this, 'register_cpt' ) );
//        add_action( 'admin_init', array($this, 'admin_init' ) );

        add_action( 'add_meta_boxes', array( $this, 'remove_wpseo_meta' ), 25 );

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 2000 );

        add_filter( 'enter_title_here', array( $this, 'change_enter_title' ) );

        /*
         * run the following code only if Polylang or Polylang Pro is activated
         */
        if ( defined( 'POLYLANG' ) || defined( 'POLYLANG_PRO' ) ) {

            add_filter( 'pll_get_post_types',  array( $this, 'pll_post_types' ) );
            add_filter( 'pll_get_taxonomies',  array( $this, 'pll_taxonomies' ) );
            add_filter( 'pll_copy_post_metas', array( $this, 'pll_post_metas' ) );

        }

    }

    public function admin_init() {

        // add column to wpo_plugin page taxonomy
        add_filter( 'manage_wpo_plugin_columns', array( $this, 'posts_columns' ) );
        add_filter( 'manage_wpo_plugin_custom_column', array( $this, 'posts_custom_columns'), 10, 3);

    }

    public function posts_columns( $defaults ){
        echo '<pre>';
        print_r( $defaults);
        echo '</pre>';

        wp_die();

        $defaults['riv_post_thumbs'] = __('Thumbs');
        return $defaults;
    }

    public function posts_custom_columns( $column_name, $id ){
        wp_die();
        if($column_name === 'riv_post_thumbs'){
           //echo the_post_thumbnail( 'featured-thumbnail' );
        }
    }

    public function remove_wpseo_meta() {

        if( class_exists('WPSEO_Metabox_Analysis_SEO') && 'wpo_plugin' == self::get_current_admin_post_type() ){

            remove_meta_box( 'wpseo_meta', 'wpo_plugin', 'normal' );
            remove_meta_box( 'yoast_internal_linking', 'wpo_plugin', 'side' );

        }
    }

    private function get_current_admin_post_type(): ?string {

        global $post, $parent_file, $typenow, $current_screen, $pagenow;

        $post_type = NULL;

        if($post && (property_exists($post, 'post_type') || method_exists($post, 'post_type')))
            $post_type = $post->post_type;

        if(empty($post_type) && !empty($current_screen) && (property_exists($current_screen, 'post_type') || method_exists($current_screen, 'post_type')) && !empty($current_screen->post_type))
            $post_type = $current_screen->post_type;

        if(empty($post_type) && !empty($typenow))
            $post_type = $typenow;

        if(empty($post_type) && function_exists('get_current_screen'))
            $post_type = get_current_screen();

        if(empty($post_type) && isset($_REQUEST['post']) && !empty($_REQUEST['post']) && function_exists('get_post_type') && $get_post_type = get_post_type((int)$_REQUEST['post']))
            $post_type = $get_post_type;

        if(empty($post_type) && isset($_REQUEST['post_type']) && !empty($_REQUEST['post_type']))
            $post_type = sanitize_key($_REQUEST['post_type']);

        if(empty($post_type) && 'edit.php' == $pagenow)
            $post_type = 'post';

        return $post_type;

    }

    /**
     * Register custom post type
     *
     * @since 1.0.0
     */
    public function register_cpt() {

        $labels = array(
            'name'                  => _x( 'WPO Plugins', 'Post Type General Name', LPI_DOMAIN ),
            'singular_name'         => _x( 'WPO Plugin', 'Post Type Singular Name', LPI_DOMAIN ),
            'menu_name'             => __( 'WPO Plugins', LPI_DOMAIN ),
            'name_admin_bar'        => __( 'WPO Plugins', LPI_DOMAIN ),
            'archives'              => __( 'WPO Plugin Archives', LPI_DOMAIN ),
            'attributes'            => __( 'WPO Plugin Attributes', LPI_DOMAIN ),
            'parent_item_colon'     => __( 'Parent WPO Plugin:', LPI_DOMAIN ),
            'all_items'             => __( 'All WPO Plugins', LPI_DOMAIN ),
            'add_new_item'          => __( 'Add New WPO Plugin', LPI_DOMAIN ),
            'add_new'               => __( 'Add New', LPI_DOMAIN ),
            'new_item'              => __( 'New WPO Plugin', LPI_DOMAIN ),
            'edit_item'             => __( 'Edit WPO Plugin', LPI_DOMAIN ),
            'update_item'           => __( 'Update WPO Plugin', LPI_DOMAIN ),
            'view_item'             => __( 'View WPO Plugin', LPI_DOMAIN ),
            'view_items'            => __( 'View WPO Plugins', LPI_DOMAIN ),
            'search_items'          => __( 'Search WPO Plugin', LPI_DOMAIN ),
            'not_found'             => __( 'Not found', LPI_DOMAIN ),
            'not_found_in_trash'    => __( 'Not found in Trash', LPI_DOMAIN ),
            'featured_image'        => __( 'WPO Plugin Icon', LPI_DOMAIN ),
            'set_featured_image'    => __( 'Set WPO plugin icon', LPI_DOMAIN ),
            'remove_featured_image' => __( 'Remove WPO plugin icon', LPI_DOMAIN ),
            'use_featured_image'    => __( 'Use as WPO plugin icon', LPI_DOMAIN ),
            'insert_into_item'      => __( 'Insert into item', LPI_DOMAIN ),
            'uploaded_to_this_item' => __( 'Uploaded to this WPO plugin', LPI_DOMAIN ),
            'items_list'            => __( 'WPO Plugins list', LPI_DOMAIN ),
            'items_list_navigation' => __( 'WPO Plugins list navigation', LPI_DOMAIN ),
            'filter_items_list'     => __( 'Filter WPO plugins list', LPI_DOMAIN ),
        );
        $args = array(
            'label'                 => __( 'WPO Plugin', LPI_DOMAIN ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'thumbnail', 'page-attributes' ),
            'taxonomies'            => array( '' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-plugins-checked',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'wpo_plugin', $args );

    }

    /**
     * Add Polylang to custom post type
     *
     * @param $types
     *
     * @return array
     *
     * @since 1.0.0
     *
     */
    public function pll_post_types( $types ): array {

        return array_merge( $types, array(
                                      'wpo_plugin' => 'wpo_plugin'
                                  )
        );

    }

    /**
     * Add Polylang to custom taxonomies
     *
     * @param $types
     *
     * @return array
     *
     * @since 1.0.0
     *
     */
    public function pll_taxonomies( $types ): array {

        return array_merge( $types, array(
                                      '' => '',
                                  )
        );

    }

    /**
     * Add Polylang copy post metas which will be copied if a field will be updated
     *
     * @param $metas
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function pll_post_metas( $metas ): array {

        $copy_metas = array(
        );

        return array_merge( $metas, $copy_metas);

    }

    /**
     * Enqueue scripts and styles
     *
     * @param $hook
     *
     * @since 1.0.0
     */
    public function admin_enqueue_scripts( $hook ) {

        global $post_type;

        if( 'wpo_plugin' != $post_type )
            return;

        if( !in_array( $hook, array( 'post.php', 'post-new.php' ) ) )
            return;

        // enqueue admin style
        wp_register_style('admin', plugins_url( '../assets/css/admin.min.css', __FILE__ ), false, LPI_Generic::get_plugin_version() );
        wp_enqueue_style('admin');

    }

    /**
     * Change Enter title here text of custom post type
     *
     * @param $input
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function change_enter_title( $input ): string {

        global $post_type;

        if( is_admin() && 'wpo_plugin' == $post_type )
            return __( 'Enter plugin SLUG here (identical as on wordpress.org) ', LPI_DOMAIN );

        return $input;

    }

}
