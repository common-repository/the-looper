<?php
/**
* 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class The_Looper_Taxonomy_Controller {

	

	public $callbacks;
	public $plugin_name;
	public $settings_page;
	public $settings;
	public $option_name;
	public $basic_settings;
	public $advanced_settings;
	public $taxonomies = array();

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
		$this->basic_settings = 'the_looper_taxonomy_basic_settings';
		$this->advanced_settings = 'the_looper_taxonomy_advanced_settings';

		$this->callbacks = new The_Looper_Callbacks;
		$this->settings = new The_Looper_Settings;
		$this->registerFields();

		add_action( 'admin_post_import_taxonomies', array( $this, 'saveImports' ) );

		
	}

	public function saveImports(){

		if ( isset($_POST['the_looper_taxonomies_meta_nonce']) && isset($_POST['the_looper_import_taxonomies'])) :
			
			$taxonomies_imports = sanitize_text_field( $_POST['the_looper_import_taxonomies'] );
			$import = stripslashes( $taxonomies_imports );
			$import = json_decode($import, true);

			$result = update_option( 'the_looper_manage_taxonomies', $import );

			set_transient( 'the-looper-import-taxonomies-success', true, 10 );
			wp_redirect( esc_url_raw( $_POST['_wp_http_referer'] ) );
			

		endif;
	}

	public function register(){

		$this->storeCustomTaxonomies();

		if ( ! empty($this->taxonomies ) ):
			add_action( 'init', array( $this, 'registerCustomTaxonomy' ), 10 );
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
				'option_group' => 'the_looper_taxonomy_settings',
				'option_name' => $this->option_name,
				'callback' => array( $this->callbacks, 'cptSanitize' )
			),
			array(
				'option_group' => 'the_looper_taxonomy_edit',
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
				'id' => 'the_looper_taxonomy_basic_settings',
				'title' => '',
				'callback' => array( $this->callbacks, 'basicSettings' ),
				'page' => $this->settings_page
			),
			array(
				'id' => 'the_looper_taxonomy_advanced_settings',
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
				'title' => 'Taxonomy Slug',
				'callback' => array( $this->callbacks, 'textField'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'slug',
					'description' => 'The name of the taxonomy. Name should only contain lowercase letters and the underscore character, and not be more than 32 characters long (database structure restriction).<br><br><strong>DO NOT CHANGE</strong> the slug unless you plan to mirgate all terms to new taxonomy.',
					'placeholder' => 'eg. genre',
					
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
					'description' => 'Migrate Taxonomy to newly named taxonomy',
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
					'placeholder' => 'eg. Genre',
					'required' => 'required',
					'array' => 'taxonomy',
					
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
					'placeholder' => 'eg. Categories',
					'required' => 'required',
					'array' => 'plural_name',
					
				),
			),
			array(
				'id' => 'objects',
				'title' => 'Post Types',
				'callback' => array( $this->callbacks, 'multiSelectField'),
				'page' => $this->settings_page,
				'section' => $this->basic_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'objects',
					'description' => 'Name of the object type for the taxonomy object. Object-types can be built-in Post Type or any Custom Post Type that may be registered.',
					'defaults' => array( '' ),
					'values' => get_post_types(array('public' => true)),
					
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
						'add' => 'Add Taxonomy',
						'edit' => 'Update Taxonomy',
						'delete' => 'Delete Taxonomy'
					),
					
				),
			),
			array(
				'id' => 'public',
				'title' => 'Public',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'public',
					'description' => ' Whether a taxonomy is intended for use publicly either via the admin interface or by front-end users. The default settings of `$publicly_queryable`, `$show_ui`, and `$show_in_nav_menus` are inherited from `$public`.',
					'default' => true
				),
			),
			array(
				'id' => 'hierarchical',
				'title' => 'Hierarchical',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'hierarchical',
					'description' => 'Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.',
					'array' => 'taxonomy',
					'default' => false
				),
			),
			array(
				'id' => 'show_ui',
				'title' => 'Show UI',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_ui',
					'description' => 'Whether to generate a default UI for managing this taxonomy.',
					'default' => true
				),
			),
			array(
				'id' => 'show_in_nav_menus',
				'title' => 'Show in Nav Menus',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_in_nav_menus',
					'description' => 'true makes this taxonomy available for selection in navigation menus.',
					'default' => true
				),
			),
			array(
				'id' => 'show_in_menu',
				'title' => 'Show in Menu',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_in_menu',
					'description' => 'Where to show the taxonomy in the admin menu. show_ui must be true.',
					'default' => true
				),
			),
			array(
				'id' => 'rewrite',
				'title' => 'Rewrite',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'rewrite',
					'description' => 'Set to false to prevent automatic URL rewriting a.k.a. "pretty permalinks".',
					'default' => true
				),
			),
			array(
				'id' => 'rewrite_with_front',
				'title' => 'Rewrite With Front',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'description' => 'Allowing permalinks to be prepended with front base',
					'label_for' => 'rewrite_with_front',
					'default' => true
				),
			),
			array(
				'id' => 'show_in_rest',
				'title' => 'Show in REST API',
				'callback' => array( $this->callbacks, 'checkBoxField'),
				'page' => $this->settings_page,
				'section' => $this->advanced_settings,
				'args' => array(
					
					'option_name' => $this->option_name,
					'label_for' => 'show_in_rest',
					'description' => 'Whether to include the taxonomy in the REST API.',
					'default' => false
				),
			),

		);
		$this->settings->setFields( $args );

	}

}