<?php
namespace Motta\Addons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

use \Motta\Addons\Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Products Recently Viewed Grid widget
 */
class Products_Recently_Viewed_Grid extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'motta-products-recently-viewed-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '[Motta] Products Recently Viewed Grid', 'motta-addons' );
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
	}

	protected function section_content() {
		$this->section_product_settings_controls();
		$this->section_pagination_settings_controls();
	}

	protected function section_product_settings_controls() {
		$this->start_controls_section(
			'section_products',
			[ 'label' => esc_html__( 'Products', 'motta-addons' ) ]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Limit', 'motta-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'     => esc_html__( 'Column', 'motta-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'1' => esc_html__( '1', 'motta-addons' ),
					'2' => esc_html__( '2', 'motta-addons' ),
					'3' => esc_html__( '3', 'motta-addons' ),
					'4' => esc_html__( '4', 'motta-addons' ),
				],
				'default'   => '4',
			]
		);

		$this->add_control(
			'load_ajax',
			[
				'label'        => __( 'Load With Ajax', 'motta-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Off', 'motta-addons' ),
				'label_on'     => __( 'On', 'motta-addons' ),
				'default'      => '',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'              => __( 'Hide Recently Viewed Empty', 'motta-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'Off', 'motta-addons' ),
				'label_on'           => __( 'On', 'motta-addons' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'section_empty_heading',
			[
				'label'     => esc_html__( 'Empty Product', 'motta-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'empty_product_description',
			[
				'label'       => esc_html__( 'Description', 'motta-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => esc_html__( 'Recently Viewed Products is a function which helps you keep track of your recent viewing history.', 'motta-addons' ),
			]
		);

		$this->add_control(
			'empty_product_text',
			[
				'label'       => esc_html__( 'Button Text', 'motta-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your text', 'motta-addons' ),
				'label_block' => true,
				'default'     => esc_html__( 'Shop Now', 'motta-addons' ),
			]
		);

		$this->add_control(
			'empty_product_link',
			[
				'label'       => esc_html__( 'Button Link', 'motta-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Enter your link', 'motta-addons' ),
				'label_block' => true,
				'default'     => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->end_controls_section();
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
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'motta-products-recently-viewed-grid',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$products_class = [];

		$products_class[] = $settings['load_ajax'] ? 'has-ajax' : 'no-ajax';

		$product_ids     = Utils::get_product_recently_viewed_ids();

		if( empty( $product_ids ) ) {
			$products_class[] =  $settings['hide_empty'] ? 'hide-empty' : '';
		}

		$this->add_render_attribute( 'products_class', 'class', $products_class );

		$atts = array(
			'limit'       => isset( $settings['limit'] ) ? intval( $settings['limit'] ) : '',
			'desc'        => isset( $settings['empty_product_description'] ) ? $settings['empty_product_description'] : '',
			'button_text' => isset( $settings['empty_product_text'] ) ? $settings['empty_product_text'] : '',
			'button_link' => isset( $settings['empty_product_link'] ) ? $settings['empty_product_link'] : '',
			'load_ajax'   => isset( $settings['load_ajax'] ) ? $settings['load_ajax'] : '',
		);

		$this->add_render_attribute( 'products', 'data-settings', wp_json_encode( $atts ) );

		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'products_class' ); ?> <?php echo $this->get_render_attribute_string( 'products' ); ?>>
				<div class="motta-products-recently-viewed__products woocommerce">
					<?php
					if ( empty( $settings['load_ajax'] ) ) {
						$this->get_products( $settings );
					} else {
						?>
						<div class="motta-posts__loading">
							<div class="motta-loading"></div>
						</div>
						<?php
					}
					?>
				</div>
            </div>
        </div>
		<?php
	}

	/**
	 * Get the product deals
	 *
	 * @return string.
	 */
	protected function get_products( $settings ) {
		$product_ids     = Utils::get_product_recently_viewed_ids();

		if ( empty( $product_ids ) ) {
			?>
			<div class="no-products">
				<p><?php echo wp_kses( isset( $settings['empty_product_description'] ) ? $settings['empty_product_description'] : '', wp_kses_allowed_html( 'post' ) ); ?></p>

				<?php echo \Motta\Addons\Helper::control_url( 'empty_button', isset( $settings['empty_product_link'] ) ? $settings['empty_product_link'] : '', isset( $settings['empty_product_text'] ) ? $settings['empty_product_text'] : '', [ 'class' => 'motta-button' ] ); ?>
			</div>
			<?php
		} else {
			$per_page   = intval( $settings['limit'] );
			$query_args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'posts_per_page'      => $per_page,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'post__in'            => Utils::get_product_recently_viewed_ids(),
				'fields'              => 'ids',
				'meta_query'          => WC()->query->get_meta_query(),
				'tax_query'           => WC()->query->get_tax_query(),
			);

			if ( $settings['pagination'] !== 'none' ) {
				$paged                       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$offset                      = ( $paged - 1 ) * $per_page;
				$query_args['offset']        = $offset;
				$query_args['no_found_rows'] = false;
			}

			$products = new \WP_Query( $query_args );

			wc_setup_loop(
				array(
					'columns' => $settings['columns']
				)
			);

			$this->get_loop_products( $products->posts );

			if ( $settings['pagination'] !== 'none' ) {
				$this->pagination_numeric( $products->max_num_pages );
			}
		}

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
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'prev_text' =>\Motta\Addons\Helper::get_svg( 'left', 'ui', 'class=motta-pagination__arrow' ),
				'next_text' =>\Motta\Addons\Helper::get_svg( 'right', 'ui', 'class=motta-pagination__arrow' ),
			);

			$links = paginate_links( $args );

			if ( $links ) {
				echo _navigation_markup( $links );
			}

			?>
        </nav>
		<?php
	}

	/**
	 * Loop over products
	 *
	 * @since 1.0.0
	 *
	 * @param string
	 */
	protected function get_loop_products( $products_ids  ) {
		update_meta_cache( 'post', $products_ids );
		update_object_term_cache( $products_ids, 'product' );

		$original_post = $GLOBALS['post'];

		woocommerce_product_loop_start();

		foreach ( $products_ids as $product_id ) {
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
			wc_get_template_part( 'content', 'product' );
		}

		$GLOBALS['post'] = $original_post; // WPCS: override ok.

		woocommerce_product_loop_end();

		wp_reset_postdata();
		wc_reset_loop();
	}
}
