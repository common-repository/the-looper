<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$options = get_option( 'the_looper_manage_cpt' ) ?: array();
$export_options = 'No Post Types have been registered';
if( !empty($options) && is_array($options) ) :
$export_options = json_encode($options);
endif;
$the_looper_post_types_meta_nonce = wp_create_nonce( 'the_looper_post_types_meta_nonce_update' );
?>
<ul class="nav nav-pills nav-shadow p-3">
	<li class="nav-item">
		<a class="nav-link active" href="#import-export" data-toggle="tab" >Import/Export</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#php-code" data-toggle="tab">PHP Code</a>
	</li>

</ul>


<div class="tab-content">

	<div class="tab-pane active" id="import-export">
		<div class="mt-1 mb-3">
			<div class="bg-light p-3 border-0 rounded-0 shadow-sm" role="alert">
			While using this <a href="#" class="alert-link text-dark">plugin</a>, if you want to migrate Custom Post Types from another website use our import/export utility.<br>
			</div>
		</div>
        <div class="card-body">
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" class="d-block">
				<input type="hidden" name="action" value="import_post_types" />
				<input type="hidden" name="the_looper_post_types_meta_nonce" value="<?= $the_looper_post_types_meta_nonce ?>" />
				<input type="hidden" name="option_name" value="the_looper_manage_cpt" />
				<?php wp_referer_field( true );?>
				<div class="form-row">
				    <div class="col">
				    	<h5>Import Post Types</h5>
				        <textarea name="the_looper_import_post_type" class="form-control" id="import-post-types" rows="7" placeholder="Copy and paste exported content from previous site here."></textarea>
				        <p class="d-inline-block text-danger mt-2">Importing will overwrite all current registered settings.</p>
						<?php submit_button( 'Import Post Types', 'd-block primary bg-primary', '', false ); ?>
				    </div>

				    <div class="col">
				    	<h5>Export Post Types</h5>
				        <textarea class="form-control force-select-all" id="export-post-types" rows="7" onClick="this.select();" readonly><?= $export_options; ?></textarea>
						<p class="text-muted mt-2"><strong>NOTE:</strong> This will not export the associated posts (e.g "Hello World"), just the post type settings.</p>
				    </div>
				</div>

			</form>
        </div>
    </div>

    <div class="tab-pane" id="php-code">
		<div class="mt-1 mb-3">
			<div class="bg-light text-dark p-3 border-0 rounded-0 shadow-sm" role="alert">
			Copy the PHP Code below into your functions.php to register these custom post types.<br>
			</div>
		</div>
        <div class="card-body">

			<div class="accordion" id="post-types-tools">
			<?php if( !empty($options) && is_array($options) ) :?>
				<?php foreach ($options as $key => $option) : ?>
					<div class="card mw-100 p-0 mt-0 rounded-0">
					    <div class="card-header bg-light rounded-0" id="heading-<?= $option['slug']?>">
					        <h5 class="mb-0 p-0">
					        	<button aria-controls="<?= $option['slug']?>" aria-expanded="true" class="btn btn-link text-secondary" data-target="#<?= $option['slug']?>" data-toggle="collapse" type="button"><?= $option['singular_name'];?></button>
								<button aria-controls="<?= $option['slug']?>" aria-expanded="true" class="btn btn-link text-secondary float-right" data-target="#<?= $option['slug']?>" data-toggle="collapse" type="button"><i class="fas fa-angle-down"></i></button>
					        </h5>

					    </div>
					    <div aria-labelledby="heading-<?= $option['slug']?>" class="collapse" data-parent="#post-types-tools" id="<?= $option['slug']?>">
					        <div class="card-body bg-light">
								<?php require plugin_dir_path( __FILE__ ) . 'post-type-code/phpcode.php';?>
					        </div>
					    </div>
					</div>

				<?php endforeach; ?>
			<?php else: ?>
			    <h1 class="text-center alert alert-primary py-3 m-3" role="alert">No Custom Post Types have been added as yet. <a href="<?=admin_url('admin.php?page=the-looper-post-types&action=add')?>" class="alert-link">Click Here.</a></h1>
			<?php endif; ?>	
			</div>

        </div>
    </div>

</div>



