<?php
/**
 * Customize and add more data into menu items.
 */

namespace Motta\Addons\Modules\Mega_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main module class for mega menu.
 */
class Module {
	/**
	 * Modal screen of mega menu settings
	 *
	 * @var array
	 */
	public $modals = array();

	/**
	 * The single instance of the class
	 */
	protected static $instance = null;

	/**
	 * Initialize
	 */
	static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->init();
	}

	/**
	 * Load core files
	 */
	public function includes() {
		require_once 'widgets/widget-base.php';
		require_once 'widgets/image.php';
		require_once 'widgets/banner.php';
		require_once 'widgets/html.php';
		require_once 'widgets/heading.php';
		require_once 'widgets/taxonomy-list.php';
		require_once 'widgets/taxonomy-grid.php';

		require_once 'walker-edit.php';
		require_once 'walker.php';
	}

	/**
	 * Initialize hooks
	 */
	protected function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'add_settings_link' ), 10, 2 );
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_nav_menu_item' ), 20 );
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_walker' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'admin_head-nav-menus.php', array( $this, 'meta_boxes' ) );
		add_action( 'admin_footer-nav-menus.php', array( $this, 'modal' ) );
		add_action( 'admin_footer-nav-menus.php', array( $this, 'templates' ) );
		add_action( 'wp_ajax_motta_addons_megamenu_save', array( $this, 'save_menu_item_data' ) );
		add_action( 'wp_ajax_motta_addons_megamenu_get_grid_items', array( $this, 'get_menu_items' ) );
		add_action( 'wp_ajax_motta_addons_megamenu_update_grid', array( $this, 'save_menu_grid_data' ) );
		add_action( 'wp_ajax_motta_addons_megamenu_update_tab', array( $this, 'save_menu_tab_data' ) );

		add_filter( 'nav_menu_css_class', array( $this, 'add_menu_icon_css_class' ), 10, 2 );
		add_filter( 'nav_menu_item_title', array( $this, 'add_menu_icon' ), 10, 2 );
	}

	/**
	 * Add the mega menu settings link to the menu item.
	 *
	 * @param int $item_id
	 */
	public function add_settings_link( $item_id ) {
		$mega_data = get_post_meta( $item_id, '_menu_item_mega', true );
		$mega_data = array_replace_recursive( self::default_settings(), (array) $mega_data );
		$grid_data = get_post_meta( $item_id, '_menu_item_mega_grid', true );
		$grid_data = $grid_data ? $grid_data : array();
		$tab_data  = get_post_meta( $item_id, '_menu_item_mega_tab', true );
		$tab_data  = $tab_data ? $tab_data : array();

		$mega_content = $mega_data['content'];
		unset( $mega_data['content'] );
		?>
		<fieldset class="field-mega-options hide-if-no-js description-wide">
			<span class="field-move-visual-label" aria-hidden="true"><?php echo esc_html( _x( 'Options', 'Mega menu options', 'motta-addons' ) ) ?></span>
			<span class="hidden mega-data" aria-hidden="true" data-mega="<?php echo esc_attr( json_encode( $mega_data ) ) ?>"><?php echo trim( $mega_content ); ?></span>
			<span class="hidden mega-data-gridbuilder" aria-hidden="true" data-griddata="<?php echo esc_attr( json_encode( $grid_data ) ); ?>"></span>
			<span class="hidden tab-data-gridbuilder" aria-hidden="true" data-tabdata="<?php echo esc_attr( json_encode( $tab_data ) ); ?>"></span>
			<button type="button" class="item-config-mega button-link"><?php esc_html_e( 'Mega Menu', 'motta-addons' ) ?></button>
			<button type="button" class="item-config-icon button-link"><?php esc_html_e( 'Icon', 'motta-addons' ) ?></button>
		</fieldset>
		<?php
	}

	/**
	 * Setup data for custom menu items.
	 * Add the [M] to mega menu items.
	 *
	 * @param object $menu_item
	 *
	 * @return object
	 */
	public function setup_nav_menu_item( $menu_item ) {
		// Setup data for custom menu items.
		if ( 0 === strpos( $menu_item->type, 'megamenu_item__' ) ) {
			$menu_item->megamenu_type = str_replace( 'megamenu_item__', '', $menu_item->type );
			$menu_item->object = 'megamenu';
			$menu_item->type = 'custom';
			$menu_item->type_label = __( 'Mega Item', 'motta-addons' );
		}

		// Add the [M] to mega menu items.
		if ( is_admin() ) {
			$mega_data = get_post_meta( $menu_item->ID, '_menu_item_mega', true );

			if ( ! empty( $mega_data['mega'] ) ) {
				$menu_item->type_label .= ' [M]';
			}
		}

		return $menu_item;
	}

	/**
	 * Change the Walker class used when adding nav menu items.
	 *
	 * @param string $walker
	 * @return string
	 */
	public function edit_nav_walker( $walker ) {
		$walker = __NAMESPACE__ . '\Walker_Edit';

		return $walker;
	}

	/**
	 * Load scripts on Menus page only
	 *
	 * @param string $hook
	 */
	public function scripts( $hook ) {
		if ( 'nav-menus.php' !== $hook ) {
			return;
		}

		// Get assets URL.
		$assets_url = plugin_dir_url( dirname( __FILE__ ) ) . basename( dirname( __FILE__ ) );

		wp_register_style( 'motta-addons-mega-menu-admin', $assets_url . '/css/mega-menu.css', array(
			'media-views',
			'wp-color-picker',
		), '1.0' );
		wp_enqueue_style( 'motta-addons-mega-menu-admin' );

		wp_register_script( 'motta-addons-mega-menu-admin', $assets_url . '/js/mega-menu.js', array(
			'jquery',
			'jquery-ui-resizable',
			'wp-util',
			'wp-color-picker',
		), '1.0', true );
		wp_enqueue_media();
		wp_enqueue_script( 'motta-addons-mega-menu-admin' );

		wp_enqueue_script( 'motta-addons-menu-item', $assets_url . '/js/menu-item.js', array( 'jquery' ),'1.0', true );

		wp_localize_script( 'motta-addons-mega-menu-admin', 'mottaAddonsMegaMenuConfig', array(
			'templates' => $this->get_modal_templates(),
			'l10n' => array(
				'close_confirm'       => esc_html__( 'Your changes are not saved. Do you want to leave?', 'motta-addons' ),
				'enable_mega_message' => esc_html__( 'You need to enable the mega menu first', 'motta-addons' ),
				'width_auto'          => esc_html__( 'Auto', 'motta-addons' ),
			),
		) );
	}

	/**
	 * Add custom nav meta box.
	 *
	 * Adapted from http://www.johnmorrisonline.com/how-to-add-a-fully-functional-custom-meta-box-to-wordpress-navigation-menus/.
	 */
	public function meta_boxes() {
		add_meta_box( 'motta_addons_mega_menu_items', __( 'Mega Menu Items', 'motta-addons' ), array( $this, 'nav_menu_items' ), 'nav-menus', 'side', 'low' );
	}

	/**
	 * Output mega menu item list.
	 */
	public function nav_menu_items() {
		$items = self::get_item_widgets();
		?>
		<div id="posttype-megamenu_item" class="posttypediv">
			<div id="tabs-panel-posttype-megamenu_item" class="tabs-panel tabs-panel-active" role="region" aria-label="<?php esc_attr_e( 'Mega Menu Item List', 'motta-addons' ) ?>" tabindex="0">
				<ul id="megamenu_item-checklist" class="categorychecklist form-no-clear">
					<?php
					$i = -1;
					foreach ( $items as $key => $classname ) :
						$object = new $classname;
						?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $object->get_label() ); ?>
							</label>
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="megamenu_item__<?php echo esc_attr( $key ) ?>" />
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_attr( $object->get_label() ); ?>" />
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="#" />
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" value="" />
						</li>
						<?php
						$i--;
					endforeach;
					?>
				</ul>
			</div>
			<p class="button-controls wp-clearfix">
				<span class="add-to-menu">
					<button type="submit" class="button submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'motta-addons' ); ?>" name="add-post-type-menu-item" id="submit-posttype-megamenu_item" disabled><?php esc_html_e( 'Add to Menu', 'motta-addons' ); ?></button>
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}

	/**
	 * Get the list of mega menu items.
	 *
	 * @return array
	 */
	public static function get_item_widgets() {
		return apply_filters( 'motta_addons_mega_menu_items', array(
			'image' 			=> '\Motta\Addons\Modules\Mega_Menu\Widgets\Image',
			'banner' 			=> '\Motta\Addons\Modules\Mega_Menu\Widgets\Banner',
			'html'  			=> '\Motta\Addons\Modules\Mega_Menu\Widgets\HTML',
			'heading'  			=> '\Motta\Addons\Modules\Mega_Menu\Widgets\Heading',
			'taxonomy-list'  	=> '\Motta\Addons\Modules\Mega_Menu\Widgets\Taxonomy_List',
			'taxonomy-grid'  	=> '\Motta\Addons\Modules\Mega_Menu\Widgets\Taxonomy_Grid',
		) );
	}

	/**
	 * Prints HTML of modal on footer
	 * @todo Remove this method after testing
	 */
	public function modal_odl() {
		?>
		<div id="megamenu-modal" class="megamenu-modal">
			<div class="megamenu-modal__modal media-modal wp-core-ui" tabindex="0">
				<button type="button" class="media-modal-close megamenu-modal__close">
					<span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'motta-addons' ) ?></span></span>
				</button>
				<div class="media-modal-content">
					<div class="media-frame-menu megamenu-modal__frame-menu"></div>
					<div class="media-frame-title megamenu-modal__title"></div>
					<div class="media-frame-content">
						<div class="megamenu-modal__content"></div>
					</div>
					<div class="media-frame-toolbar">
						<div class="megamenu-modal__toolbar media-toolbar">
							<button type="button" class="megamenu-modal__save button media-button button-primary button-large" disabled><?php esc_html_e( 'Save Changes', 'motta-addons' ) ?></button>
							<button type="button" class="megamenu-modal__cancel button media-button button-secondary button-large"><?php esc_html_e( 'Cancel', 'motta-addons' ) ?></button>
							<span class="spinner"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="media-modal-backdrop megamenu-modal__backdrop"></div>
		</div>
		<?php
	}

	/**
	 * Prints HTML of modal on footer
	 */
	public function modal() {
		?>
		<div id="megamenu-modal" class="megamenu-modal">
			<div class="megamenu-modal__modal wp-core-ui" tabindex="0">
				<button type="button" class="media-modal-close megamenu-modal__close">
					<span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'motta-addons' ) ?></span></span>
				</button>
				<div class="megamenu-modal__frame">
					<div class="megamenu-modal__frame-menu"></div>
					<div class="megamenu-modal__title"></div>
					<div class="megamenu-modal__frame-content">
						<div class="megamenu-modal__content"></div>
					</div>
					<div class="megamenu-modal__toolbar">
						<button type="button" class="megamenu-modal__save button button-primary button-large" disabled><?php esc_html_e( 'Save Changes', 'motta-addons' ) ?></button>
						<button type="button" class="megamenu-modal__cancel button button-secondary button-large"><?php esc_html_e( 'Cancel', 'motta-addons' ) ?></button>
						<span class="spinner"></span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Prints underscore template on footer
	 */
	public function templates() {
		$path = plugin_dir_path( __FILE__ );

		foreach ( $this->get_modal_templates() as $template ) {
			$file = $path . 'views/' . $template . '.php';

			if ( ! file_exists( $file ) ) {
				continue;
			}
			?>
			<script type="text/html" id="tmpl-megamenu__<?php echo esc_attr( $template ) ?>">
				<?php include( $file ); ?>
			</script>
			<?php
		}
	}

	/**
	 * Ajax function to save menu item data
	 */
	public function save_menu_item_data() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
			return;
		}

		$_POST['data'] = stripslashes_deep( $_POST['data'] );
		parse_str( $_POST['data'], $data );
		$updated = $data;

		// Sanitize svg.
		if ( ! empty( $data['icon_svg'] ) && function_exists( '\Motta\Icon::sanitize_svg' ) ) {
			$data['icon_svg'] = \Motta\Icon::sanitize_svg( $data['icon_svg'] );
		}

		// Save menu item data
		foreach ( $data['menu-item-mega'] as $id => $meta ) {
			$old_meta = get_post_meta( $id, '_menu_item_mega', true );
			$old_meta = array_replace_recursive( self::default_settings(), (array) $old_meta );
			$meta     = array_replace_recursive( $old_meta, $meta );

			$updated['menu-item-mega'][ $id ] = $meta;

			update_post_meta( $id, '_menu_item_mega', $meta );
		}

		wp_send_json_success( $updated );
	}

	/**
	 * Ajax function to save grid builder data.
	 */
	public function save_menu_grid_data() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
			return;
		}

		$item_id = intval( $_POST['id'] );
		$data    = maybe_unserialize( $_POST['data'] );

		update_post_meta( $item_id, '_menu_item_mega_grid', $data );

		wp_send_json_success();
	}

	/**
	 * Ajax function to save grid builder data.
	 */
	public function save_menu_tab_data() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
			return;
		}

		$item_id = intval( $_POST['id'] );
		$data    = maybe_unserialize( $_POST['data'] );

		update_post_meta( $item_id, '_menu_item_mega_tab', $data );

		wp_send_json_success();
	}

	/**
	 * Ajax function to get HTML of menu items.
	 */
	public function get_menu_items() {
		$items = $_POST['items'];
		$i     = 0;

		$menu_items = array();

		while ( $i < count( $items ) ) {
			$menu_item = $items[ $i ];
			$menu_item['is_widget'] = ( 'true' === $menu_item['is_widget'] || '1' === $menu_item['is_widget'] || true === $menu_item['is_widget'] );

			$menu_obj     = (object) $menu_item;
			$menu_obj->ID = $menu_obj->id;

			if ( ! $menu_obj->is_widget ) {
				$menu_obj->post_type = 'nav_menu_item';
				$menu_obj            = wp_setup_nav_menu_item( $menu_obj );
			} else {
				$menu_obj->type          = 'custom';
				$menu_obj->type_label    = __( 'Mega Item', 'motta-addons' );
				$menu_obj->megamenu_type = $menu_obj->object;
				$menu_obj->object        = 'megamenu';
				$menu_obj->_options      = $menu_item;
			}

			$menu_obj->title = empty( $menu_obj->title ) ? $menu_item['title'] : $menu_obj->title;
			$menu_obj->label = $menu_obj->title;

			$menu_items[]    = $menu_obj;
			$i++;
		}

		/** This filter is documented in wp-admin/includes/nav-menu.php */
		$walker_class_name = apply_filters( 'wp_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', 0 );

		if ( ! class_exists( $walker_class_name ) ) {
			wp_die( 0 );
		}

		if ( ! empty( $menu_items ) ) {
			$args = array(
				'after'       => '',
				'before'      => '',
				'link_after'  => '',
				'link_before' => '',
				'walker'      => new $walker_class_name,
			);

			echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
		}
	}

	/**
	 * Get modal templates
	 *
	 * @return array
	 */
	public function get_modal_templates() {
		return array(
			'menu',
			'title',
			'mega',
			'mega-grid-row',
			'mega-grid-column',
			'mega-grid-options',
			'design',
			'icon',
			'content',
			'design',
			'settings',
			'tab-content',
		);
	}

	/**
	 * Define default mega menu settings
	 */
	public static function default_settings() {
		return apply_filters(
			'motta_addons_mega_menu_default',
			array(
				'mega'         	=> false,
				'mega_mode'    	=> 'default',
				'icon_type'    	=> 'fontawesome',
				'icon_color'   	=> '',
				'icon_bg_color' => '',
				'icon'         	=> '',
				'icon_image'   	=> '',
				'icon_svg'     	=> '',
				'icon_hide_text' => '',
				'visible'      	=> 'visible',
				'disable_link' 	=> false,
				'content'      	=> '',
				'width'        	=> 'container',
				'custom_width' 	=> '1140px',
				'padding'      	=> array(
					'top'    => '',
					'bottom' => '',
					'left'   => '',
					'right'  => '',
				),
				'margin'       => array(
					'top'    => '',
					'bottom' => '',
					'left'   => '',
					'right'  => '',
				),
				'background'   => array(
					'image'      => '',
					'color'      => '',
					'attachment' => 'scroll',
					'size'       => '',
					'repeat'     => 'no-repeat',
					'position'   => array(
						'x'      => 'left',
						'y'      => 'top',
						'custom' => array(
							'x' => '',
							'y' => '',
						),
					),
				),
				'mt_menu_image'   => '',
			)
		);
	}

	/**
	 * Get default row options for the grid builder
	 */
	public static function default_row_options() {
		return array(
			'padding' => array(
				'top'    => '',
				'bottom' => '',
				'left'   => '',
				'right'  => '',
			),
			'margin' => array(
				'top'    => '',
				'bottom' => '',
			),
			'background' => array(
				'image'      => array(
					'id'  => '',
					'url' => '',
				),
				'color'      => '',
				'repeat'     => 'repeat',
				'attachment' => 'scroll',
				'size'       => '',
				'position'   => array(
					'x'        => 'center',
					'y'        => 'center',
					'custom_x' => '',
					'custom_y' => '',
				)
			),
		);
	}

	/**
	 * Get default column options for the grid builder
	 */
	public static function default_column_options() {
		return array(
			'width'   => 'auto',
			'padding' => array(
				'top'    => '',
				'bottom' => '',
				'left'   => '',
				'right'  => '',
			),
			'background' => array(
				'image'      => array(
					'id'  => '',
					'url' => '',
				),
				'color'      => '',
				'repeat'     => 'repeat',
				'attachment' => 'scroll',
				'size'       => '',
				'position'   => array(
					'x'        => 'center',
					'y'        => 'center',
					'custom_x' => '',
					'custom_y' => '',
				)
			),
		);
	}

	/**
	 * Add a class of menu with icon.
	 *
	 * @param array $classes
	 * @param object $item
	 * @return array
	 */
	public function add_menu_icon_css_class( $classes, $item ) {
		$icon = $this->get_menu_icon( $item );

		if ( $icon ) {
			$classes[] = 'menu-item-has-icon';
		}

		return $classes;
	}

	/**
	 * Add icon before the menu title
	 *
	 * @param string $title
	 * @param object $item
	 * @return string
	 */
	public function add_menu_icon( $title, $item ) {
		$data  = get_post_meta( $item->ID, '_menu_item_mega', true );
		$data  = array_replace_recursive( self::default_settings(), (array) $data );
		$icon = $this->get_menu_icon( $item );

		if ( $data['icon_hide_text'] ) {
			$title = '<span class="screen-reader-text">'. $title .'</span>';
		}

		if ( $icon ) {
			$title = $icon . $title;
		}

		return  $title;
	}

	/**
	 * Get menu icon
	 *
	 * @param object $item
	 * @return string
	 */
	public function get_menu_icon( $item ) {
		$data  = get_post_meta( $item->ID, '_menu_item_mega', true );
		$data  = array_replace_recursive( self::default_settings(), (array) $data );
		$icon  = '';
		$bg_color = $color = $style = '';

		if ( $data['icon_bg_color'] ) {
			$bg_color = 'background-color: ' . esc_attr( $data['icon_bg_color'] ) . ';';
		}

		if ( $data['icon_color'] ) {
			$color = 'color: ' . esc_attr( $data['icon_color'] ) . '';
		}

		if ( $data['icon_bg_color'] || $data['icon_color'] ) {
			$style = 'style="'. $bg_color . $color .'"';
		}

		switch ( $data['icon_type'] ) {
			case 'theme':
				if ( ! empty( $data['icon'] ) && class_exists( '\Motta\Icon' ) ) {
					$icon = \Motta\Icon::get_svg( $data['icon'], '', array( 'style' => $data['icon_color'] ? 'color:' . $data['icon_color'] : '' ) );
				}
				break;

			case 'image':
				if ( ! empty( $data['icon_image'] ) ) {
					$info = pathinfo( $data['icon_image'] );
					$name = basename( $data['icon_image'], '.' . $info['extension'] );
					$icon = '<img class="menu-item-icon menu-icon-item--image" src="' . esc_url( $data['icon_image'] ) . '" alt="' . esc_attr( $name ) . '" ' . $style . '/>';
				}
				break;

			case 'svg':
				if ( ! empty( $data['icon_svg'] ) ) {
					$classes = $data['icon_bg_color'] ? 'menu-icon-item--has-background' : '';
					$icon = '<span class="menu-item-icon menu-icon-item--svg '. $classes .'" ' . $style . '>' . $data['icon_svg'] . '</span>';
				}
				break;
		}

		return $icon;
	}
}
