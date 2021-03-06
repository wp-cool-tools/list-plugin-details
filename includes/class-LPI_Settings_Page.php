<?php
/**
 * class-LPI_Settings_Page.php
 *
 * @author      Sandro Lucifora
 * @copyright   (c) 2021, Kybernetik Services
 * @link        https://www.kybernetik-services.com
 * @package     ListPluginInformation
 * @since       1.0.0
 */

if ( !defined('ABSPATH' ) ) exit;

if ( !class_exists('LPI_Settings_Page') ) {

    class LPI_Settings_Page {

        private $admin_url;
        private $option_group;
        private $options_settings;
        private $option_page;

        public function __construct() {

            $this->admin_url    = 'admin.php?page=lpi';
            $this->option_group = 'lpi_settings';
            $this->option_page  = 'layout';

            $this->options_settings    = get_option( $this->option_group );

            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        }

        /**
         * Enqueue scripts and css on our options page
         *
         * @param $hook
         *
         * @since 1.0.0
         */
        public function admin_enqueue( $hook ) {

            if( 'wpo_plugin_page_lpi' != $hook )
                return;

            // enqueue script and style for autocomplete on admin page
/*            wp_enqueue_script( 'select2', plugins_url( '../assets/js/select2.min.js', __FILE__ ), array('jquery'), '4.0.13', 'true' );
            wp_register_style( 'select2', plugins_url( '../assets/css/select2.min.css', __FILE__ ), false, '4.0.13' );
            wp_enqueue_style( 'select2' );

            wp_enqueue_script( 'rma-admin-script', plugins_url( '../assets/js/admin.js', __FILE__ ), array('jquery'), get_option( 'lpi_version' ), 'true' );*/

        }

        /**
         * We are adding our options page
         *
         * @since 1.0.0
         */
        public function add_plugin_page(){
            // This page will be under "WooCommerce"
            add_submenu_page('edit.php?post_type=wpo_plugin', // $parent_slug
                             'Settings', // $page_title
                             __('Settings', LPI_DOMAIN), // $menu_title
                             'manage_options', // $capability
                             'lpi', // $menu_slug
                             array($this, 'create_admin_page') // $function
            );

            add_action( 'admin_init', array( $this, 'options_init') );

        }

        /**
         * Create our admin page
         *
         * @since 1.0.0
         */
        public function create_admin_page() {

            $active_page = sanitize_text_field( ( isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general' ) ); // set default tab ?>

            <div class="wrap">
                <h1><?php _e('Settings', LPI_DOMAIN); ?></h1>
                <?php settings_errors(); ?>
                <h2 class="nav-tab-wrapper">
                    <a href="<?php echo admin_url( $this->admin_url ); ?>" class="nav-tab<?php echo ( 'layout' == $active_page ? ' nav-tab-active' : '' ); ?>"><?php esc_html_e('Layout', LPI_DOMAIN); ?></a>
                </h2>

                <form method="post" action="options.php"><?php //   settings_fields( $this->option_group );
                    switch ( $active_page ) {
                        default:
                            settings_fields( $this->option_group );
                            do_settings_sections( $this->option_page );
                            submit_button();
                            break;
                    } ?>
                </form>
            </div> <?php

        }

        /**
         * Initialize Options on Settings Page
         *
         * @since 1.0.0
         */
        public function options_init() {
            register_setting(
                $this->option_group, // Option group
                $this->option_group, // Option name
                array( $this, 'sanitize' ) // Sanitize
            );

            $this->options_block();

            $this->options_row();

            $this->options_card();

            $this->options_badge();

        }

        /**
         * Page Settings, Section Block
         *
         * @since 1.0.0
         */
        public function options_block() {

            $section = 'settings_block';

            add_settings_section(
                $section, // ID
                esc_html__('Block', LPI_DOMAIN),
                '', // Callback
                $this->option_page // Page
            );

            $id = 'lpi-block-headline';
            add_settings_field(
                $id,
                esc_html__('Headline', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the headline', LPI_DOMAIN ),
                    'placeholder'  => __('Our free plugins', LPI_DOMAIN )
                )
            );

            $id = 'lpi-block-total';
            add_settings_field(
                $id,
                esc_html__('Total downloads', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the total download text and use %s as a placeholder for the retrieved number', LPI_DOMAIN ),
                    'placeholder'  => __( 'Our plugins have been downloaded a total of %s times.', LPI_DOMAIN )
                )
            );

            $id = 'lpi-block-class';
            add_settings_field(
                $id,
                esc_html__('Outer block class', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the div tag which covers the entire plugin list', LPI_DOMAIN )
                )
            );

            $id = 'lpi-block-h-tag';
            add_settings_field(
                $id,
                esc_html__('Headline Tag', LPI_DOMAIN),
                array( $this, 'option_select_cb'),
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'options'      => array(
                        'h1' => esc_html__('H1',LPI_DOMAIN),
                        'h2' => esc_html__('H2',LPI_DOMAIN),
                        'h3' => esc_html__('H3',LPI_DOMAIN),
                        'h4' => esc_html__('H4',LPI_DOMAIN),
                        'h5' => esc_html__('H5',LPI_DOMAIN),
                        'h6' => esc_html__('H6',LPI_DOMAIN),
                    ),
                    'description'  => esc_html__('Choose the headline tag for entire block', LPI_DOMAIN ),

                )
            );

            $id = 'lpi-block-h-class';
            add_settings_field(
                $id,
                esc_html__('Headline class', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the headline of the entire block', LPI_DOMAIN )
                )
            );

            $id = 'lpi-block-p-class';
            add_settings_field(
                $id,
                esc_html__('Paragraph class', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the total download text, which is shown below the headline', LPI_DOMAIN )
                )
            );

        }

        /**
         * Page General, Section Row
         *
         * @since 1.0.0
         */
        public function options_row() {
            $section = 'settings_layout';

            add_settings_section(
                $section, // ID
                esc_html__('Row', LPI_DOMAIN),
                '', // Callback
                $this->option_page // Page
            );

            $id = 'lpi-rows';
            add_settings_field(
                $id,
                esc_html__('Rows per Column', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set how many plugins should be shown per column', LPI_DOMAIN )
                )
            );

            $id = 'lpi-row-class';
            add_settings_field(
                $id,
                esc_html__('Row class', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the div tag which covers the row', LPI_DOMAIN )
                )
            );

        }

        /**
         * Page Settings, Section Card
         *
         * @since 1.0.0
         */
        public function options_card() {

            $section = 'settings_card';

            add_settings_section(
                $section, // ID
                esc_html__('Card', LPI_DOMAIN),
                '', // Callback
                $this->option_page // Page
            );

            $id = 'lpi-card-class';
            add_settings_field(
                $id,
                esc_html__('Card class', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the div tag which covers the card', LPI_DOMAIN )
                )
            );

            $id = 'lpi-figure-class';
            add_settings_field(
                $id,
                esc_html__('Figure class', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the figure tag which covers the image', LPI_DOMAIN )
                )
            );

            $id = 'lpi-button-cover';
            add_settings_field(
                $id,
                esc_html__('Button cover', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the div container that covers the button', LPI_DOMAIN )
                )
            );

            $id = 'lpi-button';
            add_settings_field(
                $id,
                esc_html__('Button', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the div container of the button', LPI_DOMAIN )
                )
            );

            $id = 'lpi-button-a';
            add_settings_field(
                $id,
                esc_html__('Button a tag', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the a tag of the button', LPI_DOMAIN )
                )
            );

            $id = 'lpi-h-tag';
            add_settings_field(
                $id,
                esc_html__('Headline Tag', LPI_DOMAIN),
                array( $this, 'option_select_cb'),
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'options'      => array(
                        'h1' => esc_html__('H1',LPI_DOMAIN),
                        'h2' => esc_html__('H2',LPI_DOMAIN),
                        'h3' => esc_html__('H3',LPI_DOMAIN),
                        'h4' => esc_html__('H4',LPI_DOMAIN),
                        'h5' => esc_html__('H5',LPI_DOMAIN),
                        'h6' => esc_html__('H6',LPI_DOMAIN),
                    ),
                    'description'  => esc_html__('Choose the headline tag for plugin card', LPI_DOMAIN ),

                )
            );

            $id = 'lpi-h-class';
            add_settings_field(
                $id,
                esc_html__('Headline class', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set the class for the plugin card', LPI_DOMAIN )
                )
            );

        }

        /**
         * Page Settings, Section Card
         *
         * @since 1.0.0
         */
        public function options_badge() {
            $section = 'settings_badge';

            add_settings_section(
                $section, // ID
                esc_html__('Badge', LPI_DOMAIN),
                '', // Callback
                $this->option_page // Page
            );

            $id = 'lpi-retrieve-version';
            add_settings_field(
                $id,
                esc_html__('Show version', LPI_DOMAIN),
                array( $this, 'option_input_checkbox_cb'), // general callback for checkbox
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Would you like to show the plugin version, retrieved from wordpress.org?', LPI_DOMAIN )
                )
            );

            $id = 'lpi-retrieve-version-label';
            add_settings_field(
                $id,
                esc_html__('Version label', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set optional the label for the plugin version', LPI_DOMAIN )
                )
            );

            $id = 'lpi-retrieve-tested';
            add_settings_field(
                $id,
                esc_html__('Show tested', LPI_DOMAIN),
                array( $this, 'option_input_checkbox_cb'), // general callback for checkbox
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Would you like to show the tested tag, retrieved from wordpress.org?', LPI_DOMAIN )
                )
            );

            $id = 'lpi-retrieve-tested-label';
            add_settings_field(
                $id,
                esc_html__('Tested label', LPI_DOMAIN),
                array( $this, 'option_input_text_cb'), // general call back for input text
                $this->option_page,
                $section,
                array(
                    'option_group' => $this->option_group,
                    'id'           => $id,
                    'value'        => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'description'  => esc_html__('Set optional the label for the tested tag', LPI_DOMAIN )
                )
            );

            $id = 'lpi-badge-style';
            add_settings_field(
                $id,
                esc_html__('Style', LPI_DOMAIN),
                array( $this, 'option_select_cb'),
                $this->option_page,
                $section,
                array(
                    'option_group'      => $this->option_group,
                    'id'                => $id,
                    'value'             => isset( $this->options_settings[ $id ] ) ? $this->options_settings[ $id ] : '',
                    'options'           => array(
                        'plastic'       => esc_html__('plastic',LPI_DOMAIN),
                        'flat'          => esc_html__('flat',LPI_DOMAIN),
                        'flat-square'   => esc_html__('flat-square',LPI_DOMAIN),
                        'for-the-badge' => esc_html__('for-the-badge',LPI_DOMAIN),
                        'social'        => esc_html__('social',LPI_DOMAIN)
                    ),
                    'description'  => esc_html__('Choose the style for your badge', LPI_DOMAIN ),
                )
            );
        }

        /**
         * General Input Field Checkbox
         *
         * @param array $args
         */
        public function option_input_checkbox_cb( array $args ){

            $option_group = ( isset( $args['option_group'] ) ) ? $args['option_group'] : '';
            $id           = ( isset( $args['id'] ) ) ? $args['id'] : '';
            $checked      = ( isset( $args['value'] ) && !empty( $args['value'] ) ) ? 'checked' : '';
            $description  = ( isset( $args['description'] ) ) ? $args['description'] : '';

            printf(
                '<input type="checkbox" id="%1$s" name="%3$s[%1$s]" value="1" %2$s />',
                $id, $checked, $option_group
            );

            if ( !empty( $description) )
                echo '<p class="description">' . $description . '</p>';

        }

        /**
         * General Input Field Text
         *
         * @param array $args
         */
        public function option_input_text_cb( array $args ) {

            $option_group = ( isset( $args['option_group'] ) ) ? $args['option_group'] : '';
            $id           = ( isset( $args['id'] ) ) ? $args['id'] : '';
            $value        = ( isset( $args['value'] ) ) ? $args['value'] : '';
            $placeholder  = ( isset( $args['placeholder'] ) ) ? $args['placeholder'] : '';
            $description  = ( isset( $args['description'] ) ) ? $args['description'] : '';

            printf(
                '<input type="text" id="%1$s" name="%3$s[%1$s]" value="%2$s" placeholder="%4$s" />',
                $id, $value, $option_group, $placeholder
            );

            if ( !empty( $description) )
                echo '<p class="description">' . $description . '</p>';
        }

        /**
         * General Select
         *
         * @param array $args
         */
        public function option_select_cb( array $args ) {
            $option_group = (isset($args['option_group'])) ? $args['option_group'] : '';
            $id           = (isset($args['id'])) ? $args['id'] : '';
            $options      = (isset($args['options'])) ? $args['options'] : array();
            $description  = (isset($args['description'])) ? $args['description'] : '';
            $class        = (isset($args['class'])) ? $args['class'] : '';

            echo '<select name="' . $option_group . '[' . $id . ']"' . ( !empty( $class) ? 'class="' . $class . '"' : '' ) . '>';

            foreach ($options as $value => $text) {
                printf(
                    '<option value="%1$s" %2$s />%3$s</option>',
                    $value,
                    ( isset( $this->options_settings[ $id ] ) && $value == $this->options_settings[ $id ] ) ? 'selected="selected"' : '',
                    $text
                );
            }

            echo '</select>';

            if ( !empty( $description) )
                echo '<p class="description">' . $description . '</p>';

        }

        /**
         * Sanitizes a string from user input
         * Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags, Removes line breaks, tabs, and extra whitespace, Strips octets
         *
         * @param array $input
         *
         * @return array
         */
        public function sanitize( array $input ): array {

            $new_input = array();

            foreach ( $input as $key => $value ) {

                $new_input[ $key ] = sanitize_text_field( $input[ $key ] );

            }

            return $new_input;
        }

    }

}
