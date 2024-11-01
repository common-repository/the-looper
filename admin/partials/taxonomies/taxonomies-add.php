<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>
<form action="options.php" method="POST" class="add-post-types">
	<?php settings_fields( 'the_looper_taxonomy_settings' ); ?>
	<?php do_settings_sections( 'the-looper-taxonomies' ); ?>
	<input type="hidden" name="add" value="true">
	<?php submit_button( 'Add Taxonomy','primary bg-primary btn-sm', 'submit',false ); ?>
</form>