<?php
/*
Plugin Name: Visual Composer Template Manager
Description: Easily version control your Visual Composer Templates
Author: khromov
Version: 1.0
GitHub Plugin URI: khromov/wp-vc-template-manager
Author URI: http://snippets.khromov.se
*/

//Prefix for hooks
$VCTM_PREFIX = 'vctm';

/** Hook into VC and assembles "Default Templates" data **/
add_action('after_setup_theme', function()
{
    global $VCTM_PREFIX;

    /** Optionally remove the default templates **/
    add_filter('vc_load_default_templates', function($templates) use ($VCTM_PREFIX)
    {
        if(apply_filters("{$VCTM_PREFIX}_disable_builtin_templates", false))
            return array();
        else
            return $templates;
    }, 11);

    /** Add our templates **/
    add_filter('vc_load_default_templates', function($templates) use ($VCTM_PREFIX)
    {
        //Load templates from plugin /vc_templates directory
        $templates = array_merge($templates, vctm_load_templates(__DIR__ . "/vc_templates/*.php", 'vctm'));

        //Load templates from theme
        $templates = array_merge($templates, vctm_load_templates( trailingslashit(get_stylesheet_directory()) . 'vc_templates/*.php', 'theme'));

        //Load additional templates from plugins or themes
        foreach(apply_filters("{$VCTM_PREFIX}_template_locations", array()) as $additional_location)
            $templates = array_merge($templates, vctm_load_templates( trailingslashit($additional_location) . '*.php', 'plugin'));

        return $templates;
    }, 12);
});

/**
 * Handles template loading
 *
 * @param string $folder
 * @param string $hook_prefix
 * @return array
 */
function vctm_load_templates($folder = 'default_templates', $hook_prefix = 'vctm')
{
    global $VCTM_PREFIX;

    $templates = array();

    //Load default tempaltes
    foreach (glob($folder) as $filename)
    {
        $filename_clean = basename($filename, '.php');

        $data = array();
        $data['name']       = __( apply_filters("{$VCTM_PREFIX}_{$hook_prefix}_name_{$filename_clean}", vctm_prettify_name($filename_clean)), apply_filters("{$VCTM_PREFIX}_textdomain", 'vc_template_manager') );
                             //TODO: This filter does not work due to VC bug as of v4.4.1
        $data['weight']     = apply_filters("{$VCTM_PREFIX}_{$hook_prefix}_weight_{$filename_clean}", apply_filters("{$VCTM_PREFIX}_default_weight", 0));
        $data['custom_class'] = apply_filters("{$VCTM_PREFIX}_{$hook_prefix}_class_{$filename_clean}", "vctm_{$hook_prefix}_{$filename_clean}");
        $data['content']    = apply_filters("{$VCTM_PREFIX}_{$hook_prefix}_content_{$filename_clean}", file_get_contents($filename));

        $templates[] = $data;
    }

    return $templates;
}

/**
 * Prettifies an ugly file name
 *
 * @param $name
 * @return string
 */
function vctm_prettify_name($name)
{
    global $VCTM_PREFIX;

    //Normalize spaces
    $name = str_replace(array('-', '_'), ' ', $name);

    $ret = '';
    foreach(explode(' ', $name) as $word)
        $ret .= ucfirst($word) . ' ';

    return rtrim($ret);
}
