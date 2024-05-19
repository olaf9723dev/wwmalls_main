<?php
namespace Motta\Addons\Elementor\Widgets;

use Motta\Addons\Elementor\Base\Products_Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Table widget
 */
class Products_Tabs extends Products_Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-products-tabs';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Products Tabs', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
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
		return [ 'product tabs', 'products', 'tabs', 'grid', 'woocommerce', 'motta-addons' ];
	}

	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->section_content();
		$this->section_style();
	}

	// Tab Content
	protected function section_content() {
		$this->start_controls_section(
			'section_product_tabs',
			[ 'label' => __( 'Product Tabs', 'motta-addons' ) ]
		);

		$this->register_products_controls( [
			'limit' => 10,
		] );

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					3 => __( '3 Columns', 'motta-addons' ),
					4 => __( '4 Columns', 'motta-addons' ),
					5 => __( '5 Columns', 'motta-addons' ),
				],
				'default' => 5,
			]
		);

		$this->add_control(
			'tabs_type',
			[
				'label'   => esc_html__( 'Tabs Type', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'category' => esc_html__( 'Categories', 'motta-addons' ),
					'tag'      => esc_html__( 'Tags', 'motta-addons' ),
					'brand'    => esc_html__( 'Brands', 'motta-addons' ),
					'groups'   => esc_html__( 'Groups', 'motta-addons' )
				],
				'default'   => 'groups',
				'toggle'    => false,
				'separator' => 'before',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is heading', 'motta-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'type',
			[
				'label'   => esc_html__( 'Products', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_options_product_type(),
				'default' => 'recent_products',
				'toggle'  => false,
			]
		);

		$repeater->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_options_product_orderby(),
				'default' => 'menu_order',
				'condition' => [
					'type' => ['featured', 'sale']
				],
			]
		);

		$repeater->add_control(
			'order',
			[
				'label' => __( 'Order', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ASC'  => __( 'Ascending', 'motta-addons' ),
					'DESC' => __( 'Descending', 'motta-addons' ),
				],
				'default' => 'ASC',
				'condition' => [
					'type' => ['featured', 'sale'],
					'orderby!' => ['', 'rand'],
				],
			]
		);

		$repeater->add_control(
			'product_outofstock',
			[
				'label'        => esc_html__( 'Show Out Of Stock Products', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'motta-addons' ),
				'label_off'    => esc_html__( 'Hide', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'groups',
			[
				'label'         => '',
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title' => esc_html__( 'New Arrivals', 'motta-addons' ),
						'type'  => 'recent_products'
					],
					[
						'title' => esc_html__( 'Best Sellers', 'motta-addons' ),
						'type'  => 'best_selling_products'
					],
					[
						'title' => esc_html__( 'Sale Products', 'motta-addons' ),
						'type'  => 'sale_products'
					]
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'groups',
				],
			]
		);

		// Product Cats
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'category', [
				'label'       => esc_html__( 'Category', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options' 	  => \Motta\Addons\Elementor\Utils::get_terms_options( 'product_cat' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'category_tabs',
			[
				'label'         => esc_html__( 'Categories', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [ ],
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'category',
				],
				'title_field'   => '{{{ category }}}',
			]
		);

		// Product Tag
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tag', [
				'label'       => esc_html__( 'Tag', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => \Motta\Addons\Elementor\Utils::get_terms_options( 'product_tag' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'tag_tabs',
			[
				'label'         => esc_html__( 'Tags', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [ ],
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'tag',
				],
				'title_field'   => '{{{ tag }}}',
			]
		);

		// Product Brands
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'brand', [
				'label'       => esc_html__( 'Brand', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options' 	  => \Motta\Addons\Elementor\Utils::get_terms_options( 'product_brand' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'brand_tabs',
			[
				'label'         => esc_html__( 'Brands', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [ ],
				'prevent_empty' => false,
				'condition'     => [
					'tabs_type' => 'brand',
				],
				'title_field'   => '{{{ brand }}}',
			]
		);

		$this->end_controls_section();

		// Pagination Settings
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => esc_html__( 'Pagination', 'motta-addons' ),
			]
		);

		$this->add_control(
			'pagination_enable',
			[
				'label'        => esc_html__( 'Pagination', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'motta-addons' ),
				'label_off'    => esc_html__( 'Hide', 'motta-addons' ),
				'return_value' => 'yes',
				'default'      => '',
				'frontend_available' => true,
			]
		);
		$this->end_controls_section();
	}

	// Tab Style
	protected function section_style() {
		$this->start_controls_section(
			'section_style_tabs',
			[
				'label' => esc_html__( 'Tabs', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tabs_style_heading',
			[
				'label'     => __( 'Tabs', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'tabs_style_heading_style',
			[
				'label' => __( 'Style', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' 	=> __( 'Default', 'motta-addons' ),
					'background' => __( 'Background', 'motta-addons' ),
				],
				'default' => 'default',
				'prefix_class' => 'motta-product-tabs__heading-style--'
			]
		);

		$this->add_control(
			'tab_background_color_box',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.motta-product-tabs__heading-style--background .motta-product-tabs__tabs' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'tabs_style_heading_style' => 'background'
				],
			]
		);

		$this->add_control(
			'tabs_style_item_heading',
			[
				'label'     => __( 'Item', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'tab_background_color_item',
			[
				'label' => __( 'Background Color Active', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.motta-product-tabs__heading-style--background .motta-product-tabs__tabs li.active' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'tabs_style_heading_style' => 'background'
				],
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-tabs__nav li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_color_active',
			[
				'label' => __( 'Color Active', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-tabs__nav li.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .motta-tabs__nav li',
			]
		);

		$this->add_responsive_control(
			'tab_spacing',
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
				'selectors' => [
					'{{WRAPPER}} .motta-tabs__nav li' => 'margin: 0 {{size}}{{UNIT}} ;',
				],
				'condition' => [
					'tabs_style_heading_style' => 'default'
				],
			]
		);

		$this->add_responsive_control(
			'tab_spacing_bottom',
			[
				'label' => __( 'Spacing Bottom', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-product-tabs__tabs' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_products',
			[
				'label' => esc_html__( 'Products', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'products_border',
			[
				'label'     => esc_html__( 'Border', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''                  => esc_html__( 'No Border', 'motta-addons' ),
					'has-border'        => esc_html__( 'Border', 'motta-addons' ),
					'has-border-bottom' => esc_html__( 'Border Bottom Only', 'motta-addons' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links',
			]
		);

		$this->add_control(
			'button_options',
			[
				'label'        => __( 'Extra Options', 'motta-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'motta-addons' ),
				'label_on'     => __( 'Custom', 'motta-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'button_width',
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
					'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]

		);

		$this->add_responsive_control(
			'button_height',
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
					'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]

		);

		$this->start_controls_tabs(
			'button_style_tabs'
		);

		$this->start_controls_tab(
			'button_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'motta-addons' ),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'      => esc_html__( 'Background Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'      => esc_html__( 'Color', 'motta-addons' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_box_shadow_color',
			[
				'label' => __( 'Box Shadow Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links' => '--mt-color__primary--box-shadow: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'motta-addons' ),
			]
		);

			$this->add_control(
				'button_hover_background_color',
				[
					'label' => __( 'Background Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links:hover' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_hover_color',
				[
					'label' => __( 'Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_box_shadow_color_hover',
				[
					'label' => __( 'Box Shadow Hover Color', 'motta-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .woocommerce-navigation__products-tabs .nav-links:hover' => '--mt-color__primary--box-shadow: {{VALUE}}',
					],
				]
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_popover();

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', [
			'motta-product-tabs',
			'motta-product-tabs--elementor',
			'motta-product-tabs__' . $settings['tabs_type'],
			'motta-tabs',
			'motta-tabs--elementor',
		] );

		$tabs = $this->get_tabs_data();
		$query_args = [];
		$query_args['per_page'] = $settings['limit'];

		if ( empty( $tabs ) ) {
			return;
		}

		$this->add_render_attribute( 'panel', 'class', [
			'motta-product-grid',
			'motta-products',
			'motta-product-tabs__panel',
			'motta-tabs__panel',
			'active'
		] );

		$this->add_render_attribute( 'panel', 'data-panel', '1' );

		$products_border = ! empty( $settings['products_border'] ) ? 'catalog-grid--' . $settings['products_border'] : '';
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<ul class="motta-product-tabs__tabs motta-tabs__nav">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<?php
					$tab_key = $this->get_repeater_setting_key( 'tab', 'products_tab', $key );
					$tab['args']['per_page']   = $settings['limit'];
					$tab['args']['pagination'] = $settings['pagination_enable'] == 'yes' ? true : false;
					$this->add_render_attribute( $tab_key, [
						'data-target' => $tab['index'],
						'data-atts'   => json_encode( $tab['args'] ),
					] );

					if ( 1 === $tab['index'] ) {
						$this->add_render_attribute( $tab_key, 'class', 'active' );
						$query_args = $tab['args'];
					}
					?>
					<li <?php echo $this->get_render_attribute_string( $tab_key ) ?>><?php echo esc_html( $tab['title'] ); ?></li>
				<?php endforeach; ?>
			</ul>
			<div class="motta-product-tabs__panels motta-tabs__panels <?php echo esc_attr( $products_border ); ?>">
					<div class="motta-product-tabs__panels-loading">
						<div class="motta-pagination--loading-dots">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
				<div <?php echo $this->get_render_attribute_string( 'panel' ) ?>>
					<?php $query_args['pagination'] = $settings['pagination_enable'] == 'yes' ? true : false; ?>
					<?php echo $this->render_products( $query_args ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get the tabs data.
	 *
	 * @return array
	 */
	protected function get_tabs_data() {
		$settings = $this->get_settings_for_display();
		$index = 1;
		$tabs  = [];

		switch ( $settings['tabs_type'] ) {
			case 'category' :
			case 'tag':
				$tabs_type = $settings[ 'tabs_type' ];
				$taxonomy  = 'category' == $tabs_type ? 'product_cat' : 'product_tag';
				$tabs_key  = $tabs_type . '_tabs';

				if ( empty( $settings[ $tabs_key ] ) ) {
					break;
				}

				foreach( $settings[ $tabs_key ] as $i => $tab ) {
					if ( empty( $tab[ $tabs_type ] ) ) {
						continue;
					}

					$term = get_term_by( 'slug', $tab[ $tabs_type ], $taxonomy );

					if ( ! $term || is_wp_error( $term ) ) {
						continue;
					}

					$args = $this->parse_settings( $tab );
					$args['limit'] = $settings['limit'];
					$args['columns'] = isset( $settings['columns'] ) ? $settings['columns'] : 5;
					unset( $args['title'] );

					$tabs[ $term->slug ] = [
						'index' => $index++,
						'args'  => $args,
						'title' => $term->name,
					];
				}

				break;

			case 'brand':
				$tabs_type = $settings[ 'tabs_type' ];
				$taxonomy  = 'product_brand';
				$tabs_key  = $tabs_type . '_tabs';

				if ( empty( $settings[ $tabs_key ] ) ) {
					break;
				}

				foreach( $settings[ $tabs_key ] as $i => $tab ) {
					if ( empty( $tab[ $tabs_type ] ) ) {
						continue;
					}

					$term = get_term_by( 'slug', $tab[ $tabs_type ], $taxonomy );

					if ( ! $term || is_wp_error( $term ) ) {
						continue;
					}

					$args = $this->parse_settings( $tab );
					$args['limit'] = $settings['limit'];
					$args['columns'] = isset( $settings['columns'] ) ? $settings['columns'] : 5;
					$args['class'] =  'sc_brand,' . $tab[ $tabs_type ];
					unset( $args['title'] );

					$tabs[ $term->slug ] = [
						'index' => $index++,
						'args'  => $args,
						'title' => $term->name,
					];
				}

				break;

			case 'groups' :
				if ( empty( $settings['groups'] ) ) {
					break;
				}

				foreach( $settings['groups'] as $i => $tab ) {
					$args = $this->parse_settings( $tab );
					$args['limit'] = $settings['limit'];
					$args['columns'] = isset( $settings['columns'] ) ? $settings['columns'] : 5;
					unset( $args['title'] );

					$tabs[ $tab['type'] . $i ] = [
						'index' => $index++,
						'args'  => $args,
						'title' => $tab['title'],
					];
				}

				break;
		}

		return $tabs;
	}
}