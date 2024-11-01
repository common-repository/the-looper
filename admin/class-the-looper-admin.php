<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://blk-canvas.com
 * @since      1.0.0
 *
 * @package    The_Looper
 * @subpackage The_Looper/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    The_Looper
 * @subpackage The_Looper/admin
 * @author     Henzly Meghie <henzlym@blk-canvas.com>
 */
class The_Looper_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	public 	$post_types;
	public  $option_name;
	public  $custom_templates;
	public 	$shortcodes;
	public 	$custom_post_types = array();
	public 	$taxonomies = array();
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'init', array( $this, 'storeCustomPostTypes' ), 10 );
		add_action( 'init', array( $this, 'storeCustomTaxonomies' ), 9 );
		add_action( 'admin_init', array( $this, 'the_looper_flush_rewrite_rules' ) );
		
	}
	
	/**
	 * Conditionally flushes rewrite rules if we have reason to.
	 *
	 * @since 1.3.0
	 */
	function the_looper_flush_rewrite_rules() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		/*
		 * Wise men say that you should not do flush_rewrite_rules on init or admin_init. Due to the nature of our plugin
		 * and how new post types or taxonomies can suddenly be introduced, we need to...potentially. For this,
		 * we rely on a short lived transient. Only 5 minutes life span. If it exists, we do a soft flush before
		 * deleting the transient to prevent subsequent flushes. The only times the transient gets created, is if
		 * post types or taxonomies are created, updated, deleted, or imported. Any other time and this condition
		 * should not be met.
		 */
		if ( true === get_transient( 'the_looper_flush_rewrite_rules' )  ) {
			flush_rewrite_rules( false );
			// So we only run this once.
			delete_transient( 'the_looper_flush_rewrite_rules' );
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in The_Looper_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The The_Looper_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		// Add the color picker css file
		$admin_pages = array('the-looper','the-looper-post-types','the-looper-taxonomies');

		if (!isset($_GET['page'])) return;

		if( in_array($_GET['page'], $admin_pages) ) {  

	        wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_style( 'code-prettify-style', plugin_dir_url( __FILE__ ) . 'css/code-prettify-desert.css'  );
			wp_enqueue_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.2.0/css/all.css' );
			wp_enqueue_style( 'admin-bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/the-looper-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-base', plugin_dir_url( __FILE__ ) . 'css/variables.css', array(), $this->version, 'all' );

		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in The_Looper_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The The_Looper_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		wp_enqueue_script( 'code-prettify-script', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js' );
		wp_enqueue_script('boostrap-popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
		wp_enqueue_script( 'boostrap-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/the-looper-admin.js', array( 'jquery','wp-color-picker' ), $this->version, true );

	}
	/**
	 * Add Admin Homepage.
	 *
	 * @since    1.0.0
	 */
	public function setPages(){

		add_menu_page( 'The Looper', 'The Looper' , 'manage_options', $this->plugin_name, array( $this, 'adminIndex' ), 'dashicons-layout', 80 );
		add_submenu_page( $this->plugin_name, 'Dashboard', 'Dashboard' , 'manage_options', $this->plugin_name, array( $this, 'adminIndex' ), 'dashicons-layout');
		add_submenu_page( $this->plugin_name, 'Post Types', 'Post Types' , 'manage_options', 'the-looper-post-types', array( $this, 'adminCustomPostType' ), 'dashicons-layout');
		add_submenu_page( $this->plugin_name, 'Taxonomies', 'Taxonomies' , 'manage_options', 'the-looper-taxonomies', array( $this, 'adminTaxonomies' ), 'dashicons-layout');

		
	}


	/**
	 * Load Admin Homepage.
	 *
	 * @since    1.0.0
	 */
	public function adminIndex(){

		require plugin_dir_path( __FILE__ ) . 'partials/the-looper-admin-display.php';

	}

	/**
	 * Load Admin Custom Post Types Page.
	 *
	 * @since    1.0.0
	 */
	public function adminCustomPostType(){
		require plugin_dir_path( __FILE__ ) . 'partials/post-types/the-looper-admin-post-type.php';
	}

	/**
	 * Load Admin Custom Taxonomies Page.
	 *
	 * @since    1.0.0
	 */
	public function adminTaxonomies(){
		require plugin_dir_path( __FILE__ ) . 'partials/taxonomies/the-looper-admin-taxonomies.php';
	}

	/**
	 * Load Admin Custom Shortcodes Page.
	 *
	 * @since    1.0.0
	 */
	public function adminLoopShortcodes(){
		require plugin_dir_path( __FILE__ ) . 'partials/shortcodes/the-looper-admin-shortcodes.php';
	}

	/**
	 * Register Admin Service Controllers.
	 *
	 * @since    1.0.0
	 */
	public function registerContollers(){

		$post_types = new The_Looper_Post_Type_Controller( $this->plugin_name, 'the_looper_manage_cpt', 'the-looper-post-types' );
		$taxonomies = new The_Looper_Taxonomy_Controller( $this->plugin_name, 'the_looper_manage_taxonomies', 'the-looper-taxonomies' );
		
	}
	
	public function storeCustomPostTypes(){


		if ( ! get_option( 'the_looper_manage_cpt' ) ) :

			$default = array();

			update_option( 'the_looper_manage_cpt', $default );

			$this->registerContollers();

			return;
			
		endif;

		$options = get_option( 'the_looper_manage_cpt'  );
		if (empty($options) && !is_array($options)) {
			
			return;
		}

		foreach ($options as $key => $option) {
			
			$add_taxonomies = array();
			$taxonomies = isset($option['taxonomies']) ? $option['taxonomies'] : array();
			if (! empty($taxonomies) && is_array($taxonomies) ) {
				foreach ( $taxonomies as $key => $taxonomy ) {
					$add_taxonomies[] = $taxonomy;
				}
			}

			$has_archive = isset($option['has_archive']) ?: false;
			$has_archive = !empty($option['has_archive_string']) ? $option['has_archive_string']: $has_archive;
			
			$public = isset($option['public']) ?: false;
			if ( ! empty( $option['exclude_from_search'] ) ) {
				$exclude_from_search = isset($option['exclude_from_search']) ?: false;
			} else {
				$exclude_from_search = ( false === $public ) ? true : false;
			}

			$show_in_menu = isset($option['show_in_menu']) ?: false;
			$show_in_menu = !empty( $option['show_in_menu_string'] ) ? $option['show_in_menu_string'] : $show_in_menu;

			$query_var = isset($option['query_var']) ?: false;
			$query_var = !empty($option['query_var_slug']) ? $option['query_var_slug']: $query_var;

			$rewrite = isset($option['rewrite']) ?: false;
			if ( $rewrite !== false ) {
				
				$rewrite = array();
				$rewrite['slug'] = ( ! empty( $option['rewrite_slug'] ) ) ? $option['rewrite_slug'] : $option['slug'];

				$rewrite['with_front'] = true; // Default value.
				$rewrite['with_front'] = isset($option['rewrite_withfront']) ?: false;

			}

			$rest_base = null;
			if ( ! empty( $option['rest_base'] ) ) {
				$rest_base = $option['rest_base'];
			}
			

			$custom_post_types[] = array(
					'post_type'             => $option['slug'],
					'name'                  => $option['plural_name'],
					'singular_name'         => $option['singular_name'],
					'menu_name'             => $option['plural_name'],
					'name_admin_bar'        => $option['singular_name'],
					'archives'              => $option['singular_name'] . ' Archives',
					'attributes'            => $option['singular_name'] . ' Attributes',
					'parent_item_colon'     => 'Parent ' . $option['singular_name'],
					'all_items'             => 'All ' . $option['plural_name'],
					'add_new_item'          => 'Add New ' . $option['singular_name'],
					'add_new'               => 'Add New',
					'new_item'              => 'New ' . $option['singular_name'],
					'edit_item'             => 'Edit ' . $option['singular_name'],
					'update_item'           => 'Update ' . $option['singular_name'],
					'view_item'             => 'View ' . $option['singular_name'],
					'view_items'            => 'View ' . $option['plural_name'],
					'search_items'          => 'Search ' . $option['plural_name'],
					'not_found'             => 'No ' . $option['singular_name'] . ' Found',
					'not_found_in_trash'    => 'No ' . $option['singular_name'] . ' Found in Trash',
					'featured_image'        => 'Featured Image',
					'set_featured_image'    => 'Set Featured Image',
					'remove_featured_image' => 'Remove Featured Image',
					'use_featured_image'    => 'Use Featured Image',
					'insert_into_item'      => 'Insert into ' . $option['singular_name'],
					'uploaded_to_this_item' => 'Upload to this ' . $option['singular_name'],
					'items_list'            => $option['plural_name'] . ' List',
					'items_list_navigation' => $option['plural_name'] . ' List Navigation',
					'filter_items_list'     => 'Filter' . $option['plural_name'] . ' List',
					'label'                 => $option['singular_name'],
					'description'           => $option['plural_name'] . 'Custom Post Type',
					'supports'              => isset($option['supports']) ? $option['supports'] : null,
					'taxonomies'            => $add_taxonomies,
					'hierarchical'          => isset($option['hierarchical']) ?: false,
					'public'                => isset($option['public']) ?: false,
					'show_ui'               => isset($option['show_ui']) ?: false,
					'show_in_menu'          => $show_in_menu,
					'menu_position'         => (int) isset($option['menu_position']) ? $option['menu_position'] : 6,
					'menu_icon'         	=> isset($option['menu_icon']) ? $option['menu_icon'] : null,
					'show_in_admin_bar'     => isset($option['show_in_admin_bar']) ?: false,
					'show_in_nav_menus'     => isset($option['show_in_nav_menus']) ?: false,
					'rest_base'           	=> $rest_base,
					'can_export'            => isset($option['can_export']) ?: false,
					'has_archive'           => $has_archive,
					'rewrite'				=> $rewrite,
					'exclude_from_search'   => $exclude_from_search,
					'publicly_queryable'    => isset($option['publicly_queryable']) ?: false,
					'capability_type'       => isset($option['capability_type']) ? $option['capability_type'] : 'post'
			);

		}
		
		$this->registerCustomPostType( $custom_post_types );
		

	}
	
	public function registerCustomPostType( $custom_post_types ){
		
		foreach ( $custom_post_types as $key => $post_type ) :

			register_post_type( $post_type['post_type'], 
				array(
					'labels' => array(
						'name'                  => $post_type['name'],
						'singular_name'         => $post_type['singular_name'],
						'menu_name'             => $post_type['menu_name'],
						'name_admin_bar'        => $post_type['name_admin_bar'],
						'archives'              => $post_type['archives'],
						'attributes'            => $post_type['attributes'],
						'parent_item_colon'     => $post_type['parent_item_colon'],
						'all_items'             => $post_type['all_items'],
						'add_new_item'          => $post_type['add_new_item'],
						'add_new'               => $post_type['add_new'],
						'new_item'              => $post_type['new_item'],
						'edit_item'             => $post_type['edit_item'],
						'update_item'           => $post_type['update_item'],
						'view_item'             => $post_type['view_item'],
						'view_items'            => $post_type['view_items'],
						'search_items'          => $post_type['search_items'],
						'not_found'             => $post_type['not_found'],
						'not_found_in_trash'    => $post_type['not_found_in_trash'],
						'featured_image'        => $post_type['featured_image'],
						'set_featured_image'    => $post_type['set_featured_image'],
						'remove_featured_image' => $post_type['remove_featured_image'],
						'use_featured_image'    => $post_type['use_featured_image'],
						'insert_into_item'      => $post_type['insert_into_item'],
						'uploaded_to_this_item' => $post_type['uploaded_to_this_item'],
						'items_list'            => $post_type['items_list'],
						'items_list_navigation' => $post_type['items_list_navigation'],
						'filter_items_list'     => $post_type['filter_items_list']
					),
					'label'                     => $post_type['label'],
					'description'               => $post_type['description'],
					'supports'                  => $post_type['supports'],
					'taxonomies'                => $post_type['taxonomies'],
					'hierarchical'              => $post_type['hierarchical'],
					'public'                    => $post_type['public'],
					'show_ui'                   => $post_type['show_ui'],
					'show_in_menu'              => $post_type['show_in_menu'],
					'menu_position'             => (int) $post_type['menu_position'],
					'menu_icon'             	=> $post_type['menu_icon'],
					'show_in_admin_bar'         => $post_type['show_in_admin_bar'],
					'show_in_nav_menus'         => $post_type['show_in_nav_menus'],
					'can_export'                => $post_type['can_export'],
					'has_archive'               => $post_type['has_archive'],
					'exclude_from_search'       => $post_type['exclude_from_search'],
					'publicly_queryable'        => $post_type['publicly_queryable'],
					'capability_type'           => $post_type['capability_type']
				) 
			);

		endforeach;
		$this->registerContollers();
		
	}

	public function storeCustomTaxonomies(){

		if ( ! get_option( 'the_looper_manage_taxonomies' ) ) :
			
			$default = array();

			update_option( 'the_looper_manage_taxonomies', $default );

			return;
			
		endif;

		$options = get_option( 'the_looper_manage_taxonomies' ) ?: array();

		foreach ($options as $option) {
			$labels = array(
				'name'              => $option['singular_name'],
				'singular_name'     => $option['singular_name'],
				'search_items'      => 'Search ' . $option['singular_name'],
				'all_items'         => 'All ' . $option['singular_name'],
				'parent_item'       => 'Parent ' . $option['singular_name'],
				'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
				'edit_item'         => 'Edit ' . $option['singular_name'],
				'update_item'       => 'Update ' . $option['singular_name'],
				'add_new_item'      => 'Add New ' . $option['singular_name'],
				'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
				'menu_name'         => $option['singular_name'],
			);

			$taxonomies[] = array(
				'hierarchical'      => isset($option['hierarchical']) ? true : false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $option['slug'] ),
				'objects'           => isset($option['objects']) ? $option['objects'] : null
			);

		}
		$this->registerCustomTaxonomy( $taxonomies );

	}

	public function registerCustomTaxonomy( $taxonomies ){
		foreach ($taxonomies as $taxonomy) {
			$objects = isset($taxonomy['objects']) ? array_keys($taxonomy['objects']) : null;
			register_taxonomy( $taxonomy['rewrite']['slug'], $objects, $taxonomy );
		}
	}

}
