<?php
/*
 * Template Name: Custom Category Page
 */

// Header include etmeyin çünkü WordPress admin panosu içeriklerini kullanmıyoruz
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Categories', 'text-domain'); ?></h1>
    <a href="<?php echo admin_url('edit-tags.php?taxonomy=category'); ?>" class="page-title-action"><?php _e('Add New Category', 'text-domain'); ?></a>

    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column"><?php _e('Name', 'text-domain'); ?></th>
                <th scope="col" class="manage-column"><?php _e('Slug', 'text-domain'); ?></th>
                <th scope="col" class="manage-column"><?php _e('Description', 'text-domain'); ?></th>
                <!-- Diğer sütunlar buraya eklenebilir -->
            </tr>
        </thead>
        <tbody>
            <?php
            $args = array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
            );
            $categories = get_categories($args);

            foreach ($categories as $category) {
                ?>
                <tr>
                    <td class="manage-column">
                        <strong><a href="<?php echo get_edit_term_link($category->term_id, 'category'); ?>"><?php echo $category->name; ?></a></strong>
                    </td>
                    <td class="manage-column"><?php echo $category->slug; ?></td>
                    <td class="manage-column"><?php echo $category->description; ?></td>
                    <!-- Diğer sütunlar buraya eklenebilir -->
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
