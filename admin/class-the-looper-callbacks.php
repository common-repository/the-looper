<?php
/**
* 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


class The_Looper_Callbacks {
	
	/**
	 * Set Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */


	public function cptSanitize( $input ){
		// Used to help flush rewrite rules on init.
		set_transient( 'the_looper_flush_rewrite_rules', 'true', 5 * 60 );
		
		if ( isset($_POST['the_looper_post_types_meta_nonce']) && isset($_POST['the_looper_import_post_type'])) :

			$import = stripslashes( sanitize_text_field( $_POST['the_looper_import_post_type'] ) );
			$import = json_decode($import, true);
			
			return $import;

		endif;

		if ( isset($_POST['the_looper_taxonomies_meta_nonce']) && isset($_POST['the_looper_import_taxonomies'])) :

			$import = stripslashes( sanitize_text_field( $_POST['the_looper_import_taxonomies'] ) );
			$import = json_decode($import, true);
			
			return $import;

		endif;
		
		// Retrive option to update from database.
		$output = isset($input) ? get_option($input['option_name']) : '';
		
		// Clean Input Data.

		if (is_array($input)) {
			foreach ( $input as $key => $value ) {
				if ( is_string( $value ) ) {
					$input[ $key ] = sanitize_text_field( $value );
				} else {
					array_map( 'sanitize_text_field', $input[ $key ] );
				}
			}
		}
	
		
		// If action Remove isset the remove Slug.
		if ( isset($_POST['remove']) ) {

			$slug = isset($input['slug']) ? sanitize_text_field( $input['slug'] ): sanitize_text_field( $_POST['remove'] );
			$option_name = isset($input['option_name']) ? sanitize_text_field( $input['option_name'] ): sanitize_text_field( $_POST['option_name'] );

			if ( $option_name === 'the_looper_manage_cpt') {
				
				return $this->delete_post_type( $slug );

			}elseif ( $option_name === 'the_looper_manage_taxonomies') {
				
				return $this->delete_taxonomy( $slug );

			}

		}
		
		// If option is and empty or is not an array create it.
		if ( count($output) == 0 || !is_array($output) ) {

			$output = array();
			$output[$input['slug']] = $input;

			return $output;
		}
		
		if ( isset($_POST['add']) && is_array($output) ) {

			foreach ( $output as $key => $type ) {
				
				if ( $input['slug'] === $key ) {

					$this->inputExists( $input );
					
					return $output;
				}

				$output[$input['slug']] = $input;
				$this->inputSuccess( $input, $output );
				
				return $output;
			}

		}

		if (isset($_POST['edit'])) {

			foreach ( $output as $key => $type ) {
				
				if ( $input['slug'] === $key ) {

					if (isset($_POST['migrate'])) {

						$output = $this->inputMigrate($key, $input);
						$output[$input['slug']] = $input;
						
						return $output;

					}else{

						$output[$input['slug']] = $input;
						
						return $output;
					}

				}elseif ( $input['slug'] != $key ){

					if (isset($_POST['migrate'])) {

						$original_slug = sanitize_text_field( $_POST['original_slug'] );
						$output = $this->inputMigrate( $original_slug , $input);
						$output[$input['slug']] = $input;
						
						return $output;

					}else{

						$output[$input['slug']] = $input;
						
						return $output;
					}
					

				}

			}
		}

	}

	public function inputExists( $input ){

		if ($input['option_name'] == 'the_looper_manage_cpt') {

			add_settings_error( 'the_looper_post_type_exist', esc_attr( 'settings_updated' ), 'Post Type slug "'.$input['slug'].'" already exists', 'error' );

		}elseif ($input['option_name'] == 'the_looper_manage_taxonomies') {

			add_settings_error( 'the_looper_taxonomy_exist', esc_attr( 'settings_updated' ), 'Taxonomy slug "'.$input['slug'].'" already exists', 'error' );

		}

	}

	public function inputSuccess( $input, $output ){

		if ( $input['option_name'] == 'the_looper_manage_cpt' && array_key_exists( $input['slug'], $output ) ) {

			add_settings_error( 'the_looper_post_type_added_success', esc_attr( 'settings_updated' ), 'Post Type "'.$input['slug'].'" successfully added', 'updated' );

		}elseif ( $input['option_name'] == 'the_looper_manage_taxonomies' && array_key_exists( $input['slug'], $output ) ){

			add_settings_error( 'the_looper_taxonomy_added_success', esc_attr( 'settings_updated' ), 'Taxonomy "'.$input['slug'].'" successfully added', 'updated' );

		}

	}

	public function inputMigrate( $original_slug, $input ){

		if ($input['option_name'] == 'the_looper_manage_cpt') {

			$output = $this->convert_post_type( $original_slug, $input['slug'] );

		}elseif ($input['option_name'] == 'the_looper_manage_taxonomies') {

			$output = $this->convert_taxonomy_terms( $original_slug, $input['slug'] );

		}

		return $output;

	}

	public function convert_post_type( $original_slug = '', $new_slug = '' ){
		
		$args = array(
			'posts_per_page' => -1,
			'post_type'      => $original_slug,
		);

		$convert = new WP_Query( $args );
		
		if ( $convert->have_posts() ) : while ( $convert->have_posts() ) : $convert->the_post();

				set_post_type( get_the_ID(), $new_slug );

		endwhile; endif;

		return $this->delete_post_type( $original_slug );
	}

	public function delete_post_type( $slug = '' ){

		$output = get_option('the_looper_manage_cpt');
		unset($output[$slug]);

		if ( !array_key_exists( $slug, $output ) ) {

			if (isset($_POST['migrate'])) {

				add_settings_error( 'the_looper_post_type_deleted_success', esc_attr( 'settings_updated' ), 'All post have been migrated and Post Type "'.$slug.'" successfully deleted', 'updated' );
			}else{

				add_settings_error( 'the_looper_post_type_deleted_success', esc_attr( 'settings_updated' ), 'Post Type "'.$slug.'" successfully deleted', 'updated' );

			}
			
		}
		if ( count($output) == 0 || !is_array($output)  ) {

			$output = array();

		}

		return $output;
	}

	public function convert_taxonomy_terms( $original_slug = '', $new_slug = '' ) {

		global $wpdb;

		$args = array(
			'taxonomy'   => $original_slug,
			'hide_empty' => false,
			'fields'     => 'ids',
		);

		$term_ids = get_terms( $args );

		if ( is_int( $term_ids ) ) {
			$term_ids = (array) $term_ids;
		}

		if ( is_array( $term_ids ) && ! empty( $term_ids ) ) {

			$term_ids = implode( ',', $term_ids );

			$query = "UPDATE `{$wpdb->term_taxonomy}` SET `taxonomy` = %s WHERE `taxonomy` = %s AND `term_id` IN ( {$term_ids} )";

			$wpdb->query(
				$wpdb->prepare( $query, $new_slug, $original_slug )
			);

		}

		return $this->delete_taxonomy( $original_slug );
	}

	public function delete_taxonomy( $slug='' ) {

		$output = get_option('the_looper_manage_taxonomies');
		unset($output[$slug]);
		
		if ( !array_key_exists( $slug, $output ) ) {

			add_settings_error( 'the_looper_taxonomy_deleted_success', esc_attr( 'settings_updated' ), 'Taxonomy "'.$slug.'" successfully deleted', 'updated' );

		}

		if ( count($output) == 0 || !is_array($output)  ) {

			$output = array();

		}

		return $output;
	}
	/**
	 * Set Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */
	public function basicSettings( $args ){
		?>
		<h5 class="my-3 p-3 bg-dark text-white rounded">Basic Settings</h5>
		<?
	}

	/**
	 * Set Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */
	public function advancedSettings( $args ){
		?>
		<h5 class="mt-5 mb-3 p-3 bg-dark text-white rounded">Advanced Settings</h5>
		<?
	}

	public function basicSubmitButton( $args ){
		$name = $args['button_text']['add'];
		$delete = $args['button_text']['delete'];

		if( isset($_POST['edit_post']) ){
			$name = $args['button_text']['edit'];
			
			submit_button( $name, 'primary bg-primary d-inline-block mx-2', 'submit' , false  );
			submit_button( $delete, 'secondary d-inline-block mx-2', 'remove', false );

		}else{

			submit_button( $name, 'primary bg-primary d-inline-block', 'submit' , false  );

		}
		
		
	}
	/**
	 * Set Section/Group for individual Fields for Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */
	public function hiddenField( $args ){

		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$value = $args['option_name'];

	?>
	<input type="text" class="regular-text" id="" name="<?=$option_name .'[' . $name .']';?>" value="<?= esc_attr( $value );?>" >
	<?php

	}

	/**
	 * Set Section/Group for individual Fields for Custom Post Type settings.
	 *
	 * @since    1.0.0
	 */
	public function textField( $args ){

		$name = $args['label_for'];
		$classes = (isset($args['class'])) ? $args['class'] : '';
		$option_name = $args['option_name'];
		$required = (isset($args['required'])) ? $args['required'] : '';
		$placeholder = (isset($args['placeholder'])) ? $args['placeholder'] : '';
		$value = (isset($args['default'])) ? $args['default'] : '';
		$description = (isset($args['description'])) ? $args['description'] : '';

		if( isset($_POST['edit_post']) ){
			
			$slug = sanitize_text_field( $_POST['edit_post'] );
			$input = get_option( $option_name );
			$value = $input[$slug][$name];


		}

	?>

	<input type="text" class="form-control shadow-sm w-75" id="<?=$option_name .'[' . $name .']';?>" name="<?=$option_name .'[' . $name .']';?>" value="<?= esc_attr( $value );?>" placeholder="<?=$args['placeholder'];?>" <?=$required;?>>

	<?php if( $name == 'menu_icon' ) : ?>

		<!-- Button trigger modal -->
		<button type="button" class="btn btn-info btn-sm mt-3" data-toggle="modal" data-target="#postTypeIconPicker">
		  Icon Picker
		</button>
		<input type="button" id="upload_post_type_icon" class="btn btn-primary btn-sm mt-3" value="Choose Image Icon">

	<?php endif; ?>

	<?php if (!empty($description)): ?>

		<p class="d-block d-block mt-3 p-2 bg-light shadow-sm w-75"><?= $description; ?></p>

	<?php endif;?>

	<?php

	}

	public function checkBoxField( $args ){
		
		$name = $args['label_for'];
		$classes = (isset($args['class'])) ? $args['class'] : '';
		$option_name = $args['option_name'];
		$checked = $args['default'];
		$default = ( $args['default'] ? 'True' : 'False' );
		$description = (isset($args['description'])) ? $args['description'] : '';

		if( isset($_POST['edit_post']) ){
			
			$slug = sanitize_text_field( $_POST['edit_post'] );
			$checkbox = get_option( $option_name );
			$checked =  isset($checkbox[$slug][$name]) ?: false ;

		}

		?>
		<div class="form-group w-75">
			<span class="switch">
				<input type="checkbox" class="switch" id="<?=$option_name .'[' . $name .']';?>" name="<?=$option_name .'[' . $name .']';?>" value="<?= esc_attr('1');?>" <?=( $checked ? esc_attr('checked') : esc_attr('') );?>>
				<label for="<?=$option_name .'[' . $name .']';?>"></label>
			</span>
			<p class="text-secondary"><?= esc_html('Default:')?><span class="font-weight-bold"><?= esc_html($default); ?></span></p>
		</div>

		<?php if (!empty($description)): ?>
		<p class="d-block mt-3 p-2 bg-light shadow-sm w-75"> <?= esc_html($description); ?></p>

		<?php endif;?>

		<?php
		
	}

	public function multiSelectField( $args ){
		
		$name = $args['label_for'];
		$classes = (isset($args['class'])) ? $args['class'] : '';
		$option_name = $args['option_name'];
		$checked = false;
		$values = $args['values'];
		$description = (isset($args['description'])) ? $args['description'] : '';


		foreach ($values as $key => $value) {
			if (!empty( $args['default'])) {
				$checked =  in_array($value, $args['default']) ?: false ;
			}
			

			if( isset($_POST['edit_post']) ){
				
				$slug = sanitize_text_field( $_POST['edit_post'] );
				$checkbox = get_option( $option_name );
				$checked =  isset($checkbox[$slug][$name][$value]) ?: false ;

			}
			?>
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="<?= $option_name .'['.$name.']['.$value.']';?>" name="<?= $option_name .'['.$name.']['.$value.']';?>"  value="<?= $value;?>" <?= ( $checked ? 'checked' : '' ); ?>>
				<label class="custom-control-label" for="<?= $option_name .'['.$name.']['.$value.']';?>"><?= $value;?></label>
			</div>
			<?php

		}
		
		if (!empty($description)): ?>

		<p class="mt-3 p-3 bg-light shadow-sm w-75"> <?= $description; ?></p>

		<?php endif;

	}

	public function mirgateSelect( $args ){

		$name = $args['label_for'];
		$classes = (isset($args['class'])) ? $args['class'] : '';
		$option_name = $args['option_name'];
		$checked = false;
		$value = $args['value'];
		$description = (isset($args['description'])) ? $args['description'] : '';

		if ( $name == 'migrate' && isset($_POST['edit_post']) ) {
			?>
			<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" id="<?= $name;?>" name="<?= $name;?>"  value="<?= $value;?>" >
				<label class="custom-control-label" for="<?= $name;?>"><?= $description; ?></label>
			</div>
			<?php
		}
	}

	public function selectDropdownField( $args ){
		
		$name = $args['label_for'];
		$classes = (isset($args['class'])) ? $args['class'] : '';
		$option_name = $args['option_name'];
		$checked = false;
		$values = $args['values'];
		$description = (isset($args['description'])) ? $args['description'] : '';
		?>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label class="input-group-text" for="inputGroupSelect01">Options</label>
			</div>
			<select class="custom-select" id="inputGroupSelect01">
		<?php
		foreach ($values as $key => $value) {
			if (!empty( $args['default'])) {
				$checked =  in_array($value, $args['default']) ?: false ;
			}
			

			if( isset($_POST['edit_post']) ){

				$checkbox = get_option( $option_name );
				$checked =  isset($checkbox[$_POST['edit_post']][$name][$value]) ?: false ;

			}
			?>
			<option><?= $value; ?></option>
			<?php
		}
		?>
			</select>
		</div>
		<?php if (!empty($description)): ?>

		<p class="mt-3 p-3 bg-light shadow-sm w-75"> <?= $description; ?></p>

		<?php endif;

	}

}