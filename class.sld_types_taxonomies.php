<?php

/**
* @author Joachim Kudish | stresslimit 
* @link <http://stresslimitdesign.com> <http://jkudish.com>
* @version 1.1
* 
* Description: this class allows us to register new post types easily 
* Based on the work of Matt Wiebe @link <http://somadesign.ca>
*
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


if ( ! class_exists('SLD_Register_Post_Type') ) {

	class SLD_Register_Post_Type {

		private $post_type;
		private $post_slug;
		private $args;
		private $post_type_object;
		private $defaults;
		
		public function __construct( $post_type = null, $args = array(), $custom_plural = false ) {
			if ( ! $post_type ) {
				return;
			}
			
			// meat n potatoes
			$this->post_type = $post_type;
			$this->post_slug = ( $custom_plural ) ? $custom_plural : $post_type . 's';
			
			// sort out those $args
			$this->set_defaults();
			$this->args = wp_parse_args($args, $this->defaults);
			
			// magic man
			$this->add_actions();
			$this->add_filters();

		}
		
		private function set_defaults() {

			$this->defaults = array(
				'show_ui' => true,
				'public' => true,
				'supports' => array('title', 'editor', 'thumbnail'),
				'publicly_queryable' => true,
				'query_var' => true,
				'exclude_from_search' => false,
				'capability_type' => 'post',
				'has_archive' => true,
				'rewrite' => array('slug' => $this->post_slug, 'with_front' => false),
			);
			
			$plural = ucwords( $this->post_slug );
			$singular = ucwords( $this->post_type );
			
			$this->defaults['labels'] = array(
				'name' => $plural,
				'singular_name' => $singular,
				'add_new_item' => 'Add New ' . $singular,
				'edit_item' => 'Edit ' . $singular,
				'new_item' => 'New ' . $singular,
				'view_item' => 'View ' . $singular,
				'search_items' => 'Search ' . $plural,
				'not_found' => 'No ' . $plural . ' found',
				'not_found_in_trash' => 'No ' . $plural . ' found in Trash'
			);
		}
		
		public function add_actions() {
			add_action( 'init', array($this, 'register_post_type') );
			add_action( 'template_redirect', array($this, 'context_fixer') );
		}

		public function add_filters() {
			add_filter( 'generate_rewrite_rules', array($this, 'add_rewrite_rules') );
			add_filter( 'body_class', array($this, 'body_classes') );
			if (is_admin()) add_filter( 'admin_body_class', array($this, 'admin_body_classes') );
		}
		
		public function context_fixer() {
			if ( get_query_var( 'post_type' ) == $this->post_type ) {
				global $wp_query;
				$wp_query->is_home = false;
			}
		}

		public function add_rewrite_rules( $wp_rewrite ) {
			$new_rules = array();
			$new_rules[$this->post_slug . '/page/?([0-9]{1,})/?$'] = 'index.php?post_type=' . $this->post_type . '&paged=' . $wp_rewrite->preg_index(1);
			$new_rules[$this->post_slug . '/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?post_type=' . $this->post_type . '&feed=' . $wp_rewrite->preg_index(1);
			$new_rules[$this->post_slug . '/?$'] = 'index.php?post_type=' . $this->post_type;
		
			$wp_rewrite->rules = array_merge($new_rules, $wp_rewrite->rules);
			return $wp_rewrite;
		}

		public function register_post_type() {
			register_post_type( $this->post_type, $this->args );		
		}

		public function body_classes( $c ) {
			if ( get_query_var('post_type') === $this->post_type ) {
				$c[] = $this->post_type;
				$c[] = 'type-' . $this->post_type;
			}
			return $c;
		}
		
		public function admin_body_classes( $c ) {
			global $wpdb, $post;
	    $post_type = get_post_type( $post->ID );
    	if ( is_admin() ) {
        $classes .= 'type-' . $post_type;
    	}
    	return $classes;
    }

	} // end SLD_Register_Post_Type class
	
	/**
	 * A helper function for the SD_Register_Post_Type class. Because typing "new" is hard.
	 * 
	 * @uses SLD_Register_Post_Type class
	 * @param string $post_type The post type to register
	 * @param array $args The arguments to pass into @link register_post_type(). Good defaults provided
	 * @param string $custom_plural The plural name to be used in rewriting (http://yourdomain.com/custom_plural/ ). If left off, an "s" will be appended to your post type, which will break some words. (person, box, ox. Oh, English.)
	 **/

	if ( ! function_exists( 'sld_register_post_type' ) && class_exists( 'SLD_Register_Post_Type' ) ) {
		function sld_register_post_type( $post_type = null, $args=array(), $custom_plural = false ) {
			$custom_post = new SLD_Register_Post_Type( $post_type, $args, $custom_plural );
		}
	}
	
} // end if class exists



