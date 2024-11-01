<?php
/**
* 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class The_Looper_Settings {
	
	public $settings = array();
	public $sections = array();
	public $fields = array();


	public function __construct() {

		add_action( 'admin_init', array( $this, 'registerCustomFields' ) );

	}

	public function setSettings( array $settings ){
		
		$this->settings = $settings;

		return $this;
	}

	public function setSections( array $sections ){
		
		$this->sections = $sections;

		return $this;
	}

	public function setFields( array $fields ){
		
		$this->fields = $fields;

		return $this;
	}

	public function registerCustomFields(){
		
		// Register setting
		foreach ( $this->settings as $key => $setting ) :
			
			register_setting( $setting['option_group'], $setting['option_name'], ( isset( $setting['callback'] ) ? $setting['callback'] : '' ) );

		endforeach;

		// Add settings section
		foreach ( $this->sections as $key => $section ) :

			add_settings_section( $section['id'], $section['title'], ( isset( $section['callback'] ) ? $section['callback'] : '' ), $section['page'] );

		endforeach;

		// Add settings field
		foreach ( $this->fields as $key => $field ) :

			add_settings_field( $field['id'], $field['title'], ( isset( $field['callback'] ) ? $field['callback'] : '' ), $field['page'], $field['section'], ( isset( $field['args'] ) ? $field['args'] : '' ) );
		endforeach;
	}


}