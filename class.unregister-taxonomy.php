<?php

class SLD_Unregister_Taxonomy {

	private $taxonomy;
	private $post_type;
	
	public function __construct( $taxonomy = null,  $post_type = null) {
	  		  
		if ( ! $taxonomy )
			return;

		if ( ! $post_type )
		  return;
		
		$this->taxonomy = $taxonomy;
		$this->post_type = $post_type;
		$this->add_actions();

	}
	
	public function add_actions() {
		add_action( 'init', array($this, 'unreregister_taxonomies') );
	}
	
	public function	unreregister_taxonomies() {
		global $wp_taxonomies;  
		if ( !isset($wp_taxonomies[$this->taxonomy]) || !get_post_type_object($this->post_type) )
			return false;
		foreach (array_keys($wp_taxonomies[$this->taxonomy]->object_type) as $array_key) {
			if ($wp_taxonomies[$this->taxonomy]->object_type[$array_key] == $array_key) {
				unset ($wp_taxonomies[$this->taxonomy]->object_type[$array_key]);
				return true;
			}
		}
		return false;
	}

} // end SLD_Unregister_Register_Taxonomy class
