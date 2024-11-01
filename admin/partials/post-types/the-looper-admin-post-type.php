<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://blk-canvas.com
 * @since      1.0.0
 *
 * @package    The_Looper
 * @subpackage The_Looper/admin/partials
 */

require_once plugin_dir_path( dirname( __DIR__ ) ) . 'fonts/icons.php';

$options = get_option( 'the_looper_manage_cpt' ) ?: array();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap ">
	<div class="container-fluid my-1">
		<div class="row">
			<div class="col-12 col-lg-12">
				<h1><?php settings_errors(); ?></h1>
			</div>
		</div>
	</div>
	<div class="container-fluid shadow-sm rounded p-0">
		<?php require_once plugin_dir_path( __FILE__ ) . 'post-type-header.php';?>
		<div class="container-fluid bg-white rounded">
			<div class="row">
				<div class="col-12 col-lg-12 p-0">
					<div class="tab-content">

						<div class="tab-pane p-3 <?= ( !isset($_REQUEST['action']) ) ? 'active': ''; ?>" id="tab-1">
							<?php require_once plugin_dir_path( __FILE__ ) . 'post-type-manage.php';?>
						</div>
						<?php if( !isset($_POST['edit_post']) ) :?>
						<div class="tab-pane p-3 fade <?= ( isset($_REQUEST['action']) && $action == 'add' ) ? 'active show': ''; ?>"  id="tab-2">
							<?php require_once plugin_dir_path( __FILE__ ) . 'post-type-add.php';?>
						</div>
						<?php endif; ?>
						<div class="tab-pane p-3 fade <?= isset($_POST['edit_post']) ? 'active show' : '' ;?>" id="tab-3">
							<?php require_once plugin_dir_path( __FILE__ ) . 'post-type-edit.php';?>
						</div>

						<div class="tab-pane fade <?= ( isset($_REQUEST['action']) && $action == 'tools' ) ? 'active show': ''; ?>" id="tab-4">
							<?php require_once plugin_dir_path( __FILE__ ) . 'post-type-tools.php';?>
						</div>
						
					</div>
				</div>
			</div>
		</div>

		<div aria-hidden="true" aria-labelledby="iconPickerLabel" class="modal fade" id="postTypeIconPicker" role="dialog" tabindex="-1">
		    <div class="modal-dialog modal-dialog-centered" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h5 class="modal-title" id="iconPickerLabel">
		                   	Icon Picker
		                </h5>
		                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
		            </div>

		            <div class="modal-body">
		            	<?php foreach ( $dashicons as $key => $group ) : ?>
		                <div class="container mb-3">
		                	<div class="row bg-secondary mb-3 py-2 shadow-sm">
		                		<div class="col-12">
		                			<h5 class="text-white m-0"><?= $group['group_name'];?></h5>
		                		</div>
		                	</div>
		                	<div class="row text-center">
								
								
		                		<?php foreach ( $group['icons'] as $key => $icon ) : ?>

		                			<div class="col-12 col-md-3 border p-3 rounded dashicons-col" tabindex="1" icon-class="<?= $icon['name']; ?>">
		                				<span class="dashicons <?= $icon['name']; ?>"></span>
		                			</div>
								
		                		<?php endforeach; ?>
		                		<?php if( $key % 4 === 0) echo '</div><div class="row text-center">'; ?>
		                	</div>
		                </div>
		                <?php endforeach; ?>
		            </div>

		            <div class="modal-footer">
		                <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
		            </div>
		        </div>
		    </div>
		</div>	

	</div>
</div>