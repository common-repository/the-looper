<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>
<pre class="prettyprint">
// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( '<?= $option['singular_name'];?>', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( '<?= $option['singular_name'];?>', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( '<?= $option['plural_name'];?>', 'text_domain' ),
		'name_admin_bar'        => __( '<?= $option['singular_name'];?>', 'text_domain' ),
		'archives'              => __( '<?= $option['singular_name'];?> Archives', 'text_domain' ),
		'attributes'            => __( '<?= $option['singular_name'];?> Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent <?= $option['singular_name'];?>:', 'text_domain' ),
		'all_items'             => __( 'All <?= $option['plural_name'];?>', 'text_domain' ),
		'add_new_item'          => __( 'Add New <?= $option['singular_name'];?>', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New <?= $option['singular_name'];?>', 'text_domain' ),
		'edit_item'             => __( 'Edit <?= $option['singular_name'];?>', 'text_domain' ),
		'update_item'           => __( 'Update <?= $option['singular_name'];?>', 'text_domain' ),
		'view_item'             => __( 'View <?= $option['singular_name'];?>', 'text_domain' ),
		'view_items'            => __( 'View <?= $option['plural_name'];?>', 'text_domain' ),
		'search_items'          => __( 'Search <?= $option['singular_name'];?>', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into <?= strtolower($option['singular_name']);?>', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this <?= strtolower($option['singular_name']);?>', 'text_domain' ),
		'items_list'            => __( '<?= $option['plural_name'];?> list', 'text_domain' ),
		'items_list_navigation' => __( '<?= $option['plural_name'];?> list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter <?= strtolower($option['plural_name']);?> list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( '<?= $option['singular_name'];?>', 'text_domain' ),
		'description'           => __( '<?= $option['singular_name'];?> Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( '<?= implode("','", $option['supports']);?>' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => <?= isset($option['hierarchical']) ? 'true': 'false';?>,
		'public'                => <?= isset($option['public']) ? 'true': 'false';?>,
		'show_ui'               => <?= isset($option['show_ui']) ? 'true': 'false';?>,
		'show_in_menu'          => <?= isset($option['show_in_menu']) ? (!empty($option['show_in_menu_string'])) ? '\''.$option['show_in_menu_string'].'\'': 'true': 'false';?>,
		'menu_position'         => <?= ($option['menu_position']) ?: 6;?>,
		'show_in_admin_bar'     => <?= isset($option['show_in_admin_bar']) ? 'true': 'false';?>,
		'show_in_nav_menus'     => <?= isset($option['show_in_nav_menus']) ? 'true': 'false';?>,
		'can_export'            => <?= isset($option['can_export']) ? 'true': 'false';?>,
		'has_archive'           => <?= isset($option['has_archive']) ? 'true': 'false';?>,
		'exclude_from_search'   => <?= isset($option['exclude_from_search']) ? 'true': 'false';?>,
		'publicly_queryable'    => <?= isset($option['publicly_queryable']) ? 'true': 'false';?>,
		'capability_type'       => '<?= isset($option['capability_type']) ? $option['capability_type']: 'post';?>',
	);
	register_post_type( '<?= $option['slug'];?>', $args );

}
add_action( 'init', 'custom_post_type', 0 );</pre>