<?php
namespace Motta\Addons\Elementor\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Base\Module;
use Elementor\Controls_Manager;

class Section_Settings extends Module {
	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'section-settings';
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		add_action( 'elementor/element/section/section_layout/after_section_end', [ $this, 'register_controls' ] );
	}

	/**
	 * @param $element    Controls_Stack
	 */
	public function register_controls( $element ) {

		$element->start_controls_section(
			'section_responsive_layout',
			[
				'label' => __( 'Responsive Layout', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'tablet_column_alignment',
			[
				'label' => esc_html__( 'Tablet Columns Alignment', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' 		=> esc_html__( 'Default', 'motta-addons' ),
					'column_xxxs' 	=> esc_html__( '4.5 columns', 'motta-addons' ),
					'column_xxs' 	=> esc_html__( '3.5 columns', 'motta-addons' ),
					'column_xs' 	=> esc_html__( '2.5 columns', 'motta-addons' ),
					'column_sm' 	=> esc_html__( '2 columns', 'motta-addons' ),
					'column_md' 	=> esc_html__( '1.5 columns', 'motta-addons' ),
					'column_lg' 	=> esc_html__( '1 column', 'motta-addons' ),
				],
				'default' => 'default',
				'selectors_dictionary' => [
					'column_xxxs' => '22',
					'column_xxs' => '30',
					'column_xs'  => '40',
					'column_sm'  => '50',
					'column_md'  => '75',
					'column_lg'  => '100',
				],
				'prefix_class' => 'motta-responsive-column motta-tablet-column--',
			]
		);

		$element->add_control(
			'mobile_column_alignment',
			[
				'label' => esc_html__( 'Mobile Columns Alignment', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' 		=> esc_html__( 'Default', 'motta-addons' ),
					'column_xxs' 	=> esc_html__( '3.5 columns', 'motta-addons' ),
					'column_xs' 	=> esc_html__( '2.5 columns', 'motta-addons' ),
					'column_sm' 	=> esc_html__( '2 columns', 'motta-addons' ),
					'column_md' 	=> esc_html__( '1.5 columns', 'motta-addons' ),
					'column_lg' 	=> esc_html__( '1 column', 'motta-addons' ),
				],
				'default' => 'default',
				'selectors_dictionary' => [
					'column_xxs' => '30',
					'column_xs'  => '40',
					'column_sm'  => '50',
					'column_md'  => '75',
					'column_lg'  => '100',
				],
				'prefix_class' => 'motta-responsive-column motta-mobile-column--',
			]
		);

		$element->end_controls_section();
	}
}