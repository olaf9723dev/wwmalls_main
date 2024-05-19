<?php
namespace Motta\Addons\Elementor;

use Motta\Addons\Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Products {
	/**
	 * The single instance of the class
	 */
	protected static $instance = null;

	/**
	 * Initialize
	 */
	static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'wp_ajax_nopriv_motta_get_products_tab', [ $this, 'ajax_get_products_tab' ] );
		add_action( 'wp_ajax_motta_get_products_tab', [ $this, 'ajax_get_products_tab' ] );
		add_action( 'wc_ajax_motta_get_products_tab', [ $this, 'ajax_get_products_tab' ] );

		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'deal_progress' ], 2 );

		// Products without Load more button
		add_action( 'wc_ajax_motta_elementor_load_products', [ $this, 'elementor_load_products' ] );

		add_action( 'wc_ajax_motta_load_recently_viewed_products', [ $this, 'ajax_load_recently_viewed_products' ] );
	}

	/**
	 * Ajax load products tab
	 */
	public function ajax_get_products_tab() {
		if ( empty( $_POST['atts'] ) ) {
			wp_send_json_error( esc_html__( 'No query data.', 'motta-addons' ) );
			exit;
		}

		$output = $this->get_content( $_POST['atts'] );

		if( $_POST['atts']['pagination'] ) {
			$output .= Utils::get_pagination( $_POST['atts'] );
		}

		wp_send_json_success( $output );
	}

	/**
	 * Get products loop content.
	 *
	 * @param array $atts
	 * @return string
	 */
	public function get_content( $atts ) {
		$shortcode = new \WC_Shortcode_Products( $atts, $atts['type'] );
		$output = $shortcode->get_content();

		return $output;
	}

	/**
	 * Load products
	 *
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function elementor_load_products() {
		$settings = $_POST['settings'];

		$atts = array(
			'type'			  => isset( $settings['type'] ) ? $settings['type'] : '',
			'columns'         => isset( $settings['columns'] ) ? intval( $settings['columns'] ) : '',
			'products'        => isset( $settings['products'] ) ? $settings['products'] : '',
			'order'           => isset( $settings['order'] ) ? $settings['order'] : '',
			'orderby'         => isset( $settings['orderby'] ) ? $settings['orderby'] : '',
			'per_page'        => isset( $settings['per_page'] ) ? intval( $settings['per_page'] ) : '',
			'limit'        => isset( $settings['limit'] ) ? intval( $settings['limit'] ) : '',
			'category'        => isset( $settings['category'] ) ? $settings['category'] : '',
			'tag'    		  => isset( $settings['tag'] ) ? $settings['tag'] : '',
			'brand'  		  => isset( $settings['brand'] ) ? $settings['brand'] : '',
			'page'            => isset( $_POST['page'] ) ? $_POST['page'] : 1,
			'paginate'        => true,
		);

		$atts['type'] = empty($atts['type']) ? $atts['products'] : $atts['type'];

		$atts['per_page'] = empty($atts['per_page']) ? $atts['limit'] : $atts['per_page'];

		$results = Utils::products_shortcode( $atts );

		if ( ! $results ) {
			return;
		}

		$product_ids = $results['ids'];

		$current_page = $atts['page'] + 1;
		$data_text    = 'data-text=""';
		if ( $results['current_page'] >= $results['total_pages'] ) {
			$current_page = 0;
			$data_text    = esc_html__( 'No products were found', 'motta-addons' );
		}

		$products = '<div class="products-loadmore">';

		ob_start();

		wc_setup_loop(
			array(
				'columns' => $atts['columns']
			)
		);

		Utils::get_template_loop( $product_ids );

		$products .= ob_get_clean();

		$products .= '<span class="page-number" data-page="' . esc_attr( $current_page ) . '" data-text="' . $data_text . '"></span>';

		$products .= '</div>';

		wp_send_json_success( $products );
	}

	/**
	 * Load products recently viewed
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function ajax_load_recently_viewed_products() {
		$settings = $_POST['settings'];

		$atts = array(
			'limit'       => isset( $settings['limit'] ) ? intval( $settings['limit'] ) : '',
			'desc'        => isset( $settings['desc'] ) ? $settings['desc'] : '',
			'button_text' => isset( $settings['button_text'] ) ? $settings['button_text'] : '',
			'button_link' => isset( $settings['button_link'] ) ? $settings['button_link'] : '',
		);

		ob_start();

		Utils::get_recently_viewed_products( $atts );

		$output = ob_get_clean();

		wp_send_json_success( $output );
	}

	public static function deal_progress() {
		global $motta_soldbar, $product;

		if ( ! $motta_soldbar ) {
			return;
		}

		$limit = get_post_meta( $product->get_id(), '_deal_quantity', true );
		$sold  = intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) );
		if( empty( $limit ) ) {
			return;
		}
		?>

		<div class="deal-sold">
			<div class="deal-progress">
				<div class="progress-bar">
					<div class="progress-value" style="width: <?php echo $sold / $limit * 100 ?>%"></div>
				</div>
				<div class="deal-text"><span class="amount"><span class="sold"><?php echo $sold ?></span></span> <?php esc_html_e( 'Sold', 'motta-addons' ) ?>
				</div>
			</div>
		</div>

		<?php
	}
}

Products::instance();