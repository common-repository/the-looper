<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$input = get_option( 'the_looper_manage_cpt' ) ?: array();
$value = $input[$_POST['edit_post']]['slug'];
?>

<form action="options.php" method="POST" class="add-post-types">
	<?php settings_fields( 'the_looper_cpt_settings' ); ?>
	<?php do_settings_sections( 'the-looper-post-types' ); ?>
	<input type="hidden" name="edit" value="true">
	<input type="hidden" name="original_slug" value="<?=$value;?>">
	<div class="form-group mt-5 mb-3">
		<?php submit_button( 'Update Post Type' , 'primary bg-primary d-inline-block mx-2', 'submit' , false  ); ?>
		<?php submit_button( 'Delete Post Type' , 'secondary d-inline-block mx-2', 'remove', false ); ?>
	</div>

</form>
