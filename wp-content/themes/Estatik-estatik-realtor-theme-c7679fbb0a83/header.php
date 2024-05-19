<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php global $ef_options;

if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
} else {
	do_action( 'wp_body_open' );
} ?>

<header id="header" <?php echo $ef_options->get( 'sticky_header' ) ? 'class="sticky-top"' : ''; ?>>
	<nav class="navbar navbar-expand-lg container">

		<a class="navbar-brand" href="<?php echo home_url(); ?>">
			<?php if ( $logo = ert_get_header_logo_image() ) : ?>
				<?php echo $logo; ?>
			<?php else: global $ef_options; ?>
				<?php if ( ! $ef_options->get( 'hide_blog_name' ) ) : ?>
                    <h1><?php echo get_option('blogname'); ?></h1>
				<?php endif; ?>
			<?php endif; ?>
		</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-header" aria-controls="navbar-header" aria-expanded="false" aria-label="<?php _e( 'Toggle navigation', 'ert' ); ?>">
			<i class="fa fa-navicon"></i>
		</button>

		<div class="collapse navbar-collapse" id="navbar-header">
			<?php

			if ( ! class_exists( 'WP_Bootstrap_Navwalker' ) ) {
				require_once 'inc/lib/class-wp-bootstrap-navwalker.php';
			}

			wp_nav_menu( array(
				'theme_location' => 'header-menu',
				'menu_class'     => 'navbar-nav mr-auto',
				'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
				'container' => false,
				'depth' => 2,
				'walker' => new WP_Bootstrap_Navwalker(),
			) ); ?>

			<?php if ( ! is_user_logged_in() ) : ?>
				<?php wp_nav_menu( array(
					'theme_location' => 'header-menu-unauthorized',
					'menu_class'     => 'navbar-nav my-2 my-lg-0 ert-login-menu',
					'container' => false,
					'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
					'depth' => 2,
					'walker' => new WP_Bootstrap_Navwalker(),
				) ); ?>
			<?php else: ?>
				<?php wp_nav_menu( array(
					'theme_location' => 'header-menu-authorized',
					'menu_class'     => 'navbar-nav my-2 my-lg-0',
					'container' => false,
					'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
					'depth' => 2,
					'walker' => new WP_Bootstrap_Navwalker(),
				) ); ?>
			<?php endif; ?>
		</div>
	</nav>
</header>
