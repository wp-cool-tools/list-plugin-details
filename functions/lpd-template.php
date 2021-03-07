<?php
/**
 *	lpd_get_template()
 *
 *	Load specific template file.
 *
 *	@param	string|array	$template_names	Template name (incl. file extension like .php)
 *	@param	string			$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool			$load			Call load_template() if true or return template path if false
 *	@uses	lpd_locate_template()
 *	@return	string			$located		Absolute path to template file (if $load is false)
 *
 *	@since 1.0.0
 */
function lpd_get_template( $template_names, $args = array(), $template_path = '', $load = true, $require_once = false ): string {

    // Execute code for this template
    do_action( 'lpd_get_template', $template_names, $args, $template_path, $load, $require_once );

    return lpd_locate_template( $template_names, $args, $template_path, $load, $require_once );

}

/**
 *	lpd_locate_template()
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
 *	 	/wp-content/plugins/		LPD_DOMAIN (e.g. list-plugin-details)	/	templates	/	$template_name
 *
 *	@param	string|array	$template_names	Template name (incl. file extension like .php)
 *	@param	string			$template_path	Custom template path for plugins and addons (default: '')
 *	@param	bool			$load			Call load_template() if true or return template path if false
 *	@uses	LPD_Template::locate_template()
 *	@return	string			$located		Absolute path to template file (if $load is false)
 *
 *	@since 1.0.0
 */
function lpd_locate_template( $template_names, $args = array(), $template_path = '', $load = false, $require_once = false ): string {
    return LPD_Template::locate_template( $template_names, $args, $template_path, $load, $require_once );
}

/**
 *	lpd_get_template_part()
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
function lpd_get_template_part( $slug, $name = null, $args = array(), $template_path = '', $load = true, $require_once = false ): string {
    return LPD_Template::get_template_part( $slug, $name, $args, $template_path, $load, $require_once );
}

/**
 *	lpd_get_templates_dir()
 *
 *	Return path to LPD_DOMAIN (e.g. list-plugin-details)
 *	templates directory.
 *
 *	@return	string
 *
 *	@since 1.0.0
 */
function lpd_get_templates_dir(): string {
    return LPD_PLUGIN_DIR . '/templates/';
}
