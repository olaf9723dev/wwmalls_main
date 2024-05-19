<?php

add_action( 'ert_property_content', 'ert_property_before', 50 );

if ( ! function_exists( 'ert_property_before' ) ) {

	/**
	 * Before property content.
	 *
	 * @return void
	 */
	function ert_property_before() {
		global $ef_options;

		$container_class = is_active_sidebar( 'sidebar-property' )
                           && $ef_options->get( 'property_sidebar' ) ? 'col-md-8' : 'col-md-12';

		echo "<div id='primary' class='{$container_class} property-single'>";
	}
}

add_action( 'ert_property_content', 'ert_property_sidebar_loop', 40 );

if ( ! function_exists( 'ert_property_sidebar_loop' ) ) {

	/**
	 * Sidebar.
	 */
	function ert_property_sidebar_loop() {

		global $ef_options;

		echo "<div class='col-12 es-property__content'><div class='row'>";

		if ( $ef_options->get( 'property_sidebar' ) == 'left' ) {
			get_sidebar( 'property' );
		}
	}
}

add_action( 'ert_property_content', 'ert_property_after', 70 );

if ( ! function_exists( 'ert_property_after' ) ) {

	/**
	 * Property close container.
	 */
	function ert_property_after() {

		echo "</div>";

		global $ef_options;

		if ( $ef_options->get( 'property_sidebar' ) == 'right' ) {
			get_sidebar( 'property' );
		}

		echo "</div></div>";
	}
}

add_action( 'ert_property_content', 'ert_property_loop', 60 );

if ( ! function_exists( 'ert_property_loop' ) ) {

	/**
	 * Property posts loop.
	 */
	function ert_property_loop() {
		es_load_template( 'content-single-loop.php' );
	}
}

add_action( 'ert_property_before_content', 'ert_property_before_widget_area', 10 );

if ( ! function_exists( 'ert_property_before_widget_area' ) ) {

	/**
	 * Before properties archive widget area.
	 *
	 * @return void
	 */
	function ert_property_before_widget_area() {
		if ( is_active_sidebar( 'before_property' ) )
			dynamic_sidebar( 'before_property' );
	}
}

add_action( 'ert_property_after_content', 'ert_property_after_widget_area', 10 );

if ( ! function_exists( 'ert_property_after_widget_area' ) ) {

	/**
	 * Before properties archive widget area.
	 *
	 * @return void
	 */
	function ert_property_after_widget_area() {
		if ( is_active_sidebar( 'after_property' ) )
			dynamic_sidebar( 'after_property' );
	}
}

add_action( 'ert_property_content', 'ert_property_gallery', 30 );

if ( ! function_exists( 'ert_property_gallery' ) ) {

	/**
	 * Display property gallery.
	 *
	 * @return void
	 */
	function ert_property_gallery() {
		do_action( 'es_single_gallery' );
	}
}

add_action( 'es_before_single_content', 'ert_page_property_header' );

if ( ! function_exists( 'ert_page_property_header' ) ) {

	/**
	 * @return void
	 */
    function ert_page_property_header() {
        $elementor_builder = function_exists( 'es_is_elementor_builder_enabled' )
            && es_is_elementor_builder_enabled( get_the_ID() );

        if ( ert_is_elementor_template_exists() || $elementor_builder ) {
            echo "<div class='row' style='margin-bottom: 30px;'>";
	        ert_property_header();
            echo "<div class='col-12'>";
            do_action( 'es_single_gallery' );
            echo "</div>";
            echo "</div>";
        }
    }
}

add_action( 'ert_archive_sorting_dropdown', 'ert_archive_sorting_dropdown', 10, 2 );

if ( ! function_exists( 'ert_archive_sorting_dropdown' ) ) {

    /**
     * @param $shortcode_identifier
     * @param $sort
     * @return void
     */
	function ert_archive_sorting_dropdown( $shortcode_identifier, $sort ) {
        if ( ! is_estatik4() ) {
            include es_locate_template( 'property/listings-filter.php' );
        }
	}
}

add_action( 'ert_property_content', 'ert_property_header', 20 );

