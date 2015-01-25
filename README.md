## Visual Composer Template Manager
Template manager for Visual Composer

### Description

This plugin provides a framework for creating themes and plugins that utilize the built-in templates in Visual Composer (as of version 4.4)

It works by loading templates from files in your theme (and optionally plugin) folders, making it possible to version control templates in a simple way.

[[image]]

### Building templates

Building a template is easy. Start out by creating a layout in Visual Composer. (For example, by creating a new page with the layout you want.)

When you are done, click the "Classic mode" button to return to the TinyMCE WYSISYG, and then click on the Text tab. Now you can copy the content 
of your layout into a template!

[[image]]

### Using in a theme

To bundle templates with your theme, create the folder:

```
/your-theme/vc_templates
```

Then, create a text file, for example:

```
/your-theme/vc_templates/my-template.php
```

Paste the template you got from the "Building templates" section above, and you will see a new template called "My Template" after you click
on Templates > Default Templates in Visual Composer. You're done!

### Using in a plugin

If you want to use this in a plugin, use the vctm_template_locations filter to add a custom path in which templates will be searched.

If your plugin directory is:

```
/my-plugin/
```

Create the folder vc_templates/

```
/my-plugin/vc_templates
```

Add the following code to your main plugin file:

```php
add_filter('vctm_template_locations', function($locations)
{
    //Add custom plugin path location
    $locations[] = plugin_dir_path( __FILE__ ) . 'my_templates/';
    
    //Return
    return $locations;
});
```

### Forking the base plugin

You can fork this plugin by simply changing the folder name and removing the row starting with "GitHub Theme URI" from the plugin header. 

You can then add your templates in the vc_templates/ folder of the plugin.

The hook prefix can be modified by changing the $VCTM_PREFIX variable in the main plugin file.

The translation textdomain can be changed with the vctm_textdomain filter:

```php
add_filter('vctm_textdomain', function($textdomain)
{
    return 'my_custom_textdomain';
});
```

### Removing default templates

Visual Composer ships with a lot of default templates. If you wish to remove these and only keep the ones you add with this plugin, add the following code in your themes functions.php file, or a plugin:

```php
add_action('vctm_disable_builtin_templates', '__return_true');
```

### Known issues

Due to issues in Visual Composer (as of 4.4.1), it is not possible to reorder the plugins. They will always be ordered in alphabetical order. We hope
to solve this with future versions of VC.

### Hooks

This plugin exposes hooks to control almost every aspect of the plugin.

##### vctm_disable_builtin_templates

Lets you disable VC:s own built-in templates

*Parameters: $current_value - Boolean*  
*Return value: Boolean*

##### vctm_template_locations

Lets you register custom template locations for use in plugins and themes.

*Parameters: $locations - Array of current paths which will be looked in for templates*  
*Return value: Array of current locations (Append your location to existing array)*

##### vctm_<TEMPLATE_SOURCE>_name_<TEMPLATE_NAME>

Lets you change the name that is displayed in VC for a given template

*Dynamic value: <TEMPLATE_SOURCE> - The source of the template. "theme" if it came from the theme, "vctm" if it came from the VCTM plugin and "plugin" if it came from a third party plugin using the vctm_template_locations hook. (Don't register multiple paths where template names can collide - you won't be able to filter them.*  
*Dynamic value: <TEMPLATE_NAME> - The name of the template, without extension. Example: my_template*  
*Parameters: $current_name - The auto-generated name*   
*Return value: String - new name*

##### vctm_<TEMPLATE_SOURCE>_class_<TEMPLATE_NAME>

Lets you change the HTML class which wraps the plugin in the VC modal. Lets you set the template icon through CSS.

*Dynamic value: <TEMPLATE_SOURCE> - The source of the template. "theme" if it came from the theme, "vctm" if it came from the VCTM plugin and "plugin" if it came from a third party plugin using the vctm_template_locations hook. (Don't register multiple paths where template names can collide - you won't be able to filter them.*  
*Dynamic value: <TEMPLATE_NAME> - The name of the template, without extension. Example: my_template*  
*Parameters: $current_class_name - The auto-generated class name*   
*Return value: String - new name*

##### vctm_<TEMPLATE_SOURCE>_content_<TEMPLATE_NAME>

Lets you dynamically alter the contents of a template, for example to perform pre-processing.

*Dynamic value: <TEMPLATE_SOURCE> - The source of the template. "theme" if it came from the theme, "vctm" if it came from the VCTM plugin and "plugin" if it came from a third party plugin using the vctm_template_locations hook. (Don't register multiple paths where template names can collide - you won't be able to filter them.*  
*Dynamic value: <TEMPLATE_NAME> - The name of the template, without extension. Example: my_template*  
*Parameters: $current_content - The template content*   
*Return value: String - new template content*

### Miscellaneous

#### Template name generation

We generate template names based on their file name. Dashes and underscores are removed and each word is capitalized. You can override it with 
the vctm_<template_type>_name_<template_name> filter.

#### GitHub updater

This plugin supports GitHub updater.

#### Composer

This plugin supports Composer through the [composer/installers](https://packagist.org/packages/composer/installers) package.

