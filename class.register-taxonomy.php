<?php

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
	  		  
		if ( ! $taxonomy )
			return;

		if ( ! $post_types )
		  return;
		
		$this->taxonomy = $taxonomy;
		$this->sing_name = ( $sing_name ) ? $sing_name : $taxonomy;
		$this->plural_name = ( $plural_name ) ? $plural_name : $this->sing_name . 's';
		$this->post_types = ( is_string($post_types) ) ? array($post_types) : $post_types;

		$this->set_defaults();
		$this->args = wp_parse_args($args, $this->defaults);

		$this->add_actions();

	}

	private function unslug( $s ) {
		return ucwords( str_replace( array('-','_'), ' ', $s ) );
	}

	public function set_defaults() {
	  $singular = $this->unslug( $this->sing_name );
	  $plural = $this->unslug( $this->plural_name );
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
