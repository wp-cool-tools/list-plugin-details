<?php
/**
 * class-LPD_Template.php
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

class LPD_Template {

    /**
     * locate_template()
     *
     * Locate a template and return the path
     * for inclusion or load if desired.
     *
     * This is the load order:
     *
     *  /wp-content/themes/  theme (child) / $template_name
     *
     *	/wp-content/themes/  theme (child) / LPD_DOMAIN (e.g. list-plugin-details) / $template_name
     *
     *  /wp-content/themes/  theme (parent) / $template_name
     *
     *	/wp-content/themes/  theme (parent) / LPD_DOMAIN (e.g. list-plugin-details) / $template_name
     *
     *  $template_path (custom path from addon for example) / $template_name
     *
     *  /wp-content/plugins/  LPD_DOMAIN (e.g. list-plugin-details) / templates / $template_name
     *
     * @param string|array $template_names Template name (incl. file extension like .php)
     * @param string $template_path Custom template path for plugins and addons (default: '')
     * @param bool $load Call load_template() if true or return template path if false
     * @uses trailingslashit()
     * @uses get_stylesheet_directory()
     * @uses get_template_directory()
     * @uses lpd_get_templates_dir()
     * @return string $located Absolute path to template file (if $load is false)
     *
     * @since 1.0.0
     */
    public static function locate_template( $template_names, $args = array(), $template_path = '', $load = false, $require_once = false ): string {
        global $post, $wp_query, $wpdb;

        if ( $args && is_array( $args ) )
            extract( $args );

        // No file found yet
        $located = false;

        // Try to find a template file
        foreach ( (array) $template_names as $template_name ) {

            // Continue if template is empty
            if ( empty( $template_name ) )
                continue;

            // Trim off any slashes from the template name
            $template_name = ltrim( $template_name, '/' );

            // Check child theme
            if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $template_name ) ) {
                $located = trailingslashit( get_stylesheet_directory() ) . $template_name;
                break;

                // Check extra folder in child theme
            } elseif ( file_exists( trailingslashit( get_stylesheet_directory() . '/' . LPD_DOMAIN ) . $template_name ) ) {
                $located = trailingslashit( get_stylesheet_directory() . '/' . LPD_DOMAIN ) . $template_name;
                break;

                // Check parent theme
            } elseif ( file_exists( trailingslashit( get_template_directory() ) . $template_name ) ) {
                $located = trailingslashit( get_template_directory() ) . $template_name;
                break;

                // Check extra folder parent theme
            } elseif ( file_exists( trailingslashit( get_template_directory() . '/' . LPD_DOMAIN ) . $template_name ) ) {
                $located = trailingslashit( get_template_directory() . '/' . LPD_DOMAIN ) . $template_name;
                break;

                // Check custom path templates (e.g. from addons)
            } elseif ( file_exists( trailingslashit( $template_path ) . $template_name ) ) {
                $located = trailingslashit( $template_path ) . $template_name;
                break;

                // Check plugin templates
            } elseif ( file_exists( trailingslashit( lpd_get_templates_dir() ) . $template_name ) ) {
                $located = trailingslashit( lpd_get_templates_dir() ) . $template_name;
                break;
            }

        }

        $located = apply_filters( 'lpd_locate_template', $located, $template_names, $template_path, $load, $require_once );

        // Load found template if required

        if ( ( true == $load ) && ! empty( $located ) ) {

            if ( $require_once )
                require_once $located;
            else
                require $located;

        }

        // Or return template file path
        return $located;
    }

    /**
     * get_template_part()
     *
     * Load specific template part.
     *
     * @param string $slug The slug name for the generic template
     * @param string $name The name of the specialized template
     * @param string $template_path Custom template path for plugins and addons (default: '')
     * @param bool $load Call load_template() if true or return template path if false
     * @uses self::locate_template()
     * @return string $located Absolute path to template file (if $load is false)
     *
     * @since 1.0.0
     */
    public static function get_template_part( $slug, $name = null, $args = array(), $template_path = '', $load = true, $require_once = false ): string {

        // Execute code for this part
        do_action( 'lpd_get_template_part_' . $slug, $slug, $name, $args, $template_path, $load, $require_once );

        // Setup possible parts
        $templates = array();
        if ( isset( $name ) )
            $templates[] = $slug . '-' . $name . '.php';
        $templates[] = $slug . '.php';

        // Allow template parts to be filtered
        $templates = apply_filters( 'lpd_get_template_part', $templates, $slug, $name, $args, $template_path, $load, $require_once );

        // Return the part that is found
        return self::locate_template( $templates, $args, $template_path, $load, $require_once );
    }

}