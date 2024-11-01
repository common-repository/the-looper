<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>
<form action="options.php" method="POST" class="add-post-types">
	<?php settings_fields( 'the_looper_cpt_settings' ); ?>
	<?php do_settings_sections( 'the-looper-post-types' ); ?>
	<input type="hidden" name="add" value="true">
	<?php submit_button('Add Post Type','primary bg-primary btn-sm', 'submit',false); ?>
</form>