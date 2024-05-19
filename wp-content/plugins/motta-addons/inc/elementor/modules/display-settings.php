<?php
namespace Motta\Addons\Elementor\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Base\Module;
use Elementor\Controls_Manager;
use Elementor\Core\DocumentTypes\PageBase as PageBase;

class Display_Settings extends Module {
	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'display-settings';
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'elementor/documents/register_controls', [ $this, 'register_display_controls' ] );

		add_action( 'elementor/document/after_save', [ $this, 'sync_settings_from_elementor' ], 10, 2 );
		add_action( 'save_post', [ $this, 'sync_settings_from_page' ], 10, 2 );
		add_action( 'updated_page_meta', [ $this, 'sync_settings_to_elementor' ], 10, 4 );
	}

	/**
	 * Preview Elementor Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'reload_elementor', MOTTA_ADDONS_URL . "/assets/js/admin/reload-elementor.js", array( 'jquery' ), '20210308', true );
	}

	/**
	 * Register display controls.
	 *
	 * @param object $document
	 */
	public function register_display_controls( $document ) {
		if ( ! $document instanceof PageBase ) {
			return;
		}

		$post_type = get_post_type( $document->get_main_id() );

		if ( 'page' != $post_type ) {
			return;
		}

		if( get_option( 'help_center_page_id'  ) == get_the_ID()) {
			return true;
		}

		$this->register_header_controls( $document );
		$this->register_page_header_controls( $document );
		$this->register_content_controls( $document );
		$this->register_footer_controls( $document );
		$this->register_navigation_bar_controls( $document );

	}

	/**
	 * Register template controls of the site header.
	 *
	 * @param object $document
	 */
	protected function register_header_controls( $document ) {
		$document->start_controls_section(
			'section_site_header',
			[
				'label' => __( 'Header Settings', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_control(
			'motta_hide_header_section',
			[
				'label'        => esc_html__( 'Hide Header Section', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => '',

			]
		);

		$document->add_control(
			'header_layout',
			[
				'label'       => esc_html__( 'Header Layout', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'' => esc_html__( 'Default Header Global', 'motta-addons' ),
					'page' => esc_html__( 'Default Header Page', 'motta-addons' ),
					'v1'      => esc_html__( 'Header V1', 'motta-addons' ),
					'v2'      => esc_html__( 'Header V2', 'motta-addons' ),
					'v3'      => esc_html__( 'Header V3', 'motta-addons' ),
					'v4'      => esc_html__( 'Header V4', 'motta-addons' ),
					'v5'      => esc_html__( 'Header V5', 'motta-addons' ),
					'v6'      => esc_html__( 'Header V6', 'motta-addons' ),
					'v7'      => esc_html__( 'Header V7', 'motta-addons' ),
					'v8'      => esc_html__( 'Header V8', 'motta-addons' ),
					'v9'      => esc_html__( 'Header V9', 'motta-addons' ),
					'v10'     => esc_html__( 'Header V10', 'motta-addons' ),
					'v11' 	  => esc_html__( 'Header V11', 'motta-addons' ),
					'v12' 	  => esc_html__( 'Header V12', 'motta-addons' ),
				],
				'default'     => 'default',
			]
		);

		$document->add_control(
			'motta_hide_topbar',
			[
				'label'        => esc_html__( 'Hide Topbar', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_layout',
							'operator' => '!=',
							'value' => 'page',
						],
					],
				],

			]
		);

		$document->add_control(
			'motta_hide_campain_bar',
			[
				'label'        => esc_html__( 'Hide Campain Bar', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_layout',
							'operator' => '!=',
							'value' => 'page',
						],
					],
				],

			]
		);

		$document->add_control(
			'motta_hide_header_border',
			[
				'label'        => esc_html__( 'Hide Border Bottom', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => ''

			]
		);


		$document->add_control(
			'page_primary_menu',
			[
				'label'       => esc_html__( 'Primary Menu', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_menus(),
				'default'     => '0',
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_layout',
							'operator' => '!=',
							'value' => 'page',
						],
					],
				],
			]
		);

		$document->add_control(
			'motta_header_background',
			[
				'label'       => esc_html__( 'Header Background', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'     => esc_html__( 'Default', 'motta-addons' ),
					'transparent' => esc_html__( 'Transparent', 'motta-addons' ),
					'no-transparent' => esc_html__( 'No Transparent', 'motta-addons' ),
				],
				'default'     => 'default',
				'condition' => [
					'header_layout' => array('v12', 'page'),
				],
			]
		);

		$document->add_control(
			'motta_header_text_color',
			[
				'label'       => esc_html__( 'Header Text Color', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default' => esc_html__( 'Default', 'motta-addons' ),
					'light'   => esc_html__( 'Light', 'motta-addons' ),
					'dark'   => esc_html__( 'Dark', 'motta-addons' ),
				],
				'default'     => 'default',
				'condition' => [
					'header_layout' => array('v12', 'page'),
					'motta_header_background' 	=> 'transparent',
				],
			]
		);

		$document->add_control(
			'header_category_menu_display',
			[
				'label'       => esc_html__( 'Category Menu Display', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'    => esc_html__( 'On Hover', 'motta-addons' ),
					'onpageload' => esc_html__( 'On Page Load', 'motta-addons' ),
				],
				'default'     => 'default',
			]
		);

		$document->add_control(
			'header_logo_type',
			[
				'label'       => esc_html__( 'Logo Type', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'' 		=> esc_html__( 'Default', 'motta-addons' ),
					'image' => esc_html__( 'Image', 'motta-addons' ),
					'text'  => esc_html__( 'Text', 'motta-addons' ),
					'svg'   => esc_html__( 'SVG', 'motta-addons' ),
				],
				'default'     => '',
			]
		);

		$document->add_control(
			'header_logo_image',
			[
				'label'       => esc_html__( 'Logo Image', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [],
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_logo_type',
							'value' => 'image',
						],

					],
				],
			]
		);

		$document->add_control(
			'header_logo_image_light',
			[
				'label'       => esc_html__( 'Logo Image Light', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [],
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_logo_type',
							'value' => 'image',
						],
					],
				],
			]
		);

		$document->add_control(
			'header_logo_text',
			[
				'label'   => esc_html__( 'Logo Text', 'motta-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_logo_type',
							'value' => 'text',
						],
					],
				],
			]
		);

		$document->add_control(
			'header_logo_svg',
			[
				'label'   => esc_html__( 'Logo SVG', 'motta-addons' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_logo_type',
							'value' => 'svg',
						],
					],
				],
			]
		);

		$document->add_control(
			'header_logo_width',
			[
				'label' => __( 'Logo Width', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					]
				],
				'default' => [],
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_logo_type',
							'value' => ['image', 'svg'],
						],
					],
				],
			]
		);

		$document->add_control(
			'header_logo_height',
			[
				'label' => __( 'Logo Height', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					]
				],
				'default' => [],
				'conditions' => [
					'terms' => [
						[
							'name' => 'header_logo_type',
							'value' => ['image', 'svg'],
						],
					],
				],
			]
		);

		$document->end_controls_section();
	}

	/**
	 * Register template controls of the site header.
	 *
	 * @param object $document
	 */
	protected function register_page_header_controls( $document ) {
		$document->start_controls_section(
			'section_page_header_settings',
			[
				'label' => esc_html__( 'Page Header Settings', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_control(
			'motta_hide_page_header',
			[
				'label'        => esc_html__( 'Hide Page Header', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => ''
			]
		);

		$document->add_control(
			'motta_hide_title',
			[
				'label'        => esc_html__( 'Hide Title', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => ''
			]
		);

		$document->add_control(
			'motta_hide_breadcrumb',
			[
				'label'        => esc_html__( 'Hide Breadcrumb', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => ''
			]
		);


		$document->end_controls_section();
	}

	/**
	 * Register template controls of the site header.
	 *
	 * @param object $document
	 */
	protected function register_content_controls( $document ) {
		$document->start_controls_section(
			'section_content_settings',
			[
				'label' => esc_html__( 'Content Settings', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_control(
			'motta_content_top_spacing',
			[
				'label'       => esc_html__( 'Top Spacing', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'default' => esc_html__( 'Default', 'motta-addons' ),
					'no'      => esc_html__( 'No spacing', 'motta-addons' ),
					'custom'  => esc_html__( 'Custom', 'motta-addons' ),
				),
				'default'     => 'default',
				'label_block' => true,
			]
		);

		$document->add_control(
			'motta_content_top_padding',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 300,
						'min' => 0,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 63,
				],
				'selectors' => [
					'{{WRAPPER}}.custom-top-spacing .site-content' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'motta_content_top_spacing' => 'custom',
				],
			]
		);

		$document->add_control(
			'motta_content_bottom_spacing',
			[
				'label'       => esc_html__( 'Bottom Spacing', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'default' => esc_html__( 'Default', 'motta-addons' ),
					'no'      => esc_html__( 'No spacing', 'motta-addons' ),
					'custom'  => esc_html__( 'Custom', 'motta-addons' ),
				),
				'default'     => 'default',
				'label_block' => true,
			]
		);

		$document->add_control(
			'motta_content_bottom_padding',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 300,
						'min' => 0,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 63,
				],
				'selectors' => [
					'{{WRAPPER}}.custom-bottom-spacing .site-content' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'motta_content_bottom_spacing' => 'custom',
				],
			]
		);

		$document->end_controls_section();
	}

	/**
	 * Register template controls of the footer.
	 *
	 * @param object $document
	 */
	protected function register_footer_controls( $document ) {
		$document->start_controls_section(
			'section_site_footer',
			[
				'label' => __( 'Footer Settings', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_control(
			'motta_hide_footer_section',
			[
				'label'        => esc_html__( 'Hide Footer Section', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => '',

			]
		);

		$document->add_control(
			'footer_layout',
			[
				'label'       => esc_html__( 'Layout', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => \Motta\Addons\Helper::customizer_get_posts( array( 'post_type' => 'motta_footer', 'source' => 'page' ) ),
				'default'     => 'default',
			]
		);

		$document->add_control(
			'footer_mobile_layout',
			[
				'label'       => esc_html__( 'Mobile Layout', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => \Motta\Addons\Helper::customizer_get_posts( array( 'post_type' => 'motta_footer', 'source' => 'page' ) ),
				'default'     => 'default',
			]
		);

		$document->end_controls_section();
	}

	/**
	 * Register template controls of the navigation bar.
	 *
	 * @param object $document
	 */
	protected function register_navigation_bar_controls( $document ) {
		$document->start_controls_section(
			'section_navigation_bar',
			[
				'label' => __( 'Navigation Bar Settings', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_control(
			'motta_hide_navigation_bar',
			[
				'label'        => esc_html__( 'Hide Navigation Bar', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => '',

			]
		);

		$document->end_controls_section();
	}

	/**
	 * Map element settings to theme settings.
	 *
	 * @param \Elementor\Core\Base\Document $document
	 * @param array $data
	 */
	public function sync_settings_from_elementor( $document, $data ) {
		if ( ! isset( $data['settings'] ) ) {
			return;
		}

		$post_id  = $document->get_main_id();
		$settings = $this->get_settings_map();

		foreach ( $settings as $elementor_setting => $theme_setting ) {
			if ( isset( $data['settings'][ $elementor_setting ] ) ) {
				$value = $data['settings'][ $elementor_setting ];
			} else {
				$control = $document->get_controls( $elementor_setting );
				$value = isset( $control['default'] ) ? $control['default'] : '';
			}

			if ( $theme_setting == 'header_logo_image' ) {
				$value = ! empty($value['id']) ? $value['id'] : '';
			}

			$value = 'default' === $value ? '' : $value;
			$value = is_array( $value ) && isset( $value['size'] ) ? $value['size'] : $value;

			update_post_meta( $post_id, $theme_setting, $value );
		}
	}

	/**
	 * Map theme settings to Elementor page settings.
	 *
	 * @param int $post_id
	 * @param object $post
	 */
	public function sync_settings_from_page( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || 'page' != $post->post_type ) {
			return;
		}

		if ( ! isset( $_POST['nonce_display-settings'] ) ) {
			return;
		}

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$this->convert_settings_to_elementor( $post_id, 'request' );
	}

	/**
	 * Convert the theme settings to document settings of Elementor
	 * when the page builder was changed to Elementor.
	 *
	 * @param int    $meta_id     ID of updated metadata entry.
	 * @param int    $object_id   ID of the object metadata is for. It is the page ID.
	 * @param string $meta_key    Metadata key.
	 * @param mixed  $meta_value Metadata value. Serialized if non-scalar.
	 */
	public function sync_settings_to_elementor( $meta_id, $object_id, $meta_key, $meta_value ) {
		if ( '_elementor_edit_mode' != $meta_key ) {
			return;
		}

		$this->convert_settings_to_elementor( $object_id, 'db' );
	}

	/**
	 * Convert theme settings to Elementor document settings.
	 *
	 * @param int $post_id
	 * @param string $source The setting source. It is 'request' or 'db'.
	 */
	protected function convert_settings_to_elementor( $post_id, $source = 'request' ) {
		$settings        = $this->get_settings_map();
		$page_settings   = [];

		$slider_settings = [
			'motta_content_top_padding',
			'motta_content_bottom_padding',
			'header_logo_width',
			'header_logo_height',
		];

		$media_settings = [
			'header_logo_image',
		];

		foreach ( $settings as $elementor_setting => $theme_setting ) {
			// Metabox uses a empty string '' as the default value, while Elementor uses 'default' value.
			if ( 'request' == $source ) {
				$value = ! empty( $_POST[ $theme_setting ] ) ? $_POST[ $theme_setting ] : 'default';
			} else {
				$value = get_post_meta( $post_id, $theme_setting, true );
				$value = ! empty( $value ) ? $value : 'default';
			}

			if ( in_array( $elementor_setting, $slider_settings ) ) {
				$page_settings[ $elementor_setting ]['size'] = $value;
			} else {
				$page_settings[ $elementor_setting ] = $value;
			}

			if ( in_array( $elementor_setting, $media_settings ) ) {
				$page_settings[ $elementor_setting ] = array(
					'id' => $value,
					'url' => wp_get_attachment_image_src( $value, 'full' )[0]
				);
			} else {
				$page_settings[ $elementor_setting ] = $value;
			}
		}

		if ( ! empty( $page_settings ) ) {
			$elementor_page_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
			$elementor_page_manager->save_settings( $page_settings, $post_id );
		}
	}

	/**
	 * Get the array of mapping setting names.
	 *
	 * @return array
	 */
	protected function get_settings_map() {
		return [
			'header_layout'                => 'header_layout',
			'header_category_menu_display' => 'header_category_menu_display',
			'header_logo_type' 		   	   => 'header_logo_type',
			'header_logo_image' 		   => 'header_logo_image',
			'header_logo_text' 		   	   => 'header_logo_text',
			'header_logo_svg' 		   	   => 'header_logo_svg',
			'header_logo_width' 		   => 'header_logo_width',
			'header_logo_height' 		   => 'header_logo_height',
			'motta_header_background'      => 'motta_header_background',
			'motta_header_text_color'      => 'motta_header_text_color',
			'motta_hide_header_section'    => 'motta_hide_header_section',
			'motta_hide_topbar' 		   => 'motta_hide_topbar',
			'motta_hide_campain_bar' 	   => 'motta_hide_campain_bar',
			'motta_hide_header_border' 	   => 'motta_hide_header_border',
			'motta_hide_page_header'       => 'motta_hide_page_header',
			'motta_hide_title'             => 'motta_hide_title',
			'motta_hide_breadcrumb'        => 'motta_hide_breadcrumb',
			'motta_content_top_spacing'    => 'motta_content_top_spacing',
			'motta_content_top_padding'    => 'motta_content_top_padding',
			'motta_content_bottom_spacing' => 'motta_content_bottom_spacing',
			'motta_content_bottom_padding' => 'motta_content_bottom_padding',
			'motta_hide_footer_section'    => 'motta_hide_footer_section',
			'footer_layout'                => 'footer_layout',
			'footer_mobile_layout'         => 'footer_mobile_layout',
			'page_primary_menu'				=> 'page_primary_menu',
			'motta_hide_navigation_bar'=> 'motta_hide_navigation_bar'
		];
	}

		/**
	 * Get nav menus
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_menus() {
		if ( ! is_admin() ) {
			return [];
		}

		$menus = wp_get_nav_menus();
		if ( ! $menus ) {
			return [];
		}

		$output = array(
			0 => esc_html__( 'Select Menu', 'motta-addons' ),
		);
		foreach ( $menus as $menu ) {
			$output[ $menu->slug ] = $menu->name;
		}

		return $output;
	}
}