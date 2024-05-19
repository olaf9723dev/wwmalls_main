<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Stack;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Motta\Addons\Helper;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Posts_Grid extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-posts-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Posts Grid', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
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
		return [ 'post grid', 'post', 'grid', 'motta' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_posts_grid',
			[ 'label' => __( 'Posts Grid', 'motta-addons' ) ]
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

		$this->add_responsive_control(
			'horizontal_scrollable',
			[
				'label' => esc_html__( 'Horizontal Scrollable', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => esc_html__( 'Yes', 'motta-addons' ),
				'label_off' => esc_html__( 'No', 'motta-addons' ),
				'prefix_class' => 'motta%s-posts-grid-scroll--',
				'separator' => 'before',
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
				'default'   => 3,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'              => esc_html__( 'Columns', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 9,
				'step'				 => 0.5,
				'default'            => 3,
				'separator'          => 'after',
				'selectors' => [
					'{{WRAPPER}} .motta-post-grid--elementor .hentry' => 'max-width: calc( 100% / {{VALUE}} ); flex: 0 0 calc( 100% / {{VALUE}} );',
				],
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
				'conditions' => [
					'terms' => [
						[
							'name' => 'description_item',
							'operator' => '==',
							'value' => 'yes'
						],
					]
				]
			]
		);

		$this->add_control(
			'category_item',
			[
				'label' => esc_html__( 'Show Category', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => esc_html__( 'Yes', 'motta-addons' ),
				'label_off' => esc_html__( 'No', 'motta-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_item',
			[
				'label' => esc_html__( 'Show Description', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__( 'Yes', 'motta-addons' ),
				'label_off' => esc_html__( 'No', 'motta-addons' ),
			]
		);


		$this->add_control(
			'meta_item',
			[
				'label' => esc_html__( 'Show Meta', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => esc_html__( 'Yes', 'motta-addons' ),
				'label_off' => esc_html__( 'No', 'motta-addons' ),
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_style_posts_grid',
			[
				'label' => __( 'Posts Grid', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'gap',
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
				'selectors' => [
					'{{WRAPPER}} .motta-post-grid--elementor' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .motta-post-grid--elementor .hentry' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.motta-posts-grid__content-position--overlay .hfeed .hentry .entry-summary' => 'left: calc({{SIZE}}{{UNIT}}/2); right: calc({{SIZE}}{{UNIT}}/2); width: calc(100% - {{SIZE}}{{UNIT}})',
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
				'selector' => '{{WRAPPER}} .hentry .entry-title',
			]
		);

		$this->add_control(
			'desc_heading',
			[
				'label' => __( 'Description', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'selector' => '{{WRAPPER}} .hentry .entry-excerpt',
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

		$classes = [
			'motta-post-grid--elementor',
			'hfeed',
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

			$category_item = $settings['category_item'] == 'yes' ? sprintf('<div class="entry-category">%s</div>', get_the_category_list(', ' )) : '';
			$desc = class_exists('\Motta\Helper') ? \Motta\Helper::get_content_limit( $settings['length_excerpt'], '' ) : get_the_excerpt();
			$desc_item = $settings['description_item'] == 'yes' ? sprintf('<div class="entry-excerpt">%s</div>', $desc ) : '';
			$meta_item = '';
			if( $settings['meta_item'] == 'yes' ) {
				$meta_item = sprintf(
					'<div class="entry-meta">
						<div class="entry-meta__date">%s</div>
						<div class="entry-meta__comments">%s</div>
					</div>',
					get_the_date(),
					Helper::get_svg( 'comment-mini' ) . get_comments_number()
				);
			}

			$output[] = sprintf(
				'<article class="%s">
					%s
					<div class="entry-summary">
						%s
						<div class="entry-title"><a href="%s" rel="bookmark">%s</a></div>
						%s
						%s
					</div>
				</article>',
				esc_attr( implode( ' ', get_post_class() ) ),
				$image,
				$category_item,
				esc_url( get_permalink() ),
				get_the_title(),
				$desc_item,
				$meta_item

			);

		$index ++;
		endwhile;

		wp_reset_postdata();

		echo sprintf(
			'<div %s>%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output )
		);
	}
}