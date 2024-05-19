<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="profile" href="//gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php $favicon_icon=emallshop_get_option('favicon-icon', array( 'url' => EMALLSHOP_IMAGES.'/favicon.png' ) );
	if(! function_exists( 'has_site_icon' ) || ! has_site_icon()) :
		if( !empty($favicon_icon)){?>
			<link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon_icon['url']);?>">
		<?php }
	endif;?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'es_after_body' ); //EmallShop 2.0 ?> 

<?php if(emallshop_get_option('site-loader',0)==1):?>
	<div id="emallshop-loader-wrapper">
		<div class="emallshop-loader-section">
			<?php if(emallshop_get_option('site-loading-image','pre-defined')=='pre-defined'){
				$loader_img=EMALLSHOP_IMAGES.'/site-'.emallshop_get_option('site-loader-img', 'loader' ).'.gif';
			}else{
				$loader_img_url = emallshop_get_option( 'custom-loader-img', array( 'url' => EMALLSHOP_IMAGES.'/site-loader.gif' ) );
				$loader_img = (!empty($loader_img_url['url'])) ?  $loader_img_url['url'] : EMALLSHOP_IMAGES.'/site-loader.gif';
			}?>
			<img src="<?php echo esc_url($loader_img);?>" alt="<?php esc_attr_e('loader', 'emallshop');?>">
		</div>
	</div>
<?php endif;?>
<div class="panel-overlay"></div>
<?php $theme_layout = ( emallshop_get_option( 'theme-layout', 'full-layout' ) == 'boxed-layout' ) ? ' boxed-layout' : ''; ?>
<div class="wrapper<?php echo esc_attr( $theme_layout );?>">
	<div id="mobile-menu-wrapper" class="mobile-menu-wrapper">
		<a href="#" id="mobile-nav-close" class=""><i class="fa fa-close"></i></a>
		<div class="navbar-collapse">			
			<?php emallshop_header_mobile_menu(); ?>				
		</div><!-- /.navbar-collapse -->		
	</div>
	<div class="main-container">
		<header id="header" class="header <?php echo esc_attr(emallshop_get_option('header-layout','header-1'));?>">
			
			<?php // Topbar, Header and Navigation		
				get_template_part( 'templates/header/'.emallshop_get_option('header-layout','header-1'));
			?>
			
			<?php // Page Heading and Breadcrumbs
			if(!is_front_page() && emallshop_get_option('show-page-heading', 1)){			
					get_template_part( 'templates/page-heading/'.emallshop_get_option('page-heading-layout','page-heading-2'));			
			}?>
		</header>
		<div id="main-content" class="site-content">
			<div class="container">		
				<div class="row">