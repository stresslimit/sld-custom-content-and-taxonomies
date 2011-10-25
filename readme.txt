=== Stresslimit Custom Content Types ===
Contributors: jkudish, cvernon, stresslimit
Tags: custom post types, custom content types, custom taxonomies, post type, taxonomy
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 1.3.2

== Description ==

This code-only developer plugin allows you to register new post types easily, with good default values and some extended functionality.

It gives 3 functions, each that wraps or extends a core WordPress function with a few extras:

1. sld_register_post_type( 'type', $optional_args, $optional_custom_plural ) which extends register_post_type()
2. sld_register_taxonomy( 'taxonomy', $post_types, $optional_singular_name, $optional_args, $optional_plural_name ) which extends register_taxonomy()
3. sld_unregister_taxonomy( 'taxonomy', $object_type ) which unregisters taxonomies (core taxonomies or those registered by other plugins)

We use it in many of our sites here @ <http://stresslimitdesign.com> and here @ <http://jkudish.com>

Originally based on the work of Matt Wiebe @ <http://somadesign.ca/projects/smarter-custom-post-types/>

You can follow development of this plugin on GitHub @ <https://github.com/jkudish/sld-custom-content-and-taxonomies>

== Installation ==

1. Upload the `sld-custom-content-and-taxonomies` folder to the `/wp-content/plugins/` directory, use SVN to manage WordPress and plugins installation or just search for the plugin in the Plugins tab and install it!
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the useful functions included here in your functions.php files to create custom post types, and to register and unregister custom taxonomies

== Frequently Asked Questions ==

= What is this plugin? I installed it and nothing happened =

This is a developer plugin, you have to put some php code in your functions.php file. If you don't know what this means, you should probably use another plugin to register your custom post types or hire a developer to help you out.

== Screenshots ==

1. No screenshots, sorry.

== Changelog ==

= 1.3.2 =

Rewrite admin body class function

= 1.3.1 =

Bug fix in the admin body class function

= 1.3 =

Various bug fixes and changes in the readme

= 1.2 =

Move classes into different files, commit to WordPress.org Plugin repository

= 1.1 =

Adding better defaults, and fixing rewrite rules (since 3.1 does most of it very well out of the box). Good & stable release now.

= 1.0 =

Added ability to unregister taxonomies

= 0.9 =

Initial commit to github