<?php
/*
Plugin Name: Visual Composer Template Manager
Description: Easily version control your Visual Composer Templates
Author: khromov
Version: 1.0
Author URI: http://snippets.khromov.se
*/

//Prefix for hooks
$VCTM_PREFIX = 'vctm_';

/** Hook into VC and assembles "Default Templates" data **/
add_action('after_setup_theme', function()
{
    add_filter('vc_load_default_templates', function($templates)
    {
        global $VCTM_PREFIX;

        //Load templates from plugin /vc_templates directory
        $templates = array_merge($templates, vctm_load_templates(__DIR__ . "/vc_templates/*.php", 'vctm'));

        //Load templates from theme
        $templates = array_merge($templates, vctm_load_templates( trailingslashit(get_stylesheet_directory_uri()) . 'vc_templates/*.php', 'theme'));

        //Load additional templates from plugins or themes
        foreach(apply_filters("{$VCTM_PREFIX}_template_locations", array()) as $additional_location)
            $templates = array_merge($templates, vctm_load_templates( trailingslashit(get_stylesheet_directory_uri()) . 'vc_templates/*.php', 'theme'));

        return $templates;
    }, 11);
});

/**
 * Handles template loading
 *
 * @param string $folder
 * @param bool $absolute_path
 * @return array
 */
function vctm_load_templates($folder = 'default_templates', $absolute_path = false)
{
    global $VCTM_PREFIX;
    $templates = array();

    if($absolute_path === true)
    {
        $path = $folder;
    }
    else if($folder === '_root')
    {
        $folder_prefix = 'default';
        $path =  "/templates/*.php";
    }
    else
        $path =  "/templates/{$folder}/*.php";

    //Load default tempaltes
    foreach (glob(__DIR__ . $path) as $filename)
    {
        $filename_clean = basename($filename, '.php');

        $data = array();
        $data['name']       = __( apply_filters("{$VCTM_PREFIX}_{$folder}_name_{$filename_clean}", vctm_prettify_name($filename_clean)), apply_filters("{$VCTM_PREFIX}_textdomain", 'vc_template_manager') );
        $data['weight']     = apply_filters("{$VCTM_PREFIX}_{$folder}_weight_{$filename_clean}", apply_filters("{$VCTM_PREFIX}_default_weight", 0));
        $data['custom_class'] = apply_filters("{$VCTM_PREFIX}_{$folder}_class_{$filename_clean}", $filename_clean);
        $data['content']    = apply_filters("{$VCTM_PREFIX}_{$folder}_content_{$filename_clean}", file_get_contents($filename));

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

    return apply_filters("{$VCTM_PREFIX}_prettify_filename_{$name}", rtrim($ret));
}