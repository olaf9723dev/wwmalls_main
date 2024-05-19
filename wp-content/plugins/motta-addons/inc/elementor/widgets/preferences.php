<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Preferences widget
 */
class Preferences extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-preferences';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Preferences', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-globe';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['motta-addons'];
	}

	/**
	 * Get widget keywords.
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'preferences', 'currency', 'language', 'motta-addons', 'motta-addons' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->section_content();
		$this->section_style();
	}

	protected function section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Preferences', 'motta-addons' ),
			]
		);

		$this->add_control(
			'language',
			[
				'label'     => esc_html__( 'Language', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'currency',
			[
				'label'     => esc_html__( 'Currency', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Show', 'motta-addons' ),
				'label_on'  => __( 'Hide', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();
	}

	// Tab Style
	protected function section_style() {
		$this->start_controls_section(
			'section_style',
			[
				'label'     => __( 'Preferences', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'motta-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'motta-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'motta-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'motta-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .motta-preferences--elementor' => 'justify-content: {{VALUE}};text-align:{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_language',
			[
				'label' => __( 'Language', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'flag',
			[
				'label' => esc_html__( 'Show Flag', 'motta-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Hide', 'motta-addons' ),
				'label_off' => esc_html__( 'Show', 'motta-addons' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'language_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-language' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'language_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-language .motta-button__icon + .motta-button__text' => 'padding-left: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'heading_currency',
			[
				'label' => __( 'Currency', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'currency_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-currency' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'currency_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-currency .motta-button__icon + .motta-button__text' => 'padding-left: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?><div class="motta-preferences motta-preferences--elementor"><?php
		$languages    = \Motta\Helper::language_status();

		if ( ! empty( $languages ) && ! empty( $settings['language'] ) ) {
			\Motta\Theme::set_prop( 'modals', 'preferences' );

			foreach ( (array) $languages as $key => $language ) {
				if( $language['active'] ) {
					$name = $language['native_name'];

					if( $settings['flag'] ) {
						$flag = $language['country_flag_url'];
					}
				} else {
					$name = $languages['en']['native_name'];

					if( $settings['flag'] ) {
						$flag = $languages['en']['country_flag_url'];
					}
				}
			}
			?>
			<div class="motta-language motta-preferences__item">
				<a href="#" data-toggle="modal" data-target="preferences-modal" class="motta-button motta-button--text motta-button--color-black">
					<span class="motta-button__icon">
						<?php if( empty( $flag ) ) : ?>
							<?php echo \Motta\Addons\Helper::get_svg( esc_attr( 'language' ) ); ?>
						<?php else : ?>
							<img src="<?php echo esc_url( $flag ); ?>" width="16" height="16" alt="<?php echo esc_html( $name ); ?>" />
						<?php endif; ?>
					</span>
					<span class="motta-button__text">
						<?php echo esc_html( $name ); ?>
					</span>
				</a>
			</div>
			<?php
		}

		$args = \Motta\Helper::currency_status();

		if( ! empty( $args ) && ! empty( $settings['currency'] ) ) {

			\Motta\Theme::set_prop( 'modals', 'preferences' );

			$symbol_currency = $args['symbol_currency'];
			$current_currency = $args['current_currency'];

			?>
			<div class="motta-currency motta-preferences__item">
				<a href="#" data-toggle="modal" data-target="preferences-modal" class="motta-button motta-button--text motta-button--color-black">
					<span class="motta-button__icon">
						<?php echo \Motta\Addons\Helper::get_svg( esc_attr( 'currency' ) ); ?>
					</span>
					<span class="motta-button__text">
						<?php echo esc_html( $symbol_currency ) . ' - ' . esc_html( $current_currency ); ?>
					</span>
				</a>
			</div>
			<?php
		}

		?></div><?php
	}
}