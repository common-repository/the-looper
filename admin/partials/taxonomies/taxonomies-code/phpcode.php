<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

?>

<pre class="prettyprint">
// Register Custom Post Type
function custom_taxonomy() {

	$labels = array(
		'name'              => '<?= $option['singular_name'];?>',
		'singular_name'     => '<?= $option['singular_name'];?>',
		'search_items'      => 'Search <?= $option['singular_name'];?>',
		'all_items'         => 'All <?= $option['singular_name'];?>',
		'parent_item'       => 'Parent <?= $option['singular_name'];?>',
		'parent_item_colon' => 'Parent <?= $option['singular_name'];?> :',
		'edit_item'         => 'Edit <?= $option['singular_name'];?>',
		'update_item'       => 'Update <?= $option['singular_name'];?>',
		'add_new_item'      => 'Add New <?= $option['singular_name'];?>',
		'new_item_name'     => 'New <?= $option['singular_name'];?> Name',
		'menu_name'         => '<?= $option['singular_name'];?>',
	);

	$args = array(
		'hierarchical'      => <?= isset($option['hierarchical']) ? 'true': 'false';?>,
		'labels'            => $labels,
		'show_ui'           => <?= isset($option['show_ui']) ? 'true': 'false';?>,
		'show_admin_column' => <?= isset($option['show_admin_column']) ? 'true': 'false';?>,
		'query_var'         => <?= isset($option['query_var']) ? 'true': 'false';?>,
		'rewrite'           => array( 'slug' => '<?= $option['slug'];?>' ),
		'objects'           => array( 'post','page' )
	);

	register_taxonomy( $args['rewrite']['slug'], $objects, $args );
}
add_action( 'init', 'custom_taxonomy', 0 );</pre>