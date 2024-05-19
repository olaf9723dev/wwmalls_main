<?php
/**
 * Integrate with Elementor.
 */

namespace Motta\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor {
	/**
	 * Instance
	 *
	 * @access private
	 */
	private static $_instance = null;

	/**
	 * Elementor modules
	 *
	 * @var array
	 */
	public $module = [];

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Motta_Addons_Elementor An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->setup_hooks();
		$this->_includes();

		\Motta\Addons\Elementor\Controls\AutoComplete_AjaxLoader::instance();
		\Motta\Addons\Elementor\Library::instance();
	}

	/**
	 * Auto load widgets
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$path = explode( '\\', $class );
		$filename = strtolower( array_pop( $path ) );
		$filename = str_replace( '_', '-', $filename );

		$module = array_pop( $path );

		if ( 'Modules' == $module ) {
			$filename = MOTTA_ADDONS_DIR . 'inc/elementor/modules/' . $filename . '.php';
		} elseif ( 'Widgets' == $module ) {
			$filename = MOTTA_ADDONS_DIR . 'inc/elementor/widgets/' . $filename . '.php';
		} elseif ( 'Base' == $module ) {
			$filename = MOTTA_ADDONS_DIR . 'inc/elementor/base/' . $filename . '.php';
		} elseif ( 'Controls' == $module ) {
			$filename = MOTTA_ADDONS_DIR . 'inc/elementor/controls/' . $filename . '.php';
		} elseif ( 'Traits' == $module ) {
			$filename = MOTTA_ADDONS_DIR . 'inc/elementor/widgets/traits/' . $filename . '.php';
		}

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	/**
	 * Includes files which are not widgets
	 */
	private function _includes() {
		require MOTTA_ADDONS_DIR . 'inc/elementor/utils.php';

		if ( class_exists( 'Woocommerce' ) ) {
			require MOTTA_ADDONS_DIR . 'inc/elementor/products.php';
		}

		\Motta\Addons\Auto_Loader::register( [
			'Motta\Addons\Elementor\Controls\AjaxLoader'  => MOTTA_ADDONS_DIR . 'inc/elementor/controls/autocomplete-ajaxloader.php',
			'Motta\Addons\Elementor\Library'  => MOTTA_ADDONS_DIR . 'inc/elementor/library/library.php',
		] );
	}

	/**
	 * Hooks to init
	 */
	protected function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'init_modules' ] );

		// Widgets
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_styles' ] );

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

		// Register controls
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );

		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
			add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
		}
	}

	/**
	 * Register WC hooks for Elementor editor
	 */
	public function register_wc_hooks() {
		if ( function_exists( 'wc' ) ) {
			wc()->frontend_includes();
		}
	}

	/**
	 * Init modules
	 */
	public function init_modules() {
		$this->modules['theme-display-settings'] = \Motta\Addons\Elementor\Modules\Display_Settings::instance();
		$this->modules['section-settings'] = \Motta\Addons\Elementor\Modules\Section_Settings::instance();
		$this->modules['column-settings'] = \Motta\Addons\Elementor\Modules\Column_Settings::instance();
		$this->modules['icon-list-settings'] = \Motta\Addons\Elementor\Modules\Icon_List_Settings::instance();

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$this->modules['motion-parallax'] = \Motta\Addons\Elementor\Modules\Motion_Parallax::instance();
		}
	}

	/**
	 * Register autocomplete control
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_controls( $controls_manager ) {
		$controls_manager->register( new \Motta\Addons\Elementor\Controls\AutoComplete() );
	}

	/**
	 * Register styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style( 'image-slide-css',  MOTTA_ADDONS_URL . 'assets/css/image-slide.css', array(), '1.0' );

	}

	/**
	 * Register styles
	 */
	public function register_scripts() {
		wp_enqueue_script( 'motta-countdown', MOTTA_ADDONS_URL . 'assets/js/plugins/jquery.countdown.js', ['jquery'], MOTTA_ADDONS_VER, true );
		wp_register_script( 'image-slide', MOTTA_ADDONS_URL . 'assets/js/plugins/image-slide.js', ['jquery'], MOTTA_ADDONS_VER, true );
		wp_register_script( 'eventmove', MOTTA_ADDONS_URL . 'assets/js/plugins/jquery.event.move.js', ['jquery'], MOTTA_ADDONS_VER, true );
		wp_register_script( 'masonry', MOTTA_ADDONS_URL . 'assets/js/plugins/masonry-pkgd.min.js', ['jquery'], MOTTA_ADDONS_VER, true );
		wp_register_script( 'threesixty', MOTTA_ADDONS_URL . 'assets/js/plugins/threesixty.min.js', ['jquery'], '2.0.5', true );

		wp_register_script( 'motta-elementor-widgets', MOTTA_ADDONS_URL . 'assets/js/elementor-widgets.js', ['jquery', 'underscore', 'elementor-frontend', 'regenerator-runtime'], MOTTA_ADDONS_VER, true );

		wp_register_script( 'jarallax', MOTTA_ADDONS_URL . 'assets/js/plugins/jarallax.min.js', [], '1.12.8', true );
		wp_register_script( 'motta-elementor-parallax', MOTTA_ADDONS_URL . 'assets/js/elementor-parallax-widgets.js', [], '1.0', true );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . \Motta\Addons\Elementor\Widgets\Google_Map::get_api_key() );
		}

		wp_enqueue_script( 'motta-elementor-widgets' );
	}

	/**
	 * Init Widgets
	 */
	public function init_widgets() {
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Heading() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Accordion() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Button() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Blockquote() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Counter() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Countdown() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Contact_Form() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Gallery() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Gallery_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Banner_Image() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Image_Grid() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Image_Box() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Image_Box_Grid() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Image_Box_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Images_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Image_Before_After() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Image_Hotspot() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Icon_Box() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Icons_Box_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Instagram() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Instagram_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Message_Box() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Modal_Popup() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Advanced_Menu() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Navigation_Menu() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Navigation_Bar() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Posts_Grid() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Posts_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Pricing_Table() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Quick_Links() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Social_Icons() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Slides() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Search() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Subscribe_Box() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Share_Socials() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Store_Locations() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Team_Member_Grid() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Team_Member_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\CheckList() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Tabs() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Testimonial_Carousel() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Product_360_Viewer() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Google_Map() );
		$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Articles_Grid() );

		if ( class_exists( 'Woocommerce' ) ) {
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Product_Grid() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Products_Listing() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Products_Carousel() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Product_Deals_Grid() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Product_Deals_Carousel() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Products_Tabs() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Products_Tabs_Carousel() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Product_Category_Box() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Products_Recently_Viewed_Grid() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Products_Recently_Viewed_Carousel() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Preferences() );
			$widgets_manager->register( new \Motta\Addons\Elementor\Widgets\Brands_Grid() );
		}

	}

	/**
	 * Add Motta category
	 */
	public function add_category( $elements_manager ) {
		$elements_manager->add_category(
			'motta-addons',
			[
				'title' => __( 'Motta', 'motta-addons' )
			]
		);
	}
}

Elementor::instance();