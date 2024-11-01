<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
if( !empty($options) && is_array($options) ) :
?>
<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th scope="col">Slug</th>
            <th scope="col"># of Terms</th>
            <th scope="col">Public</th>
            <th scope="col">Has Archive</th>
            <th scope="col">Attached PT</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
    	<?php foreach ($options as $key => $option ) : ?>
    	<?php 
    		$public = isset($option['public']) ? '<i class="text-success fas fa-check-circle"></i>' : '<i class="text-danger fas fa-times"></i>';
			$hierarchical = isset($option['hierarchical']) ? '<i class="text-success fas fa-check-circle"></i>' : '<i class="text-danger fas fa-times"></i>';
            $objects = is_array($option['objects']) ? $option['objects'] : 'None';
		?>
        <tr>
            <th scope="row"><?= $option['slug'];?></th>
            <td class="text-center"><?= wp_count_terms( $option['slug']); ?></td>
            <td class="text-center"><?= $public;?></td>
            <td class="text-center"><?= $hierarchical;?></td>
            <td class=""><?= implode(',', $objects);?></td>
            <td>
        	<form action="" method="POST" class="d-inline-block">
        		<?php settings_fields( 'the_looper_taxonomy_settings' ); ?>
        		<input type="hidden" name="edit_post" value="<?= $option['slug'];?>">
                <input type="hidden" name="action" value="edit">
        		<?php submit_button('Edit', 'primary bg-primary btn-sm', 'submit', false ); ?>
        		<!-- <a class="btn btn-danger btn-sm" href="#" role="button">Delete</a> -->
        	</form>
        	<form action="options.php" method="POST" class="d-inline-block">
        		<?php settings_fields( 'the_looper_taxonomy_settings' ); ?>
        		<input type="hidden" name="remove" value="<?= $option['slug'];?>">
                <input type="hidden" name="option_name" value="<?= $option['option_name'];?>">
        		<?php submit_button('Delete', 'btn btn-danger btn-sm', 'submit',false, array(
					'onClick' => 'return confirm("Are you sure you want to delete this Custom Taxonomy?");'
        		) ); ?>
        		<!-- <a class="btn btn-danger btn-sm" href="#" role="button">Delete</a> -->
        	</form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <h1 class="text-center alert alert-info py-3" role="alert">No Custom Taxonomies have been registered as yet. <a href="<?=admin_url('admin.php?page=the-looper-taxonomies&action=add')?>" class="alert-link">Click Here.</a></h1>
<?php endif; ?> 