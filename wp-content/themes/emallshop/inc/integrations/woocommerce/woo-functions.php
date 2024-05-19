<?php 
/**
 * EmallShop Woocommerce Customizer Functions
 *
 * @package PressLayouts
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 	woocommerce fucntions and customize

/**
 * Add custom classes to the array of body classes.
 */
function emallshop_body_woocommerce_classes( $classes ) {
	
	if( is_cart() && emallshop_get_option( 'cart-auto-update', 1 ) ) {
		$classes[] = 'has-auto-update-cart';
	}
	
	$classes = apply_filters( 'emallshop_body_woocommerce_classes', $classes );
	
	return $classes;
}

/*  Prodcut live search form
 *
 * @ since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_products_live_search_form' ) ) :
	function emallshop_products_live_search_form( $arg="es_product_cat" ) {
		if(!is_woocommerce_activated()):
			return false;
		endif;?>
	<div class="search-area">
		<form method="get" class="search-header-form woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
			<div class="search-control-group">				
				<input type="search" class="search-field"  name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( emallshop_get_option('search-placeholder-text', 'I\'m shopping for...' ) ); ?>"/>
				<div class="search-categories">
				<?php
					$selected_cat = isset($_GET['product_cat']) ? $_GET['product_cat'] : '';     
					
					$args = array(
					  'name'         => 'product_cat',
					  'value_field'  =>'slug',
					  'class'        => 'categories-filter selectBox product_cat',
					  'id'        	 => $arg,
					  'show_option_none' => esc_html__( 'All Categories','emallshop' ),
					  'option_none_value' => '',
					  'hide_empty'   => 1,
					  'orderby'      => 'name',
					  'order'        => 'asc',
					  'echo'         => 0,
					  'taxonomy'     => 'product_cat',
					);
					
					if($selected_cat !=''):
						$args['selected'] = $selected_cat;
					else:
						$args['selected'] = 0;
					endif;
					
					if(emallshop_get_option('search-categories','all') =='parent'):
						$args['depth'] = 1;
					endif;
					
					if( emallshop_get_option('categories-hierarchical', 1) ==1):
						$args['hierarchical'] = true;
					endif;
					
					if(emallshop_get_option('show-categories-dropdow', 1) ==1):
						echo wp_dropdown_categories( $args );
					endif;
					?>
				</div>
				<div class="input-search-btn">
					<button type="submit" class="search-btn"></button>
					<input type="hidden" name="post_type" value="product" />
				</div>
			</div>
			<div class="live-search-results"></div>
		</form>
	</div><?php
	}
endif;

/* 	Prodcut live search
 *
 * @ since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_products_live_search' ) ) :
	function emallshop_products_live_search() {
		check_ajax_referer( 'emallshop-ajax-nonce', 'nonce' );
		$products = array();
		$sku_products = array();	
		
		$products = emallshop_ajax_search_products();
		$sku_products =emallshop_get_option('enable-search-by-sku', 0)
		  ? emallshop_ajax_search_products_by_sku()
		  : array();			

		$results = array_merge( $products, $sku_products );

		$suggestions = array();

		foreach ( $results as $key => $post ) { 
			$product 		= wc_get_product( $post );
			$product_image 	= wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ) );

			$suggestions[] = array(
						'id' 	=> esc_attr( $product->get_id() ),
						'value' => esc_attr( $product->get_title() ),
						'url' 	=> esc_url( $product->get_permalink() ),
						'img' 	=> esc_url( $product_image[0] ),
						'price' => wp_kses_post( $product->get_price_html() ),
					);
			
		}

		if ( empty( $results ) ) {			
			$no_results 	= esc_html__( 'No products found.', 'emallshop' );
			$suggestions[] 	= array(
						'id' 	=> - 1,
						'value' => $no_results,
						'url' 	=> '',
					);
		}

		echo json_encode( array( 'suggestions' => $suggestions ) );
		die();
	}
endif;

/* 	Search for products.
 *
 * @ since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_ajax_search_products' ) ) {
	function emallshop_ajax_search_products ( ) {
		global $woocommerce;
		$ordering_args = $woocommerce->query->get_catalog_ordering_args( 'title', 'asc' );
		
		// Add products to the results.
		$args = array(
			's'					=> $_REQUEST['query'],
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product',
			'posts_per_page' 	=> -1,
			'ignore_sticky_posts'=> 1,
			'orderby' 			=> $ordering_args['orderby'],
			'order' 			=> $ordering_args['order'],
			'tax_query' 		=> WC()->query->get_tax_query(),
			'meta_query'	 	=> WC()->query->get_meta_query(),
		);

		if ( isset( $_REQUEST['product_cat'] ) ) {
			$args['tax_query'][] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => esc_attr( $_REQUEST['product_cat'] )
			)
			);
		}

		$search_query 	= http_build_query( $args );		
		$query_results 	= new WP_Query( $search_query );
		
		return $query_results->get_posts();	
	}
}

/* 	Search for products.
 *
 * @ since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_ajax_search_products_by_sku' ) ) {
	function emallshop_ajax_search_products_by_sku () {
	  $query = apply_filters( 'emallshop_ajax_search_products_by_sku_search_query', esc_attr( $_REQUEST['query'] ) );
	  
	  $query_args = array(
		'post_status' => 'publish',
		'post_type' => 'product',
		'meta_query' => array(
		  array(
			'key' => '_sku',
			'value' => $query,
		  )
		),
		'tax_query' => array(
			'relation' => 'AND',
		),
	  );

	  $query_args = emallshop_ajax_search_catalog_visibility( $query_args );
	  $query_results = new WP_Query($query_args);

	  return $query_results->get_posts();
	}
}

if( ! function_exists( 'emallshop_ajax_search_catalog_visibility' ) ) {
	function emallshop_ajax_search_catalog_visibility( $query_args ) {
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();

		$query_args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field' => 'term_taxonomy_id',
			'terms' => $product_visibility_term_ids['exclude-from-search'],
			'operator' => 'NOT IN',
		);
		$query_args['post_parent'] = 0;

		return $query_args;
	}
}

/* 	Ensure cart contents update when products are added to the cart via AJAX
 *
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_header_cart_count_fragment' ) ) {
	function emallshop_header_cart_count_fragment( $fragments ) {
		$header_style 	= emallshop_get_option( 'header-layout', 'header-1' );
		$count 				= is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$subtotal 		= WC()->cart->get_cart_subtotal();
		
		ob_start();		
		if( 'header-1' ==  $header_style || 'header-2' == $header_style || 'header-3' == $header_style || 'header-11' == $header_style ){?>			
			<samp class="mini-cart-count"><?php echo wp_kses_post( $count );?></samp>						
		<?php }elseif( 'header-4' == $header_style || 'header-5' == $header_style || 'header-6' == $header_style || 'header-7' == $header_style || 'header-8' == $header_style || 'header-9' == $header_style ){?>			
			<span class="mini-cart-count"><?php echo sprintf(_n('%d item', '%d item(s)', $count, 'emallshop'), $count);?> - <?php echo wp_kses_post( $subtotal ); ?></span>
		<?php }
		
		$fragments['.mini-cart-count'] = ob_get_clean();		
		$fragments['.cart-count'] = '<span class="cart-count">'. $count .'</span>';
		
		return $fragments;
	}
	add_filter( 'woocommerce_add_to_cart_fragments', 'emallshop_header_cart_count_fragment' );
}

if( ! function_exists( 'emallshop_topbar_cart_fragment' ) ) {
	function emallshop_topbar_cart_fragment( $fragments ) {
		global $woocommerce;
		$cart_url = wc_get_cart_url();
		$count 				= is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		ob_start();?>
		<span class="topbar-cart">
			<a href="<?php echo esc_url($cart_url);?>">
				<i class="fa fa-shopping-cart"></i>
				<label class="mini-cart-count"><?php echo wp_kses_post( $count );?></label>
				<span class="header-cart-text"><?php esc_html_e('Shopping Cart','emallshop');?></span>
			</a>
		</span>
		<?php
		$fragments['span.topbar-cart'] = ob_get_clean();
		return $fragments;
	}
	add_filter('woocommerce_add_to_cart_fragments', 'emallshop_topbar_cart_fragment');
}
	
/* 	Ajax Count Wishlist Product
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_ajax_wishlist_count' ) ) {
	function emallshop_ajax_wishlist_count(){
		check_ajax_referer( 'emallshop-ajax-nonce', 'nonce' );
		if( function_exists( 'YITH_WCWL' ) ){
			wp_send_json( YITH_WCWL()->count_products() );
			die();
		}
	}
	add_action( 'wp_ajax_emallshop_ajax_wishlist_count', 'emallshop_ajax_wishlist_count' );
	add_action( 'wp_ajax_nopriv_emallshop_ajax_wishlist_count', 'emallshop_ajax_wishlist_count' );
}

/* 	Ajax Count Compare Product
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_ajax_compare_count' ) ) {
	function emallshop_ajax_compare_count(){
		check_ajax_referer( 'emallshop-ajax-nonce', 'nonce' );
		if( defined( 'YITH_WOOCOMPARE' ) ){			
			
			$products_list=array();
			$products_list = isset( $_COOKIE[ 'yith_woocompare_list' ] ) && !empty($_COOKIE[ 'yith_woocompare_list' ]) ? maybe_unserialize( $_COOKIE[ 'yith_woocompare_list' ] ) : array();
			$products_list= json_decode($products_list);
			if (!empty($products_list) && $products_list > 0) {
				
				if( isset( $_REQUEST['id'] ) ) {
					if ( $_REQUEST['id'] == 'all' ) {
						unset($products_list);
					} else {
						$products_list=array_diff($products_list, array($_REQUEST['id']));
					}
				}			
				
				echo count($products_list);
			} else {
				echo '0';
			}
		}
		die();
	}
	add_action( 'wp_ajax_emallshop_ajax_compare_count', 'emallshop_ajax_compare_count' );
	add_action( 'wp_ajax_nopriv_emallshop_ajax_compare_count', 'emallshop_ajax_compare_count' );
}

/* User's Login/Register Popup
 *
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_login_popup' ) ) {
	function emallshop_login_popup() {
		if( is_account_page() || is_page('vendor-registration') ){
			return;
		}
		if ( ! shortcode_exists( 'woocommerce_my_account' ) || is_user_logged_in() ) {
			return;
		}?>
		<div id="login-popup" class="modal fade loginPopup popup-modal">
			<div class="modal-dialog">
				<div class="login-content modal-content modal-md">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<?php echo do_shortcode( '[woocommerce_my_account]' ) ?>
				</div>
			</div>		
		</div>
	<?php }
	add_action( 'wp_footer', 'emallshop_login_popup', 5 );
}

/* 	Output the start of page wrapper
/* --------------------------------------------------------------------- */

