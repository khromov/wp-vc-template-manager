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

### Hooks

The plugin exposes hooks 

### Forking the base plugin

You can fork this plugin by simply changing the folder name and removing the row starting with "GitHub Theme URI" from the plugin header.

The hook prefix can be modified by changing the $VCTM_PREFIX variable in the main plugin file.

The translation textdomain can be changed with the vctm_textdomain filter:

```php
add_filter('vctm_textdomain', function($textdomain)
{
    return 'my_custom_textdomain';
});
```

### Miscellaneous

#### Template name generation

We generate template names based on their file name. Dashes and underscores are removed and each word is capitalized. You can override it with 
the vctm_<template_type>_name_<template_name> filter.

#### GitHub updater

This plugin supports GitHub updater.

#### Composer

This plugin supports Composer through the [composer/installers](https://packagist.org/packages/composer/installers) package.