if ( ! function_exists( 'ert_property_header' ) ) {

	/**
	 * Display property breadcrumbs, price, title etc.
	 */
	function ert_property_header() {
		ert_the_breadcrumbs();
		$property = es_get_property( get_the_ID() );
		global $es_settings;
        $render_shares = false;

        if ( $es_settings ) {
            $render_shares = $es_settings->share_facebook || $es_settings->share_google_plus
                || $es_settings->share_linkedin || $es_settings->share_twitter;
        } ?>

		<header class="entry-header col-12 ert-property-header">
			<div class="row">
				<div class="col-md">
                    <h2 class="entry-title"><?php the_title(); ?></h2>
					<?php es_the_address( '<span class="property-address">', '</span>' ); ?>
                </div>
				<div class="col-auto">
                    <div class="ert-property__price">
                        <?php if ( is_estatik4() ) : ?>
                            <?php es_the_formatted_field( 'price', '<div class="es-price">', '</div>' ); ?>
                        <?php else : ?>
                            <?php es_the_formatted_price(); ?>
                        <?php endif; ?>
						<?php if ( ! empty( $property->price_note ) ) : ?>
                            <div class="property-price-note">
								<?php echo $property->price_note; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
			</div>
            <div class="row ert-property__header-bottom">
                <div class="col-md">
                    <div class="badge-container">
		                <?php do_action( 'es_property_categories_badges' ); ?>
                        <?php if ( is_estatik4() ) : ?>
                            <?php do_action( 'es_property_badges' ); ?>
                        <?php else : ?>
		                    <?php do_action( 'es_property_labels_badges' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="ert-property-buttons">
		                <?php do_action( 'ert_wishlist_add_button', get_the_ID() ); ?>
		                <?php if ( $es_settings && $es_settings->use_pdf ) : ?>
                            <a href="<?php echo add_query_arg( 'es-pdf', get_the_ID(), get_the_permalink() ); ?>" target="_blank">
                                <span class="ert-icon ert-icon_pdf"></span><?php _e( 'PDF', 'ert' ); ?>
                            </a>
		                <?php endif; ?>

		                <?php if ( $render_shares ) : ?>
                            <a href="#ert-share-popup" class="js-ert-share-popup"><span class="ert-icon ert-icon_share"></span><?php _e( 'Share', 'ert' ); ?></a>
		                <?php endif; ?>
                    </div>
                </div>
            </div>
		</header>

		<?php if ( $render_shares ) : wp_enqueue_script( 'es-share-script' ); ?>
            <div id="ert-share-popup" class="ert-share-popup mfp-hide">
                <h4 class="page-title"><?php _e( 'Share this property', 'ert' ); ?></h4>
                <div class="a2a_kit ert-contact-us-widget">
					<?php if ( $es_settings->share_facebook ) : ?>
                        <a class="a2a_button_facebook">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
					<?php endif; ?>

					<?php if ( $es_settings->share_twitter ) : ?>
                        <a class="a2a_button_twitter">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
					<?php endif; ?>

					<?php if ( $es_settings->share_google_plus ) : ?>
                        <a class="a2a_button_google_plus">
                            <i class="fa fa-google-plus" aria-hidden="true"></i>
                        </a>
					<?php endif; ?>

					<?php if ( $es_settings->share_linkedin ) : ?>
                        <a class="a2a_button_linkedin">
                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                        </a>
					<?php endif; ?>
                </div>
            </div>
		<?php endif;
	}
}

add_action( 'es_property_categories_badges', 'es_property_categories_badges' );

if ( ! function_exists( 'es_property_categories_badges' ) ) {

	/**
	 * Property Categories badges.
	 *
	 * @return void
	 */
	function es_property_categories_badges() {

		$categories = wp_get_post_terms( get_the_ID(), 'es_category' );
        global $ef_options;

		if ( $ef_options->get( 'is_badges_enabled' ) && $categories && ! $categories instanceof WP_Error ) : ?>
			<?php foreach ( $categories as $category ) : $link = get_term_link( $category->term_id ); ?>
				<?php if ( ! $link instanceof WP_Error ) : ?>
					<a class="badge badge-dark badge-<?php echo $category->slug; ?>" target="_blank" href="<?php echo $link; ?>"><?php echo $category->name; ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif;
	}
}

add_action( 'es_property_categories_hero_badges', 'es_property_categories_hero_badges' );

if ( ! function_exists( 'es_property_categories_hero_badges' ) ) {

	/**
	 * Property Categories badges.
	 *
	 * @return void
	 */
	function es_property_categories_hero_badges() {

		$categories = wp_get_post_terms( get_the_ID(), 'es_category' );
        global $ef_options;

		if ( $ef_options->get( 'is_badges_enabled' ) && $categories && ! $categories instanceof WP_Error ) : ?>
			<?php foreach ( $categories as $category ) : $link = get_term_link( $category->term_id ); ?>
				<?php if ( ! $link instanceof WP_Error ) : ?>
                    <span class="badge badge-dark badge-<?php echo $category->slug; ?>"><?php echo $category->name; ?></span>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif;
	}
}

add_action( 'es_property_labels_badges', 'es_property_labels_badges' );

if ( ! function_exists( 'es_property_labels_badges' ) ) {

	/**
	 * Display property labels.
	 *
	 * @return void
	 */
	function es_property_labels_badges() {
		$es_property = es_get_property( get_the_ID() );
		global $es_settings;

		if ( $es_settings->show_labels ) {

		    if ( ! is_singular( 'properties' ) || ( is_singular( 'properties' ) && $es_settings->show_labels_on_single_page ) ) {
                foreach ( $es_property->get_labels_list() as $label ) : ?>
                    <?php if ( $value = $es_property->{ $label->slug } && ( $link = get_term_link( $label->term_id ) ) ) : ?>
                        <?php if ( ! $link instanceof WP_Error ) : ?>
                            <span class="badge badge-light badge-<?php echo $label->slug; ?>"><?php _e( $label->name, 'es-plugin' ) ; ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach;
		    }
		}
	}
}

add_action( 'es_property_labels_hero_badges', 'es_property_labels_hero_badges' );

if ( ! function_exists( 'es_property_labels_hero_badges' ) ) {

	/**
	 * Display property labels.
	 *
	 * @return void
	 */
	function es_property_labels_hero_badges() {
        if ( is_estatik4() ) return;
		$es_property = es_get_property( get_the_ID() );
        global $es_settings;

        if ( $es_settings->show_labels ) {
            foreach ( $es_property->get_labels_list() as $label ) : ?>
                <?php if ( $value = $es_property->{ $label->slug } && ( $link = get_term_link( $label->term_id ) ) ) : ?>
                    <?php if ( ! $link instanceof WP_Error ) : ?>
                        <span class="badge badge-light badge-<?php echo $label->slug; ?>"><?php _e( $label->name, 'es-plugin' ) ; ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach;
        }
	}
}

/**
 * @param $post_id
 * @param bool $added
 */
function ert_wishlist_add_button( $post_id, $added = false ) {

	global $es_settings;

	if ( $es_settings && $es_settings->is_wishlist_enabled ) {
		$instance = es_get_wishlist_instance();

		if ( $instance->has( $post_id ) || $added ) {
			echo "<a href='#' class='js-es-wishlist-button active' data-id='{$post_id}' data-method='remove'>
                <i class='fa fa-heart' aria-hidden='true'></i> " . __( 'Remove from favourites', 'ert' ) . "
              </a>";
		} else {
			echo "<a href='#' class='js-es-wishlist-button' data-id='{$post_id}' data-method='add'>
                <i class='fa fa-heart-o' aria-hidden='true'></i> " . __( 'Add to favorites', 'ert' ) . "
              </a>";
		}
	}
}
add_action( 'ert_wishlist_add_button', 'ert_wishlist_add_button', 10, 1 );

/**
 * @param $content
 *
 * @return string
 */
function ert_the_content( $content ) {
    return $content . "<div class='clearfix'></div>";
}
add_filter( 'es_the_content', 'ert_the_content' );