if( ! function_exists( 'emallshop_output_primary_wrapper' ) ) {
	function emallshop_output_primary_wrapper() {
			
		if( is_single() ):
			$page_layout = get_post_meta ( get_the_ID(), '_emallshop_product_layout', true );
			if($page_layout!="")
				$column_classs = emallshop_woo_page_colunm_class( $page_layout );
			else
				$column_classs = emallshop_woo_page_colunm_class( emallshop_get_option( 'single-product-page-layout', 'none-left' ) );
			
		elseif( is_dokan_activated() && dokan_is_store_page() ):
			$column_classs = emallshop_woo_page_colunm_class( emallshop_get_option( 'dokan-store-page-layout', 'left' ) );
		else:
			$column_classs = emallshop_woo_page_colunm_class( emallshop_get_option( 'shop-page-layout', 'left') );
		endif;
		?>
		<!--<div class="row">-->
        	<div class="content-area <?php echo esc_attr( $column_classs );?>">
	<?php	
	}
}

/* 	shop / single page colunm class
/* --------------------------------------------------------------------- */
if ( !function_exists( 'emallshop_woo_page_colunm_class' ) ) {
	function emallshop_woo_page_colunm_class( $page_layout ){ 
		if(isset( $page_layout ) && ( $page_layout == 'left' || $page_layout == 'right' ) ):
			if( $page_layout == 'left' ):
				if(is_rtl()){
					$column_classs="col-xs-12 col-sm-8 col-md-9 col-sm-pull-4 col-md-pull-3";
				}else{
					$column_classs="col-xs-12 col-sm-8 col-md-9 col-sm-push-4 col-md-push-3";
				}				
			else:
				$column_classs="col-xs-12 col-sm-8 col-md-9";
			endif;
		else:
			$column_classs="col-xs-12 col-sm-12 col-md-12";
		endif;
		
		return $column_classs;
	}
}

