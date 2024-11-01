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

$options = get_option( 'the_looper_manage_taxonomies' ) ?: array();
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
		<?php require_once plugin_dir_path( __FILE__ ) . 'taxonomies-header.php';?>
		<div class="container-fluid bg-white rounded">
			<div class="row">
				<div class="col-12 col-lg-12 p-0">
					<div class="tab-content p-0">

						<div class="tab-pane p-3 <?= ( !isset($_REQUEST['action']) ) ? 'active': ''; ?>" id="tab-1">
							<?php require_once plugin_dir_path( __FILE__ ) . 'taxonomies-manage.php';?>
						</div>
						<?php if( !isset($_POST['edit_post']) ) :?>
						<div class="tab-pane fade p-3 <?= ( isset($_REQUEST['action']) && $action == 'add' ) ? 'active show': ''; ?>"  id="tab-2">
							<?php require_once plugin_dir_path( __FILE__ ) . 'taxonomies-add.php';?>
						</div>
						<?php endif; ?>
						<div class="tab-pane fade p-3 <?= isset($_POST['edit_post']) ? 'active show' : '' ;?>" id="tab-3">
							<?php require_once plugin_dir_path( __FILE__ ) . 'taxonomies-edit.php';?>
						</div>

						<div class="tab-pane fade <?= ( isset($_REQUEST['action']) && $action == 'tools' ) ? 'active show': ''; ?>" id="tab-4">
							<?php require_once plugin_dir_path( __FILE__ ) . 'taxonomies-tools.php';?>
						</div>
						
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>