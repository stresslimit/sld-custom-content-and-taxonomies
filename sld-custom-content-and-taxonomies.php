<?php
/*
Plugin Name: Stresslimit Custom Content and Taxonomies
Description: This code-only developer plugin allows you to register new post types & taxonomies easily, with good default values and some extended functionality.
Version: 1.3
Author: Joachim Kudish, Stresslimit, Colin Vernon
Author URI: http://jkudish.com/
Plugin URI: http://stresslimitdesign.com/
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'SLD_PLUGINPATH', dirname(__FILE__) );

if ( ! class_exists('SLD_Register_Post_Type') ) :

	require_once( SLD_PLUGINPATH . '/class.register-post-type.php' );

	if ( ! function_exists( 'sld_register_post_type' ) && class_exists( 'SLD_Register_Post_Type' ) ) :

	/**
	 * A helper function for the SD_Register_Post_Type class. Because typing "new" is hard.
	 * 
	 * @uses SLD_Register_Post_Type class
	 * @param string $post_type The post type to register
	 * @param array $args The arguments to pass into @link register_post_type(). Good defaults provided
	 * @param string $custom_plural The plural name to be used in rewriting (http://yourdomain.com/custom_plural/ ). If left off, an "s" will be appended to your post type, which will break some words. (person, box, ox. Oh, English.)
	 **/
	function sld_register_post_type( $post_type = null, $args=array(), $custom_plural = false ) {
		$custom_post = new SLD_Register_Post_Type( $post_type, $args, $custom_plural );
	}

	endif; // shortcut function exists

endif; // class exists



if ( ! class_exists('SLD_Register_Taxonomy') ) :

	require_once( SLD_PLUGINPATH . '/class.register-taxonomy.php' );

	if ( ! function_exists( 'sld_register_taxonomy' ) && class_exists( 'SLD_Register_Taxonomy' ) ) :

	/**
	 * Helper function for the SLD_Register_Taxonomy class.
	 * 
	 * @uses SLD_Register_Taxonomy class
	 * @param string $taxonomy The taxonomy to register
	 * @param mixed $post_types The posts types to register the taxonomies for
	 * @param string $sing_name The singular name to be used in labels for the taxonomy
	 * @param array $args The arguments to pass into @link register_taxonomy(). Good defaults provided.
	 * @param string $plural_name The plural name to be used in labels for the taxonomy. If left off, an "s" will be appended to your taxonomy, which will break some words. (person, box, ox. Oh, English.)
	**/
	function sld_register_taxonomy( $taxonomy = null,  $post_types = null, $sing_name = null, $args = array(), $plural_name = null ) {
		$custom_taxonomy = new SLD_Register_Taxonomy( $taxonomy, $post_types, $sing_name, $args, $plural_name );
	}

	endif; // shortcut function exists

endif; // class exists



if ( ! class_exists('SLD_Unregister_Taxonomy') ) :

	require_once( SLD_PLUGINPATH . '/class.unregister-taxonomy.php' );
  
	if ( ! function_exists( 'sld_unregister_taxonomy' ) && class_exists( 'SLD_Unregister_Taxonomy' ) ) :

	/**
	 * A helper function for the SLD_Unregister_Taxonomy class.
	 * 
	 * @uses SLD_Unregister_Taxonomy class
	 * @param string $taxonomy The taxonomy to unregister
	 * @param mixed $post_types The posts types to unregister the taxonomies for
	**/
	function sld_unregister_taxonomy($taxonomy, $object_type) { 
		$remove_taxonomy = new SLD_Unregister_Taxonomy( $taxonomy, $object_type );
	}
	endif; // shortcut function exists

endif; // class exists