/* 	shop / single page sidebar position
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_woo_page_sidebar_position')) {
	function emallshop_woo_page_sidebar_position($page_layout)
	{
		$sidebar_position='';
		if(isset( $page_layout ) && ( $page_layout == 'left' || $page_layout == 'right' ) ):
			if( $page_layout == 'left' ):
				if(is_rtl()){
					$sidebar_position='col-xs-12 col-sm-4 col-md-3 col-sm-push-8 col-md-push-9';
				}else{
					$sidebar_position='col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-9';
				}
			else:
				$sidebar_position='col-xs-12 col-sm-4 col-md-3';
			endif;		
		endif;
		
		return $sidebar_position;
	}
}

/* 	Removes the "shop" title on the main shop page
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_woo_hide_page_title')) {
	function emallshop_woo_hide_page_title() {
		if( emallshop_get_option('show-page-title', 1) && emallshop_get_option('show-title-breadcrumb-content','in-page-heading')=="in-page-content" ){
			return true;
		}else{
			return false;	
		}
	}
}
add_filter( 'woocommerce_show_page_title' , 'emallshop_woo_hide_page_title' );

/* 	Change woocommerce breadcrumb seperator
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_woocommerce_breadcrumbs')) {
	function emallshop_woocommerce_breadcrumbs() {
		return array(
				'delimiter'   => is_rtl() ? ' <span><i class="fa fa-angle-left"></i></span> ' : ' <span><i class="fa fa-angle-right"></i></span> ',
				'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
				'wrap_after'  => '</nav>',
				'before'      => '<span>',
				'after'       => '</span>',
				'home'        => _x( 'Home', 'breadcrumb', 'emallshop' ),
			);
	} 
}
add_filter( 'woocommerce_breadcrumb_defaults', 'emallshop_woocommerce_breadcrumbs' );

/* 	Category Banner
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_caregory_banner')) {
	function emallshop_caregory_banner() {
		
		$cate = get_queried_object();
		$enable_banner=emallshop_get_option('banner-sub-categories-brands',array());
		if( empty($enable_banner) || (!empty($enable_banner) && !in_array('category-banner', $enable_banner)) || !isset($cate->term_id)) return;
		
		$thumbnail_id = get_term_meta( $cate->term_id, 'banner_thumbnail_id', true );
		$image_src = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		if(!empty($image_src)){ ?>		
			<div class="category-banner-content">
				<img class="category-banner" src="<?php echo esc_url($image_src[0]);?>" alt="<?php esc_attr_e('category banner','emallshop');?>"/>
			</div>
		<?php }
	}
}

/* 	Sub Categories slider
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_sub_caregories')) {
	function emallshop_sub_caregories() {
		
		$cate = get_queried_object();
		$enable_banner=emallshop_get_option('banner-sub-categories-brands',array());
		if( empty($enable_banner) || ( !empty($enable_banner) && !in_array('sub-categories', $enable_banner)) || !isset($cate->term_id)) return;
		
		$args = array(
				    'hide_empty' => 1,
					'number' => 12,
					'parent' => $cate->term_id,
				    'taxonomy' => 'product_cat'
				);
		$subcats = get_categories($args);
		if ( !empty($subcats) ) : $lastElement = end($subcats);
			
			$id = uniqid();
			$categories_row=1;
			$category_style=emallshop_get_option('sub-categories-style', 'only_category');
			global $emallshop_owlparam;
			$emallshop_owlparam['productsCarousel']['section-'.$id] = array(
				'autoplay'    => "false",
				'loop'        => "false",
				'navigation'  => "true",
				'dots'  => "false",
				'rp_desktop'     => 4,
				'rp_small_desktop' => 4,
				'rp_tablet'     => 3,
				'rp_mobile'     => 2,
				'rp_small_mobile' => 2,
			);			
			$row=1;?>
			<div id="section-<?php echo esc_attr($id);?>" class="product-section categories-slider-content product-items <?php echo esc_attr($category_style);?>">
				<div class="section-header">
					<div class="section-title">
						<h3><?php esc_html_e("Categories","emallshop");?></h3>
					</div>
				</div>
				<ul class="product-carousel owl-carousel">
					<?php foreach($subcats as $cate):
										
						$cate_link = get_term_link( $cate );
						
						//Get Sub Categories								
						$args['parent']= $cate->term_id;
						$args['number']= 4;
						$inner_subcats = get_categories($args);
						if($category_style=="sub_category_box" && !empty($inner_subcats)){
							if($row==1){?>
								<li class="slide-row">
									<ul>
							<?php }?>							
							
							<li class="category-entry">	
								<h6 class="category-title">
									<a href="<?php echo esc_url($cate_link);?>"><?php echo esc_html($cate->name);?></a>
								</h6>
								<div class="category-image">
									<a href="<?php echo esc_url($cate_link);?>">
										<?php $thumbnail_id = get_term_meta( $cate->term_id, 'thumbnail_id', true );
										$catalog_img = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
										if ( !empty($catalog_img) ) {?>											
											<img src="<?php echo esc_url($catalog_img[0]);?>" alt="<?php echo esc_attr($cate->name);?>" />
										<?php }else{?>
											<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/product-listing-placeholder.jpg');?>"/>
										<?php }?>
									</a>
								</div>
								<div class="sub-categories-list">
									<?php if(!empty($inner_subcats)){?>
										<ul class="sub-categories">
											<?php foreach($inner_subcats as $iner_cate){ 
												$inner_subcat_link = get_term_link( $iner_cate ); ?>
												<li>
													<a href="<?php echo esc_url($inner_subcat_link);?>"><?php echo esc_html($iner_cate->name);?></a>
												</li>
											<?php }?>
											<li class="show-all-cate">
												<a href="<?php echo esc_url($cate_link);?>"><?php echo esc_html__('Show All', 'emallshop');?></a>
											</li>
									</ul>
									<?php }?>
								</div>
							</li>
							<?php if($row==$categories_row || $cate==$lastElement){ $row=0;?>
									</ul>
								</li>
							<?php } $row++;
						}elseif($category_style=="only_category"){
							if($row==1){?>
								<li class="slide-row">
									<ul>
							<?php }?>
								<li class="category-entry">
									<a href="<?php echo esc_url($cate_link);?>">
										<div class="category-image">
											<?php $thumbnail_id = get_term_meta( $cate->term_id, 'thumbnail_id', true );
											$catalog_img = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
											if ( !empty($catalog_img) ) {?>											
												<img src="<?php echo esc_url($catalog_img[0]);?>" alt="<?php echo esc_attr($cate->name);?>" />
											<?php }else{?>
												<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/product-listing-placeholder.jpg');?>"/>
											<?php }?>											
										</div>
										<div class="category-content">
											<h3><?php echo esc_html($cate->name);?></h3>
											<?php echo apply_filters( 'woocommerce_subcategory_count_html', sprintf( '<span class="category-items" />%s %s</span>', $cate->count, esc_html__( 'Items', 'emallshop' ) ), $cate );?>											
										</div>
									</a>
								</li>
							<?php if($row==$categories_row || $cate==$lastElement){ $row=0;?>
									</ul>
								</li>
							<?php } $row++;
						}?>
					<?php endforeach; // end of the loop. ?>
				</ul>
			</div>
		<?php endif;
		wp_reset_query();
	}
}

/* 	Prodcut Brands
/* --------------------------------------------------------------------- */
if ( !function_exists('emallshop_caregory_brands')) {
	function emallshop_caregory_brands() {
		$cate = get_queried_object();
		
		$enable_banner=emallshop_get_option('banner-sub-categories-brands',array());
		if( empty($enable_banner) || (!empty($enable_banner) && !in_array('caregory-brands', $enable_banner)) || !isset($cate->term_id)) return;
		
		$args = array(
					'orderby' => 'ID',
					'order' => 'DESC',
					'number' => 16,
					'hierarchical' => 1,
				    'show_option_none' => '',
				    'hide_empty' => 1,
				    'taxonomy' => 'product_brand'
				);
		
		$brands = get_categories($args);
		
		$id = uniqid();
		$brands_row=1;
		global $emallshop_owlparam;
		$emallshop_owlparam['productsBrands']['section-'.$id] = array(
			'item_columns'     => 6,
			'autoplay'    => 'false',
			'loop'        => 'false',
			'navigation'  => 'true',
			'dots'  	  => 'false',
		);		
		$row=1;?>
		<?php if ( !empty($brands) ) :?>
		<div id="section-<?php echo esc_attr($id);?>" class="products_brands-content product-section products_brands">
			<div class="section-header">
				<div class="section-title">
					<h3><?php esc_html_e("Brands","emallshop");?></h3>
				</div>
			</div>			
			<?php $row=1;?>
			<div class="product-items">
				<ul class="brands brands-carousel owl-carousel">
				<?php foreach($brands as $brand): ?>
					<?php if($row==1){?>
						<li class="slide-row">
							<ul>
					<?php }
						$thumbnail_id = get_term_meta( $brand->term_id, 'thumbnail_id', true ) ;
						$image_src = wp_get_attachment_image_src( $thumbnail_id, 'full' ) ;
						$brand_link = get_term_link( $brand, 'product_brand' ) ;?>
						<li class="brand-item">
							<a href="<?php echo esc_url($brand_link) ?>">									
							<?php if ( !empty($image_src) ) {?>
								<img class="lazyOwl" alt="<?php echo esc_attr($brand->cat_name)?>" src="<?php echo esc_url($image_src[0])?>"/>
							<?php }else{?>
								<img src="<?php echo esc_url(EMALLSHOP_IMAGES.'/brand-placeholder.jpg');?>"/>
							<?php }?>									
							</a>
						</li>
					<?php if($row==$brands_row){ $row=0;?>
						</ul>
							</li>
					<?php } $row++;?>
				<?php endforeach; // end of the loop. ?>
				</ul>
			</div>
			<?php wp_reset_query();?>
		</div>
	<?php endif;
	}
}

