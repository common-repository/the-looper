<?php
/**
* 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


class The_Looper_Post_Type_Controller {

	public $callbacks;
	public $plugin_name;
	public $settings_page;
	public $settings;
	public $option_name;
	public $custom_post_types = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $option_name, $settings_page ) {

		$this->plugin_name = $plugin_name;
		$this->option_name = $option_name;
		$this->settings_page = $settings_page;
		$this->section_id = 'the_looper_cpt_index';
		$this->basic_settings = 'the_looper_cpt_core_settings';

		$this->callbacks = new The_Looper_Callbacks;
		$this->settings = new The_Looper_Settings;
		
		$this->registerFields();

		add_action( 'admin_post_import_post_types', array( $this, 'saveImports' ) );

		
	}

	public function saveImports(){

		if ( isset($_POST['the_looper_post_types_meta_nonce']) && isset($_POST['the_looper_import_post_type'])) :
			
			$post_type_imports = sanitize_text_field( $_POST['the_looper_import_post_type'] );
			$import = stripslashes( $post_type_imports );
			$import = json_decode($import, true);

			$result = update_option( 'the_looper_manage_cpt', $import );

			set_transient( 'the-looper-import-post-types-success', true, 10 );
			wp_redirect( esc_url_raw( $_POST['_wp_http_referer'] ) );
			

		endif;
	}

	public function registerFields(){

		$this->setSettings();
		$this->setSections();
		$this->setFields();
	}
	
	/**
	 * Set Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */
	public function setSettings(){

		$args = array(
			array(
				'option_group' => 'the_looper_cpt_settings',
				'option_name' => $this->option_name,
				'callback' => array( $this->callbacks, 'cptSanitize' )
			),
		);

		$this->settings->setSettings( $args );
	}

	/**
	 * Set Section/Group for individual Fields for Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */
	public function setSections(){


		$args = array(
			array(
				'id' => $this->basic_settings,
				'title' => '',
				'callback' => array( $this->callbacks, 'basicSettings' ),
				'page' => $this->settings_page
			),
			array(
				'id' => $this->section_id,
				'title' => '',
				'callback' => array( $this->callbacks, 'advancedSettings' ),
				'page' => $this->settings_page
			),
			
		);

		$this->settings->setSections( $args );
	}

	/**
	 * Set Fields for Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */
	public function setFields(){

		$taxonomies = get_taxonomies( array( 'public' => true ), 'names');
		unset( $taxonomies['nav_menu'] );unset( $taxonomies['post_format'] );
		
		$args = array(

			array(
				'id' => 'option_name',
				'title' => '',
				'callback' => array( $this->callbacks, 'hiddenField'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'option_name',
					'value' => $this->option_name,
					'class' => 'hidden',
					
				),
			),
			array(
				'id' => 'slug',
				'title' => 'Slug Name',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'slug',
					'placeholder' => 'eg. projects',
					'description' => 'The post type name/slug. Used for various queries for post type content.<br><br> Slugs should only contain alphanumeric, latin characters. Underscores should be used in place of spaces. Set "Custom Rewrite Slug" field to make slug use dashes for URLs.<br><br><strong>DO NOT CHANGE</strong> the slug unless you plan to mirgate all post to new post type.',
					'required' => 'required'
					
				),
			),
			array(
				'id' => 'migrate',
				'title' => '',
				'callback' => array( $this->callbacks, 'mirgateSelect'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'migrate',
					'description' => 'Migrate Post to newly named post type',
					'placeholder' => '',
					'value' => '1',
					
				),
			),
			array(
				'id' => 'singular_name',
				'title' => 'Singular Name',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'singular_name',
					'placeholder' => 'eg. Project',
					'description' => 'Used for the post type admin menu item.',
					'required' => 'required'
					
				),
			),
			array(
				'id' => 'plural_name',
				'title' => 'Plural Name',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'plural_name',
					'placeholder' => 'eg. Projects',
					'description' => 'Used when a singular label is needed.',
					'required' => 'required'
					
				),
			),
			array(
				'id' => 'submit_basic_settings',
				'title' => '',
				'callback' => array( $this->callbacks, 'basicSubmitButton'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'submit_basic_settings',
					'value' => $this->option_name,
					'button_text' => array(
						'add' => 'Add Post Type',
						'edit' => 'Update Post Type',
						'delete' => 'Delete Post Type'
					)
					
				),
			),
			array(
				'id' => 'public',
				'title' => 'Public',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'public',
					'description' => 'Select whether post type should be shown in the Admin UI and it\'s publicly queryable.',
					'default' => true,
					
				),
			),
			array(
				'id' => 'publicly_queryable',
				'title' => 'Publicly Queryable',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'publicly_queryable',
					'description' => 'Whether queries can be performed on the front end as part of parse_request.',
					'default' => true,
					
				),
			),
			array(
				'id' => 'has_archive',
				'title' => 'Has Archive',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'has_archive',
					'description' => 'Enables post type archives. Will use $post_type as archive slug by default.',
					'default' => false
				),
			),
			array(
				'id' => 'has_archive_string',
				'title' => 'Archive Base Slug',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'has_archive_string',
					'placeholder' => 'Change slug for Archive Url',

					
				),
			),
			array(
				'id' => 'show_ui',
				'title' => 'Show UI',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_ui',
					'description' => 'Whether to generate a default UI for managing this post type in the admin.',
					'default' => true
				),
			),
			array(
				'id' => 'show_in_nav_menus',
				'title' => 'Show in Nav Menus',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_in_nav_menus',
					'description' => 'Whether post_type is available for selection in navigation menus.',
					'default' => true
				),
			),
			array(
				'id' => 'show_in_menu',
				'title' => 'Show in Menu',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_in_menu',
					'description' => 'Where to show the post type in the admin menu. show_ui must be true.',
					'default' => true
				),
			),
			array(
				'id' => 'show_in_menu_string',
				'title' => 'Show in Menu String',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'description' => 'The top-level admin menu page file name for which the post type should be in the sub menu of.',
					'label_for' => 'show_in_menu_string',
					'placeholder' => '',
					'required' => ''
					
				),
			),
			array(
				'id' => 'show_in_rest',
				'title' => 'Show in REST API',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_in_rest',
					'description' => 'Whether to expose this post type in the REST API.',
					'default' => false
				),
			),
			array(
				'id' => 'capability_type',
				'title' => 'Capability Type',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'description' => 'The string to use to build the read, edit, and delete capabilities',
					'label_for' => 'capability_type',
					'default' => 'post',
					
					
				),
			),
			array(
				'id' => 'rest_base',
				'title' => 'REST API base slug',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'rest_base',
					'description' => 'The base slug that this post type will use when accessed using the REST API.',
					'placeholder' => 'Slug for REST API Url',
					
					
				),
			),
			array(
				'id' => 'exclude_from_search',
				'title' => 'Exclude From Search',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'exclude_from_search',
					'description' => 'Whether to exclude posts with this post type from front end search results.',
					'default' => false
				),
			),
			array(
				'id' => 'hierarchical',
				'title' => 'Hierarchical',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'hierarchical',
					'description' => 'Whether the post type is hierarchical (e.g. page). Allows Parent to be specified.',
					'default' => false
				),
			),
			array(
				'id' => 'rewrite',
				'title' => 'Rewrite',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'rewrite',
					'description' => 'Triggers the handling of rewrites for this post type. To prevent rewrites, set to false.',
					'default' => true
				),
			),
			array(
				'id' => 'rewrite_slug',
				'title' => 'Custom Rewrite Slug',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'rewrite_slug',
					'placeholder' => 'default: post type slug',
					'default' => ''
					
				),
			),
			array(
				'id' => 'rewrite_withfront',
				'title' => 'Rewrite With Front',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'rewrite_withfront',
					'default' => true
				),
			),
			array(
				'id' => 'query_var',
				'title' => 'Query Var',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'query_var',
					'description' => 'If set to true it allows you to request a custom posts type (book) using this: example.com/?book=life-of-pi',
					'default' => true
				),
			),
			array(
				'id' => 'query_var_slug',
				'title' => 'Custom Query Var Slug',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'query_var_slug',
					'description' => 'If set to a string rather than true (for example ‘publication’), you can do: example.com/?publication=life-of-pi',
					'placeholder' => 'default: post type slug. Query Var must be True.',
					'default' => ''
					
				),
			),
			array(
				'id' => 'menu_icon',
				'title' => 'Menu Icon',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'menu_icon',
					'placeholder' => 'Full Url or Dashicon class',
					'description' => 'The url to the icon to be used for this menu or the name of the icon from the iconfont',
					'default' => 'dashicons-admin-post'
					
				),
			),
			array(
				'id' => 'menu_position',
				'title' => 'Menu Position',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'menu_position',
					'description' => 'The position in the menu order the post type should appear. show_in_menu must be true.',
					'placeholder' => '',
					'default' => 6
					
				),
			),
			array(
				'id' => 'supports',
				'title' => 'Supports',
				'callback' => array( $this->callbacks, 'multiSelectField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'supports',
					'description' => 'Add supported by selecting the following post editor features.',
					'placeholder' => '',
					'default' => array('title','editor','thumbnail'),
					'values' => array(
						'title',
						'editor',
						'thumbnail',
						'author',
						'excerpt',
						'trackbacks',
						'custom-fields',
						'comments',
						'revisions',
						'page-attributes',
						'post-formats'
					)
					
				),
			),
			array(
				'id' => 'taxonomies',
				'title' => 'Taxonomies',
				'callback' => array( $this->callbacks, 'multiSelectField'),
				'page' => $this->settings_page,
				'section' => $this->section_id,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'taxonomies',
					'description' => 'Add registerd Taxonomies to post type.',
					'placeholder' => '',
					'default' => array( 'category', 'post_tag' ),
					'values' => $taxonomies
					
				),
			),
		);
		
		$this->settings->setFields( $args );

	}

}