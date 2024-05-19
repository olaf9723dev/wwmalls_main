<?php
namespace Motta\Addons\Modules\Popup;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Base\Module;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Core\DocumentTypes\PageBase as PageBase;

class Elementor_Settings extends Module {
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
		add_action( 'elementor/documents/register_controls', [ $this, 'register_display_controls' ] );
		add_action( 'elementor/document/after_save', [ $this, 'sync_settings_from_elementor' ], 10, 2 );
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

		if ( 'motta_popup' != $post_type ) {
			return;
		}

		add_action('elementor/element/after_section_end', [ $this, 'update_controls' ]);

		$this->register_popup_content( $document );
		$this->register_popup_style( $document );

	}

	/**
	 * Register template controls of display.
	 *
	 * @param object $document
	 */
	protected function register_popup_content( $document ) {
		$document->start_controls_section(
			'section_display',
			[
				'label' => __( 'Popup Settings', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_control(
			'popup_display',
			[
				'label'        => esc_html__( 'Display', 'motta-addons' ),
				'type'         => Controls_Manager::HEADING,
				'default'      => '',
			]
		);

		$document->add_control(
			'enable_popup',
			[
				'label'        => esc_html__( 'Enable Popup', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',

			]
		);

		$document->add_control(
			'hide_overlay',
			[
				'label'        => esc_html__( 'Hide Overlay', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'@media (min-width: 768px) {.motta-popup-' . get_the_ID() . ' .motta-popup__backdrop' =>  'display: {{VALUE}}}',
				],

			]
		);

		$document->add_control(
			'hide_overlay_mobile',
			[
				'label'        => esc_html__( 'Hide Overlay on Mobile', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'none',
				'default'      => '',
				'selectors' => [
					'@media (max-width: 767px) {.motta-popup-' . get_the_ID() . ' .motta-popup__backdrop' =>  'display: {{VALUE}}}',
				],

			]
		);

		$document->add_control(
			'popup_position',
			[
				'label'       => esc_html__( 'Position', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'center' => esc_html__( 'Center', 'motta-addons' ),
					'left-bottom'      	=> esc_html__( 'Left Bottom', 'motta-addons' ),
					'right-bottom'      => esc_html__( 'Right Bottom', 'motta-addons' ),
					'left-top'      	=> esc_html__( 'Left Top', 'motta-addons' ),
				],
				'default'     => 'left-bottom',
			]
		);

		$document->add_control(
			'popup_frequency',
			[
				'label'        => esc_html__( 'Frequency', 'motta-addons' ),
				'type'         => Controls_Manager::NUMBER,
				'description' => esc_html__('Do NOT show the popup to the same visitor again until this much days has passed', 'motta-addons' ),
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 1,

			]
		);

		$document->add_control(
			'popup_triggers',
			[
				'label'        => esc_html__( 'Triggers', 'motta-addons' ),
				'type'         => Controls_Manager::HEADING,
				'default'      => '',
				'separator' => 'before',

			]
		);

		$document->add_control(
			'popup_visible',
			[
				'label'       => esc_html__( 'Visible', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'loaded' 		=> esc_html__( 'After page loads', 'motta-addons' ),
					'delayed'      	=> esc_html__( 'Wait for seconds', 'motta-addons' ),
				],
				'default'     => 'loaded',
			]
		);

		$document->add_control(
			'popup_seconds',
			[
				'label'       => esc_html__( 'Seconds', 'motta-addons' ),
				'type'         => Controls_Manager::NUMBER,
				'description' => esc_html__('The time before the popup is displayed, after the page loaded', 'motta-addons' ),
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 5,
				'conditions' => [
					'terms' => [
						[
							'name' => 'popup_visible',
							'operator' => '==',
							'value' => 'delayed'
						],
					]
				]
			]
		);

		$document->add_control(
			'popup_targeting',
			[
				'label'        => esc_html__( 'Targeting', 'motta-addons' ),
				'type'         => Controls_Manager::HEADING,
				'default'      => '',
				'separator' => 'before',

			]
		);

		$pages = get_pages();
		foreach( $pages as $page ) {
			$page_options[$page->ID] = $page->post_title;
		}
		$document->add_control(
			'popup_include_pages',
			[
				'label'       => esc_html__( 'Include Pages', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $page_options,
				'default'     => '',
				'multiple' => true,
			]
		);

		$document->add_control(
			'popup_exclude_pages',
			[
				'label'       => esc_html__( 'Exclude Pages', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $page_options,
				'default'     => '',
				'multiple' => true,
			]
		);

		$document->end_controls_section();

	}

	/**
	 * Register template controls of display.
	 *
	 * @param object $document
	 */
	protected function register_popup_style( $document ) {
		$document->start_controls_section(
			'popup_style',
			[
				'label' => esc_html__( 'Popup Style', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$document->add_control(
			'popup_width',
			[
				'label' => esc_html__( 'Width', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1170,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vw' ],
				'default' => [
					'size' => 640,
				],
				'selectors' => [
					'.motta-popup-' . get_the_ID() . ' .motta-popup__content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$document->add_control(
			'popup_close_heading',
			[
				'label' => __( 'Close', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$document->add_control(
			'popup_close_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'.motta-popup-' . get_the_ID() . ' .motta-popup__close' => 'color: {{VALUE}};'
				],
			]
		);


		$document->end_controls_section();

	}

	/**
	 * @param $element    Controls_Stack
	 */
	public function update_controls( $document ) {
		$document->remove_control( 'hide_title' );
		$document->remove_control( 'section_page_style' );
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
			if ( $theme_setting == 'enable_popup' ) {
				$value = empty($value) ? 'no' : 'yes';
			}

			if ( $theme_setting == 'popup_include_pages' ) {
				$value = empty($value) ? '0' : $value;
			}

			update_post_meta( $post_id, $theme_setting, $value );
		}
	}

		/**
	 * Get the array of mapping setting names.
	 *
	 * @return array
	 */
	protected function get_settings_map() {
		return [
			'enable_popup'                => 'enable_popup',
			'popup_include_pages'         => 'popup_include_pages',
			'popup_exclude_pages'         => 'popup_exclude_pages',
		];
	}

}