/* 	Grid / List view toggle
 * 
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
function emallshop_grid_list_view() {
	if(!emallshop_get_option('show-grid-list-button', 1)) return; ?>	
	
	<div class="gridlist-toggle">
		<?php $productViewStyle=emallshop_get_option('product-view-style',array( 'grid', 'list' ));?>
		<?php if(!empty($productViewStyle) && in_array('grid', $productViewStyle)){?>
			<a href="#" class="grid grid-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="grid") ? 'active' : '';?>" title="<?php esc_html_e('View as Grid', 'emallshop'); ?>"><i class="fa fa-th"></i></a>
		<?php }?>
		<?php if(!empty($productViewStyle) && in_array('expand-grid', $productViewStyle)){?>
			<a href="#" class="grid-expand grid-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="expand-grid") ? 'active' : '';?>" title="<?php esc_html_e('View as Expand Grid', 'emallshop'); ?>"><i class="fa fa-th-large"></i></a>
		<?php }?>
		<?php if(!empty($productViewStyle) && in_array('list', $productViewStyle)){?>
			<a href="#" class="list list-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="list") ? 'active' : '';?>" title="<?php esc_html_e('View as List', 'emallshop'); ?>"><i class="fa fa-th-list"></i></a>
		<?php }?>
		<?php if(!empty($productViewStyle) && in_array('thin-list', $productViewStyle)){?>
			<a href="#" class="list-thin list-view <?php echo (emallshop_get_option('product-default-view-style','grid')=="thin-list") ? 'active' : '';?>" title="<?php esc_html_e('View as Thin List ', 'emallshop'); ?>"><i class="fa fa-list"></i></a>	
		<?php }?>
	</div>
	<?php
}

/* 	Change number of products to be displayed 
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_pre_get_products' ) ) {
	function emallshop_pre_get_products() {
		
		$default_products=emallshop_get_option('products-show-per-page','12');		
		
		return $number = isset( $_GET['showproducts'] ) ? absint( $_GET['showproducts'] ) : $default_products;
	}
}
add_filter('loop_shop_per_page', 'emallshop_pre_get_products');
	
/* 	Product show per page 
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_product_show_pager' ) ) {
	function emallshop_product_show_pager() {
		
		if(!emallshop_get_option('show-products-per-page', 1) ) return;
		
		$numbers = array(6, 8, 10, 12, 15, 16, 18, 20, 24, 27, 28, 30, 32, 33, 36, 40, 48, 60, 72, 84, 108, 120 );

		$options   = array();
		$showproducts = get_query_var( 'posts_per_page' );
		if(!$showproducts){
			$showproducts =emallshop_get_option('products-show-per-page','12');	
		}
		foreach ( $numbers as $number ):
			$options[] = sprintf(
				'<option value="%s" %s>%s %s</option>',
				esc_attr( $number ),
				selected( $number, $showproducts, false ),
				$number,'','');
		endforeach;
		?>		
		<form class="show-products-number" method="get">
			<span><?php esc_html_e( 'View', 'emallshop' ) ?>:</span>
			<select name="showproducts" class="selectBox">
				<?php echo implode( '', $options ); ?>
			</select>
			<?php
			foreach( $_GET as $name => $value ) {
				if ( 'showproducts' != $name ) {
					printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $name ), esc_attr( $value ) );
				}
			}
			?>
		</form>
		<?php
	}
}

/* 	Change number or products per row
/* --------------------------------------------------------------------- */
add_filter('loop_shop_columns', 'emallshop_loop_columns');
if (!function_exists('emallshop_loop_columns')) {
	function emallshop_loop_columns() {
		
		return emallshop_get_option('products-per-row','4'); // products per row	
		
	}
}

