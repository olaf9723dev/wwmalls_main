<?php
/*
Plugin Name: Custom DokanPro Submenu
Description: Adds a custom submenu item to DokanPro admin menu.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dokan_extend_add_custom_taxonomy() {
    $labels = array(
        'name'              => _x( 'Custom Categories', 'taxonomy general name', 'text-domain' ),
        'singular_name'     => _x( 'Custom Category', 'taxonomy singular name', 'text-domain' ),
        // Diğer etiketler buraya eklenebilir
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false, // true ise kategori gibi çalışır, false ise etiket gibi çalışır
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        // Diğer ayarlar buraya eklenebilir
    );

    register_taxonomy( 'my-custom-category', array( 'dokan' ), $args ); // 'post' yerine başka bir post type kullanabilirsiniz
}
add_action( 'init', 'dokan_extend_add_custom_taxonomy' );

function dokan_extend_add_custom_category_page() {
    add_rewrite_rule('^my-custom-category/?$','index.php?custom_category_page=true','top');
}
add_action('init', 'dokan_extend_add_custom_category_page');

function dokan_extend_flush_rewrite_rules() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'dokan_extend_flush_rewrite_rules');

class Custom_DokanPro_Submenu {
    public function __construct() {
        if(is_admin()){
            
            add_action( 'dokan-vue-admin-scripts', [ $this, 'enqueue_admin_scripts' ],20 );
        }
        add_action('admin_menu', array($this, 'add_custom_submenu'));
    }
    public function enqueue_admin_scripts() {
         
        wp_enqueue_script('your-custom-script', plugin_dir_url( __FILE__ ) . 'js/custom-script.js', array('wp-vue'), null, true);
         

        
    }
    
    public function add_custom_submenu() {
        add_submenu_page(
            'dokan', // Parent slug olarak 'Posts' sayfası belirleniyor
            __('Custom Categories', 'dokan'), // Page title
            __('Custom Categories', 'dokan'), // Menu title
            'manage_options', // Capability
            'edit-tags.php?taxonomy=my-custom-category', // Yeni menü slug'ı, özel kategori sayfasını işaret ediyor
            array($this, 'custom_category_page') // Callback function
        );
    }
    
    public function custom_category_page() {
        include_once(plugin_dir_path(__FILE__) . 'templates/category-custom.php');
    }
}


// Taxonomy için meta alanı oluştur
function dokan_store_category_add_meta_field() {
    ?>
    <div class="form-field">
        <label for="store_category_meta">Meta Field</label>
        <input type="text" name="store_category_meta" id="store_category_meta" value="">
    </div>
    <?php
}
add_action('store_category_add_form_fields', 'dokan_store_category_add_meta_field', 10, 2);

function dokan_store_category_edit_meta_field($term) {
    $meta_value = get_term_meta($term->term_id, 'store_category_meta', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="store_category_meta">Meta Field</label></th>
        <td>
            <input type="text" name="store_category_meta" id="store_category_meta" value="<?php echo esc_attr($meta_value); ?>">
        </td>
    </tr>
    <?php
}
add_action('store_category_edit_form_fields', 'dokan_store_category_edit_meta_field', 10, 2);

function dokan_save_store_category_meta_field($term_id) {
    if (isset($_POST['store_category_meta'])) {
        $meta_value = sanitize_text_field($_POST['store_category_meta']);
        update_term_meta($term_id, 'store_category_meta', $meta_value);
    }
}
add_action('edited_store_category', 'dokan_save_store_category_meta_field', 10, 2);
add_action('create_store_category', 'dokan_save_store_category_meta_field', 10, 2);



function dokan_extend_enqueue_scripts() {
    wp_enqueue_script('your-custom-script', plugin_dir_url( __FILE__ ) . 'js/custom-script.js', array('wp-vue'), null, true);
}
#add_action('admin_enqueue_scripts', 'dokan_extend_enqueue_scripts');



$custom_dokan_submenu = new Custom_DokanPro_Submenu();
