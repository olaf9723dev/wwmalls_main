<?php
/**
 * Register post types
 *
 * @package Motta
 */

namespace Motta\Addons;

/**
 * Class Product_Brands
 */
class Product_Brands {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;


	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Check if active brand
	 *
	 * @var bool
	 */
	private $active_brand = true;
	const OPTION_NAME   = 'motta_product_brand';

	/**
	 * @var string placeholder image
	 */
	public $placeholder_img_src;

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_sections_products', array( $this, 'product_brand_section' ), 15, 2 );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'product_brand_settings' ), 15, 2 );

		$this->active_brand = apply_filters( 'motta_register_brand', true );
		if ( ! $this->active_brand ) {
			return;
		}

		if ( get_option( 'motta_product_brand', 'yes' ) != 'yes' ) {
			return;
		}

		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_action( 'current_screen', array( $this, 'settings_save' ) );

		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_brand' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		$this->placeholder_img_src = MOTTA_ADDONS_URL . '/img/placeholder.png';
		// Add form
		add_action( 'product_brand_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'product_brand_edit_form_fields', array( $this, 'edit_category_fields' ), 20 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 20, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 20, 3 );

		// Add columns
		add_filter( 'manage_edit-product_brand_columns', array( $this, 'product_brand_columns' ) );
		add_filter( 'manage_product_brand_custom_column', array( $this, 'product_brand_column' ), 10, 3 );

		add_filter( 'woocommerce_sortable_taxonomies', array( $this, 'product_brand_sortable' ) );

	}

	/**
	 * Add Product Tabs settings section to the Products setting tab.
     *
	 * @since 1.0.0
	 *
	 * @param array $sections
	 * @return array
	 */
	public function product_brand_section( $sections ) {
		$sections['motta_addons_product_brand'] = esc_html__( 'Product Brand', 'motta-addons' );

		return $sections;
	}