/* 	Add second thumbnail in products list page
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_loop_product_thumbnail' ) ) {
	function emallshop_template_loop_product_thumbnail(){
		global $post;
		$product_image_hover_style= emallshop_get_option('product-image-hover-style','product-image-style2');
		$id = get_the_ID();
		$gallery = get_post_meta($id, '_product_image_gallery', true);
		$size = 'shop_catalog';
		$thumb_image = get_the_post_thumbnail($id , $size, array('class' => 'front-image'));
		if(!wp_get_attachment_url( get_post_thumbnail_id() )){
			$thumb_image ="<img src='".EMALLSHOP_IMAGES."/product-listing-placeholder.jpg' />";
		}elseif(!$thumb_image) {
			if ( wc_placeholder_img_src() ) {
				$thumb_image = wc_placeholder_img( $size );
			}
		}
		
		$attachment_image = '';
		if (!empty($gallery) && ($product_image_hover_style=="product-image-style2")) {
			$galleries1 = explode(',', $gallery);
			$first_image_id = $galleries1[0];
			$attachment_image = wp_get_attachment_image($first_image_id , $size, false, array('class' => 'back-image'));
		}
		
		if (!empty($gallery) && ($product_image_hover_style=="product-image-style3" || $product_image_hover_style=="product-image-style4" )) {
			$galleries2 = explode(',', $gallery);
			
			$attachment_image ='<ul class="product_image_gallery owl-carousel owl-theme">';
			$attachment_image .='<li>'.$thumb_image.'</li>';
			foreach($galleries2 as $gallery2):
				//$image_id = $gallery2;
				
				$attachment_image .='<li>';
				$attachment_image .= wp_get_attachment_image($gallery2 , $size, false, array());
				$attachment_image .='</li>';
				
			endforeach;
			$attachment_image .='</ul>';			
		}				
		echo wp_kses_post( $thumb_image );
		echo wp_kses_post( $attachment_image );
	}
}

/*  Product image action buttons
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_loop_image_action_buttons' ) ) {
	function emallshop_loop_image_action_buttons() {
		global $post;
		$id = get_the_ID();
		$gallery = get_post_meta($id, '_product_image_gallery', true);
		$product_image_hover_style=  emallshop_get_option('product-image-hover-style','product-image-style2');
		$navigation = ($product_image_hover_style == 'product-image-style3') ? true : false;
		
		if(!empty($gallery) && $navigation):?>
			<div class="product-slider-controls owl-nav post-<?php echo esc_attr($id);?>">
				<div class="owl-prev"></div>
				<div class="owl-next"></div>
			</div>
		<?php endif;?>
		
		<?php if(!emallshop_get_option('show-product-buttons', 1)) return;?>
		
		<div class="product-buttons">
			<?php 
			if( emallshop_get_option('show-wishlist-button', 1) ==1 ): 
				if( function_exists( 'YITH_WCWL' ) ):
					echo do_shortcode('[yith_wcwl_add_to_wishlist]');
				endif;
			endif;
			
			if( emallshop_get_option('show-compare-button', 1) ==1 ): 
				if(defined( 'YITH_WOOCOMPARE' )):
					echo do_shortcode('[yith_compare_button]');
				endif;
			endif;
			
			if( emallshop_get_option('show-quick-view-button', 1) ==1 ): ?>
				<div class="quickview-button">
					<a class="quickview" href="#" data-product_id="<?php echo esc_attr( $post->ID ); ?>"><?php esc_html_e('Quick View','emallshop');?></a>
				</div><?php 
			endif;?>
		</div>
		<?php 
	}
}

/*  Product content action buttons
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_loop_content_action_buttons' ) ) {
	function emallshop_loop_content_action_buttons() {
		global $post;
		
		if(!emallshop_get_option('show-product-buttons', 1)) return;
				
		if( emallshop_get_option('show-wishlist-button', 1)==1 ): 
			if( function_exists( 'YITH_WCWL' ) ):
				echo do_shortcode('[yith_wcwl_add_to_wishlist]');
			endif;
		endif;
		
		if( emallshop_get_option('show-compare-button', 1)==1): 
			if(defined( 'YITH_WOOCOMPARE' )):
				echo do_shortcode('[yith_compare_button]');
			endif;
		endif;
		
		if( emallshop_get_option('show-quick-view-button', 1) ==1 ): ?>
			<div class="quickview-button">
				<a class="quickview" href="#" data-product_id="<?php echo esc_attr( $post->ID ); ?>"><?php esc_html_e('Quick View','emallshop');?></a>
			</div><?php 
		endif;
	}
}

/*  Product action buttons
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_show_product_loop_sale_flash' ) ) {
	function emallshop_show_product_loop_sale_flash() {
		global $post, $product;
		if((!is_product() && !emallshop_get_option('show-product-highlight-labels', 1) ) || (is_product() && !emallshop_get_option('show-single-product-highlight-labels', 1))) return; 
		
		if(is_product()){ //EmallShop 2.0
			$page_layout = get_post_meta ( get_the_ID(), '_emallshop_product_layout', true ); 
			if(isset($page_layout) && $page_layout!="")
				$page_layout=$page_layout;
			else
				$page_layout=emallshop_get_option('single-product-page-layout','none-left');
		}

		if( emallshop_get_option('show-product-highlight-labels', 1) ==1):?>
			<div class="product-highlight <?php echo (is_product()) ? esc_attr($page_layout):'';?>">

				<?php if ( (!is_product() && emallshop_get_option('show-outofstock-product-highlight-label', 1))  || (is_product() && emallshop_get_option('show-single-product-highlight-label-outofstock', 1)) ) :
				
					$outofstock_label	= emallshop_get_option('outofstock-highlight-label-text', esc_html__('Out Of Stock','emallshop')) ;
					$availability = $product->get_availability();
					if ( $availability['class'] == 'out-of-stock') :
						echo apply_filters( 'woocommerce_stock_html', '<div class="' . esc_attr( $availability['class'] ) . '"><span>' . esc_attr($outofstock_label). '</span></div>', $availability['availability'] );
					endif;
				endif;
				
				if ( (!is_product() && emallshop_get_option('show-new-product-highlight-label', 1))  || (is_product() && emallshop_get_option('show-single-product-highlight-label-new', 1))) :
				
					$postdate 		= get_the_time( 'Y-m-d' );			// Post date
					$postdatestamp 	= strtotime( $postdate );			// Timestamped post date
					$newness 		= emallshop_get_option('product-newness-days', 30); 	// Newness in days
					$newness_label	= emallshop_get_option('new-highlight-label-text',esc_html__('New','emallshop')) ;

					if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) { // If the product was published within the newness time frame display the new badge						
						echo apply_filters( 'woocommerce_sale_flash','<div class="new"><span>' . esc_attr($newness_label). '</span></div>',$post, $product );
					}
					
				endif;
				
				if ( (!is_product() && emallshop_get_option('show-featured-product-highlight-label', 1) && $product->is_featured())  || (is_product() && emallshop_get_option('show-single-product-highlight-label-featured', 1) && $product->is_featured()) ) :
				
					$featured_label	= emallshop_get_option('featured-highlight-label-text', esc_html__('Featured','emallshop'));
					echo apply_filters( 'woocommerce_sale_flash','<div class="featured"><span>' . esc_attr($featured_label). '</span></div>',$post, $product );
					
				endif;
				
				if( $product->is_on_sale() && emallshop_get_option('show-sale-product-highlight-label', 1) ) :		
					$percentage = '';
					$sale_percentage_label	= emallshop_get_option('sale-label-percentages-text','percentages');
					if( $product->get_type() == 'variable' && $sale_percentage_label =='percentages' ){				
						$available_variations = $product->get_variation_prices();
						$max_value = 0;
						foreach( $available_variations['regular_price'] as $key => $regular_price ) {					
							$sale_price = $available_variations['sale_price'][$key];					
							if ( $sale_price < $regular_price ) {
								$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
								if ( $percentage > $max_value ) {
									$max_value = $percentage;
								}
							}
						}
						$percentage = $max_value;
						
					} elseif ( ( $product->get_type() == 'simple' || $product->get_type() == 'external' ) && $sale_percentage_label =='percentages' ) {				
						$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
					}
					if ( $percentage ) {	
						$sale_percentage_label_text = emallshop_get_option('sale-highlight-percentages-label-text', 'Off');
						echo '<div class="onsale"><span>'. esc_html($percentage) . '% ' .esc_html( $sale_percentage_label_text ). '</span></div>';
					}else{
						$sale_label_text = emallshop_get_option('sale-highlight-label-text', 'Sale');
						echo '<div class="onsale"><span>' . esc_html( $sale_label_text ) . '</span></div>';
					}
				endif; ?>
			
			</div>
		<?php endif;
	}
}

/*  Get Product's ratting
/* --------------------------------------------------------------------- */
function emallshop_product_rating_html( $rating = null ) {
	global $product;
	if( ! emallshop_get_option( 'show-product-rating', 1 ) || !wc_review_ratings_enabled() ) return; 
	$rating_html = '';
	
	if ( ! is_numeric( $rating ) ) {
        $rating = $product->get_average_rating();
		$review_count = $product->get_review_count();
    }

    $rating_html  = '<div class="product-rating"> <div class="rating-content"> ';
	$rating_html .= '<div class="star-rating" title="' . $rating . '">';

    $rating_html .= '<span style="width:' . ( ( (int)$rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5', 'emallshop' ) . '</span>';
	
	$rating_html .= ' </div>';
	$rating_html .='<span class="product-rating-count">('.$review_count.')</span>';
    $rating_html .= '</div></div>';

    echo wp_kses_post( $rating_html );

}

/*  Product short description
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_product_short_description' ) ) {
	function emallshop_product_short_description() {
		global $post;
		if(!emallshop_get_option('show-short-description', 1)) return; ?>
		<div class="short-description">
			<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt );?>
		</div><?php 
	}
}

/*  Change variation price 
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_variation_price_format' ) ):
	function emallshop_variation_price_format( $price, $product ) {
		// Main Price
		$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
		$price = $prices[0] !== $prices[1] ?  wc_price( $prices[0] ) : wc_price( $prices[0] );
		// Sale Price
		$prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
		sort( $prices );
		//$saleprice = $prices[0] !== $prices[1] ? sprintf( esc_html__( 'From','%1$s', 'emallshop' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
		$saleprice = $prices[0] !== $prices[1] ?  wc_price( $prices[0] ) : wc_price( $prices[0] );
		if ( $price !== $saleprice ) {
		$price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
		}
		return $price;
	}
endif;

/* 	Quick view product
 *
 * @ since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_product_quickview' ) ) {
	function emallshop_product_quickview() {
		check_ajax_referer( 'emallshop-ajax-nonce', 'nonce' );
		global $post, $product, $woocommerce;

		$id      = ( int ) $_POST['pid'];
		$post    = get_post( $id );
		$product = wc_get_product( $id );?>
		
		<div class="woocommerce product-quickview">
			<div id="product-<?php echo esc_attr($id); ?>" class="product">
				<div class="row single-product-entry">
					<div class="col-sm-6">
						<div class="images">
							<div id="product-image" class="emallshop-slick-carousel" data-slick='{"slidesToShow": 1,"slidesToScroll": 1, "fade":true, <?php if ( is_rtl()) echo '"rtl": true'; ?>}'>
								<?php if ( has_post_thumbnail() ) {
									$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
									$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
									$thumbnail_post    = get_post( $post_thumbnail_id );
									$image_title       = $thumbnail_post->post_content;

									$attributes = array(
										'title'                   => $image_title,
										'data-src'                => $full_size_image[0],
										'data-large_image'        => $full_size_image[0],
										'data-large_image_width'  => $full_size_image[1],
										'data-large_image_height' => $full_size_image[2],
									);

									$html  = '<div>';
										$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
									$html .= '</div>';

								} else {
									$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
										$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'emallshop' ) );
									$html .= '</div>';
								}

								echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

								$attachment_ids = $product->get_gallery_image_ids(); 

								if ( $attachment_ids ) {
									foreach ( $attachment_ids as $attachment_id ) {
										$full_size_image  = wp_get_attachment_image_src( $attachment_id, 'full' );
										$thumbnail        = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
										$thumbnail_post   = get_post( $attachment_id );
										$image_title      = $thumbnail_post->post_content;

										$attributes = array(
											'title'                   => $image_title,
											'data-src'                => $full_size_image[0],
											'data-large_image'        => $full_size_image[0],
											'data-large_image_width'  => $full_size_image[1],
											'data-large_image_height' => $full_size_image[2],
										);

										$html = '<div>';									
											$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
										$html .= '</div>';

										echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
									}
								} ?>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="summary entry-summary">
							<?php do_action( 'woocommerce_single_product_summary' ); ?>
						</div>
					</div><!-- .summary -->
				</div>
			</div>
		</div>
		<?php exit;
	}
}
add_action('wp_ajax_emallshop_product_quickview', 'emallshop_product_quickview');
add_action('wp_ajax_nopriv_emallshop_product_quickview', 'emallshop_product_quickview');

if ( ! function_exists( 'emallshop_quantity_button_plus' ) ) :
	/**
	 * Quantity Button Plus
	 */
	function emallshop_quantity_button_plus() { ?>
		<input type="button" value="+" class="plus" />
	<?php }
endif;

if ( ! function_exists( 'emallshop_quantity_button_minus' ) ) :
	/**
	 * Quantity Button Minus
	 */
	function emallshop_quantity_button_minus() { ?>
		<input type="button" value="-" class="minus" />
	<?php }
endif;	

/*  Product attributes
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_output_product_attr' ) ) {
	function emallshop_output_product_attr() {
		global $product;
		$has_row = false;
		$attributes = $product->get_attributes();
		
		$cleverSwatches_settings = get_option('zoo-cw-settings', true);
		$product_swatch_data_array = get_post_meta($product->get_id(), 'zoo_cw_product_swatch_data', true);
		
		if ( empty($attributes) || !emallshop_get_option('show-product-variation', 1) ) return;
		
		ob_start();	?>	
		
		<div class="product-extra-info">
			<div class="product-attrs">
				<?php if ( emallshop_check_plugin_active( 'clever-swatches/clever-swatches.php' ) && ($cleverSwatches_settings['display_shop_page'] == 1 && $cleverSwatches_settings['display_shop_page_hook'] == 'shortcode')) {
					if( $product_swatch_data_array != '' ){
						echo do_shortcode('[zoo_cw_shop_swatch]');
						$has_row = true;
					}
				}else{
					foreach ( $attributes as $attribute ) :
					if ( empty( $attribute['is_visible'] ) || empty( $attribute['is_variation'] )  && ! taxonomy_exists( $attribute['name'] ) ) {
						continue;
					} else {
						$has_row = true;
					}?>
					
					<div class="product-attribute">
					<?php echo wc_attribute_label( $attribute['name'] )." : "; 
					
					if ( $attribute['is_taxonomy'] ) {

						$values = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );
						$lastElement = end($values);
						foreach($values as $value){?>
							<span class="attr-value">
								<?php echo esc_attr($value);?>
							</span><?php 
							if($value != $lastElement) {
								echo ' - ';
							}
						}
					}else{
						// Convert pipes to commas and display values
						$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
						$lastElement = end($values);
						foreach($values as $value){?>
							<span class="attr-value">
								<?php echo esc_attr($value);?>
							</span><?php 
							if($value != $lastElement) {
								echo ' - ';
							}
						}
					}?>
					</div>								
					<?php endforeach;
				}?>
			</div>
		</div>
		<?php
		if ( $has_row ) {
			echo ob_get_clean();
		} else {
			ob_end_clean();
		}
	}
}

/*  Product attributes
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_related_products' ) ) {
	function emallshop_related_products() {
		wc_get_template( 'single-product/related.php' );
	}
}

function emallshop_ajax_add_to_cart(){
	
	// Get messages
	ob_start();
	wc_print_notices();
	$notices = ob_get_clean();
	
	// Get fragments
	// Get mini cart
	ob_start();

	woocommerce_mini_cart();

	$mini_cart = ob_get_clean();
	
	// Fragments and mini cart are returned
	$data = array(
		'notices' => $notices,
		'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
				)
			),
		'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
	);
	wp_send_json( $data );		
    die();
}
add_action('wp_ajax_emallshop_ajax_add_to_cart', 'emallshop_ajax_add_to_cart');
add_action('wp_ajax_nopriv_emallshop_ajax_add_to_cart', 'emallshop_ajax_add_to_cart');

if ( ! function_exists( 'emalshop_update_cart_widget_quantity' ) ){
	/** 
	 * MINI Cart Quantity Update
	 */
	function emalshop_update_cart_widget_quantity(){	
		$cart_item_key 	= sanitize_text_field( $_POST['cart_item_key'] );
		$quantity 		= (float) sanitize_text_field( $_POST['quantity'] );
		if( !empty( $cart_item_key ) ){
			$cart =  WC()->cart->get_cart();
			if( isset($cart[$cart_item_key]) ){
				$quantity = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( '/[^0-9\.]/', '', $quantity ) ), $cart_item_key );
				if( !($quantity === '' || $quantity === $cart[$cart_item_key]['quantity']) ){
					if( !($cart[$cart_item_key]['data']->is_sold_individually() && $quantity > 1) ){
						WC()->cart->set_quantity( $cart_item_key, $quantity, false );
						$cart_updated = apply_filters( 'woocommerce_update_cart_action_cart_updated', true );
						if( $cart_updated ){
							WC()->cart->calculate_totals();
						}
					}
				}
			}
			WC_AJAX::get_refreshed_fragments();
		}	
	}
	add_action( 'wp_ajax_emalshop_update_cart_widget_quantity','emalshop_update_cart_widget_quantity' );
	add_action( 'wp_ajax_nopriv_emalshop_update_cart_widget_quantity','emalshop_update_cart_widget_quantity' );
}

