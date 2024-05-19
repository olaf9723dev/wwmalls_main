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
 * Elementor Stores Location widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Store_Locations extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Stores Location widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-store-locations';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve Stores Location widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Store Locations', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve TeamMemberGrid widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-map-pin';
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
		return [ 'stores', 'location', 'locations', 'map', 'motta-addons' ];
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
					'url' => 'https://via.placeholder.com/370x240/f1f1f1',
				],
			]
		);

		$repeater->add_control(
			'location',
			[
				'label' => esc_html__( 'Location', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter location', 'motta-addons' ),
			]
		);

		$repeater->add_control(
			'address',
			[
				'label' => esc_html__( 'Address', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'phone', [
				'label' => esc_html__( 'Phone', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'button_text', [
				'label' => esc_html__( 'Button Text', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'button_link',
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

		$repeater->add_control(
			'hours_text', [
				'label' => esc_html__( 'Hours Text', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$count = apply_filters( 'motta_store_locations_hours_count', 2 );

		if ( $count > 0 ) {
			for ( $i = 1; $i <= $count; $i ++ ) {
				$repeater->add_control(
					'store_hours_' . $i, [
						'label' => esc_html__( 'Store Hours ' . $i, 'motta-addons' ),
						'type' => Controls_Manager::HEADING,
					]
				);

				$repeater->add_control(
					'day_'. $i, [
						'label' => esc_html__( 'Day', 'motta-addons' ),
						'type' => Controls_Manager::TEXT,
					]
				);

				$repeater->add_control(
					'time_'. $i, [
						'label' => esc_html__( 'Time', 'motta-addons' ),
						'type' => Controls_Manager::TEXT,
					]
				);
			}
		}

		$repeater->add_control(
			'tag',
			[
				'label' => esc_html__( 'Tag', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter the tag', 'motta-addons' ),
				'description' => esc_html__( 'Enter tag names, separate by "|". Eg: tag|tag1', 'motta-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'locations',
			[
				'label' => esc_html__( 'Locations', 'motta-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ location }}}',
				'default' => [
					[
						'location'   	=> esc_html__( 'Localtion #1', 'motta-addons' ),
						'address'   	=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => 'https://via.placeholder.com/370x240/f1f1f1'],
						'button_text'    => esc_html__( 'Directions', 'motta-addons' ),
						'button_link'    => ['url' => '#'],
					],
					[
						'location'   	=> esc_html__( 'Localtion #2', 'motta-addons' ),
						'address'   	=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => 'https://via.placeholder.com/370x240/f1f1f1'],
						'button_text'    => esc_html__( 'Directions', 'motta-addons' ),
						'button_link'    => ['url' => '#'],
					],
					[
						'location'   	=> esc_html__( 'Localtion #3', 'motta-addons' ),
						'address'   	=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
						'image'   		=> ['url' => 'https://via.placeholder.com/370x240/f1f1f1'],
						'button_text'    => esc_html__( 'Directions', 'motta-addons' ),
						'button_link'    => ['url' => '#'],
					],
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'              => esc_html__( 'Columns', 'motta-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 6,
				'default'            => 3,
				'separator'          => 'after',
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__item' => 'max-width: calc( 100% / {{VALUE}} ); flex: 0 0 calc( 100% / {{VALUE}} );',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function section_style() {
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
					'{{WRAPPER}} .motta-store-locations__wrapper' => 'justify-content: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'item_style_heading',
			[
				'label' => __( 'Item', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__item' => 'padding: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-store-locations__wrapper' => 'margin: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-store-locations__item:before' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}}; bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}}; width: calc( 100% - ( {{SIZE}}{{UNIT}} * 2 )); height: calc( 100% - ( {{SIZE}}{{UNIT}} * 2 ));',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-store-locations__item:before',
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
					'{{WRAPPER}} .motta-store-locations__item img' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'localtion_heading',
			[
				'label' => __( 'Location', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'location_typography',
				'selector' => '{{WRAPPER}} .motta-store-locations__location',
			]
		);

		$this->add_control(
			'location_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__location' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'location_spacing',
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
					'{{WRAPPER}} .motta-store-locations__location' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'address_heading',
			[
				'label' => __( 'Address', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'address_typography',
				'selector' => '{{WRAPPER}} .motta-store-locations__address',
			]
		);

		$this->add_control(
			'address_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__address' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'address_spacing',
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
					'{{WRAPPER}} .motta-store-locations__address' => 'margin-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'phone_heading',
			[
				'label' => __( 'Phone', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'phone_typography',
				'selector' => '{{WRAPPER}} .motta-store-locations__phone',
			]
		);

		$this->add_control(
			'phone_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__phone' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'phone_spacing',
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
					'{{WRAPPER}} .motta-store-locations__phone' => 'margin-bottom: {{size}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hours_text_heading',
			[
				'label' => __( 'Hours Text', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hours_text_typography',
				'selector' => '{{WRAPPER}} .motta-store-locations__hours-text',
			]
		);

		$this->add_control(
			'hours_text_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__hours-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hours_text_spacing',
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
					'{{WRAPPER}} .motta-store-locations__hours-text' => 'margin-bottom: {{size}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hours_item_heading',
			[
				'label' => __( 'Store Hours', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hours_item_typography',
				'selector' => '{{WRAPPER}} .motta-store-locations__store-hours--item',
			]
		);

		$this->add_control(
			'hours_item_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__store-hours--item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_heading',
			[
				'label' => __( 'Button', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__button, {{WRAPPER}} .motta-store-locations__button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-store-locations__button' => 'color: {{VALUE}};',
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

		$content_html = $tags = $tag_html = [];

		foreach( $settings['locations'] as $locations ) {
			$settings['image'] = $locations['image'];
			$class_tag = [];

			if( ! empty( $locations['tag'] ) ) {
				$list_tags = explode('|', $locations['tag'] );

				if( count( $list_tags ) > 1 ) {
					foreach( $list_tags as $list_tag ) {
						$tags[] = $list_tag;
						$class_tag[] = str_replace(' ', '', $list_tag );
					}
				} else {
					$tags[] = $locations['tag'];
					$class_tag[] = str_replace(' ', '', $locations['tag'] );
				}
			}

			$count = apply_filters( 'motta_store_locations_hours_count', 2 );

			$content_html[] = '<div class="motta-store-locations__item motta-store-locations__item--' . $locations['_id'] . ' ' . esc_attr( implode(' ', $class_tag ) ) . '">';
				$content_html[] = '<div class="motta-store-locations__image">' . Group_Control_Image_Size::get_attachment_image_html( $settings ) . '</div>';
				$content_html[] = '<div class="motta-store-locations__content">';
					$content_html[] = ! empty( $locations['location'] ) ? '<div class="motta-store-locations__location">' . $locations['location'] . '</div>' : '';
					$content_html[] = ! empty( $locations['address'] ) ? '<div class="motta-store-locations__address">' . $locations['address'] . '</div>' : '';
					$content_html[] = ! empty( $locations['phone'] ) ? '<div class="motta-store-locations__phone">' . $locations['phone'] . '</div>' : '';
					$content_html[] = ! empty( $locations['hours_text'] ) ? '<div class="motta-store-locations__hours-text">' . $locations['hours_text'] . '</div>' : '';

					$content_html[] = '<div class="motta-store-locations__store-hours">';
					for ( $i = 1; $i <= $count; $i ++ ) {
						$content_html[] = '<div class="motta-store-locations__store-hours--item">';
						$content_html[] = '<div class="motta-store-locations__store-hours--day">'. $locations['day_'. $i .''] .'</div>';
						$content_html[] = '<div class="motta-store-locations__store-hours--time">'. $locations['time_'. $i .''] .'</div>';
						$content_html[] = '</div>';
					}
					$content_html[] = '</div>';

					$content_html[] = ! empty( $locations['button_text'] ) ? '<a class="motta-button motta-button--small motta-store-locations__button" href="'. esc_url( $locations['button_link']['url'] ) .'">'. $locations['button_text'] .'</a>' : '';

				$content_html[] = '</div>';
			$content_html[] = '</div>';
		}

		if( ! empty ( $tags ) ) {
			$tags = array_unique($tags);
			$tag_html[] = '<div class="motta-store-locations__tags">';
				$tag_html[] = '<span class="motta-store-locations__tags-item active" data-tag="*">' . esc_html__( 'All Stores', 'motta-addons' ) . '</span>';
				foreach( $tags as $tag ) {
					$tag_html[] = '<span class="motta-store-locations__tags-item" data-tag=".' . esc_attr( str_replace(' ', '', $tag ) ) . '">' . esc_html( $tag ) . '</span>';
				}
			$tag_html[] = '</div>';
		}

		echo sprintf( '%s<div class="motta-store-locations__wrapper">%s</div>',
				implode( '', $tag_html ),
				implode( '', $content_html ),
			);
	}
}