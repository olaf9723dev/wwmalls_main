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
class Team_Member_Grid extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve TeamMemberGrid widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-team-member-grid';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve TeamMemberGrid widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Team Member Grid', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve TeamMemberGrid widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
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
		return [ 'team', 'member', 'grid', 'motta-addons' ];
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
			'tag',
			[
				'label' => esc_html__( 'Tag', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter the tag', 'motta-addons' ),
				'description' => esc_html__( 'Enter tag names, separate by "|". Eg: tag|tag1', 'motta-addons' ),
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
				'default'            => 4,
				'tablet_default'            => 3,
				'mobile_default'            => 2,
				'separator'          => 'after',
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-grid__item' => 'max-width: calc( 100% / {{VALUE}} ); flex: 0 0 calc( 100% / {{VALUE}} );',
				],
			]
		);

		$this->end_controls_section();

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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-grid__wrapper' => 'justify-content: {{VALUE}};',
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
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-grid__item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-team-member-grid__wrapper' => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-grid__item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-team-member-grid__wrapper' => 'margin-top: -{{SIZE}}{{UNIT}}; margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => __( 'Box Shadow', 'motta-addons' ),
				'selector' => '{{WRAPPER}} .motta-team-member-grid__item',
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
					'{{WRAPPER}} .motta-team-member-grid__item img' => 'max-width: {{size}}{{UNIT}} ;',
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
					'{{WRAPPER}} .motta-team-member-grid__item img' => 'margin-bottom: {{size}}{{UNIT}} ;',
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
					'{{WRAPPER}} .motta-team-member-grid__item img' => 'border-radius: {{SIZE}}{{UNIT}}',
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
				'selector' => '{{WRAPPER}} .motta-team-member-grid__title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-grid__title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-team-member-grid__title' => 'margin-bottom: {{size}}{{UNIT}} ;',
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
				'selector' => '{{WRAPPER}} .motta-team-member-grid__description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-grid__description' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .motta-team-member-grid__socials a',
			]
		);

		$this->add_control(
			'socials_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-team-member-grid__socials a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .motta-team-member-grid__socials a' => 'padding-left: {{size}}{{UNIT}}; padding-right: {{size}}{{UNIT}};',
					'{{WRAPPER}} .motta-team-member-grid__socials' => 'margin-left: -{{size}}{{UNIT}}; margin-right: -{{size}}{{UNIT}};',
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
					'{{WRAPPER}} .motta-team-member-grid__socials' => 'margin-top: {{size}}{{UNIT}} ;',
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
		$count = 1;
		foreach( $settings['items'] as $items ) {
			$settings['image'] = $items['image'];
			$class_tag = [];

			if( ! empty( $items['tag'] ) ) {
				$list_tags = explode('|', $items['tag'] );

				if( count( $list_tags ) > 1 ) {
					foreach( $list_tags as $list_tag ) {
						$tags[] = $list_tag;
						$class_tag[] = $list_tag;
					}
				} else {
					$tags[] = $items['tag'];
					$class_tag[] = $items['tag'];
				}
			}
			$class = $count == count($settings['items'] ) ? 'last' : '';

			if ( ! empty( $items['link']['url'] ) ) {
				$title = '<a href=' . $items['link']['url'] . '>' . $items['title'] . '</a>';
				$image = '<a href=' . $items['link']['url'] . ' class="motta-team-member-grid__image">' . Group_Control_Image_Size::get_attachment_image_html( $settings ) . '</a>';
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

			$content_html[] = '<div class="motta-team-member-grid__item motta-team-member-grid__item--' . $items['_id'] . '' . esc_attr( $class ) . ' ' . esc_attr( implode(' ', $class_tag ) ) . '">';
				$content_html[] = $image;
				$content_html[] = ! empty( $items['title'] ) ? '<div class="motta-team-member-grid__title">' . $title . '</div>' : '';
				$content_html[] = ! empty( $items['description'] ) ? '<div class="motta-team-member-grid__description">' . $items['description'] . '</div>' : '';
				$content_html[] = $items['socials_toggle'] ? '<div class="motta-team-member-grid__socials">' . implode( '', $socials_html ) . '</div>' : '';
			$content_html[] = '</div>';

			$count++;
		}

		if( ! empty ( $tags ) ) {
			$tags = array_unique($tags);
			$tag_html[] = '<div class="motta-team-member-grid__tags">';
				$tag_html[] = '<span class="motta-team-member-grid__tags-item active" data-tag="*">' . esc_html__( 'All', 'motta-addons' ) . '</span>';
				foreach( $tags as $tag ) {
					$tag_html[] = '<span class="motta-team-member-grid__tags-item" data-tag=".' . esc_attr( $tag ) . '">' . esc_html( $tag ) . '</span>';
				}
			$tag_html[] = '</div>';

		}

		echo sprintf( '%s<div class="motta-team-member-grid__wrapper">%s</div>',
				implode( '', $tag_html ),
				implode( '', $content_html ),
			);
	}
}