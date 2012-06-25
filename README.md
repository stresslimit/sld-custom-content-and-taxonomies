# SLD Register Post Types & Taxonomies for WordPress

This code-only developer WordPress plugin allows you to register new post types easily, with good default values and some extended functionality.

NOTE: The 3 classes are now bundled as a [WordPress Plugin](http://wordpress.org/extend/plugins/sld-custom-content-and-taxonomies/). We will sync changes between github and the WordPress.org plugin repository

# Installation

1. Upload the `sld-custom-content-and-taxonomies` folder to the `/wp-content/plugins/` directory, use SVN to manage WordPress and plugins installation or just search for the plugin in the Plugins tab and install it!
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the useful functions included here in your functions.php files to create custom post types, and to register and unregister custom taxonomies

If you prefer to use this as part of your plugin or theme instead of as a stand-alone plugin, simply remove the plugin header from the sld-custom-content-and-taxonomies.php file and include the plugin as just a simple include.

# Usage

It gives 3 functions, each that wraps or extends a core WordPress function with a few extras:

1. `sld_register_post_type( 'type', $optional_args, $optional_custom_plural )` which extends `register_post_type()`. [Here's how to use it](https://gist.github.com/1338686).


2. `sld_register_taxonomy( 'taxonomy', $post_types, $optional_singular_name, $optional_args, $optional_plural_name )` which extends `register_taxonomy()`. [Here's how to use it](https://gist.github.com/1338692).

3. `sld_unregister_taxonomy( 'taxonomy', $object_type )` which unregisters taxonomies (core taxonomies or those registered by other plugins)

We use it in many of our sites [here](http://stresslimitdesign.com) and [here](http://jkudish.com)

# Credits

This plugin is built and maintained by [Stresslimit Design](http://stresslimitdesign.com/about-our-wordpress-expertise "Stresslimit Design") & [Joachim Kudish](http://jkudish.com "Joachim Kudish")

Awesome custom admin icons designed by [Laura Kalbag](http://laurakalbag.com/wordpress-admin-icons/)

The plugin is based on the original work of Matt Wiebe and his [Smarter Custom Post Types class](http://somadesign.ca/projects/smarter-custom-post-types/ "Smarter Custom Post Types class")

# Change log

= 1.6.2 =

Add support for taxonomy names with underscores or dashes, like we did for type in v.1.6

= 1.6.1 =

Somehow our nice default for menu_poition got lost, so we added it back

= 1.6 =

Add better support for post types with more than one word (e.g. 'Research Projects'), can now be declared with - or _ between words and the label will be adjusted accordingly. So we can do:

`sld_register_post_type( 'research_project' );` and the admin menu will show a nice 'Research Projects' automatically.

= 1.5 =

Add the ability to choose an icon for your custom content type in the WP admin section. sld_register_post_type() now accepts a new parameter 'custom_icon' to the optional $args array, which will provide an awesome custom icon to the admin section

= 1.4 =

Removed old rewrite rules that aren't necessary since WP 3.1

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

# License

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to:

Free Software Foundation, Inc.
51 Franklin Street, Fifth Floor,
Boston, MA
02110-1301, USA.