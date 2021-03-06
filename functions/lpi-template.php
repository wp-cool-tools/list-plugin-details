<?php
/**
 *	lpi_get_template()
 *
 *	Load specific template file.
 *
 *	@param	string|array	$template_names	Template name (incl. file extension like .php)
 *	@param	string			$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool			$load			Call load_template() if true or return template path if false
 *	@uses	lpi_locate_template()
 *	@return	string			$located		Absolute path to template file (if $load is false)
 *
 *	@since 1.0.0
 */
function lpi_get_template( $template_names, $args = array(), $template_path = '', $load = true, $require_once = false ): string {

    // Execute code for this template
    do_action( 'lpi_get_template', $template_names, $args, $template_path, $load, $require_once );

    return lpi_locate_template( $template_names, $args, $template_path, $load, $require_once );

}

/**
 *	lpi_locate_template()
 *
 *	Locate a template and return the path
 *	for inclusion or load if desired.
 *
 *	This is the load order:
 *
 *	 	/wp-content/themes/		theme (child)	/										$template_name
 *
 *	 	/wp-content/themes/		theme (parent)	/										$template_name
 *
 *	 	$template_path (custom path from addon for example) 						/	$template_name
 *
 *	 	/wp-content/plugins/		LPI_DOMAIN (e.g. list-plugin-information)	/	templates	/	$template_name
 *
 *	@param	string|array	$template_names	Template name (incl. file extension like .php)
 *	@param	string			$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool			$load			Call load_template() if true or return template path if false
 *	@uses	LPI_Template::locate_template()
 *	@return	string			$located		Absolute path to template file (if $load is false)
 *
 *	@since 1.0.0
 */
function lpi_locate_template( $template_names, $args = array(), $template_path = '', $load = false, $require_once = false ): string {
    return LPI_Template::locate_template( $template_names, $args, $template_path, $load, $require_once );
}

/**
 *	lpi_get_template_part()
 *
 *	Load specific template part.
 *
 *	@param	string	$slug			The slug name for the generic template
 *	@param	string	$name			The name of the specialized template
 *	@param	string	$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool	$load			Call load_template() if true or return template path if false
 *	@return	string	$located		Absolute path to template file (if $load is false)
 *
 *	@since 1.0.0
 */
function lpi_get_template_part( $slug, $name = null, $args = array(), $template_path = '', $load = true, $require_once = false ): string {
    return LPI_Template::get_template_part( $slug, $name, $args, $template_path, $load, $require_once );
}

/**
 *	lpi_get_templates_dir()
 *
 *	Return path to LPI_DOMAIN (e.g. list-plugin-information)
 *	templates directory.
 *
 *	@return	string
 *
 *	@since 1.0.0
 */
function lpi_get_templates_dir(): string {
    return LPI_PLUGIN_DIR . '/templates/';
}
