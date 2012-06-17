<?php

class SLD_Register_Post_Type {

	private $post_type;
	private $post_slug;
	private $args;
	private $post_type_object;
	private $defaults;
	private $custom_icon;
	private $custom_icons;
	
	public function __construct( $post_type = null, $args = array(), $custom_plural = false ) {
		if ( ! $post_type )
			return;
		
		// meat n potatoes
		$this->post_type = $post_type;
		$this->post_slug = ( $custom_plural ) ? $custom_plural : $post_type . 's';
		
		// sort out those $args
		$this->set_defaults();
		if ( !empty($args['custom_icon']) ) {
			$this->custom_icon = $args['custom_icon'];
			unset($args['custom_icon']);
		}
		$this->args = wp_parse_args($args, $this->defaults);
		
		// here's the magic
		$this->add_actions();
		$this->add_filters();

	}
	
	private function unslug( $s ) {
		return ucwords( str_replace( array('-','_'), ' ', $s ) );
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
			'menu_position' => 5,
		);
		
		$plural = $this->unslug( $this->post_slug );
		$singular = $this->unslug( $this->post_type );
		
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

		$this->custom_icons = array(
			'audio',
			'pound',
			'clipboard',
			'bookmark',
			'jamjar',
			'locationpin',
			'calendar',
			'mobile',
			'mortarboard',
			'star',
			'box',
			'users',
			'pencil',
			'book',
			'folder',
			'rectangles',
			// 'bookmarkbook', // need 32px image from Laura
			);
	}
	
	public function add_actions() {
		add_action( 'init', array($this, 'register_post_type') );
		add_action( 'template_redirect', array($this, 'context_fixer') );
		if ( !empty($this->custom_icon) && in_array( $this->custom_icon, $this->custom_icons ) )
			add_action( 'admin_head', array($this, 'admin_head') );
	}

	public function add_filters() {
		add_filter( 'generate_rewrite_rules', array($this, 'add_rewrite_rules') );
		add_filter( 'body_class', array($this, 'body_classes') );
		if (is_admin()) add_filter( 'admin_body_class', array($this, 'admin_body_class') );
	}
	
	public function context_fixer() {
		if ( get_query_var( 'post_type' ) == $this->post_type ) {
			global $wp_query;
			$wp_query->is_home = false;
		}
	}

	public function add_rewrite_rules( $wp_rewrite ) {
		$new_rules = array();
		$new_rules[$this->post_slug . '/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?post_type=' . $this->post_type . '&feed=' . $wp_rewrite->preg_index(1);

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
	
	public function admin_body_class( $c ) {
		$screen = get_current_screen(); 
		$post_type = (isset($screen->post_type)) ? $screen->post_type : null;
		if (isset($post_type) && $post_type == $this->post_type) {
			$c = 'type-' . $post_type;
			echo $c;
		}
	}

	public function admin_head() {
		$icon = $this->custom_icon;
		$admin_icons = $this->custom_icons;
		$type = $this->post_type;
		$url = plugins_url( 'images', __FILE__ );
		$index = array_search( $icon, $admin_icons );
		?>
		<style type="text/css" media="screen">
		#adminmenu #menu-posts-<?php echo $type ?> div.wp-menu-image { background:transparent url('<?php echo $url ?>/icons16.png') no-repeat <?php echo ( $index * -32 ); ?>px -32px !important; }
		#adminmenu #menu-posts-<?php echo $type ?>:hover div.wp-menu-image, #adminmenu #menu-posts-<?php echo $type ?>.wp-has-current-submenu div.wp-menu-image { background-position:<?php echo ( $index * -32 ); ?>px 0px!important; }
		.type-<?php echo $type ?> #icon-edit { background:transparent url('<?php echo $url ?>/icons32.png') <?php echo ( $index * -60 ); ?>px -5px no-repeat; }
		</style>
		<?php
	}

} // end SLD_Register_Post_Type class