if ( ! function_exists( 'emallshop_update_checkout_quantity' ) ){
	/** 
	 * Checkout Quantity Update
	 */
	function emallshop_update_checkout_quantity(){
		$data = array();
        parse_str($_POST['form_data'], $data);
        $cart 			= $data['cart'];
        $cart_updated 	= false;
		$cart_totals  	= isset( $data['cart'] ) ? wp_unslash( $data['cart'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		
		if ( ! WC()->cart->is_empty() && is_array( $cart_totals ) ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {

				$_product = $values['data'];

				// Skip product if no updated quantity was posted.
				if ( ! isset( $cart_totals[ $cart_item_key ] ) || ! isset( $cart_totals[ $cart_item_key ]['qty'] ) ) {
					continue;
				}

				// Sanitize.
				$quantity = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( '/[^0-9\.]/', '', $cart_totals[ $cart_item_key ]['qty'] ) ), $cart_item_key );

				if ( '' === $quantity || $quantity === $values['quantity'] ) {
					continue;
				}

				// Update cart validation.
				$passed_validation = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $values, $quantity );

				// is_sold_individually.
				if ( $_product->is_sold_individually() && $quantity > 1 ) {
					/* Translators: %s Product title. */
					wc_add_notice( sprintf( __( 'You can only have 1 %s in your cart.', 'emallshop' ), $_product->get_name() ), 'error' );
					$passed_validation = false;
				}

				if ( $passed_validation ) {
					WC()->cart->set_quantity( $cart_item_key, $quantity, false );
					$cart_updated = true;
				}
			}
		}
		
		if ( $cart_updated ) {
            WC()->cart->calculate_totals();            
			WC_AJAX::get_refreshed_fragments();
        }
		die();
	}	
	add_action( 'wp_ajax_emallshop_update_checkout_quantity','emallshop_update_checkout_quantity' );
	add_action( 'wp_ajax_nopriv_emallshop_update_checkout_quantity','emallshop_update_checkout_quantity' );
}

