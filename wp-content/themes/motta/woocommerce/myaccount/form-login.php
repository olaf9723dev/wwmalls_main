<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="u-columns col2-set woocommerce-account__summary" id="customer_login">

	<div class="woocommerce-account__heading">
		<h2 class="active" data-tab="login"><?php esc_html_e( 'Sign in', 'motta' ); ?></h2>
		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
			<h2 class="register" data-tab="register"><?php esc_html_e( 'Create Account', 'motta' ); ?></h2>
		<?php endif; ?>
	</div>

	<div class="woocommerce-account__form">
		<form class="woocommerce-form woocommerce-form-login login active" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label><?php echo esc_html__( 'Email', 'motta' ); ?></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label><?php echo esc_html__( 'Password', 'motta' ); ?></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
				<a class="woocommerce-LostPassword lost_password" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'motta' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row form-row-remember">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Keep me signed in', 'motta' ); ?></span>
				</label>
			</p>
			<p class="form-row">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login"><?php esc_html_e( 'Sign in', 'motta' ); ?></button>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

			<div class="motta-create-account"><a href="#"><?php echo esc_html__( 'New Customer? Create account', 'motta' ); ?></a></div>
		</form>
		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
			<form method="post" id="register" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php
					if( class_exists('NextendSocialLogin', false) ) {
						echo '<div class="nsl-register-text">'. esc_html__( 'Sign up with...', 'motta' ) .'</div>';
						echo do_shortcode( '[nextend_social_login]' );
						echo '<div class="nsl-register-text nsl-register-text__sign-up">'. esc_html__( 'or sign up using your email address', 'motta' ) .'</div>';
					}
				?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label><?php echo esc_html__( 'Name', 'motta' ); ?></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					</p>

				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label><?php echo esc_html__( 'Email', 'motta' ); ?></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label><?php echo esc_html__( 'Password', 'motta' ); ?></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password"/>
					</p>

				<?php else : ?>

					<p><?php esc_html_e( 'A password will be sent to your email address.', 'motta' ); ?></p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'motta' ); ?>"><?php esc_html_e( 'Register', 'motta' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

				<div class="motta-sign-in"><a href="#"><?php echo esc_html__( 'Already a member? Sign in', 'motta' ); ?></a></div>
			</form>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
