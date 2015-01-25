## Visual Composer Template Manager

This plugin provides a framework for creating themes and plugins that utilize the built-in templates in Visual Composer (as of version 4.4)

It works by loading templates from files in your theme (and optionally plugin) folders, making it possible to version control templates in a simple way.

### Building templates

Building a template is easy. Start out by creating a layout in Visual Composer. (For example, by creating a new page with the layout you want.)

When you are done, click the "Classic mode" button to return to the TinyMCE WYSISYG, and then click on the Text tab. Now you can copy the content of your layout into a template!

Workflow:  
![Visual workflow of text above](https://dl.dropboxusercontent.com/u/2758854/vc-whatdo.png)

### Using in a theme

To bundle templates with your theme, create the folder:

```
/your-theme/vc_templates
```

Then, create a text file, for example:

```
/your-theme/vc_templates/my-template.php
```

Paste the template you got from the "Building templates" section above, and you will see a new template called "My Template" after you click on Templates > Default Templates in Visual Composer. You're done!

Workflow:  
![Visual explanation of text above](https://dl.dropboxusercontent.com/u/2758854/vc-insert.png)

### Using in a plugin

If you want to use this in a plugin, use the vctm\_template\_locations filter to add a custom path in which templates will be searched.

If your plugin directory is:

```
/my-plugin/
```

Create the folder vc\_templates/

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

You can then add your templates in the vc\_templates/ folder of the plugin.

The hook prefix can be modified by changing the $VCTM\_PREFIX variable in the main plugin file.

The translation textdomain can be changed with the vctm\_textdomain filter:

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

Due to a bug in Visual Composer (as of 4.4.1), it is not possible to reorder the templates. They will always be ordered in alphabetical order. We hope
to solve this with future versions of VC.

### Hooks

This plugin exposes hooks to control almost anything.

##### vctm\_disable\_builtin\_templates

Lets you disable VC:s own built-in templates

*Parameters: $current\_value - Boolean*  
*Return value: Boolean*

##### vctm\_template\_locations

Lets you register custom template locations for use in plugins and themes.

*Parameters: $locations - Array of current paths which will be looked in for templates*  
*Return value: Array of current locations (Append your location to existing array)*

##### vctm\_\<TEMPLATE\_SOURCE\>\_name\_\<TEMPLATE_NAME\>

Lets you change the name that is displayed in VC for a given template

*Dynamic value: <TEMPLATE\_SOURCE> - The source of the template. "theme" if it came from the theme, "vctm" if it came from the VCTM plugin and "plugin" if it came from a third party plugin using the vctm\_template\_locations hook. (Don't register multiple paths where template names can collide - you won't be able to filter them.)*  
*Dynamic value: <TEMPLATE\_NAME> - The name of the template, without extension. Example: my\_template*  
*Parameters: $current\_name - The auto-generated name*   
*Return value: String - new name*

##### vctm\_\<TEMPLATE\_SOURCE\>\_class\_\<TEMPLATE_NAME\>

Lets you change the HTML class which wraps the plugin in the VC modal. Lets you set the template icon through CSS.

*Dynamic value: <TEMPLATE\_SOURCE> - The source of the template. "theme" if it came from the theme, "vctm" if it came from the VCTM plugin and "plugin" if it came from a third party plugin using the vctm\_template\_locations hook. (Don't register multiple paths where template names can collide - you won't be able to filter them.)*  
*Dynamic value: <TEMPLATE\_NAME> - The name of the template, without extension. Example: my\_template*  
*Parameters: $current\_class\_name - The auto-generated class name*   
*Return value: String - new name*

##### vctm\_\<TEMPLATE\_SOURCE\>\_content\_\<TEMPLATE_NAME\>

Lets you dynamically alter the contents of a template, for example to perform pre-processing.

*Dynamic value: <TEMPLATE\_SOURCE> - The source of the template. "theme" if it came from the theme, "vctm" if it came from the VCTM plugin and "plugin" if it came from a third party plugin using the vctm\_template\_locations hook. (Don't register multiple paths where template names can collide - you won't be able to filter them.)*  
*Dynamic value: <TEMPLATE\_NAME> - The name of the template, without extension. Example: my\_template*  
*Parameters: $current\_content - The template content*   
*Return value: String - new template content*

### Miscellaneous

#### Template name generation

We generate a "nice" template name based on the file name. Dashes and underscores are replaced with spaces and each word is capitalized. You can override it with 
the vctm\_\<template\_type\>\_name\_\<template_name\> filter.

#### GitHub updater

This plugin supports GitHub updater.

#### Composer

This plugin supports Composer through the [composer/installers](https://packagist.org/packages/composer/installers) package.

