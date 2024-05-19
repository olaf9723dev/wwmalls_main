<?php

/**
 * @var $entity_plural string
 * @var $entity string
 */

$property = es_get_the_property();
$wishlist = es_get_wishlist_instance( $entity );
$classes = array( 'js-es-wishlist-button' );
$icon_size = ! empty( $icon_size ) ? $icon_size : 'small';
$compare = function_exists( 'es_get_compare_instance' ) ? es_get_compare_instance() : false;
$compare_page_url = es_get_page_url( 'compare' );

if ( $wishlist->has( get_the_ID() ) ) {
    $classes[] = 'es-wishlist-link--active';
}

if ( $compare && $compare->has( get_the_ID() ) ) {
    $classes_compare[] = 'es-btn--active';
}

$share_popup_id = ! empty( $share_popup_id ) ? $share_popup_id : 'es-share-popup';

if ( $compare && $compare->is_enabled() && ! empty( $show_compare ) ) : ?>
    <?php if ( $compare->has( get_the_ID() ) ) : ?>
        <?php if ( $compare_page_url = es_get_page_url( 'compare' ) ) : ?>
            <a href="<?php echo esc_url( $compare_page_url ); ?>" class="<?php echo implode( ' ', $classes_compare ); ?>">
                <span class="es-icon es-icon_scales"></span>
            </a>
        <?php endif; ?>
    <?php elseif ( $compare->is_auth_required() && ! is_user_logged_in() ) : $classes_compare[] = 'js-es-popup-link'; ?>
        <a href="#" data-popup-id="#es-authentication-popup" class="<?php echo implode( ' ', $classes_compare ); ?>">
            <span class="es-icon es-icon_scales"></span>
        </a>
    <?php else :
        if ( $compare_page_url ) : $classes_compare[] = 'js-es-compare'; ?>
            <a href="#" data-id="<?php the_ID(); ?>" class="<?php echo implode( ' ', $classes_compare ); ?>">
                <span class="es-icon es-icon_scales"></span>
            </a>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<?php if ( ests( 'is_' . $entity_plural . '_wishlist_enabled' ) ) : ?>
    <?php if ( ! is_user_logged_in() ) : $classes[] = 'js-es-popup-link'; ?>
        <a href="#" data-popup-id="#es-authentication-popup" class="<?php echo implode( ' ', $classes ); ?>">
            <i class="es-icon es-icon_heart"></i>
        </a>
    <?php else :
        if ( ! empty( $wishlist_confirm ) ) $classes[] = 'js-es-wishlist--confirm';
        $classes[] = 'js-es-wishlist'; ?>
        <a href="#" data-entity="<?php echo $entity; ?>" data-id="<?php the_ID(); ?>" class="<?php echo implode( ' ', $classes ); ?>">
            <i class="es-icon es-icon_heart"></i>
        </a>
    <?php endif; ?>
<?php endif; ?>

<?php if ( ! is_singular( es_et_builder_third_party_post_types( array() ) ) ) : ?>
    <a href="<?php the_permalink(); ?>"><i class="fa fa-mail-forward"></i></a>
<?php else: ?>
    <?php if ( ests( 'is_' . $entity_plural . '_sharing_enabled' ) && ! empty( $show_sharing ) ) : ?>
        <a href="#" data-popup-id="#<?php echo $share_popup_id; ?>" class="js-es-popup-link">
            <i class="fa fa-share-alt"></i>
        </a>
    <?php endif; ?>
<?php endif; ?>

<?php if ( $property->latitude && $property->longitude && ests( 'google_api_key' ) ) : ?>
    <a href="#es-map-popup" class="js-es-popup-link" data-longitude="<?php echo $property->longitude; ?>"
       data-latitude="<?php echo $property->latitude; ?>"><i class="fa fa-map-marker"></i></a>
<?php endif;

do_action( 'es_after_' . $entity . '_control_inner' ); ?>
