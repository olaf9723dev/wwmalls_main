<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/dokan/store-lists-filter.php
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Dokan/Templates
 * @version 2.9.30
 */

defined( 'ABSPATH' ) || exit; ?>

<?php do_action( 'dokan_before_store_lists_filter', $stores ); ?>

<div id="dokan-store-listing-filter-wrap">
    <?php do_action( 'dokan_before_store_lists_filter_left', $stores ); ?>
    <div class="left">
        <div class="item">
            <button class="dokan-store-list-filter-button dokan-btn dokan-btn-theme">
                <?php echo \Motta\Icon::get_svg( 'filter' ) ?>
                <?php esc_html_e( 'Filter', 'motta' ); ?>
            </button>
        </div>

        <p class="item store-count">
            <?php
            // translators: 1) number of stores
            printf( _n( 'Total store showing: %d', 'Total stores showing: %d', $number_of_store, 'motta' ), number_format_i18n( $number_of_store ) );
            ?>
        </p>
    </div>

    <?php do_action( 'dokan_before_store_lists_filter_right', $stores ); ?>
    <div class="right">


        <form name="stores_sorting" class="sort-by item" method="get">
            <label><?php esc_html_e( 'Sort by', 'motta' ); ?>:</label>

            <select name="stores_orderby" id="stores_orderby" aria-label="<?php esc_attr_e( 'Sort by', 'motta' ); ?>">
                <?php
                foreach ( $sort_filters as $key => $filter ) {
                    $optoins = "<option value='. $key .'" . selected( $sort_by, $key, false ) . ">'. $filter . '</option>";
                    printf( $optoins );
                }
                ?>
            </select>
        </form>

        <div class="toggle-view item">


            <?php echo \Motta\Icon::get_svg( 'view-medium', 'ui', array( 'data-view' => 'grid-view' ) ); ?>
            <?php echo \Motta\Icon::get_svg( 'view-list', 'ui', array( 'data-view' => 'list-view' ) ); ?>
        </div>


    </div>
</div>

<?php do_action( 'dokan_before_store_lists_filter_form', $stores ); ?>

<form role="store-list-filter" method="get" name="dokan_store_lists_filter_form" id="dokan-store-listing-filter-form-wrap" style="display: none">

    <?php
    do_action( 'dokan_before_store_lists_filter_search', $stores );

    if ( apply_filters( 'dokan_load_store_lists_filter_search_bar', true ) ) :
        ?>
        <div class="store-search grid-item">
            <input type="search" class="store-search-input" name="dokan_seller_search" placeholder="<?php esc_attr_e( 'Search Vendors', 'motta' ); ?>">
        </div>
        <?php
    endif;

    do_action( 'dokan_before_store_lists_filter_apply_button', $stores );
    ?>

    <div class="apply-filter">
        <button id="cancel-filter-btn" class="dokan-btn dokan-btn-theme"><?php esc_html_e( 'Cancel', 'motta' ); ?></button>
        <button id="apply-filter-btn" class="dokan-btn dokan-btn-theme" type="submit"><?php esc_html_e( 'Apply', 'motta' ); ?></button>
    </div>

    <?php do_action( 'dokan_after_store_lists_filter_apply_button', $stores ); ?>
    <?php wp_nonce_field( 'dokan_store_lists_filter_nonce', '_store_filter_nonce', false ); ?>
</form>
