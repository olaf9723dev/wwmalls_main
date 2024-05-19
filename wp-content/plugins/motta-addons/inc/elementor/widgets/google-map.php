<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Google Map widget
 */
class Google_Map extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-google-map';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Google Maps', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['motta'];
	}

	/**
	 * Get widget keywords.
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'google', 'map', 'motta' ];
	}

	/**
	 * Register the widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_google_map',
			[ 'label' => __( 'Google Map', 'motta-addons' ) ]
		);

		$this->add_control(
			'address',
			[
				'label' => __( 'Address', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => _x( 'New York', 'The default address for Google Map', 'motta-addons' ),
				'frontend_available' => true,
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'latlng',
			[
				'label' => __( 'Or enter coordinates', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Latitude, Longitude', 'motta-addons' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'height',
			[
				'label'     => __( 'Height', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'default' => [
					'size' => 600,
				],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-google-map__wapper' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => __( 'Zoom', 'motta-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'frontend_available' => true,
				'render_type' => 'ui',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'address',
			[
				'label'   => __( 'Address', 'motta-addons' ),
				'type'    => Controls_Manager::TEXT,
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'latlng',
			[
				'label' => __( 'Or enter coordinates', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Latitude, Longitude', 'motta-addons' ),
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'info',
			[
				'label' => __( 'Infomation', 'motta-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'render_type' => 'ui',
			]
		);
		$repeater->add_control(
			'phone',
			[
				'label' => __( 'Phone', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'store_time_label_1',
			[
				'label' => __( 'Store Time Label 1', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'store_time_value_1',
			[
				'label' => __( 'Store Time Value 1', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'store_time_label_2',
			[
				'label' => __( 'Store Time Label 2', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'store_time_value_2',
			[
				'label' => __( 'Store Time Value 2', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'store_time_label_3',
			[
				'label' => __( 'Store Time Label 3', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'store_time_value_3',
			[
				'label' => __( 'Store Time Value 3', 'motta-addons' ),
				'type' => Controls_Manager::TEXT,
				'render_type' => 'ui',
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Marker Icon', 'motta-addons' ),
				'type' => Controls_Manager::MEDIA,
				'skin' => 'inline',
				'exclude_inline_options' => [ 'icon' ],
			]
		);

		$this->add_control(
			'markers',
			[
				'label'         => __( 'Markers', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ address }}}',
				'default'       => [
					[ 'address' => _x( 'New York', 'The default address for Google Map', 'motta-addons' ) ]
				],
				'separator'     => 'before',
				'render_type'   => 'ui',
				'prevent_empty' => false,
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_gg_map',
			[
				'label' => __( 'Google Map', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'map_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .motta-google-map' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'map_spacing_left',
			[
				'label'     => esc_html__( 'Spacing Left', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .motta-google-map' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motta-google-map--header' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'map_spacing_right',
			[
				'label'     => esc_html__( 'Spacing Right', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .motta-google-map' => 'ppadding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .motta-google-map--header' => 'padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'map_search',
			[
				'label' => __( 'Search', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hide_search',
			[
				'label' => __( 'Hide Search', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motta-addons' ),
				'label_off'    => esc_html__( 'No', 'motta-addons' ),
				'return_value' => 'none',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .motta-google-map--header' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_width',
			[
				'label'     => esc_html__( 'Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .motta-google-map--search' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'search_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-google-map--search' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);


		$this->add_control(
			'search_bg_color',
			[
				'label'     => __( 'Background Color', 'motta-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .motta-google-map--header' => 'background-color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'map_content',
			[
				'label' => __( 'Content', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'map_content_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-google-map' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'hide_sidebar',
			[
				'label' => __( 'Hide Sidebar', 'motta-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'motta-addons' ),
				'label_off'    => esc_html__( 'No', 'motta-addons' ),
				'return_value' => 'none',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .motta-google-map__markers' => 'display: {{VALUE}};',
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

		$latlng = explode( ',', $settings['latlng'] );

		if ( count( $latlng ) > 1 ) {
			$coordinates = [
				'lat' => floatval( $latlng[0] ),
				'lng' => floatval( $latlng[1] ),
			];
		}

		$api_key = self::get_api_key();

		if ( ! isset( $coordinates ) ) {
			$coordinates = \Motta\Addons\Elementor\Utils::get_coordinates( $settings['address'], $api_key );
		}

		if ( ! empty( $coordinates['error'] ) ) {
			echo $coordinates['error'];
			return;
		}

		if ( isset( $coordinates['address'] ) ) {
			unset( $coordinates['address'] );
		}

		$this->add_render_attribute( 'map', 'id', 'motta-google-map-' . $this->get_id() );
		$this->add_render_attribute( 'map', 'class', ['motta-google-map__wapper'] );
		$this->add_render_attribute( 'map', 'data-location', json_encode( $coordinates ) );

		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key );
		?>
		<div class="motta-google-map--header">
			<div class="motta-google-map--search">
				<span class="motta-svg-icon motta-location-map-icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
						<path d="M16 6.4c2 0 3.872 0.768 5.28 2.176 1.408 1.392 2.176 3.248 2.176 5.232 0 1.968-0.768 3.824-2.176 5.232l-5.28 5.248-5.28-5.248c-1.408-1.392-2.176-3.248-2.176-5.232 0-1.968 0.768-3.824 2.176-5.232s3.28-2.176 5.28-2.176zM16 3.2c-2.736 0-5.456 1.040-7.536 3.104-4.16 4.144-4.16 10.848 0 14.992l7.536 7.504 7.536-7.504c4.16-4.144 4.16-10.848 0-14.992-2.080-2.064-4.8-3.104-7.536-3.104v0z"></path>
						<path d="M16 10.688c-0.816 0-1.632 0.32-2.256 0.944-1.248 1.248-1.248 3.28 0 4.528 0.624 0.624 1.44 0.944 2.256 0.944s1.632-0.32 2.256-0.944c1.248-1.248 1.248-3.28 0-4.528-0.624-0.624-1.44-0.944-2.256-0.944v0z"></path>
					</svg>
				</span>
				<input type="text" class="motta-gm-search__field motta-input--raised" placeholder="<?php esc_html_e('Enter a location', 'motta-addons') ?>">
				<span class="motta-svg-icon motta-seach-map-icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
						<path d="M28.8 26.544l-5.44-5.44c1.392-1.872 2.24-4.192 2.24-6.704 0-6.176-5.024-11.2-11.2-11.2s-11.2 5.024-11.2 11.2 5.024 11.2 11.2 11.2c2.512 0 4.832-0.848 6.688-2.24l5.44 5.44 2.272-2.256zM6.4 14.4c0-4.416 3.584-8 8-8s8 3.584 8 8-3.584 8-8 8-8-3.584-8-8z"></path>
					</svg>
				</span>
			</div>
		</div>
		<div class="motta-google-map motta-google-map--elementor" >

			<div <?php echo $this->get_render_attribute_string( 'map' ) ?>></div>
			<?php if ( $settings['markers'] ) : ?>
				<div class="motta-google-map__markers" aria-hidden="true">
					<?php foreach ( $settings['markers'] as $marker ) : ?>
						<?php
						$info = $marker['info'];
						unset( $marker['info'] );
						$phone = $marker['phone'];
						unset( $marker['phone'] );
						$address = $marker['address'];
						$time_html = '';
						$time_counts = 3;
						for( $i = 1; $i <= $time_counts ; $i++ ) {
							if( ! empty( $marker['store_time_label_' . $i] ) &&  ! empty( $marker['store_time_value_' . $i] ) ) {
								$time_html .= sprintf('<li><label>%s</label><span>%s</span></li>', $marker['store_time_label_' . $i], $marker['store_time_value_' . $i] );
							}
							unset( $marker['store_time_label_' . $i] );
							unset( $marker['store_time_value_' . $i] );
						}

						?>
						<div class="motta-google-map__marker" data-marker="<?php echo esc_attr( json_encode( $marker ) ) ?>">
							<div class="motta-google-map__marker--head">
								<h4><?php echo $address; ?></h4>
								<?php echo wpautop( $info ) ?>
							</div>
							<div class="motta-google-map__marker--foot">
								<?php echo $phone; ?>
								<?php if ( !empty( $time_html ) ) : ?>
									<h5><?php esc_html_e('Regular Store Hours', 'motta-addons'); ?></h5>
									<ul><?php echo $time_html; ?></ul>
								<?php endif; ?>
							</div>
							<div class="motta-google-map__marker--arrow">
								<span class="motta-svg-icon"><svg width="24" height="24" aria-hidden="true" role="img" focusable="false" viewBox="0 0 32 32"><path d="M16 30.112l-15.072-15.040 4.544-4.544 10.528 10.56 10.528-10.56 4.544 4.544z"></path></svg></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template() {

	}

	/**
	 * Get google map api key from theme option.
	 *
	 * @return string
	 */
	public static function get_api_key() {
		return esc_html( get_option( 'elementor_google_maps_api_key' ) );
	}
}