	/**
	 * Adds a new setting field to products tab.
     *
	 * @since 1.0.0
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function product_brand_settings( $settings, $section ) {
		if ( 'motta_addons_product_brand' != $section ) {
			return $settings;
		}

		$settings_product_tabs = array(
			array(
				'name' => esc_html__( 'Product Brand', 'motta-addons' ),
				'type' => 'title',
				'id'   => self::OPTION_NAME . '_options',
			),
			array(
				'name'    => esc_html__( 'Product Brand', 'motta-addons' ),
				'desc'    => esc_html__( 'Enable product brand manager', 'motta-addons' ),
				'id'      => self::OPTION_NAME,
				'default' => 'yes',
				'type'    => 'checkbox',
				'checkboxgroup' => 'start',
			),
			array(
				'title'    => esc_html__( 'Brand Page', 'motta-addons' ),
				'desc'     => esc_html__( 'Choose page product brands', 'motta-addons' ),
				'id'       => 'motta_product_brand_page_id',
				'type'     => 'single_select_page',
				'class'    => 'wc-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
				'desc_tip' => true,
			),
			array(
				'type' => 'sectionend',
				'id'   => self::OPTION_NAME . '_options',
			),
		);

		return $settings_product_tabs;
	}

	/**
	 * Register custom post type for brand
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_brand() {
		// Return if post type is exists
		if ( ! post_type_exists( 'product' ) ) {
			return;
		}

		$labels = array(
			'name'                       => __( 'Product Brands', 'motta-addons' ),
			'singular_name'              => __( 'Brand', 'motta-addons' ),
			'menu_name'                  => __( 'Brands', 'motta-addons' ),
			'all_items'                  => __( 'All Brands', 'motta-addons' ),
			'edit_item'                  => __( 'Edit Brand', 'motta-addons' ),
			'view_item'                  => __( 'View Brand', 'motta-addons' ),
			'update_item'                => __( 'Update Brand', 'motta-addons' ),
			'add_new_item'               => __( 'Add New Brand', 'motta-addons' ),
			'new_item_name'              => __( 'New Brand Name', 'motta-addons' ),
			'parent_item'                => __( 'Parent Brand', 'motta-addons' ),
			'parent_item_colon'          => __( 'Parent Brand:', 'motta-addons' ),
			'search_items'               => __( 'Search Brands', 'motta-addons' ),
			'popular_items'              => __( 'Popular Brands', 'motta-addons' ),
			'separate_items_with_commas' => __( 'Separate brands with commas', 'motta-addons' ),
			'add_or_remove_items'        => __( 'Add or remove brands', 'motta-addons' ),
			'choose_from_most_used'      => __( 'Choose from the most used brands', 'motta-addons' ),
			'not_found'                  => __( 'No brands found', 'motta-addons' )
		);

		$permalinks         = get_option( 'product_brand_permalinks' );
		$product_brand_base = empty( $permalinks['product_brand_base'] ) ? _x( 'product-brand', 'slug', 'motta-addons' ) : $permalinks['product_brand_base'];

		$args = array(
			'hierarchical'          => true,
			'update_count_callback' => '_wc_term_recount',
			'labels'                => $labels,
			'show_ui'               => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'         => $product_brand_base,
				'hierarchical' => true,
				'ep_mask'      => EP_PERMALINK
			)
		);

		register_taxonomy( 'product_brand', array( 'product' ), $args );
	}


	/**
	 * Register admin scripts
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_admin_scripts( $hook ) {
		$screen = get_current_screen();
		if ( $hook == 'edit-tags.php' && $screen->taxonomy == 'product_brand' || $hook == 'term.php' && $screen->taxonomy == 'product_brand' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'mt_product_brand_js', MOTTA_ADDONS_URL . "/assets/js/admin/brand-taxes.js", array( 'jquery' ), '20211210', true );
		}
	}

	/**
	 * Sortable brand
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function product_brand_sortable( $taxonomy ) {
		$taxonomy[] = 'product_brand';

		return $taxonomy;

	}

	/**
	 * Add  field in 'Settings' > 'Writing'
	 * for enabling CPT functionality.
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function settings_api_init() {
		add_settings_field(
			'product_brand_slug',
			'<label for="product_brand_slug">' . esc_html__( 'Product brand base', 'motta-addons' ) . '</label>',
			array( $this, 'product_brand_slug_input' ),
			'permalink',
			'optional'
		);

		register_setting(
			'permalink',
			'product_brand_slug',
			'sanitize_text_field'
		);
	}

	/**
	 * Show a slug input box.
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function product_brand_slug_input() {
		$permalinks = get_option( 'product_brand_permalinks' );
		$brand_base = isset( $permalinks['product_brand_base'] ) ? $permalinks['product_brand_base'] : '';
		?>
        <input name="product_brand_slug" type="text" class="regular-text code"
               value="<?php echo esc_attr( $brand_base ); ?>"
               placeholder="<?php echo esc_attr_x( 'product-brand', 'slug', 'motta-addons' ) ?>"/>
		<?php
	}

	/**
	 * Save the settings.
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! $screen = get_current_screen() ) {
			return;
		}

		if ( 'options-permalink' != $screen->id ) {
			return;
		}

		$permalinks = get_option( 'product_brand_permalinks' );

		if ( isset( $_POST['product_brand_slug'] ) ) {
			$permalinks['product_brand_base'] = $this->sanitize_permalink( trim( $_POST['product_brand_slug'] ) );
		}

		update_option( 'product_brand_permalinks', $permalinks );
	}

	/**
	 * Sanitize permalink
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private function sanitize_permalink( $value ) {
		global $wpdb;

		$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

		if ( is_wp_error( $value ) ) {
			$value = '';
		}

		$value = esc_url_raw( $value );
		$value = str_replace( 'http://', '', $value );

		return untrailingslashit( $value );
	}

	/**
	 * Category thumbnail fields.
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function add_category_fields() {
		?>
        <div class="form-field" id="product-brand-thumb-box">
            <label><?php esc_html_e( 'Thumbnail', 'motta-addons' ); ?></label>

            <div id="product_brand_thumb" class="product-brand-thumb"
                 data-rel="<?php echo esc_url( $this->placeholder_img_src ); ?>">
                <img src="<?php echo esc_url( $this->placeholder_img_src ); ?>" width="60px" height="60px"/></div>
            <div class="product-brand-thumb-box">
                <input type="hidden" id="product_brand_thumb_id" name="product_brand_thumb_id"/>
                <button type="button"
                        class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'motta-addons' ); ?></button>
                <button type="button"
                        class="remove_image_button button"><?php esc_html_e( 'Remove image', 'motta-addons' ); ?></button>
            </div>
            <div class="clear"></div>
        </div>
		<?php
	}

	/**
	 * Edit category thumbnail field.
	 *
	 * @param mixed $term Term (category) being edited
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function edit_category_fields( $term ) {
		$thumbnail_id = '';
		if ( function_exists( 'get_term_meta' ) ) {
			$thumbnail_id = absint( get_term_meta( $term->term_id, 'brand_thumbnail_id', true ) );
		}

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = $this->placeholder_img_src;
		}
		?>
        <tr class="form-field product-brand-thumb" id="product-brand-thumb-box">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'motta-addons' ); ?></label></th>
            <td>
                <div id="product_brand_thumb" class="product-brand-thumb"
                     data-rel="<?php echo esc_url( $this->placeholder_img_src ); ?>">
                    <img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/>
                </div>
                <div class="product-brand-thumb-box">
                    <input type="hidden" id="product_brand_thumb_id" name="product_brand_thumb_id"
                           value="<?php echo esc_attr( $thumbnail_id ); ?>"/>
                    <button type="button"
                            class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'motta-addons' ); ?></button>
                    <button type="button"
                            class="remove_image_button button"><?php esc_html_e( 'Remove image', 'motta-addons' ); ?></button>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
		<?php
	}

	/**
	 * save_category_fields function.
	 *
	 * @param mixed $term_id Term ID being saved
	 * @param mixed $tt_id
	 * @param string $taxonomy
     *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['product_brand_thumb_id'] ) && 'product_brand' === $taxonomy && function_exists( 'update_term_meta' ) ) {
			update_term_meta( $term_id, 'brand_thumbnail_id', absint( $_POST['product_brand_thumb_id'] ) );
		}
	}

	/**
	 * Thumbnail column added to category admin.
	 *
	 * @param mixed $columns
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function product_brand_columns( $columns ) {
		$new_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
			unset( $columns['cb'] );
		}

		$new_columns['thumb'] = esc_html__( 'Image', 'motta-addons' );

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @param string $columns
	 * @param string $column
	 * @param int $id
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function product_brand_column( $columns, $column, $id ) {
		if ( 'thumb' == $column ) {

			$thumbnail_id = get_term_meta( $id, 'brand_thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = $this->placeholder_img_src;
			}

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: https://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'motta-addons' ) . '" class="wp-post-image" height="80" width="80" />';

		}

		return $columns;
	}
}
