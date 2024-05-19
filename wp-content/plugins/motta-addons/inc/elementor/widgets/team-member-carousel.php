<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor TeamMemberGrid widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Team_Member_Carousel extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve TeamMemberGrid widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-team-member-carousel';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve TeamMemberGrid widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Team Member Carousel', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve TeamMemberGrid widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-carousel';
	}

	/**
	 * Get widget categories
	 *
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return string Widget categories
	 */
	public function get_categories() {
		return [ 'motta-addons' ];
	}

	/**
	 * Get widget keywords.
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'team carousel', 'carousel', 'team', 'motta' ];
	}


	/**
	 * Get Team Member Socials
	 */
	protected function get_social_icons() {
		$socials = [
			'twitter' => [
				'name'  => 'twitter',
				'label' => __( 'Twitter', 'motta-addons' )
			],
			'facebook' => [
				'name'  => 'facebook',
				'label' => __( 'Facebook', 'motta-addons' )
			],
			'youtube' => [
				'name'  => 'youtube',
				'label' => __( 'Youtube', 'motta-addons' )
			],
			'dribbble' => [
				'name'  => 'dribbble',
				'label' => __( 'Dribbble', 'motta-addons' )
			],
			'linkedin' => [
				'name'  => 'linkedin',
				'label' => __( 'Linkedin', 'motta-addons' )
			],
			'pinterest' => [
				'name' 	=> 'pinterest',
				'label' => __( 'Pinterest', 'motta-addons' )
			],
			'instagram' => [
				'name' 	=> 'instagram',
				'label' => __( 'Instagram', 'motta-addons' )
			],
		];

		return apply_filters( 'motta_addons_team_member_social_icons' , $socials );
	}

	/**
	 * Register heading widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
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
				'label' => __( 'Content', 'motta-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Image', 'motta-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => MOTTA_ADDONS_URL . '/assets/images/person.jpg',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter title text', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'description', [
				'label' => esc_html__( 'Description', 'motta-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'motta-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				],
			]
		);

		// Socials
		$repeater->add_control(
			'socials_toggle',
			[
				'label' => __( 'Socials', 'motta-addons' ),
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'Default', 'motta-addons' ),
				'label_on' => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
				'separator' => 'before',
			]
		);

		$repeater->start_popover();

		$socials = $this->get_social_icons();

		foreach( $socials as $key => $social ) {
			$repeater->add_control(
				$key,
				[
					'label'       => $social['label'],
					'type'        => Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'motta-addons' ),
					'default'     => [
						'url' => '',
					],
				]
			);
		}

		$repeater->end_popover();

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Items', 'motta-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default' => [
					[
						'title'   		=> esc_html__( 'Item #1', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => MOTTA_ADDONS_URL . '/assets/images/person.jpg'],
						'link'    		=> ['url' => '#'],
					],
					[
						'title'   		=> esc_html__( 'Item #2', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => MOTTA_ADDONS_URL . '/assets/images/person.jpg'],
						'link'    		=> ['url' => '#'],
					],
					[
						'title'   		=> esc_html__( 'Item #3', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => MOTTA_ADDONS_URL . '/assets/images/person.jpg'],
						'link'    		=> ['url' => '#'],
					],
					[
						'title'   		=> esc_html__( 'Item #4', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => MOTTA_ADDONS_URL . '/assets/images/person.jpg'],
						'link'    		=> ['url' => '#'],
					],
					[
						'title'   		=> esc_html__( 'Item #5', 'motta-addons' ),
						'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => MOTTA_ADDONS_URL . '/assets/images/person.jpg'],
						'link'    		=> ['url' => '#'],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->section_content_carousel();
	}

	protected function section_style() {
		$this->start_controls_section(
			'section_products_carousel',
			[
				'label' => __( 'Carousel Settings', 'motta-addons' ),
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'              => esc_html__( 'Slides to show', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 4,
				'frontend_available' => true,
				'separator'          => 'before',
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'              => esc_html__( 'Slides to scroll', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 1,
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'arrows'           => esc_html__( 'Arrows', 'motta-addons' ),
					'dots'             => esc_html__( 'Dots', 'motta-addons' ),
					'both'             => esc_html__( 'Arrows and Dots', 'motta-addons' ),
					'none'             => esc_html__( 'None', 'motta-addons' ),
				],
				'default'            => 'arrows',
				'toggle'             => false,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => __( 'Autoplay', 'motta-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'motta-addons' ),
				'label_on'  => __( 'On', 'motta-addons' ),
				'default'   => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'   => __( 'Autoplay Speed', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3000,
				'min'     => 100,
				'step'    => 100,
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'   => __( 'Pause on Hover', 'motta-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'motta-addons' ),
				'label_on'  => __( 'On', 'motta-addons' ),
				'default'   => 'yes',
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => __( 'Animation Speed', 'motta-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 800,
				'min'         => 100,
				'step'        => 50,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function section_content_carousel() {
		// Style
		$this->start_controls_section(
			'section_style',
			[
				'label'     => __( 'Content', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'motta-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'motta-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'motta-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'motta-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__item' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 24
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-team-member-carousel__item:before',
			]
		);

		$this->add_control(
			'image_heading',
			[
				'label' => __( 'Image', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
			]
		);

		$this->add_responsive_control(
			'image_max_width',
			[
				'label' => __( 'Max-width', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__item img' => 'max-width: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
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
					'{{WRAPPER}} .motta-team-member-carousel__item img' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_responsive_control(
			'img_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', '' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__item img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .motta-team-member-carousel__title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
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
					'{{WRAPPER}} .motta-team-member-carousel__title' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'description_heading',
			[
				'label' => __( 'Description', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .motta-team-member-carousel__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'socials_heading',
			[
				'label' => __( 'Socials', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'socials_typography',
				'selector' => '{{WRAPPER}} .motta-team-member-carousel__socials a',
			]
		);

		$this->add_control(
			'socials_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__socials a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_gap',
			[
				'label' => __( 'Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__socials a' => 'padding-left: {{size}}{{UNIT}}; padding-right: {{size}}{{UNIT}};',
					'{{WRAPPER}} .motta-team-member-carousel__socials' => 'margin-left: -{{size}}{{UNIT}}; margin-right: -{{size}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_spacing',
			[
				'label' => __( 'Spacing', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-carousel__socials' => 'margin-top: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->end_controls_section();

		$this->section_style_carousel();
	}

	protected function section_style_carousel() {
		$this->start_controls_section(
			'section_style_carousel',
			[
				'label'     => __( 'Carousel', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		// Arrows
		$this->add_control(
			'arrow_style_heading',
			[
				'label' => esc_html__( 'Arrows', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sliders_arrow_style',
			[
				'label'        => __( 'Option', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'sliders_arrows_size',
			[
				'label'     => __( 'Size', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_arrows_width',
			[
				'label'     => __( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_arrows_height',
			[
				'label'     => __( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrows_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrow_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrow_bgcolor',
			[
				'label'     => esc_html__( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sliders_arrow_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-swiper-button',
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'sliders_arrows_horizontal_spacing',
			[
				'label'      => esc_html__( 'Horizontal Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => -100,
						'max' => 1170,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_arrows_vertical_spacing',
			[
				'label'      => esc_html__( 'Vertical Spacing', 'motta-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1170,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-team-carousel--elementor .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}} ;',
				],
			]
		);

		// Dots
		$this->add_control(
			'dots_style_heading',
			[
				'label' => esc_html__( 'Dots', 'motta-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sliders_dots_style',
			[
				'label'        => __( 'Option', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'sliders_dots_gap',
			[
				'label'     => __( 'Gap', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_dots_size',
			[
				'label'     => __( 'Size', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sliders_dots_bgcolor',
			[
				'label'     => esc_html__( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets.swiper-pagination--background' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sliders_dot_item_color',
			[
				'label'     => esc_html__( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'sliders_dot_item_active_color',
			[
				'label'     => esc_html__( 'Color Active', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .motta-team-carousel--elementor .swiper-pagination-bullet:hover' => 'background-color : {{VALUE}};',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'sliders_dots_vertical_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 0,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .motta-team-carousel--elementor .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render heading widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$classes = [
			'motta-team-carousel--elementor',
			'motta-carousel--elementor',
			'motta-swiper-carousel-elementor',
			'motta-carousel--swiper',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = array();

		foreach( $settings['items'] as $items ) {
			$settings['image'] = $items['image'];

			$title = $image = '';

			if ( ! empty( $items['link']['url'] ) ) {
				$title = '<a href=' . $items['link']['url'] . '>' . $items['title'] . '</a>';
				$image = '<a href=' . $items['link']['url'] . ' class="motta-team-member-carousel__image">' . Group_Control_Image_Size::get_attachment_image_html( $settings ) . '</a>';
			} else{
				$title = $items['title'];
				$image = Group_Control_Image_Size::get_attachment_image_html( $settings );
			}

			$socials = $this->get_social_icons();
			$socials_html = array();

			foreach( $socials as $key => $social ) {
				if ( empty( $items[ $key ]['url'] ) ) {
					continue;
				}

				$link_key = $this->get_repeater_setting_key( 'link', 'social', $key );
				$this->add_link_attributes( $link_key, $items[ $key ] );
				$this->add_render_attribute( $link_key, 'title', $social['name'] );

				$socials_html[] = sprintf(
					'<a %s>%s</a>',
					$this->get_render_attribute_string( $link_key ),
					Helper::get_svg( $social['name'], 'social' )
				);
			}

			$output[] = sprintf(
				'<div class="motta-team-member-grid__item motta-team-member-carousel__item motta-team-member-carousel__item--%s swiper-slide">%s%s%s%s</div>',
				$items['_id'],
				$image,
				! empty( $items['title'] ) ? '<div class="motta-team-member-grid__title motta-team-member-carousel__title">' . $title . '</div>' : '',
				! empty( $items['description'] ) ? '<div class="motta-team-member-grid__description motta-team-member-carousel__description">' . $items['description'] . '</div>' : '',
				$items['socials_toggle'] ? '<div class="motta-team-member-grid__socials motta-team-member-carousel__socials">' . implode( '', $socials_html ) . '</div>' : ''
			);
		}

		echo sprintf(
			'<div %s>
				<div class="motta-team-member-carousel__list swiper-container">
					<div class="motta-team-member-carousel__wrapper swiper-wrapper">
						%s
					</div>
				</div>
				%s%s
				<div class="swiper-pagination"></div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output ),
			Helper::get_svg('left', 'ui' , [ 'class' => 'motta-swiper-button-prev swiper-button motta-swiper-button' ] ),
			Helper::get_svg('right', 'ui' , [ 'class' => 'motta-swiper-button-next swiper-button motta-swiper-button' ] )
		);
	}
}