if ( ! class_exists('SLD_Register_Taxonomy') ) {

	class SLD_Register_Taxonomy {
	  private $taxonomy;
		private $post_types;
		private $args;
		private $sing_name;
		private $plural_name;

		private $defaults = array(
			'hierarchical' => true, 	// behave like a category			
		);
		
		
		public function __construct( $taxonomy = null,  $post_types = null, $sing_name = null, $args = array(), $plural_name = null ) {
		  		  
			if ( ! $taxonomy ) {
				return;
			}
			if ( ! $post_types ) {
			  return;
			}
			
			// meat n potatoes
			$this->taxonomy = $taxonomy;
			$this->sing_name = ( $sing_name ) ? $sing_name : $taxonomy;
			$this->plural_name = ( $plural_name ) ? $plural_name : $this->sing_name . 's';
			$this->post_types = ( is_string($post_types) ) ? array($post_types) : $post_types;
			
			// a few extra defaults. Mostly for labels. Overridden if proper $args present.
			$this->set_defaults();
			// sort out those $args
			$this->args = wp_parse_args($args, $this->defaults);
			
			// magic man
			$this->add_actions();

		}
		
		public function set_defaults() {
		  $singular = ucwords($this->sing_name);
		  $plural = ucwords($this->plural_name);
		  $this->defaults['labels'] = array(
		      'name' => __( $plural ),
    			'singular_name' => __( $singular ),
			    'search_items' => __( $plural ),
			    'popular_items' => __( 'Most used ' . $plural ),
			    'all_items' => __( 'All ' . $plural ),
			    'parent_item' => __( 'Parent' ),
			    'parent_item_colon' => __( 'Parent:' ),
			    'edit_item' => __( 'Edit ' . $singular ),
			    'update_item' => __( 'Update ' . $singular ),
			    'add_new_item' => __( 'Add New ' . $singular  ),
			    'new_item_name' => __( 'New ' . $singular . 'Name' ),
					'separate_items_with_commas' =>  __( 'Separate '.$plural.' with commas' ),
					'choose_from_most_used' => __( 'Choose from the most used '.$plural ),
					'add_or_remove_items' => __( 'Add or remove '.$plural ),
					'menu_name' => __( $plural ),
		  );
		}
		
		public function add_actions() {
			add_action( 'init', array($this, 'register_taxonomies') );
		}
		
		public function	register_taxonomies() {
	  	register_taxonomy( $this->taxonomy, $this->post_types, $this->args); 
		}
			
  
  } // end SLD_Register_Taxonomy class
  
  /**
	 * A helper function for the SLD_Register_Taxonomy class. Because typing "new" is hard.
	 * 
	 * @uses SLD_Register_Taxonomy class
	 * @param string $taxonomy The taxonomy to register
	 * @param mixed $post_types The posts types to register the taxonomies for
	 * @param string $sing_name The singular name to be used in labels for the taxonomy
	 * @param array $args The arguments to pass into @link register_taxonomy(). Good defaults provided.
	 * @param string $plural_name The plural name to be used in labels for the taxonomy. If left off, an "s" will be appended to your taxonomy, which will break some words. (person, box, ox. Oh, English.)
	 **/

	if ( ! function_exists( 'sld_register_taxonomy' ) && class_exists( 'SLD_Register_Taxonomy' ) ) {
		function sld_register_taxonomy( $taxonomy = null,  $post_types = null, $sing_name = null, $args = array(), $plural_name = null ) {
			$custom_taxonomy = new SLD_Register_Taxonomy( $taxonomy, $post_types, $sing_name, $args, $plural_name );
		}
	} // end if function exists
} // end if class exists

if ( ! class_exists('SLD_Unregister_Taxonomy') ) {

	class SLD_Unregister_Taxonomy {
	  private $taxonomy;
		private $post_type;
		
		public function __construct( $taxonomy = null,  $post_type = null) {
		  		  
			if ( ! $taxonomy ) {
				return;
			}
			if ( ! $post_type ) {
			  return;
			}
			
			$this->taxonomy = $taxonomy;
			$this->post_type = $post_type;
			
			// magic man
			$this->add_actions();

		}
		
		public function add_actions() {
			add_action( 'init', array($this, 'unreregister_taxonomies') );
		}
		
		public function	unreregister_taxonomies() {
	  	global $wp_taxonomies;  
			if ( !isset($wp_taxonomies[$this->taxonomy]) || !get_post_type_object($this->post_type) ) return false; 
			foreach (array_keys($wp_taxonomies[$this->taxonomy]->object_type) as $array_key) { 
				if ($wp_taxonomies[$this->taxonomy]->object_type[$array_key] == $array_key) { 
					unset ($wp_taxonomies[$this->taxonomy]->object_type[$array_key]); 
					return true; 
				} 
			} 
			return false; 
		}
			
  
  } // end SLD_Unregister_Register_Taxonomy class
  
  /**
	 * A helper function for the SLD_Register_Taxonomy class. Because typing "new" is hard.
	 * 
	 * @uses SLD_Register_Taxonomy class
	 * @param string $taxonomy The taxonomy to register
	 * @param mixed $post_types The posts types to register the taxonomies for
	 * @param string $sing_name The singular name to be used in labels for the taxonomy
	 * @param array $args The arguments to pass into @link register_taxonomy(). Good defaults provided.
	 * @param string $plural_name The plural name to be used in labels for the taxonomy. If left off, an "s" will be appended to your taxonomy, which will break some words. (person, box, ox. Oh, English.)
	 **/

	if ( ! function_exists( 'sld_unregister_taxonomy' ) && class_exists( 'SLD_Unregister_Taxonomy' ) ) {
		function sld_unregister_taxonomy($taxonomy, $object_type) { 
			$remove_taxonomy = new SLD_Unregister_Taxonomy( $taxonomy, $object_type );
		}
	} // end if function exists
} // end if class exists

