<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$manage_post_type = 'class="nav-link py-3 rounded-0 active text-secondary" href="#tab-1" data-toggle="tab"';
$add_post_type = 'class="nav-link py-3 rounded-0 text-secondary" href="#tab-2" data-toggle="tab"';
$edit_post_type = 'class="nav-link py-3 rounded-0 text-secondary" href="#tab-3" data-toggle="tab"';
$export_post_type = 'class="nav-link py-3 rounded-0 text-secondary" href="#tab-4" data-toggle="tab"';

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
	if ($action == 'edit') {
		$manage_post_type = 'class="nav-link py-3 rounded-0" href="'.admin_url('admin.php?page=the-looper-post-types').'"';
		$add_post_type = 'class="nav-link py-3 rounded-0" href="'.admin_url('admin.php?page=the-looper-post-types&action=add').'"';
		$edit_post_type = 'class="nav-link py-3 rounded-0 active" href="#tab-3"';
		$export_post_type = 'class="nav-link py-3 rounded-0" href="'.admin_url('admin.php?page=the-looper-post-types&action=tools').'"';
	}
	elseif ($action == 'add') {
		$manage_post_type = 'class="nav-link py-3 rounded-0" href="#tab-1" data-toggle="tab"';
		$add_post_type = 'class="nav-link py-3 rounded-0 active" href="#tab-2" data-toggle="tab"';
		$edit_post_type = 'class="nav-link py-3 rounded-0" href="#tab-3" data-toggle="tab"';
		$export_post_type = 'class="nav-link py-3 rounded-0" href="#tab-4" data-toggle="tab"';
	}
	elseif ($action == 'tools') {
		$manage_post_type = 'class="nav-link py-3 rounded-0" href="#tab-1" data-toggle="tab"';
		$add_post_type = 'class="nav-link py-3 rounded-0" href="#tab-2" data-toggle="tab"';
		$edit_post_type = 'class="nav-link py-3 rounded-0" href="#tab-3" data-toggle="tab"';
		$export_post_type = 'class="nav-link py-3 rounded-0 active" href="#tab-4" data-toggle="tab"';
	}
}

?>

<div class="container-fluid rounded">
	<div class="row row bg-dark py-2 border-bottom border-primary looper-border-sm">
		<div class="col-12 col-lg-12">
			<h1 class="text-white"><span class="font-weight-bold">Custom Post Type Settings</span></h1>
		</div>
	</div>
	<div class="row bg-light">
		<div class="col-12 col-lg-12 p-0">
			<ul class="nav nav-tabs ml-0">
				<li class="nav-item">
					<a <?= $manage_post_type;?>>Custom Post Types</a>
				</li>
				<li class="nav-item">
					<a <?= $add_post_type;?>>Add Post Type</a>
				</li>
				<?php if( isset($_REQUEST['action']) && $action == 'edit' ): ?>
				<li class="nav-item">
					<a <?= $edit_post_type;?>>Edit Post Type</a>
				</li>
				<?php endif; ?>
				<li class="nav-item">
					<a <?= $export_post_type;?>>Tools</a>
				</li>
			</ul>
		</div>
	</div>
</div>