/* 	Get Product info added in cart
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_get_productinfo' ) ) {
	function emallshop_get_productinfo() {
		check_ajax_referer( 'emallshop-ajax-nonce', 'nonce' );
		global $product, $woocommerce_loop, $road_opt;
		
		$productid 	= intval( $_POST['pid'] );
		$product 	= wc_get_product( $productid );
		?>
		<h3><?php esc_html_e('Product is added in cart', 'emallshop');?></h3>
		<div class="product-wrapper">
			<div class="product-image">
				<?php echo wp_kses($product->get_image('shop_thumbnail'), array(
					'img'=>array(
						'src'=>array(),
						'height'=>array(),
						'width'=>array(),
						'class'=>array(),
						'alt'=>array(),
					)
				));?>
			</div>
			<div class="product-info">
				<h4><?php echo esc_html($product->get_title());?></h4>
				<p class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
			</div>
		</div>
		<div class="buttons">
			<a class="button" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'cart' ) ) ) ;?>"><?php esc_html_e('View Cart', 'emallshop');?></a>
		</div>
		<?php
		die();
	}
}
add_action( 'wp_ajax_get_productinfo', 'emallshop_get_productinfo' );
add_action( 'wp_ajax_nopriv_get_productinfo', 'emallshop_get_productinfo' );

/*  Category title and items
/* --------------------------------------------------------------------- */
if( ! function_exists( 'woocommerce_template_loop_category_title' ) ) {
	function woocommerce_template_loop_category_title( $category ) { ?>
		<div class="category-content">
			<h3>
				<?php echo esc_attr($category->name);	?>
			</h3>
			<?php if ( $category->count > 0 )
				echo apply_filters( 'woocommerce_subcategory_count_html', sprintf( '<span class="category-items" />%s %s</span>', $category->count, esc_html__( 'Items', 'emallshop' ) ), $category );
			?>
		</div>
	<?php }
}

/*  Change number of related products output
/* --------------------------------------------------------------------- */
add_filter( 'woocommerce_output_related_products_args', 'emallshop_related_products_args' );
if( ! function_exists( 'emallshop_related_products_args' ) ) { 
	function emallshop_related_products_args( $args ) {
		$args['posts_per_page'] = 6; // 4 related products
		return $args;
	}
}

/* 	Load more product
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_load_more_products' ) ) { 
	function emallshop_load_more_products() {
		$load_more_options=array();
		
		$load_more_options['type']= emallshop_get_option('product-pagination-style', 'default_pagination');
		
		$load_more_options['use_mobile']=false;
		$load_more_options['mobile_type']='more_button';
		$load_more_options['mobile_width']=767;
		
		$load_more_text='<i class="fa  fa-arrow-circle-o-down"></i> ';
		$load_more_text.= emallshop_get_option('load-more-button-text','Load More');
		$load_more_options['lazy_load']= (emallshop_get_option('enable-lazy-load', 0)==1) ? true : false ;
		
		$load_more_options['lazy_load_m']=false;
		
		$load_more_options['LLanimation']=( emallshop_get_option('enable-lazy-load', 0)==1) ? emallshop_get_option('load-animation-style', 'fadeInUp') : '' ;
		
		$loading_image= EMALLSHOP_ADMIN_IMAGES.'/ajax-'.emallshop_get_option('pagination-loading-image','loader').'.gif';
		
		$load_more_options['loading']=esc_html__('Loading...','emallshop');
		$load_more_options['loading_class'] = '';
		$load_more_options['end_text'] = esc_html__('No more product','emallshop');
		
		$load_more_options['products_selector'] = 'ul.products.is_shop';
		$load_more_options['item_selector'] = 'li.product';
		$load_more_options['pagination_selector'] = '.woocommerce-pagination';
		$load_more_options['next_page_selector'] = '.woocommerce-pagination a.next';
			
		$image_class = 'lmp_rotate';
		
		$image = '<div class="lmp_products_loading">';	
		$image .= '<img src="'.esc_url($loading_image).'">';
		$image .= '</div>';
		
		$load_more_options['image']=$image;

		$load_more_button = '<div class="lmp_load_more_button">';
		$load_more_button .= '<a class="lmp_button"';	
		$load_more_button .= ' href="#load_next_page">'.$load_more_text.'</a>';
		$load_more_button .= '</div>';
		
		$load_more_options['load_more_button']=$load_more_button;
		
		return $load_more_options;
	}
}

/* 	add product detail next and prev products
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_single_product_pagination' ) ) { 
	function emallshop_single_product_pagination(){
		if(!emallshop_get_option('show-product-navigation', 1)) return; 
	
		$next = get_next_post();
	    $prev = get_previous_post();

	    $next = ( ! empty( $next ) ) ? wc_get_product( $next->ID ) : false;
	    $prev = ( ! empty( $prev ) ) ? wc_get_product( $prev->ID ) : false;

		?>
		<div class="product-next-previous">
			<?php if ( ! empty( $prev ) ): ?>
				<div class="product-prev">
					<a href="<?php echo esc_url( $prev->get_permalink() ); ?>">					
					<span class="product-navbar">					
						<?php if(is_rtl()):?>
							<i class="fa fa-chevron-right"></i>
						<?php else:?>
							<i class="fa fa-chevron-left"></i>
						<?php endif;?>
					</span>
					<div class="product-prev-popup">
						<div class="product-thumb">
							 <?php echo wp_kses( $prev->get_image(), array( 'img' => array('class' => true,'width' => true,'height' => true,'src' => true,'alt' => true) ) );?>
						</div>
						<div class="product-title-price">
							<span class="ptitle"><?php echo esc_html( $prev->get_title() ); ?></span>
						<?php echo wp_kses_post( $prev->get_price_html() ); ?>
						</div>
					</div>
					</a>
				</div>
			<?php endif ?>
			
			<?php if ( ! empty( $next ) ): ?>
			<div class="product-next">				
				<a href="<?php echo esc_url( $next->get_permalink() ); ?>">
				<span class="product-navbar">
					<?php if(is_rtl()):?>
						<i class="fa fa-chevron-left"></i>
					<?php else:?>
						<i class="fa fa-chevron-right"></i>
					<?php endif;?>					
				</span>
				<div class="product-next-popup">
					<div class="product-thumb">
						 <?php echo wp_kses( $next->get_image(), array( 'img' => array('class' => true,'width' => true,'height' => true,'src' => true,'alt' => true) ) );?>
					</div>
					<div class="product-title-price">
						<span class="ptitle"><?php echo esc_html( $next->get_title() ); ?></span>
						<?php echo wp_kses_post( $next->get_price_html() ); ?>
					</div>
				</div>
				</a>
			</div>
			<?php endif ?>
		</div>
	<?php }
}

/*  Single Product Availability
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_single_availability' ) ) {
	function emallshop_template_single_availability() {
		global $product;
		if(!emallshop_get_option('show-single-product-availability', 1)) return; ?>
		
		<span class="availability <?php echo esc_html( $product->is_in_stock() ) ? 'instock' : ''; ?>"><?php echo esc_html( $product->is_in_stock() ) ? esc_html__('In  Stock','emallshop') : ''; ?></span>
		
	<?php }
}

/*  Sale Product Countdown
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_sale_product_countdown' ) ) {
	function emallshop_sale_product_countdown() {
		global $product;
		
		if(is_single() && !emallshop_get_option('show-single-product-countdown', 1)) return; 
		
		if ( $product->is_on_sale() ) : 
			$time_sale = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
		endif;
		
		/* variable product */
		if( $product->has_child() && $product->is_on_sale()){
			$vsale_end = array();
			
			$pvariables = $product->get_children();
			foreach($pvariables as $pvariable){
				$vsale_end[] = (int)get_post_meta( $pvariable, '_sale_price_dates_to', true );
			}			
			/* get the latest time */
			$time_sale = max($vsale_end);				
		}?>
		
		<?php if( $product->is_on_sale() && $time_sale ) :?>
			<div class="product-countdown">			
				<div class="countdown" data-year="<?php echo date('Y',$time_sale);?>" data-month="<?php echo date('m',$time_sale)-1;?>" data-day="<?php echo date('d',$time_sale);?>" data-hours="<?php echo date('H',$time_sale);?>" data-minutes="<?php echo date('i',$time_sale);?>" data-seconds="<?php echo date('s',$time_sale);?>"></div>
			</div>
		<?php endif;?>	
		
	<?php }
}

