<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor social icons widget.
 *
 * Elementor widget that displays icons to social pages like Facebook and Twitter.
 *
 * @since 1.0.0
 */
class Share_Socials extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve social icons widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-share-socials';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve social icons widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( '[Motta] Share Socials', 'motta-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve social icons widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-share';
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
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'social', 'icon', 'link', 'motta-addons' ];
	}

	/**
	 * Register social icons widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->content_sections();
		$this->style_sections();
	}

	protected function content_sections() {
		$this->start_controls_section(
			'section_social_icon',
			[
				'label' => esc_html__( 'Social Icons', 'motta-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'network',
			[
				'label' => esc_html__( 'Network', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_share_socials(),
				'default' => 'facebook',
			]
		);

		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Icon', 'motta-addons' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
				],
			]
		);

		$repeater->add_control(
			'whatsapp_number',
			[
				'label' => esc_html__( 'Number', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'network' => 'whatsapp',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enter your title', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Official Color', 'motta-addons' ),
					'custom' => esc_html__( 'Custom', 'motta-addons' ),
				],
			]
		);

		$repeater->add_control(
			'item_icon_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'item_icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}}.motta-share-socials-title-below {{CURRENT_ITEM}} .motta-svg-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.motta-share-socials-title-beside {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'item_icon_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'item_icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}}.motta-share-socials-title-below {{CURRENT_ITEM}} .motta-svg-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.motta-share-socials-title-beside {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'social_icon_list',
			[
				'label' => esc_html__( 'Social Icons', 'motta-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'network' => 'facebook',
						'title' => esc_html__('Facebook', 'motta-addons'),
					],
					[
						'network' => 'twitter',
						'title' => esc_html__('Twitter', 'motta-addons'),
					],
					[
						'network' => 'pinterest',
						'title' => esc_html__('Pinterest', 'motta-addons'),
					],

				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_control(
			'title_position',
			[
				'label' => esc_html__( 'Title Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'below' => esc_html__( 'Below', 'motta-addons' ),
					'beside' => esc_html__( 'Beside', 'motta-addons' ),
				],
				'prefix_class' => 'motta-share-socials-title-',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => esc_html__( 'Shape', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'rounded',
				'options' => [
					'rounded' => esc_html__( 'Rounded', 'motta-addons' ),
					'square' => esc_html__( 'Square', 'motta-addons' ),
					'circle' => esc_html__( 'Circle', 'motta-addons' ),
				],
				'prefix_class' => 'motta-share-socials-shape-',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'Auto', 'motta-addons' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'elementor-grid%s-',
				'selectors' => [
					'{{WRAPPER}}' => '--grid-template-columns: repeat({{VALUE}}, auto);',
				],
			]
		);

		$this->add_control(
			'copy_link',
			[
				'label'        => esc_html__( 'Show Copy Link', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function style_sections() {
		$this->start_controls_section(
			'section_social_style',
			[
				'label' => esc_html__( 'Share Socials', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_text_align',
			[
				'label'       => esc_html__( 'Alignment', 'motta-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'motta-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .elementor-grid-item' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-grid-item' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-grid-item:last-child' => 'margin-right:0',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'item_style_heading',
			[
				'label' => __( 'Item', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'title_position' => 'beside',
				],
			]
		);

		$this->add_control(
			'item_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Official Color', 'motta-addons' ),
					'custom' => esc_html__( 'Custom', 'motta-addons' ),
				],
				'condition' => [
					'title_position' => 'beside',
				],
			]
		);

		$this->add_control(
			'item_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'title_position' => 'beside',
					'item_color' => 'custom'
				],
				'selectors' => [
					'{{WRAPPER}} .motta-share-icons .social-share-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'title_position' => 'beside',
					'item_color' => 'custom'
				],
				'selectors' => [
					'{{WRAPPER}} .motta-share-icons .social-share-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motta-share-icons .social-share-link svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'motta-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-share-icons .social-share-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'title_position' => 'beside',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'motta-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .motta-share-icons .social-share-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'title_position' => 'beside',
				],
			]
		);

		$this->add_control(
			'icon_style_heading',
			[
				'label' => __( 'Icon', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Official Color', 'motta-addons' ),
					'custom' => esc_html__( 'Custom', 'motta-addons' ),
				],
				'condition' => [
					'title_position' => 'below',
				],
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .motta-share-icons .motta-svg-icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'title_position' => 'below',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'icon_color' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} .motta-share-icons .motta-svg-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motta-share-icons svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'title_position' => 'below',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				// The `%' and `em` units are not supported as the widget implements icons differently then other icons.
				'size_units' => [ 'px', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--share-icon-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--share-icon-padding: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'title_position' => 'below',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.motta-share-socials-title-below .motta-share-icons .motta-svg-icon' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.motta-share-socials-title-beside .motta-share-icons .motta-svg-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_order_radius',
			[
				'label' => esc_html__( 'Border Radius', 'motta-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}.motta-share-socials-title-below .motta-share-icons .motta-svg-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'title_position' => 'below',
				],
			]
		);

		$this->add_control(
			'title_style_heading',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-share-icons .social-share__label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .motta-share-icons .social-share__label',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render social icons widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$args = array();
		?>
		<div class="motta-share-icons elementor-grid">
			<?php

				foreach ( $settings['social_icon_list'] as $index => $item ) {

					if( ! empty( $item['social_icon'] ) ) {
						$args[$item['network'] . '_icon_html'] = Icons_Manager::try_get_icon_html($item['social_icon']);
					}

					if( ! empty($item['title']) ) {
						$args[$item['network'] . '_title'] = $item['title'];
					}

					if( $item['network'] == 'whatsapp' && ! empty( $item['whatsapp_number'] )  ) {
						$args['whatsapp_number'] = $item['whatsapp_number'];
					}

					$args['repeat_classes'] =  'elementor-grid-item elementor-repeater-item-' . $item['_id'];

					echo \Motta\Addons\Helper::share_link( $item['network'], $args );

				}

				if ( $settings['copy_link'] ) { ?>

					<div class="motta-share-icons__copylink">
						<div class="motta-share-icons__copylink-heading"><?php echo esc_html__( 'Copy Link', 'motta-addons' ); ?></div>
						<form>
							<input class="motta-share-icons__copylink--link" type="text" value="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>" readonly="readonly" />
							<button class="motta-share-icons__copylink--button motta-button motta-button--bg-color-black"><?php echo esc_html__( 'Copy', 'motta-addons' ); ?></button>
						</form>
					</div>

				<?php }
			?>
		</div>
		<?php
	}

	public function get_share_socials() {
		return array(
			'facebook' => esc_html__('Facebook', 'motta-addons'),
			'twitter' => esc_html__('Twitter', 'motta-addons'),
			'pinterest' => esc_html__('Pinterest', 'motta-addons'),
			'googleplus' => esc_html__('Google Plus', 'motta-addons'),
			'linkedin' => esc_html__('Linkedin', 'motta-addons'),
			'tumblr' => esc_html__('Tumblr', 'motta-addons'),
			'reddit' => esc_html__('Reddit', 'motta-addons'),
			'stumbleupon' => esc_html__('Stumbleupon', 'motta-addons'),
			'telegram' => esc_html__('Telegram', 'motta-addons'),
			'whatsapp' => esc_html__('Whatsapp', 'motta-addons'),
			'pocket' => esc_html__('Pocket', 'motta-addons'),
			'digg' => esc_html__('Digg', 'motta-addons'),
			'vk' => esc_html__('VK', 'motta-addons'),
			'email' => esc_html__('Email', 'motta-addons')
		);
	}
}
