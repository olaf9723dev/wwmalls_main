<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Motta\Addons\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Posts_Carousel extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-posts-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Posts Carousel', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-carousel';
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
		return [ 'post carousel', 'post', 'carousel', 'motta' ];
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

	// Tab Content
	protected function section_content() {
		$this->start_controls_section(
			'section_posts_carousel',
			[ 'label' => __( 'Posts Carousel', 'motta-addons' ) ]
		);

		$this->add_control(
			'content_position',
			[
				'label'      => esc_html__( 'Content Position', 'motta-addons' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'bottom'    => esc_html__( 'On The Bottom', 'motta-addons' ),
					'left' 		=> esc_html__( 'On The Left', 'motta-addons' ),
					'overlay'   => esc_html__( 'Over on an Image', 'motta-addons' ),
				],
				'default'    => 'bottom',
				'prefix_class' => 'motta-posts-grid__content-position--',
			]
		);

		$this->add_control(
			'limit',
			[
				'label'     => __( 'Total', 'motta-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => -1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 6,
			]
		);

		$this->add_control(
			'category',
			[
				'label'    => __( 'Category', 'motta-addons' ),
				'type'     => Controls_Manager::SELECT2,
				'options'  => \Motta\Addons\Elementor\Utils::get_terms_options(),
				'default'  => '',
				'multiple' => true,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'      => esc_html__( 'Order By', 'motta-addons' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'date'       => esc_html__( 'Date', 'motta-addons' ),
					'name'       => esc_html__( 'Name', 'motta-addons' ),
					'id'         => esc_html__( 'Ids', 'motta-addons' ),
					'rand' 		=> esc_html__( 'Random', 'motta-addons' ),
				],
				'default'    => 'date',
			]
		);

		$this->add_control(
			'order',
			[
				'label'      => esc_html__( 'Order', 'motta-addons' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					''     => esc_html__( 'Default', 'motta-addons' ),
					'ASC'  => esc_html__( 'Ascending', 'motta-addons' ),
					'DESC' => esc_html__( 'Descending', 'motta-addons' ),
				],
				'default'    => '',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'length_excerpt',
			[
				'label'     => __( 'Length Excerpt', 'motta-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'default'   => 12,
				'separator' => 'before',
				'condition' => [
					'content_position' => 'left',
				],
			]
		);

		$this->end_controls_section();

		$this->section_content_carousel();
	}

	protected function section_content_carousel() {
		$this->start_controls_section(
			'section_products_carousel',
			[
				'label' => __( 'Carousel Settings', 'motta-addons' ),
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'   => esc_html__( 'Slides to show', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1'    => __( '1', 'motta-addons' ),
					'2'    => __( '2', 'motta-addons' ),
					'3'    => __( '3', 'motta-addons' ),
					'4'    => __( '4', 'motta-addons' ),
					'5'    => __( '5', 'motta-addons' ),
					'6'    => __( '6', 'motta-addons' ),
					'7'    => __( '7', 'motta-addons' ),
				],
				'default'            => 3,
				'frontend_available' => true,
				'toggle'             => false,
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
					'both'             => esc_html__( 'Arrows and Dots', 'motta-addons' ),
					'arrows'           => esc_html__( 'Arrows', 'motta-addons' ),
					'dots'             => esc_html__( 'Dots', 'motta-addons' ),
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

	// Tab Style
	protected function section_style() {
		// Style
		$this->start_controls_section(
			'section_style_posts_grid',
			[
				'label' => __( 'Posts Grid', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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

		$this->end_controls_section();

		// Style

		$this->section_style_carousel();
	}

	protected function section_style_carousel() {
		$this->start_controls_section(
			'section_style_carousel',
			[
				'label' => esc_html__( 'Carousel', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
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
				'default' => [ 'size' => 44, 'unit' => 'px' ],
				'selectors' => [
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .motta-swiper-button' => 'top: {{SIZE}}{{UNIT}} ;',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .swiper-pagination-bullet' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .swiper-pagination-bullet-active' => 'background-color : {{VALUE}};',
					'{{WRAPPER}} .motta-post-carousel--elementor .swiper-pagination-bullet:hover' => 'background-color : {{VALUE}};',
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
					'{{WRAPPER}} .motta-post-carousel--elementor .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav : $settings['navigation_mobile'];

		$classes = [
			'motta-post-grid--elementor',
			'motta-post-carousel--elementor',
			'hfeed',
			'motta-carousel--elementor',
			'motta-swiper-carousel-elementor',
			'motta-carousel--swiper',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = array();

		$args = array(
			'post_type'              => 'post',
			'posts_per_page'         => $settings['limit'],
			'orderby'     			 => $settings['orderby'],
			'ignore_sticky_posts'    => 1,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'suppress_filters'       => false,
		);

		if ($settings['order'] != ''){
			$args['order'] = $settings['order'];
		}

		if ( $settings['category'] ) {
			$args['category_name'] = trim( $settings['category'] );
		}

		$posts = new \WP_Query( $args );

		if ( ! $posts->have_posts() ) {
			return '';
		}

		$index = 0;

		while ( $posts->have_posts() ) : $posts->the_post();
			$post_url = array();

			$image = '';

			$post_url['url'] = esc_url(get_permalink());
			$post_url['is_external'] = $post_url['nofollow'] = '';

			$key_img = 'img_'.$index;

			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );

			if ( $post_thumbnail_id ) {

				$image_src = wp_get_attachment_image_src( $post_thumbnail_id );

				$settings['image'] = array(
					'url' => $image_src ? $image_src[0] : '',
					'id'  => $post_thumbnail_id
				);

				$image = Helper::control_url( $key_img, $post_url, Group_Control_Image_Size::get_attachment_image_html( $settings ), ['class' => 'post-thumbnail'] );
			}

			$output[] = sprintf(
				'<article class="%s">
					%s
					<div class="entry-summary">
						<div class="entry-category">%s</div>
						<div class="entry-title"><a href="%s" rel="bookmark">%s</a></div>
						<div class="entry-excerpt">%s</div>
						<div class="entry-meta">
							<div class="entry-meta__date">%s</div>
							<div class="entry-meta__comments">%s</div>
						</div>
					</div>
				</article>',
				esc_attr( implode( ' ', get_post_class( 'swiper-slide' ) ) ),
				$image,
				get_the_category_list(', ' ),
				esc_url( get_permalink() ),
				get_the_title(),
				\Motta\Helper::get_content_limit( 12, '' ),
				get_the_date(),
				Helper::get_svg( 'comment-mini' ) . get_comments_number()
			);

		$index ++;
		endwhile;

		wp_reset_postdata();

		echo sprintf(
			'<div %s>
				<div class="list-posts swiper-container">
					<div class="list-posts__inner swiper-wrapper">
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