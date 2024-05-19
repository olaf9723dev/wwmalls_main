<?php
namespace Motta\Addons\Elementor\Widgets;

use Motta\Addons\Elementor\Base\Products_Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use \Motta\Addons\Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Table widget
 */
class Products_Listing extends Products_Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-products-listing';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Products Listing', 'motta-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
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
		return [ 'products listing', 'products', 'listing', 'woocommerce', 'motta-addons' ];
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
			[ 'label' => __( 'Products Listing', 'motta-addons' ) ]
		);

		$this->register_products_controls( [
			'limit' => 3,
		] );

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
				'label'   => esc_html__( 'Type', 'motta-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_options_product_type(),
				'default' => 'recent_products',
				'toggle'  => false,
			]
		);

		$repeater->add_control(
			'category', [
				'label'       => esc_html__( 'Category', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options' 	  => Utils::get_terms_options( 'product_cat' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tag', [
				'label'       => esc_html__( 'Tag', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Utils::get_terms_options( 'product_tag' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'brand', [
				'label'       => esc_html__( 'Brand', 'motta-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Utils::get_terms_options( 'product_brand' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'products',
			[
				'label'         => esc_html__( 'Products', 'motta-addons' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [ ],
				'prevent_empty' => false,
				'default' => [
					[
						'title' => esc_html__( 'Recent Products', 'motta-addons' ),
						'type' => 'recent_products',
					],
					[
						'title' => esc_html__( 'Featured Products', 'motta-addons' ),
						'type' => 'featured_products',
					],
					[
						'title' => esc_html__( 'Top Products', 'motta-addons' ),
						'type' => 'top_rated_products',
					],
				],
				'title_field'   => '{{{ title }}}',
			]
		);

		$this->end_controls_section();
	}

	// Tab Style
	protected function section_style() {
		$this->start_controls_section(
			'section_style_products_tabs',
			[
				'label' => esc_html__( 'Products Listing', 'motta-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_style_divider',
			[
				'label' => __( 'Image', 'motta-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'shop_thumbnail',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'     => __( 'Image Width', 'motta-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 0,
					],
					'%' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .motta-products-listing__item .motta-products-listing__image' => 'width: {{SIZE}}{{UNIT}};',
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

		$this->add_render_attribute( 'wrapper', 'class', [
			'motta-products-listing',
			'motta-products-listing--elementor',
			'columns-' . esc_attr( count( $settings['products'] ) ),
		] );

		?>
		<div class="motta-products-listing__heading motta-products-listing__tabs">
			<?php
			foreach ( $settings['products'] as $products => $product ) {
				echo sprintf( '<span data-tabs="%s">%s</span>', esc_attr( $product['type'] ), esc_html( $product['title'] ) );
			} ?>
		</div>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
				foreach ( $settings['products'] as $products => $product ) {
					$attr = [
						'type' 				=> $product['type'],
						'category'    		=> $product['category'],
						'tag'    			=> $product['tag'],
						'product_brands'    => $product['brand'],
						'limit'    			=> $settings['limit'],
					];

					if ( isset( $product['product_outofstock'] ) && empty( $product['product_outofstock'] ) ) {
						$attr['product_outofstock'] = $product['product_outofstock'];
					}

					$product_ids = Utils::products_shortcode( $attr );

					$product_ids = ! empty($product_ids) ? $product_ids['ids'] : 0;
					?>
					<div class="motta-products-listing__items motta-products-listing--<?php echo esc_attr( $product['type'] ); ?>" data-tabs="<?php echo esc_attr( $product['type'] ); ?>">
						<?php
						foreach( $product_ids as $id ) {
							$_product = wc_get_product( $id );
							$price    = $_product->get_price_html();
							$image_id = get_post_thumbnail_id( $id );
							$image    = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image', $settings );
							?>
							<div class="motta-products-listing__item">
								<a class="motta-products-listing__box" href="<?php echo get_permalink( $id ); ?>">
									<?php echo ! empty( $image ) ? sprintf( '<img src="%s" alt="%s" class="motta-products-listing__image">', $image, esc_html( get_the_title( $id ) ) ) : '';; ?>
									<div class="motta-products-listing__content">
										<div class="motta-products-listing__title"><?php echo esc_html( get_the_title( $id ) ); ?></div>
										<div class="motta-products-listing__price price"><?php echo wp_kses_post( $price ); ?></div>
										<?php $this->get_ratings( $_product ); ?>
									</div>
								</a>
							</div>
						<?php } ?>
					</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Get ratings
	 *
	 * @return void
	 */
	protected function get_ratings( $product ) {
		if ( $product->get_rating_count() ) {
			echo '<div class="motta-products-listing__rating">';
				echo wc_get_rating_html( $product->get_average_rating() );
				if( intval( $product->get_review_count() ) > 0 ) {
					?><span class="review-count">(<?php echo esc_html( $product->get_review_count() ); ?>)</span><?php
				}
			echo '</div>';
		}
	}
}