/*  Single Product Size Guide
 * 
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_single_product_sizeguide' ) ) {
	function emallshop_single_product_sizeguide() {
		$attachment_id = get_post_meta ( get_the_ID(), '_emallshop_product_size_guide', true );
		if(isset($attachment_id) && $attachment_id!=""):
			$sizeGuideImage=wp_get_attachment_url( $attachment_id );
		else:
			$imageurl = emallshop_get_option('size-guide-image', array( 'url' => EMALLSHOP_IMAGES.'/sizeguide.png' ) );
			$sizeGuideImage = (!empty( $imageurl['url'])) ? $imageurl['url'] : "";
		endif;
		
		if(emallshop_get_option('enable-size-guide',0) && (isset($sizeGuideImage) && $sizeGuideImage!="")):?>
			<div class="size-guide">
				<a class="zoom-gallery" href="<?php echo esc_url($sizeGuideImage);?>"><i class="fa fa-bar-chart"></i> <?php esc_html_e('Size Guide','emallshop');?></a>
			</div>
		<?php endif;
	}
}
/*  Single Product Brand
 * 
 * @since EmallShop 2.0
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_template_single_brand' ) ) {
	function emallshop_template_single_brand() {
		global $post, $product;
		
		$productmeta=emallshop_get_option('show-specific-productmeta', array('brand','sku','cats','tags'));
		
		if(taxonomy_exists('product_brand') && (!empty($productmeta) && in_array('brand',$productmeta) ) ):
			$brands_array = get_the_terms( $post->ID, 'product_brand' );
			if( is_array( $brands_array ) ){
				$brand_count = sizeof( $brands_array );
				echo get_the_term_list( $post->ID, 'product_brand', ' <span class="brand_in">' . _n( 'Brand:', 'Brands:', $brand_count, 'emallshop' ) . ' ', ' , ', ' </span>' );
			}
		endif;
	}
}

/*  Define image sizes
/* --------------------------------------------------------------------- */
if( ! function_exists( 'emallshop_woocommerce_image_dimensions' ) ) {
	function emallshop_woocommerce_image_dimensions() {
		global $pagenow;

		if ( $pagenow != 'themes.php' || ! isset( $_GET['activated'] ) ) {
			return;
		}
		
		update_option( 'woocommerce_single_image_width', '850' ); 		// Single product image
		update_option( 'woocommerce_thumbnail_image_width', '450' ); 	// Gallery and catalog image
	}
}
add_action( 'admin_init', 'emallshop_woocommerce_image_dimensions', 1 );

/** 
 *Reduce the strength requirement on the woocommerce password.
 * 
 * Strength Settings
 * 3 = Strong (default)
 * 2 = Medium
 * 1 = Weak
 * 0 = Very Weak / Anything
 */
if( ! function_exists( 'emallshop_reduce_woocommerce_min_strength_requirement' )) {
	function emallshop_reduce_woocommerce_min_strength_requirement( $strength ) {
		if(emallshop_get_option('manage-password-strength', 0))
			return emallshop_get_option('user-password-strength', 3);
		else
			return 3;		 
	}
}
add_filter( 'woocommerce_min_password_strength', 'emallshop_reduce_woocommerce_min_strength_requirement' );

/* Fix Single Product Image Sizes Issue*/
if ( ! function_exists( 'fix_single_product_image_sizes') ) {
   function fix_single_product_image_sizes() {
     $sizes = wc_get_image_size( 'woocommerce_single' );
     if ( ! $sizes['height'] ) {
       $sizes['height'] = $sizes['width'];
     }

 return array( $sizes['width'], $sizes['height'] );
 }

   add_filter( 'woocommerce_gallery_thumbnail_size', 'fix_single_product_image_sizes' );
}

/**
 * Remove WCWL default options
 */
add_action( 'wp_head', 'emallshop_wcwl_settings',10 );
function emallshop_wcwl_settings(){
	if(function_exists( 'YITH_WCWL_Frontend' )){
		$es_wcwl_obj = YITH_WCWL_Frontend();
		remove_action( 'woocommerce_before_shop_loop_item', array( $es_wcwl_obj, 'print_button' ), 5 );
		remove_action( 'woocommerce_after_shop_loop_item', array( $es_wcwl_obj, 'print_button' ), 7 );
		remove_action( 'woocommerce_after_shop_loop_item', array( $es_wcwl_obj, 'print_button' ),15 );		
		remove_action( 'woocommerce_single_product_summary', array( $es_wcwl_obj, 'print_button' ),31 );		
		remove_action( 'woocommerce_product_thumbnails', array( $es_wcwl_obj, 'print_button' ),21 );		
		remove_action( 'woocommerce_after_single_product_summary', array( $es_wcwl_obj, 'print_button' ),11 );
		add_action( 'woocommerce_single_product_summary', array( $es_wcwl_obj, 'print_button' ),31 );
	}
}

if ( ! function_exists( 'emallshop_sticky_add_to_cart_button' ) ) :
	/**
	 * Single Product Sticky Add To Cart Button
	 */
	function emallshop_sticky_add_to_cart_button(){
		
		global $product;		
		$stick_add_to_cart = emallshop_get_option( 'sticky-add-to-cart-button', 1 );
		
		if ( ! is_singular( 'product' ) || ! $stick_add_to_cart || !$product->is_in_stock() ) {
			return;
		}
				
		?>
		<div class="emallshop-sticky-add-to-cart">
			<div class="container">
				<div class="row">
					<div class="col sticky-add-to-cart-left">
						<div class="sticky-product-image">
							<?php echo woocommerce_get_product_thumbnail( 'woocommerce_gallery_thumbnail'); ?>
						</div>
						<div class="sticky-product-info">
							<div class="sticky-product-title"><?php the_title(); ?></div>
							<?php if( wc_review_ratings_enabled() ) {
								echo wc_get_rating_html( $product->get_average_rating() );
							} ?>
						</div>
					</div>
					<div class="col-auto sticky-add-to-cart-right">
						<span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>						
						<a href="<?php echo esc_url( '#' ); ?>" class="button <?php echo esc_attr( $product->get_type() );?>" rel="nofollow">
							<?php echo esc_attr( $product->add_to_cart_text() ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'emallshop_free_shipping_bar' ) ) :
	/**
	 * Free Shipping Progress Bar
	 */
	function emallshop_free_shipping_bar() {
		
		if( ! emallshop_get_option( 'free-shipping-bar', 0 ) ) {
			return;
		}
		if( empty( emallshop_get_option( 'free-shipping-amount', '' ) ) ) {
			return;
		}
		$subtotal = WC()->cart->subtotal;
		$minimum_amount = emallshop_get_option( 'free-shipping-amount', 0 );
		if( $subtotal < $minimum_amount ){
			$remaining = $minimum_amount - $subtotal;
			$percentage = round( ( $subtotal / $minimum_amount ) * 100 ) ;
			$missing_amount = wc_price($remaining);
			$free_shipping_text = emallshop_get_option('free-shipping-msg','Spend {missing_amount} to get <strong>free shipping</strong>');
			$free_shipping_text = str_replace( '{missing_amount}', $missing_amount, $free_shipping_text );
			$class = 'active';
			
		}else{
			$free_shipping_text = emallshop_get_option('free-shipping-complete-msg','Congratulation! You have got free shipping');
			$percentage = 100;
			$class = 'completed';
		}?>
		<div class="emallshop-freeshipping-bar <?php echo esc_attr($class);?>">
			<div class="freeshipping-bar">				
				<span class="progress-bar active" style="width:<?php echo esc_attr( $percentage );?>%"><?php echo wp_kses_post( $percentage );?>%</span>
			</div>
			<div class="freeshipping-bar-msg"><?php echo wp_kses_post( $free_shipping_text );?></div>
		</div>
	<?php }
endif;