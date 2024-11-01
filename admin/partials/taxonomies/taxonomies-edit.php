<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$input = get_option( 'the_looper_manage_taxonomies' ) ?: array();
$value = $input[$_POST['edit_post']]['slug'];
?>
<form action="options.php" method="POST" class="add-post-types">
	<?php settings_fields( 'the_looper_taxonomy_edit' ); ?>
	<?php do_settings_sections( 'the-looper-taxonomies' ); ?>
	<input type="hidden" name="edit" value="true">
		<input type="hidden" name="original_slug" value="<?=$value;?>">
	<div class="form-group mt-5 mb-3">
		<?php submit_button( 'Update Taxonomy' , 'primary d-inline-block mx-2', 'submit' , false  ); ?>
		<?php submit_button( 'Delete Taxonomy' , 'secondary d-inline-block mx-2', 'remove', false ); ?>
	</div>
</form>