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
 * Brands widget
 */
class Brands_Grid extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-brands-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Motta - Brands Grid', 'motta-addons' );
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

		// Brands Settings
		$this->start_controls_section(
			'section_blogs',
			[ 'label' => esc_html__( 'Content', 'motta-addons' ) ]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 6,
				'default' => 6,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'limit',
			[
				'label'     => esc_html__( 'Total', 'motta-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 8,
				'min'       => 2,
				'max'       => 50,
				'step'      => 1,
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
					],
				],

			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => esc_html__( 'Hide Empty Brands', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'hide_title',
			[
				'label'        => esc_html__( 'Hide Title', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
			]
		);

		$this->end_controls_section();

		$this->section_pagination_settings_controls();
	}

	protected function section_pagination_settings_controls() {
		$this->start_controls_section(
			'section_pagination_settings',
			[ 'label' => esc_html__( 'Pagination', 'motta-addons' ) ]
		);

		$this->add_control(
			'pagination',
			[
				'label'   => esc_html__( 'Pagination', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'     => esc_html__( 'None', 'motta-addons' ),
					'numberic' => esc_html__( 'Numberic', 'motta-addons' ),
				],
				'default'            => 'none',
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
				'label' => __( 'Brands', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label'     => esc_html__( 'Gap', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid__item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-brands-grid__items' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_spacing_bottom',
			[
				'label'     => esc_html__( 'Spacing Bottom', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid__image' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_height',
			[
				'label'     => esc_html__( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid__image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'items_spacing',
			[
				'label'      => __( 'Padding Item', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-brands-grid__image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'items_border_radius',
			[
				'label'      => __( 'Border Radius', 'motta-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .motta-brands-grid__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motta-brands-grid__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'items_background',
			[
				'label'     => __( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid__image' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'items_title_color',
			[
				'label'     => __( 'Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid__name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .motta-brands-grid__name',
			]
		);

		$this->add_responsive_control(
			'items_title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid__name' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->section_pagination_style();
	}

	protected function section_pagination_style() {
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => __( 'Pagination', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 250,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-brands-grid .woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
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

		$classes = [
			'motta-brands-grid',
		];

		$offset = '';

		// Paginated
		if( $settings['pagination'] !== 'none' ) {
			$page         = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$offset       = ( $page > 0 ) ?  $settings['limit'] * ( $page - 1 ) : 1;
			$totalterms   = wp_count_terms( 'product_brand', array( 'hide_empty' => TRUE ) );
			$max_num_pages   = ceil( $totalterms / $settings['limit'] );
		}

		$atts = [
			'taxonomy'   	=> 'product_brand',
			'hide_empty' 	=> ! empty( $settings['hide_empty'] ) ? 1 : 0,
			'number'     	=> $settings['limit'],
			'orderby'     	=> $settings['orderby'],
			'offset'        => $offset,
		];

		$atts['menu_order'] = false;

		if ( $settings['orderby'] == 'menu_order' ) {
			$atts['menu_order'] = 'asc';
		} elseif ($settings['order'] != ''){
			$atts['order'] = $settings['order'];
		}

		$columns = isset( $settings['columns'] ) ? $settings['columns'] : '6';
		$columns_tablet = isset( $settings['columns_tablet'] ) && ! empty( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : $settings['columns'];
		$columns_mobile = isset( $settings['columns_mobile'] ) && ! empty( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : $settings['columns'];

		$add_col_class = $columns != '5' ? 'col-flex-md-'.(12/$columns) : 'col-flex-md-1-5';
		$add_col_class .= $columns_tablet != '5' ? ' col-flex-sm-'.(12/$columns_tablet) : ' col-flex-sm-1-5';
		$add_col_class .= $columns_mobile != '5' ? ' col-flex-xs-'.(12/$columns_mobile) : ' col-flex-xs-1-5';

		$terms   = get_terms( $atts );

		$output  = array();

		if ( is_wp_error( $terms ) ) {
			return;
		}

		if ( empty( $terms ) || ! is_array( $terms ) ) {
			return;
		}

		foreach ( $terms as $term ) {

			$thumbnail_id = absint( get_term_meta( $term->term_id, 'brand_thumbnail_id', true ) );

			if ( $thumbnail_id ) {

				$image_url = wp_get_attachment_image_src( $thumbnail_id ) ? wp_get_attachment_image_src( $thumbnail_id )[0] : '';

				$settings['image'] = array(
					'url' => $image_url,
					'id'  => $thumbnail_id
				);

				$html = Group_Control_Image_Size::get_attachment_image_html( $settings );

			} else {
				$html = $term->name;
			}

			$title = empty ( $settings['hide_title'] ) ? '<span class="motta-brands-grid__name">'. esc_html( $term->name ) .'</span>' : '';

			$output[] = sprintf(
				'<div class="motta-brands-grid__item %s">' .
				'<a href="%s"><span class="motta-brands-grid__image">%s</span>%s</a>' .
				'</div>',
				esc_attr($add_col_class),
				esc_url(get_term_link( $term->term_id, 'product_brand' )),
			 	$html,
				$title
			);
		}


		$this->add_render_attribute('wrapper', 'class', $classes );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="motta-brands-grid__items" >
				<?php echo implode('', $output ) ?>
			</div>
			<?php
				if( $settings['pagination'] !== 'none' ) {
					echo $this->pagination_numeric( $max_num_pages );
				}
			?>
		</div>
		<?php

	}

	/**
	 * Get pagination numeric
	 *
	 * @return string.
	 */
	protected function pagination_numeric( $max_num_pages ) {
		?>
        <nav class="woocommerce-pagination">
			<?php
			$big  = 999999999;
			$args = array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'total'     => $max_num_pages,
				'end_size'  => 3,
				'mid_size'  => 3,
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'prev_text' => \Motta\Addons\Helper::get_svg( 'left' ),
				'next_text' => \Motta\Addons\Helper::get_svg( 'right' ),
				'type'      => 'list',
			);

			$links = paginate_links( $args );

			if ( $links ) {
				echo paginate_links( $args );
			}
			?>
        </nav>
		<?php
	}
}
