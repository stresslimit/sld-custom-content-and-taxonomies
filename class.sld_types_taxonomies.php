<?php

/**
* @author stresslimit
* @link <http://stresslimitdesign.com>
* @version 0.9
* 
* Description: this class allows us to register new post types easily 
* Heavily based on the work of Matt Wiebe @link <http://somadesign.ca>
*
*/

if ( ! class_exists('SLD_Register_Post_Type') ) {

	class SLD_Register_Post_Type {

		private $post_type;
		private $post_slug;
		private $args;
		private $post_type_object;

		private $defaults = array(
			'show_ui' => true,
			'public' => true,
			'supports' => array('title', 'editor', 'thumbnail'),
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'capability_type' => 'post',
			
		);
		
		public function __construct( $post_type = null, $args = array(), $custom_plural = false ) {
			if ( ! $post_type ) {
				return;
			}
			
			// meat n potatoes
			$this->post_type = $post_type;
			$this->post_slug = ( $custom_plural ) ? $custom_plural : $post_type . 's';
			
			// a few extra defaults. Mostly for labels. Overridden if proper $args present.
			$this->set_defaults();
			// sort out those $args
			$this->args = wp_parse_args($args, $this->defaults);
			
			// magic man
			$this->add_actions();
			$this->add_filters();

		}
		
		private function set_defaults() {
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
			add_filter( 'template_include', array($this, 'template_include') );
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

		public function template_include( $template ) {
			if ( get_query_var('post_type') == $this->post_type ) {
				
				if ( is_single() ) {
					if ( $single = locate_template( array( $this->post_type.'/single.php') ) )
						return $single;
				}
				else { // loop
					return locate_template( array(
						$this->post_type . '/index.php',
						$this->post_type . '.php', 
						'index.php' 
					));
				}

			}
			return $template;
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
			
			if ( is_string($post_types)) $post_types = array($post_types);
			
			// meat n potatoes
			$this->taxonomy = $taxonomy;
			$this->sing_name = ( $sing_name ) ? $sing_name : $taxonomy
			$this->plural_name = ( $plural_name ) ? $plural_name : $sing_name . 's';
			
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
		      'name' => __( $singular ),
    			'singular_name' => __( $singular ),
			    'search_items' => __( $plural ),
			    'popular_items' => __( 'Most used ' . $plural ),
			    'all_items' => __( 'All ' . $plural ),
			    'parent_item' => __( 'Parent' ),
			    'parent_item_colon' => __( 'Parent:' ),
			    'edit_item' => __( 'Edit ' . $singular ),
			    'update_item' => __( 'Update ' . $singular ),
			    'add_new_item' => __( 'Add New ' . $singular  ),
			    'new_item_name' => __( 'New ' . $singular . 'Name' )
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
	}

} // end if class exists	  