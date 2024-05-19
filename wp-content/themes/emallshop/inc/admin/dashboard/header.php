<?php
/**
 * Emallshop Dashboard
 *
 * Handles the about us page HTML
 *
 * @package Emallshop
 * @since 2.3.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$emallshop_tabs = apply_filters('emallshop_dashboard_tabs', array(
					'emallshop-theme' 			=> esc_html__("Dashboard", 'emallshop'),
					'emallshop-system-status' 	=> esc_html__("System Status", 'emallshop'),
					'emallshop-theme-option' 	=> esc_html__("Theme Options", 'emallshop'),
				));
$active_tab 	= isset($_GET['page']) ? $_GET['page'] : 'emallshop-theme';
?>
<div class="wrap about-wrap emallshop-admin-wrap emallshop-dashboard-wrap">
	<h1><?php echo esc_html__('Welcome to', 'emallshop').' Emallshop'; ?></h1>
	<div class="about-text">
		<?php echo sprintf( esc_html__('Thank you for purchasing our premium Emallshop theme. Here you are able
            to start creating your awesome web store by importing our dummy content and theme options.', 'emallshop')); ?>
	</div>
	<div class="wp-badge emallshop-page-logo"><?php echo esc_html__('Version', 'emallshop') .' '.EMALLSHOP_VERSION; ?></div>
	<p class="emallshop-actions">
		<a href="https://docs.presslayouts.com/emallshop/" target="_blank" class="btn-docs button"><?php esc_html_e('Documentation','emallshop');?></a>
		<a href="https://themeforest.net/downloads" class="btn-rate button" target="_blank"><?php esc_html_e('Rate our theme','emallshop');?></a>
    </p>
	<?php if( !empty( $emallshop_tabs ) ) { ?>
		<h2 class="nav-tab-wrapper">
			<?php foreach ($emallshop_tabs as $tab_key => $tab_val) { 

				if( empty($tab_key) ) {
					continue;
				}
				if( !defined( 'ES_EXTENSIONS_VERSION' ) && $tab_key == 'emallshop-theme-option') {
					continue;
				}
				$active_tab_cls	= ( $active_tab == $tab_key ) ? ' nav-tab-active' : '';
				$tab_link 		= add_query_arg( array( 'page' => $tab_key ), admin_url('admin.php') );
				?>
				<a class="nav-tab<?php echo esc_attr( $active_tab_cls ); ?>" href="<?php echo esc_url( $tab_link ); ?>"><?php echo esc_html( $tab_val ); ?></a>
			<?php } ?>
		</h2>
	<?php } ?>
	<div id="emallshop-dashboard" class="emallshop-dashboard wp-clearfix">
	