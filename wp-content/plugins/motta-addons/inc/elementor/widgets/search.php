<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Motta\Addons\Helper;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Search extends Widget_Base {
/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-search';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve heading widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Search', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve heading widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-search';
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
		return [ 'search', 'motta-addons' ];
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

	// Tab Content
	protected function section_content() {
		$this->section_content_search();
	}

	// Tab Style
	protected function section_style() {
		$this->section_style_search();
	}

	protected function section_content_search() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'motta-addons' ) ]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => __( 'Place Holder', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' =>  esc_html__( 'Search for anything', 'motta-addons' ),
			]
		);

		$this->add_control(
			'form_type',
			[
				'label' => __( 'Search For', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'adaptive',
				'options' => [
					'product'  		=> esc_html__( 'Search for products', 'motta-addons' ),
					'post'     		=> esc_html__( 'Search for posts', 'motta-addons' ),
					'adaptive' 		=> esc_html__( 'Search for adaptive', 'motta-addons' ),
					'help-center' 	=> esc_html__( 'Search for help center', 'motta-addons' ),
				],
			]
		);

		$this->add_control(
			'show_categories',
			[
				'label'        => esc_html__( 'Show Categories', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'return_value' => 'none',
				'default'      => '',
			]
		);

		$this->add_control(
			'categories_position',
			[
				'label'                => esc_html__( 'Categories Position', 'motta-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'prefix_class' => 'motta-search__categories-position--',
				'conditions' => [
					'terms' => [
						[
							'name' => 'show_categories',
							'operator' => '!=',
							'value' => ''
						],
					]
				]
			]
		);

		$this->add_control(
			'trending_title',
			[
				'label'   => esc_html__( 'Trending Title', 'motta-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'separator' => 'before',
				'condition'   => [
					'form_type' => 'help-center',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text',
			[
				'label'   => esc_html__( 'Text', 'motta-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'This is trending', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'link', [
				'label'         => esc_html__( 'Link', 'motta-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'motta-addons' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'elements',
			[
				'label'         => esc_html__( 'Treding', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [],
				'prevent_empty' => false
			]
		);

		$this->end_controls_section();
	}

	protected function section_style_search() {
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_skin',
			[
				'label' => __( 'Form Skin', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'base',
				'options' => [
					'base' => __( 'Base', 'motta-addons' ),
					'raised' => __( 'Raised', 'motta-addons' ),
					'smooth' => __( 'Smooth', 'motta-addons' ),
					'ghost' => __( 'Ghost', 'motta-addons' ),
					'subtle' => __( 'Subtle', 'motta-addons' ),
					'text' => __( 'Text', 'motta-addons' ),
				],
				'prefix_class' => 'motta-skin--',
			]
		);

		$this->add_control(
			'form_shape',
			[
				'label' => __( 'Form Shape', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'motta-addons' ),
					'circle' => __( 'Circle', 'motta-addons' ),
					'round' => __( 'Round', 'motta-addons' ),
					'sharp' => __( 'Sharp', 'motta-addons' ),
				],
				'prefix_class' => 'motta-shape--',
			]
		);

		$this->add_responsive_control(
			'form_width',
			[
				'label' => __( 'Width', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1170,
					]
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-search__wrapper' => 'max-width: {{SIZE}}{{UNIT}}; margin: 0 auto;',
				],
			]
		);

		$this->add_control(
			'style_input',
			[
				'label' => __( 'Input', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Form Size', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => [
					'large' => __( 'Large', 'motta-addons' ),
					'medium' => __( 'Medium', 'motta-addons' ),
					'small' => __( 'Small', 'motta-addons' ),
				],
				'prefix_class' => 'motta-search__input-size--',
			]
		);

		$this->add_control(
			'style_button',
			[
				'label' => __( 'Button', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_position',
			[
				'label' => __( 'Button Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'inside' => __( 'Inside', 'motta-addons' ),
					'outside' => __( 'Outside', 'motta-addons' ),
				],
				'prefix_class' => 'motta-search__button-position--',
			]
		);

		$this->add_control(
			'button_display',
			[
				'label' => __( 'Button Display', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => __( 'Icon', 'motta-addons' ),
					'text' => __( 'Text', 'motta-addons' ),
				],
			]
		);

		$this->add_control(
			'button_icon_skins',
			[
				'label' => __( 'Button Icons Skins', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'motta-addons' ),
					'text' => __( 'Text', 'motta-addons' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'button_display',
							'operator' => '==',
							'value' => 'icon'
						],
					]
				]
			]
		);

		$this->add_control(
			'button_icon_position',
			[
				'label'                => esc_html__( 'Button Position', 'motta-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'motta-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'motta-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'prefix_class' => 'motta-search__button-icon-position--',
				'conditions' => [
					'terms' => [
						[
							'name' => 'button_display',
							'operator' => '==',
							'value' => 'icon'
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'button_spacing',
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
					'{{WRAPPER}} .motta-search__button' => 'margin-left: {{SIZE}}{{UNIT}};',
					'.motta-rtl-smart {{WRAPPER}} .motta-search__button' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'button_position',
							'operator' => '==',
							'value' => 'outside'
						],
					]
				]
			]

		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_trending_style',
			[
				'label' => esc_html__( 'Trending', 'motta-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'trending_position',
			[
				'label' => __( 'Trending Position', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => __( 'Inside', 'motta-addons' ),
					'outside' => __( 'Outside', 'motta-addons' ),
				],
				'prefix_class' => 'motta-search__trending-position--',
			]
		);

		$this->add_control(
			'text_bg_color',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .header-search__trending-links li a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .header-search__trending-links li' => 'color: {{VALUE}};',
					'{{WRAPPER}} .header-search__trending .header-search__trending--title' => 'color: {{VALUE}};',
					'{{WRAPPER}}.motta-search__trending-position--outside .header-search__trending-links li' => 'color: {{VALUE}};',
					'{{WRAPPER}}.motta-search__trending-position--outside .header-search__trending-links li:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .header-search__trending-links li a, {{WRAPPER}} .header-search__trending--title',
			]
		);

		$this->add_responsive_control(
			'spacing',
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
					'{{WRAPPER}} .header-search__trending--inside .header-search__trending-links li a' => 'margin-left: {{size}}{{UNIT}} ;',
					'.motta-rtl-smart {{WRAPPER}} .header-search__trending--inside .header-search__trending-links li a' => 'margin-right: {{size}}{{UNIT}} ;',
					'{{WRAPPER}}.motta-search__trending-position--outside .header-search__trending-links li' => 'padding-left: {{size}}{{UNIT}}; padding-right: {{size}}{{UNIT}};',
					'{{WRAPPER}}.motta-search__trending-position--outside .header-search__trending-links' => 'margin-left: -{{size}}{{UNIT}}; margin-right: -{{size}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'spacing_top',
			[
				'label' => __( 'Spacing Top', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.motta-search__trending-position--outside .header-search__trending-links li' => 'padding-top: {{size}}{{UNIT}}; padding-bottom: {{size}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'trending_position',
							'operator' => '==',
							'value' => 'outside'
						],
					]
				]
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

		$this->add_render_attribute( 'wrapper', 'class', [ 'motta-search', 'header-search' ] );

		if ( $settings['button_position'] == 'outside' ) {
			$this->add_render_attribute( 'wrapper', 'class', 'header-search--outside' );
		}

		$this->add_render_attribute( 'form-search', 'class', [ 'motta-search__wrapper', 'motta-custom-button--skin' ] );

		$this->add_render_attribute( 'button', 'class', [ 'motta-search__button', 'motta-button' ] );

		if ( $settings['form_skin'] ) {
			$this->add_render_attribute( 'button', 'class', 'motta-button--' . $settings['form_skin'] );
		}

		if ( $settings['button_display'] && $settings['button_display'] == 'icon' ) {
			$this->add_render_attribute( 'button', 'class', 'motta-button--' . $settings['button_display'] );

			if ( $settings['button_icon_skins'] == 'text' ) {
				$this->add_render_attribute( 'button', 'class', 'motta-button--text' );
			}
		}

		$this->add_render_attribute( 'search-form', 'class', 'header-search__form' );
		$this->add_render_attribute( 'search-form', 'method', 'get' );
		$this->add_render_attribute( 'search-form', 'action', esc_url( home_url( '/' ) ) );

		$this->add_render_attribute( 'search-container', 'class', 'header-search__container' );

		if ( $settings['button_position'] == 'outside' ) {
			$this->add_render_attribute( 'search-container', 'class', 'motta-type--input-text' );
		} else {
			$this->add_render_attribute( 'search-form', 'class', 'motta-type--input-text' );
		}

		$this->add_inline_editing_attributes( 'text' );

		$icon = \Motta\Addons\Helper::get_svg( 'search', 'ui' , [ 'class' => '' ]  );

		$button_text = $settings['button_display'] == 'icon' ? $icon : esc_html__( 'Search', 'motta-addons' );

		$categories_html = '';

		if ( $settings['form_type'] == 'product' || $settings['form_type'] == 'adaptive' ) {
			$type = 'product_cat';
		} elseif ( $settings['form_type'] == 'help-center' ) {
			$type = 'motta_help_cat';
		} else {
			$type = 'category_name';
		}

		$term_slug = 0;

		if ( isset( $_GET['product_cat'] ) ) {
			$term_slug = $_GET['product_cat'];
		}

		if ( isset( $_GET['category_name'] ) ) {
			$term_slug = $_GET['category_name'];
		}

		if ( $settings['show_categories'] ) {
			$divider = '<div class="header-search__divider"></div>';

			$left = $settings['categories_position'] != 'left' ? $divider : '';
			$right = $settings['categories_position'] != 'right' ? $divider : '';

			$categories_html = sprintf(
				'%s<div class="header-search__categories-label">
					<span class="header-search__categories-text">%s%s</span>
				</div>
				%s
				<input class="category-name" type="hidden" name="%s" value="%s">',
				$left,
				esc_html__( 'All Categories', 'motta-addons' ),
				\Motta\Addons\Helper::get_svg( 'select-arrow' ),
				$right,
				esc_attr( $type ),
				isset( $term_slug ) ? $term_slug : 0
			);
		}

		$trending = '';

		$els = $settings['elements'];

		if ( ! empty ( $els ) ) {
			$output = [];

			foreach ( $els as $index => $item ) {

				$text = $item['text'] ? $item['text'] : '';

				$links ='';
				if ( ! empty( $item['link']['url'] ) ) {
					$this->add_link_attributes( 'link-'. $index, $item['link'] );

					$links = '<a ' . $this->get_render_attribute_string( 'link-'. $index ) . ' > ' . $text . '</a>';
				} else {
					$links = $text;
				}

				$output_content = $links;

				$output[] = sprintf( '<li>%s</li>', $output_content );
			}

			$classes = $settings['trending_position'] == 'inside' ? ' header-search__trending--inside' : '';
			$classes .= $settings['form_type'] == 'help-center' && $settings['trending_position'] == 'outside' && ! empty( $settings['trending_title'] ) ? ' show-title' : '';

			$trending_title = $settings['form_type'] == 'help-center' && $settings['trending_position'] == 'outside' && ! empty( $settings['trending_title'] ) ? '<div class="header-search__trending--title">'. $settings['trending_title'] .'</div>' : '';

			$trending = '<div class="header-search__trending'. esc_attr( $classes ) .'">'. $trending_title .'<ul class="header-search__trending-links">'. implode('', $output ) .'</ul></div>';
		}

		if ( $settings['form_type'] == 'product' || $settings['form_type'] == 'adaptive' ) {
			$post_type = 'product';
		} elseif ( $settings['form_type'] == 'help-center' ) {
			$post_type = 'motta_help_article';
		} else {
			$post_type = 'post';
		}

		$button = '<button '. $this->get_render_attribute_string( 'button' ) .' type="submit">'. $button_text .'</button><input type="hidden" name="post_type" class="header-search__post-type" value="'. esc_attr( $post_type ) .'">';

		$left = $settings['categories_position'] == 'left' ? $categories_html : '';
		$right = $settings['categories_position'] == 'right' ? $categories_html : '';

		if ( $settings['button_icon_position'] == 'left' ) {
			$left = $button . $left;
			$button = '';
		}

		$trending_inside = $settings['trending_position'] == 'inside' ? $trending : '';
		$trending_outside = $settings['trending_position'] == 'outside' ? $trending : '';

		echo sprintf( '<div %s>
						<div %s>
							<form %s>
								<div %s>
									%s
									<input type="text" name="s" class="header-search__field" value="%s" placeholder="%s" autocomplete="off">
									%s%s%s
								</div>
								%s
							</form>
						</div>
						%s
						</div>',
						$this->get_render_attribute_string( 'wrapper' ),
						$this->get_render_attribute_string( 'form-search' ),
						$this->get_render_attribute_string( 'search-form' ),
						$this->get_render_attribute_string( 'search-container' ),
						$left,
						esc_attr( get_search_query() ),
						esc_attr( $settings['placeholder'] ),
						$trending_inside,
						$right,
						$this->categories_items(),
						$button,
						$trending_outside
					);
	}

	/**
	 * Get category items.
	 *
	 * @since 1.0.0
	 *
	 * @param string $label
	 * @return void
	 */
	public static function categories_items() {
		if( ! \Motta\Helper::get_option( 'header_search_type' ) ) {
			return;
		}

		$label = esc_html__( 'All Categories', 'motta-addons' );

		if ( \Motta\Helper::get_option( 'header_search_type' ) == 'adaptive' ) {
			$type = 'product';
			$taxonomy = 'product_cat';
		}else {
			$type = \Motta\Helper::get_option( 'header_search_type' );
			$taxonomy = ( \Motta\Helper::get_option( 'header_search_type' ) === 'product' ) ? 'product_cat' : 'category';
		}

		$cats = \Motta\Helper::get_option( 'header_search_' . $type . '_cats' );
		$hide_empty = \Motta\Helper::get_option( 'header_search_cats_empty' ) ? false : true;

		$args = array(
			'taxonomy' => $taxonomy,
			'hide_empty' => $hide_empty,
		);

		if ( \Motta\Helper::get_option( 'header_search_cats_top' ) ) {
			$args['parent'] = 0;
		}

		if ( is_numeric( $cats ) ) {
			$args['number'] = $cats;
		} elseif ( ! empty( $cats ) ) {
			$args['name'] = explode( ',', $cats );
			$args['orderby'] = 'include';
			unset( $args['parent'] );
		}

		$terms = get_terms( $args );
		$terms[]['all_categories'] = array (
			'slug' => '0',
			'name' => $label
		);
		$rows = ceil((count($terms))/3);
		if ( count($terms) % 3 == 0 ) {
			$rows = $rows+1;
		}
		$term_html = [];

		foreach ( $terms as $term ) :
			if ( !empty($term->slug) ) {
				$term_html[] = '<li><a href="' . get_term_link( $term->slug, $taxonomy ) . '" data-slug="'.esc_attr( $term->slug ).'">'.esc_html( $term->name ).'</a></li>';
			} else {
				$term_html[] = '<li><a href="#" class="active" data-slug="0">' . $label . '</a></li>';
			}
		endforeach;

		if ( $terms && ! is_wp_error( $terms ) ) {
			return sprintf(
				'<div class="header-search__categories">
					<div class="header-search__categories-title">
						<span>%s</span>
						%s
					</div>
					<ul class="header-search__categories-container">
					%s
					</ul>
				</div>',
				esc_html__( 'Select Categories', 'motta-addons' ),
				\Motta\Addons\Helper::get_svg( 'close', 'ui', 'class=header-search__categories-close' ),
				implode( '', $term_html )
			);
		}
	}
}