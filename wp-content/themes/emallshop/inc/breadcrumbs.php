<?php 
/**
 * EmallShop Breadcrumbs
 * Since ver 1.0
 * Add this to any template file by calling emallshop_breadcrumbs()
 * Changes: MC added taxonomy support
 */
function emallshop_breadcrumbs(){
	
    $post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

    $output = '';   
	
    // breadcrumbs start wrap
    $output .= '<ul class="breadcrumbs">';

    // add home link
    if ( ! is_front_page() ) {
        $output .= emallshop_breadcrumbs_link( esc_html__('Home', 'emallshop'), apply_filters( 'woocommerce_breadcrumb_home_url', home_url('/') ) );
    }
	
    // add woocommerce shop page link
    if ( class_exists( 'WooCommerce' ) && ( ( is_woocommerce() && is_archive() && ! is_shop() ) || is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
        $output .= emallshop_breadcrumbs_shop_link();
    }   
	
    if ( is_singular() ) {
        if ( isset( $post->post_type ) && $post->post_type !== 'product' && get_post_type_archive_link( $post->post_type )) {
            $output .= emallshop_breadcrumbs_archive_link();
        } elseif ( isset( $post->post_type ) && $post->post_type == 'post' && get_option( 'show_on_front' ) == 'page' ) {

            $output .= emallshop_breadcrumbs_link( get_the_title( get_option('page_for_posts', true) ), get_permalink( get_option('page_for_posts' ) ) );
        }
		
        if ( isset( $post->post_parent ) && $post->post_parent == 0 ) {
            $output .= emallshop_breadcrumbs_terms_link();
        } else {
            $output .= emallshop_breadcrumbs_ancestors_link();
        }
        $output .= emallshop_breadcrumb_leaf();

    } else {
        if ( is_post_type_archive() ) {
            if ( is_search() ) {
                $output .= emallshop_breadcrumbs_archive_link();
                $output .= emallshop_breadcrumb_leaf( 'search' );
            } else {
                $output .= emallshop_breadcrumbs_archive_link( false );
            }
        } elseif ( is_tax() || is_tag() || is_category() ) {
            $html = emallshop_breadcrumbs_taxonomies_link();
            $html .= emallshop_breadcrumb_leaf( 'term' );
            if ( is_tag() ) {
                if ( get_option( 'show_on_front' ) == 'page' ) {
                    $output .= emallshop_breadcrumbs_link( get_the_title( get_option('page_for_posts', true) ), get_permalink( get_option('page_for_posts' ) ) );
                }
                $output .= sprintf( __( 'Tag - %s', 'emallshop' ), $html );
            } elseif ( is_tax('product_tag') ) {
                $output .= sprintf( __( 'Product Tag - %s', 'emallshop' ), $html );
            } else {
                if ( is_category() && get_option( 'show_on_front' ) == 'page' ) {
                    $output .= emallshop_breadcrumbs_link( get_the_title( get_option('page_for_posts', true) ), get_permalink( get_option('page_for_posts' ) ) );
                }
				
                if ( is_tax('portfolio_cat') || is_tax('portfolio_skills') ) {
                    $output .= emallshop_breadcrumbs_link( emallshop_breadcrumbs_archive_name('portfolio'), get_post_type_archive_link( 'portfolio' ) );
                }
				
                $output .= $html;
            }
        } elseif ( is_date() ) {
            global $wp_locale;

            if ( get_option( 'show_on_front' ) == 'page' ) {
                $output .= emallshop_breadcrumbs_link( get_the_title( get_option('page_for_posts', true) ), get_permalink( get_option('page_for_posts' ) ) );
            }

            $year = esc_html( get_query_var( 'year' ) );
            if ( is_month() || is_day() ) {
                $month = get_query_var( 'monthnum' );
                $month_name = $wp_locale->get_month( $month );
            }
			
            if ( is_year() ) {
                $output .= emallshop_breadcrumb_leaf( 'year' );
            } elseif ( is_month() ) {
                $output .= emallshop_breadcrumbs_link( $year, get_year_link( $year ) );
                $output .= emallshop_breadcrumb_leaf( 'month' );
            } elseif ( is_day() ) {
                $output .= emallshop_breadcrumbs_link( $year, get_year_link( $year ) );
                $output .= emallshop_breadcrumbs_link( $month_name, get_month_link( $year, $month ) );
                $output .= emallshop_breadcrumb_leaf( 'day' );
            }
        } elseif ( is_author() ) {
            $output .= emallshop_breadcrumb_leaf( 'author' );
        } elseif ( is_search() ) {
            $output .= emallshop_breadcrumb_leaf( 'search' );
        } elseif ( is_404() ) {
            $output .= emallshop_breadcrumb_leaf( '404' );
        } else {
            if ( is_home() && !is_front_page() ) {
                if ( get_option( 'show_on_front' ) == 'page' ) {
                    $output .= emallshop_breadcrumbs_link( get_the_title( get_option('page_for_posts', true) ) );
                } 
            }
        }
    }

    // breadcrumbs end wrap
    $output .= '</ul>';
	
    if ( function_exists( 'yoast_breadcrumb' ) ) {
		$yoast_breadcrumbs = yoast_breadcrumb( '', '', false );
		yoast_breadcrumb( '<div class="breadcrumbs">','</div>' );
		
	}else{
		return apply_filters('emallshop_breadcrumbs', $output);
	}
}

function emallshop_breadcrumbs_link($title, $link = '') {
	
    $output = sprintf( '<span>%s</span>', $title );
    $delimiter = '';

    if ( $link ) {
        $output = sprintf( '<a href="%s"%s>%s</a>', $link, ( $title == 'Home' ) ? ' title="'.esc_attr('Go to Home Page', 'emallshop').'"' : '', $output );
        $delimiter = is_rtl() ? ' <span class="delimiter"><i class="fa fa-angle-left"></i></span> ' : ' <span class="delimiter"><i class="fa fa-angle-right"></i></span> ';
        $before = '<li>';
    } else {
        $before = '<li>';
    }

    $after = '</li>';
	
    return $before . $output . $delimiter . $after;
}

function emallshop_breadcrumbs_simple_link($title, $link = '') {

    $output = sprintf( '<span>%s</span>', $title );

    if ( $link ) {
        $output = sprintf( '<a href="%s" >%s</a>', esc_url($link), $output );
    }

    $before = '<span>';
    $after = '</span>';
	
    return $before . $output . $after;
}

function emallshop_breadcrumb_leaf( $object_type = '' ) {
	
    global $wp_query, $wp_locale;
    $post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

    switch( $object_type ) {
		
        case 'term':
            $term = $wp_query->get_queried_object();
            $title = $term->name;
            break;

        case 'year':
            $title = esc_html( get_query_var( 'year' ) );
            break;

        case 'month':
            $title = $wp_locale->get_month( get_query_var( 'monthnum' ) );
            break;

        case 'day':
            $title = get_query_var( 'day' );
            break;

        case 'author':
            $user = $wp_query->get_queried_object();
            $title = $user->display_name;
            break;

        case 'search':
            $search = esc_html( get_search_query() );
            if ( $product_cat = get_query_var('product_cat') ) {
                $product_cat = get_term_by('slug', $product_cat, 'product_cat');
                $search = '<a href="' . esc_url( get_term_link($product_cat, 'product_cat') ) . '">' . esc_html( $product_cat->name ) . '</a>' . ( $search ? ' / ' : '' ) . $search;
            }
            $title = sprintf( __( 'Search - %s', 'emallshop' ), $search );
            break;

        case '404':
            $title = __( '404', 'emallshop' );
            break;       

        default:
            $title = get_the_title( $post->ID );
			break;
    }
    $before = '<li>';
    $after = '</li>';
	
    return $before . $title . $after;
}

function emallshop_breadcrumbs_links( $output ) {
   	$delimiter = is_rtl() ? ' <span class="delimiter"><i class="fa fa-angle-left"></i></span> ' : ' <span class="delimiter"><i class="fa fa-angle-right"></i></span> ';
    $before = '<li>';
    $after = '</li>';
	
    return $before . $output . $delimiter . $after;
}

function emallshop_breadcrumbs_shop_link( $linked = true ) {
    $post_type = 'product';
    $post_type_object = get_post_type_object( $post_type );
    $link = '';
    $output = '';

    if ( is_object( $post_type_object ) && class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        $shop_page_id = wc_get_page_id( 'shop' );
        $shop_page_name = $shop_page_id ? get_the_title( $shop_page_id ) : '';

        if ( ! $shop_page_name ) {
            $shop_page_name = $post_type_object->labels->name;
        }
        if ($linked ) {
            $link = $shop_page_id !== -1 ? get_permalink($shop_page_id) : get_post_type_archive_link( $post_type );
        }
        $output .= emallshop_breadcrumbs_link( $shop_page_name, $link );
    }
	
    return $output;
}

function emallshop_breadcrumbs_archive_link( $linked = true ) {

    global $wp_query;
    $post_type = $wp_query->query_vars['post_type'];
    $post_type_object = get_post_type_object( $post_type );
    $link = '';
    $archive_title = '';
	
    if ( is_object( $post_type_object ) ) {
		// woocommerce

        if ( $post_type == 'product' && $shop_link = emallshop_breadcrumbs_shop_link( $linked ) ) {
            return $shop_link;
        }
        $archive_title = emallshop_breadcrumbs_archive_name( $post_type );
    }
    if ( $linked ) {
        $link = get_post_type_archive_link( $post_type );
    }
    if ( $archive_title ) {
        return emallshop_breadcrumbs_link( $archive_title, $link );
    }
	
    return '';
}

function emallshop_breadcrumbs_archive_name( $post_type ) {
   
    $archive_title = '';
	
	$post_type_object = get_post_type_object( $post_type );

	if ( is_object( $post_type_object ) ) {	
		
		
		if ( isset( $post_type_object->label ) && $post_type_object->label !== '' ) {
			$archive_title = $post_type_object->label;
		} elseif ( isset( $post_type_object->labels->menu_name ) && $post_type_object->labels->menu_name !== '' ) {
			$archive_title = $post_type_object->labels->menu_name;
		} else {
			$archive_title = $post_type_object->name;
		}
	}
    
    return $archive_title;
}

function emallshop_breadcrumbs_terms_link() {

    $output = '';
	
    $post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;    

    if ( $post->post_type == 'post' ) {
        $taxonomy = 'category';
    } elseif ( $post->post_type == 'portfolio' ) {
        $taxonomy = 'portfolio_cat';
    } elseif ( $post->post_type == 'product' && class_exists( 'WooCommerce' ) && is_woocommerce() ) {
        $taxonomy = 'product_cat';
    }else {
        return $output;
    }
	
    $terms = wp_get_object_terms( $post->ID, $taxonomy, array('orderby' => 'term_id') );

    if ( empty( $terms ) ) {
        return $output;
    }
	
    $terms_by_id = array();

    foreach ( $terms as $term ) {
        $terms_by_id[ $term->term_id ] = $term;
    }
	
    foreach ( $terms as $term ) {
        unset( $terms_by_id[ $term->parent ] );
    }

    if ( count( $terms_by_id ) == 1 ) {
        unset( $terms );
        $terms[0] = array_shift( $terms_by_id );
    }

    if ( count( $terms ) == 1 ) {
        $term_parent = $terms[0]->parent;
		
        if ( $term_parent ) {
            $term_tree = get_ancestors( $terms[0]->term_id, $taxonomy );
            $term_tree = array_reverse( $term_tree );
            $term_tree[] = get_term( $terms[0]->term_id, $taxonomy );

            $i = 0;

            foreach ( $term_tree as $term_id ) {
                $term_object = get_term( $term_id, $taxonomy );
                if ( $i++ == 0 ) {
                    $output .= emallshop_breadcrumbs_simple_link( $term_object->name, get_term_link( $term_object ) );
                } else {
                    $output .= ', ' . emallshop_breadcrumbs_simple_link( $term_object->name, get_term_link( $term_object ) );
                }
            }
            $output = emallshop_breadcrumbs_links($output);
        } else {
            $output = emallshop_breadcrumbs_link( $terms[0]->name, get_term_link( $terms[0] ) );
        }
    } else {
        $output = emallshop_breadcrumbs_simple_link( $terms[0]->name, get_term_link( $terms[0] ) );
        array_shift( $terms );
        foreach ( $terms as $term ) {
            $output .= ', ' . emallshop_breadcrumbs_simple_link( $term->name, get_term_link( $term ) );
        }
        $output = emallshop_breadcrumbs_links($output);
    }
	
    return $output;
}

function emallshop_breadcrumbs_ancestors_link() {

    $output = '';
    $post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
    $post_ancestor_ids = array_reverse( get_post_ancestors( $post ) );

    foreach ( $post_ancestor_ids as $post_ancestor_id ) {
        $post_ancestor = get_post( $post_ancestor_id );
        $output .= emallshop_breadcrumbs_link( $post_ancestor->post_title, get_permalink( $post_ancestor->ID ) );
    }
    return $output;
}

function emallshop_breadcrumbs_taxonomies_link() {
	
    global $wp_query;
    $term = $wp_query->get_queried_object();
    $output = '';
	
    if ( $term && $term->parent != 0 && isset($term->taxonomy) && isset($term->term_id) && is_taxonomy_hierarchical( $term->taxonomy ) ) {
        $term_ancestors = get_ancestors( $term->term_id, $term->taxonomy );
        $term_ancestors = array_reverse( $term_ancestors );
		
        foreach ( $term_ancestors as $term_ancestor ) {
            $term_object = get_term( $term_ancestor, $term->taxonomy );
            $output .= emallshop_breadcrumbs_link( $term_object->name, get_term_link( $term_object->term_id, $term->taxonomy ) );
        }
    }

    return $output;

} // end emallshop_breadcrumbs()