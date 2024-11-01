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

$post_types = get_option( 'the_looper_manage_cpt' ) ?: array();
$taxonomies = get_option( 'the_looper_manage_taxonomies' ) ?: array();
// print("<pre>".print_r($post_types,true)."</pre>");

$num_of_post_types = count($post_types);
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap the-looper-admin">

	<div class="container-fluid my-1">
		<div class="row">
			<div class="col-12 col-lg-12">
				<h1><?php settings_errors(); ?></h1>
			</div>
		</div>
	</div>

	<div class="container-fluid shadow-sm p-0">

		<div class="container-fluid bg-dark border-bottom border-primary looper-border-sm">
			<div class="row p-3">
				<div class="col-12">
					<h2 class="text-uppercase text-white font-weight-bold mb-0">The Looper</h2>
				</div>
			</div>
		</div>

		<div class="container-fluid bg-light pb-5 pt-3">
			<div class="row mb-3">

				<div class="col-md-6">
					<div class="card p-0 rounded-0 border-0 mw-100 shadow">
						<div class="card-header bg-dark border-bottom border-primary looper-border-sm">
							<h6 class="d-inline-block text-white mb-0 "><span class="dashicons dashicons-admin-post"></span> Post Types</h6>
							
						</div>
						<div class="card-body p-0">
							
							<ul class="list-group list-group-flush">
								<?php if( !empty($post_types) ) : ?>
								<?php foreach ( $post_types as $key => $post_type ) : ?>
									<li class="list-group-item bg-white d-flex justify-content-between">
										<div class="d-inline-block">
											<?= $post_type['plural_name']; ?>
											<span class="d-inline-block badge badge-secondary text-white"><?= wp_count_posts( $post_type['slug'])->publish; ?></span>
										</div>
										<div class="d-inline-block">
								        	<form action="<?php echo esc_url( admin_url( 'admin.php?page=the-looper-post-types' ) ); ?>" method="POST" class="d-inline-block">
								        		<?php settings_fields( 'the_looper_cpt_settings' ); ?>
								        		<input type="hidden" name="edit_post" value="<?= $post_type['slug'];?>">
								                <input type="hidden" name="action" value="edit">
								        		<?php submit_button('Edit', 'primary bg-primary', 'submit', false ); ?>
								        		<!-- <a class="btn btn-danger btn-sm" href="#" role="button">Delete</a> -->
								        	</form>
								        	<form action="options.php" method="POST" class="d-inline-block">
								        		<?php settings_fields( 'the_looper_cpt_settings' ); ?>
								        		<input type="hidden" name="remove" value="<?= $post_type['slug'];?>">
								                <input type="hidden" name="option_name" value="<?= $post_type['option_name'];?>">
								        		<?php submit_button('Delete', '', 'submit',false, array(
													'onClick' => 'return confirm("Are you sure you want to delete this Custom Post Type? Deleting will NOT remove created content.");'
								        		) ); ?>
								        		<!-- <a class="btn btn-danger btn-sm" href="#" role="button">Delete</a> -->
								        	</form>
										</div>
									</li>
								<?php endforeach; ?>
								<?php else: ?>
									<li class="list-group-item bg-light d-flex justify-content-center">
										No Custom Post Types have been registered as yet.
									</li>
								<?php endif; ?>
							</ul>
							
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="card p-0 rounded-0 border-0 mw-100 shadow">
						<div class="card-header bg-dark border-bottom border-primary looper-border-sm">
							<h6 class="d-inline-block text-white mb-0 "><span class="dashicons dashicons-tag"></span> Taxonomies</h6>
							
						</div>
						<div class="card-body p-0">
							<ul class="list-group list-group-flush">
								<?php if( !empty($taxonomies) ) : ?>
								<?php foreach ( $taxonomies as $key => $taxonomy ) : ?>
									<li class="list-group-item bg-white d-flex justify-content-between">
										<div class="d-inline-block">
											<?= $taxonomy['plural_name']; ?>
											<span class="d-inline-block badge badge-secondary text-white"><?= wp_count_terms( $taxonomy['slug']); ?></span>
										</div>
										<div class="d-inline-block">
								        	<form action="<?php echo esc_url( admin_url( 'admin.php?page=the-looper-taxonomies' ) ); ?>" method="POST" class="d-inline-block">
								        		<?php settings_fields( 'the_looper_taxonomy_settings' ); ?>
								        		<input type="hidden" name="edit_post" value="<?= $taxonomy['slug'];?>">
								                <input type="hidden" name="action" value="edit">
								        		<?php submit_button('Edit', 'primary bg-primary', 'submit', false ); ?>
								        		<!-- <a class="btn btn-danger btn-sm" href="#" role="button">Delete</a> -->
								        	</form>
								        	<form action="options.php" method="POST" class="d-inline-block">
								        		<?php settings_fields( 'the_looper_taxonomy_settings' ); ?>
								        		<input type="hidden" name="remove" value="<?= $taxonomy['slug'];?>">
								                <input type="hidden" name="option_name" value="<?= $taxonomy['option_name'];?>">
								        		<?php submit_button('Delete', '', 'submit',false, array(
													'onClick' => 'return confirm("Are you sure you want to delete this Custom Post Type? Deleting will NOT remove created content.");'
								        		) ); ?>
								        		<!-- <a class="btn btn-danger btn-sm" href="#" role="button">Delete</a> -->
								        	</form>
										</div>
									</li>
								<?php endforeach; ?>
								<?php else: ?>
									<li class="list-group-item bg-light d-flex justify-content-center">
										No Custom Taxonomies have been registered as yet.
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>

			</div>

			<div class="row my-3">
				<div class="col-md-12">
					<div class="card p-0 rounded-0 border-0 mw-100 shadow">
						<div class="card-header bg-dark border-bottom border-primary looper-border-sm">
							<h6 class="d-inline-block text-white mb-0 "><span class="dashicons dashicons-admin-tools"></span> Utilities</h6>
							
						</div>
						<div class="card-body p-0 bg-white">
							<ul class="nav nav-tabs bg-light rounded-0" id="looper-home-utilities" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="home-post-type-tab" data-toggle="tab" href="#home-post-types" role="tab" aria-controls="home-post-types" aria-selected="true">Post Types</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="home-taxonomies-tab" data-toggle="tab" href="#home-taxonomies" role="tab" aria-controls="home-taxonomies" aria-selected="true">Taxonomies</a>
								</li>
							</ul>
							<div class="tab-content">
  								<div class="tab-pane active" id="home-post-types" role="tabpanel" aria-labelledby="home-post-type-tab">
  									<?php require_once plugin_dir_path( __FILE__ ) . 'post-types/post-type-tools.php';?>
  								</div>
  								<div class="tab-pane" id="home-taxonomies" role="tabpanel" aria-labelledby="home-taxonomies-tab">
  									<?php require_once plugin_dir_path( __FILE__ ) . 'taxonomies/taxonomies-tools.php';?>
  								</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		
	</div>

</div>