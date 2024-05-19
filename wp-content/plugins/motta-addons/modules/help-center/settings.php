<?php

namespace Motta\Addons\Modules\Help_Center;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class of plugin for admin
 */
class Settings  {

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

	const POST_TYPE     		= 'motta_help_article';
	const OPTION_NAME   		= 'motta_help_article';
	const TAXONOMY_TAB_TYPE     = 'motta_help_cat';


	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		//Handle post columns
		add_filter( sprintf( 'manage_%s_posts_columns', self::POST_TYPE ), array( $this, 'edit_admin_columns' ) );
		add_action( sprintf( 'manage_%s_posts_custom_column', self::POST_TYPE ), array( $this, 'manage_custom_columns' ), 10, 2 );

		// Enqueue style and javascript
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Add form
		add_action( 'motta_help_cat_add_form_fields', array( $this, 'add_category_fields' ), 30 );
		add_action( 'motta_help_cat_edit_form_fields', array( $this, 'edit_category_fields' ), 20 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 20, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 20, 3 );
	}

	/**
	 * Add custom column to popups management screen
	 * Add Thumbnail column
     *
	 * @since 1.0.0
	 *
	 * @param  array $columns Default columns
	 *
	 * @return array
	 */
	public function edit_admin_columns( $columns ) {
		unset( $columns['date']   );

		$columns = array_merge( $columns, array(
			'article_categories' 	=> esc_html__( 'Categories', 'motta-addons' ),
			'date' 				=> esc_html__( 'Date', 'motta-addons' ),
		) );

		return $columns;
	}

	/**
	 * Handle custom column display
     *
	 * @since 1.0.0
	 *
	 * @param  string $column
	 * @param  int    $post_id
	 */
	public function manage_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'article_categories':
				$categories = get_the_terms( $post_id, 'motta_help_cat' );

				foreach ( $categories as $category ) {
					$characters = count( $categories ) > 1 ? '<span class="characters">, </span>' : '';

					echo '<a href="'. esc_url( admin_url( 'edit.php?motta_help_cat=' . $category->slug . '&post_type=motta_help_article' ) ) .'">' . $category->name . '</a>' . $characters;
				}

				break;
		}
	}

	/**
	 * Load scripts and style in admin area
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_scripts( $hook ) {
		$screen = get_current_screen();

		if ( self::POST_TYPE == $screen->post_type ) {
			wp_enqueue_media();
			wp_enqueue_style( 'motta-help-center', MOTTA_ADDONS_URL . 'modules/help-center/assets/css/admin.css' );
			wp_enqueue_script( 'motta-help-center', MOTTA_ADDONS_URL . 'modules/help-center/assets/js/admin.js', array( 'jquery' ),'1.0', true );

		}

	}

	/**
	 * Category thumbnail fields.
     *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_category_fields() {
		 ?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="motta_help_article_icon_type"><?php esc_html_e( 'Icon Type', 'motta-addons' ); ?></label>
				</th>
				<td>
					<select name="motta_help_article_icon_type" id="motta_help_article_icon_type" class="postform">
						<option value="svg"><?php esc_html_e( 'SVG Code', 'motta-addons' ) ?></option>
						<option value="image"><?php esc_html_e( 'Image', 'motta-addons' ) ?></option>
					</select>
				</td>
			</tr>
			<div id="motta_help_article_icon_type_field_image" class="form-field hidden">
				<label><?php esc_html_e( 'Image', 'motta-addons' ); ?></label>

				<div id="motta_help_article_icon_type_image" class="motta-icon-image">
					<ul class="motta-cat-icon-image"></ul>
					<input type="hidden" id="motta_help_article_icon_image_id" class="motta_help_article__icon-image-id" name="motta_help_article_icon_image_id"/>
					<button type="button"
							data-delete="<?php esc_attr_e( 'Delete image', 'motta-addons' ); ?>"
							data-text="<?php esc_attr_e( 'Delete', 'motta-addons' ); ?>"
							class="upload_images_button button"><?php esc_html_e( 'Upload/Add Images', 'motta-addons' ); ?></button>
				</div>
				<div class="clear"></div>
			</div>
			<tr id="motta_help_article_icon_type_field_svg" class="form-field">
				<th scope="row" valign="top">
					<label for="motta_help_article_icon_svg"><?php esc_html_e( 'SVG Code', 'motta-addons' ); ?></label>
				</th>
				<td>
					<textarea id="motta_help_article_icon_svg" name="motta_help_article_icon_svg" class="motta_help_article_icon_svg" rows="5" contenteditable="true"></textarea>
				</td>
			</tr>
		<?php
	}

	/**
	 * Edit category thumbnail field.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $term Term (category) being edited
     *
	 * @return void
	 */
	public function edit_category_fields( $term ) {
		$icon_type = get_term_meta( $term->term_id, 'motta_help_article_icon_type', true );
		$image_id  = get_term_meta( $term->term_id, 'motta_help_article_icon_image_id', true );
		$icon_svg  = get_term_meta( $term->term_id, 'motta_help_article_icon_svg', true );

		?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="motta_help_article_icon_type"><?php esc_html_e( 'Icon Type', 'motta-addons' ); ?></label>
				</th>
				<td>
					<select name="motta_help_article_icon_type" id="motta_help_article_icon_type" class="postform">
						<option value="svg" <?php selected( 'svg', $icon_type ) ?>><?php esc_html_e( 'SVG Code', 'motta-addons' ) ?></option>
						<option value="image" <?php selected( 'image', $icon_type ) ?>><?php esc_html_e( 'Image', 'motta-addons' ) ?></option>
					</select>
				</td>
			</tr>
			<tr id="motta_help_article_icon_type_field_image" class="form-field hidden">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Image', 'motta-addons' ); ?></label></th>
				<td>
					<div id="motta_help_article_icon_type_image" class="motta-icon-image">
						<ul class="motta-cat-icon-image">
							<?php

							if ( $image_id ) {
								$image = wp_get_attachment_image_url( $image_id, 'full' );
								?>
								<li class="image" data-attachment_id="<?php echo esc_attr( $image_id ); ?>">
									<img alt="<?php echo esc_attr__( 'Icon Image', 'motta-addons' ); ?>" src="<?php echo esc_url( $image ); ?>" width="auto" height="100px"/>
									<ul class="actions">
										<li>
											<a href="#" class="delete"
												title="<?php esc_attr_e( 'Delete image', 'motta-addons' ); ?>"><?php esc_html_e( 'Delete', 'motta-addons' ); ?></a>
										</li>
									</ul>
								</li>
								<?php
							}
							?>
						</ul>
						<input type="hidden" id="motta_help_article_icon_image_id" class="motta_help_article_icon_image_id" name="motta_help_article_icon_image_id"
							value="<?php echo esc_attr( $image_id ); ?>"/>
						<button type="button"
								data-delete="<?php esc_attr_e( 'Delete image', 'motta-addons' ); ?>"
								data-text="<?php esc_attr_e( 'Delete', 'motta-addons' ); ?>"
								class="upload_images_button button"><?php esc_html_e( 'Upload/Add Images', 'motta-addons' ); ?></button>
					</div>
					<div class="clear"></div>
				</td>
			</tr>
			<tr id="motta_help_article_icon_type_field_svg" class="form-field">
				<th scope="row" valign="top">
					<label for="motta_help_article_icon_svg"><?php esc_html_e( 'SVG Code', 'motta-addons' ); ?></label>
				</th>
				<td>
					<textarea id="motta_help_article_icon_svg" name="motta_help_article_icon_svg" class="motta_help_article_icon_svg" rows="5" contenteditable="true"><?php echo esc_attr( $icon_svg ); ?></textarea>
				</td>
			</tr>
		<?php
	}

	/**
	 * Save Category fields
	 *
	 * @param mixed $term_id Term ID being saved
	 * @param mixed $tt_id
	 * @param string $taxonomy
     *
	 * @return void
	 */
	public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( 'motta_help_cat' === $taxonomy && function_exists( 'update_term_meta' ) ) {
			if ( isset( $_POST['motta_help_article_icon_type'] ) ) {
				update_term_meta( $term_id, 'motta_help_article_icon_type', $_POST['motta_help_article_icon_type'] );
			}

			if ( isset( $_POST['motta_help_article_icon_image_id'] ) ) {
				update_term_meta( $term_id, 'motta_help_article_icon_image_id', $_POST['motta_help_article_icon_image_id'] );
			}

			if ( isset( $_POST['motta_help_article_icon_svg'] ) ) {
				update_term_meta( $term_id, 'motta_help_article_icon_svg', \Motta\Icon::sanitize_svg( $_POST['motta_help_article_icon_svg'] ) );
			}
		}
	}

}