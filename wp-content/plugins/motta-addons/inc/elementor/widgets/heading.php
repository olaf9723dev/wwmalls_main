<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
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
class Heading extends Widget_Base {
	use \Motta\Addons\Elementor\Widgets\Traits\Button_Trait;

	/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-heading';
	}

	/**
	 * Get widget title
	 *
	 * Retrieve heading widget title
	 *
	 * @return string Widget title
	 */
	public function get_title() {
		return __( '[Motta] Heading', 'motta-addons' );
	}

	/**
	 * Get widget icon
	 *
	 * Retrieve heading widget icon
	 *
	 * @return string Widget icon
	 */
	public function get_icon() {
		return 'eicon-t-letter';
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
		return [ 'heading', 'title', 'text', 'motta-addons' ];
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
			'section_title',
			[
				'label' => __( 'Title', 'motta-addons' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'motta-addons' ),
				'default' => __( 'Add Your Heading Text Here', 'motta-addons' ),
			]
		);

		$controls = [
			'button_text_label' => __( 'Button Text', 'motta-addons' ),
			'button_text_label_mobile'     => __( 'Button Text Mobile', 'motta-addons'),
			'skin'     => 'subtle',
			'size'      => 'medium',
		];

		$this->register_button_content_controls( $controls );

		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'motta-addons' ),
					'normal'  => __( 'Normal', 'motta-addons' ),
					'medium'  => __( 'Medium', 'motta-addons' ),
					'large'   => __( 'Large', 'motta-addons' ),
				],
			]
		);

		$this->add_control(
			'title_size',
			[
				'label' => __( 'HTML Tag', 'motta-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
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
					'space-between' => [
						'title' => __( 'Between', 'motta-addons' ),
						'icon' => 'eicon-justify-space-between-h',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .motta-heading-elementor' => 'justify-content: {{VALUE}};text-align:{{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Style before Title
		$this->start_controls_section(
			'section_style_content',
			[
				'label'     => __( 'Content', 'motta-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'heading_title',
			[
				'label'     => esc_html__( 'Title', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'motta-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .motta-heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .motta-heading',
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
					'{{WRAPPER}} .motta-heading' => 'padding-bottom: {{size}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'heading_button',
			[
				'label'     => esc_html__( 'Button', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'button_spacing',
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
					'{{WRAPPER}} .motta-button' => 'margin-left: {{size}}{{UNIT}} ;',
				],
			]
		);


		$this->register_button_style_controls($controls);

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

		if ( empty( $settings['title'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', [ 'motta-heading-elementor motta-heading__button-on' ] );

		$this->add_render_attribute( 'title', 'class', [ 'motta-heading', 'motta-heading--' . $settings['size'] ] );

		$this->add_inline_editing_attributes( 'title' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<<?php echo esc_attr( $settings['title_size'] ); ?> <?php echo $this->get_render_attribute_string( 'title' ); ?>>
					<?php echo wp_kses_post( do_shortcode($settings['title']) ); ?>
				</<?php echo esc_attr( $settings['title_size'] ); ?>>
				<?php $this->render_button(); ?>
			</div>
		<?php
	}
}