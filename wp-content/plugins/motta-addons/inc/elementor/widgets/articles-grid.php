<?php

namespace Motta\Addons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Articles widget
 */
class Articles_Grid extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-articles-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Motta - Help Center Grid', 'motta-addons' );
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
		return [ 'motta-addons' ];
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

	/**
	 * Section Content
	 */
	protected function section_content() {

		// Articles Settings
		$this->start_controls_section(
			'section_blogs',
			[ 'label' => esc_html__( 'Content', 'motta-addons' ) ]
		);

		$this->add_control(
			'limit',
			[
				'label'     => esc_html__( 'Total Categories', 'motta-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'min'       => 1,
				'max'       => 50,
				'step'      => 1,
				'condition'   => [
					'source' => 'default',
				],
			]
		);

		$this->add_control(
			'article_number',
			[
				'label'     => esc_html__( 'Article Number', 'motta-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition'   => [
					'source' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 4,
				'default' => 3,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'source',
			[
				'label'     => esc_html__( 'Source', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'default'    => esc_html__( 'Default', 'motta-addons' ),
					'custom'     => esc_html__( 'Custom', 'motta-addons' ),
				],
				'default'   => 'default',
				'toggle'    => false,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'help_center_cats',
			[
				'label'       => esc_html__( 'Categories', 'motta-addons' ),
				'placeholder' => esc_html__( 'Click here and start typing...', 'motta-addons' ),
				'type'        => 'motta-autocomplete',
				'default'     => '',
				'label_block' => true,
				'multiple'    => true,
				'source'      => 'motta_help_cat',
				'sortable'    => true,
				'condition'   => [
					'source' => 'custom',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'      => esc_html__( 'Order By', 'motta-addons' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'date'       => esc_html__( 'Date', 'motta-addons' ),
					'count'      => esc_html__( 'Count', 'motta-addons' ),
					'name'       => esc_html__( 'Name', 'motta-addons' ),
					'id'         => esc_html__( 'Ids', 'motta-addons' ),
					'menu_order' => esc_html__( 'Menu Order', 'motta-addons' ),
				],
				'default'    => 'date',
				'condition'   => [
					'source' => 'default',
				],
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
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'orderby',
							'operator' => '!=',
							'value' => 'menu_order',
						],
						[
							'name' => 'source',
							'operator' => '!=',
							'value' => 'custom',
						],
					],
				],

			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => esc_html__( 'Hide Empty Categories', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'separator' 	=> 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Section Style
	 */
	protected function section_style() {

		$this->start_controls_section(
			'section_content_styles',
			[
				'label' => __( 'Articles', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_spacing',
			[
				'label'      => esc_html__( 'Padding', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-articles-grid__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-articles-grid__wrapper' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label'     => __( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-articles-grid__wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_icon_box',
			[
				'label' => __( 'Icon Box', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_box_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-articles-grid__icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_box_typography',
				'selector' => '{{WRAPPER}} .motta-articles-grid__icon',
			]
		);

		$this->add_responsive_control(
			'icon_box_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-articles-grid__icon-box' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_categories',
			[
				'label' => __( 'Categories', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'categories_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-articles-grid__category' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'categories_typography',
				'selector' => '{{WRAPPER}} .motta-articles-grid__category',
			]
		);

		$this->add_responsive_control(
			'categories_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-articles-grid__category' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_list_items',
			[
				'label' => __( 'List Items', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_items_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-articles-grid__list a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'list_items_typography',
				'selector' => '{{WRAPPER}} .motta-articles-grid__list a',
			]
		);

		$this->add_responsive_control(
			'list_items_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-articles-grid__list' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_button',
			[
				'label' => __( 'Button', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .motta-button',
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

		$classes = [
			'motta-articles-grid',
		];

		if( $settings['source'] == 'default' ) {
			$atts = [
				'taxonomy'   	=> 'motta_help_cat',
				'hide_empty' 	=> ! empty( $settings['hide_empty'] ) ? 1 : 0,
				'number'     	=> $settings['limit'],
				'orderby'     	=> $settings['orderby'],
				'fields' => 'slugs'
			];

			$atts['menu_order'] = false;

			if ( $settings['orderby'] == 'menu_order' ) {
				$atts['menu_order'] = 'asc';
			} elseif ($settings['order'] != ''){
				$atts['order'] = $settings['order'];
			}

			$slugs   = get_terms( $atts );
		} else {
			$slugs = $settings['help_center_cats'];
			$slugs = ! empty( $slugs) ? explode(',', $slugs ) : '';
		}

		if ( is_wp_error( $slugs ) || empty( $slugs ) ) {
			return;
		}

		$columns = isset( $settings['columns'] ) ? $settings['columns'] : '4';
		$columns_tablet = isset( $settings['columns_tablet'] ) && ! empty( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : $settings['columns'];
		$columns_mobile = isset( $settings['columns_mobile'] ) && ! empty( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : $settings['columns'];

		$add_col_class = 'col-flex-md-'.(12/$columns);
		$add_col_class .= ' col-flex-sm-'.(12/$columns_tablet);
		$add_col_class .= ' col-flex-xs-'.(12/$columns_mobile);

		$output  = array();

		foreach ( $slugs as $slug ) {
			if( empty( $slug ) ) {
				continue;
			}
			$term = get_term_by('slug', $slug, 'motta_help_cat');

			if ( is_wp_error( $term ) || empty( $term ) ) {
				return;
			}
			// Icon
			$icon_type 	= get_term_meta( $term->term_id, 'motta_help_article_icon_type', true );
			$icon_image = absint( get_term_meta( $term->term_id, 'motta_help_article_icon_image_id', true ) );
			$icon_svg 	= get_term_meta( $term->term_id, 'motta_help_article_icon_svg', true );

			$icon_html = '';

			if ( ! ( empty( $icon_svg ) ) || ! ( empty( $icon_image ) ) ) {
				if ( $icon_type == 'svg' ) {
					$icon_html = '<span class="motta-svg-icon motta-articles-grid__icon">'. \Motta\Addons\Helper::sanitize_svg($icon_svg) .'</span>';
				} elseif ( $icon_type == 'image' ) {
					if ( $icon_image ) {
						$image     = wp_get_attachment_image_src( $icon_image, 'full' );
						$icon_html = '<span class="motta-articles-grid__image"><img src="' . $image['0'] .'" alt="'. $term->name .'"/></span>';
					}
				}

				$icon_html = $icon_html ? '<div class="motta-articles-grid__icon-box">'. $icon_html .'</div>' : '';
			}

			// List item in categories
			$list_item = array();

			$posts = get_posts(
				array(
					'posts_per_page' => $settings['article_number'],
					'post_type' => 'motta_help_article',
					'tax_query' => array(
						array(
							'taxonomy' => 'motta_help_cat',
							'field' => 'term_id',
							'terms' => $term->term_id,
						)
					)
				)
			);

			foreach ( $posts as $post ) {
				$list_item[] = '<li class="motta-articles-grid__list-item">';
				$list_item[] = '<a href="'. get_permalink( $post->ID ) .'">';
				$list_item[] = $post->post_title;
				$list_item[] = '</a>';
				$list_item[] = '</li>';
			}

			$output[] = sprintf(
				'<div class="motta-articles-grid__item %s">' .
				'<div class="motta-articles-grid__wrapper">' .
				'%s' .
				'<div class="motta-articles-grid__category"><a href="%s" class="motta-articles-grid__name">%s</a></div>' .
				'<ul class="motta-articles-grid__list">%s</ul>' .
				'<a href="%s" class="motta-button motta-button--view-more motta-button-primary motta-button--subtle motta-button--color-black motta-button--small"><span class="motta-button__text">%s</span></a>' .
				'</div>'.
				'</div>',
				esc_attr( $add_col_class ),
				$icon_html,
				esc_url( get_term_link( $term->term_id, 'motta_help_cat' ) ),
				esc_html( $term->name ),
				implode('', $list_item ),
				esc_url( get_term_link( $term->term_id, 'motta_help_cat' ) ),
				esc_html__( 'View More', 'motta-addons' )
			);
		}


		$this->add_render_attribute('wrapper', 'class', $classes );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="motta-articles-grid__items" >
				<?php echo implode('', $output ) ?>
			</div>
		</div>
		<?php

	}
}
