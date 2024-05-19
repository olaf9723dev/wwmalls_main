<?php
/**
 * Template part for displaying the account panel
 *
 * @package Motta
 */

if ( ! function_exists( 'WC' ) ) {
	return;
}
?>

<div id="account-panel" class="offscreen-panel account-panel">
	<div class="panel__backdrop"></div>
	<div class="panel__container">
		<?php echo \Motta\Icon::get_svg( 'close', 'ui', 'class=panel__button-close' ); ?>

		<div class="panel__header">
			<?php if ( is_user_logged_in() ) : ?>
				<div class="account-panel__avatar logged"><?php echo get_avatar( get_current_user_id(), 44 ); ?></div>
				<div class="account-panel__name"><?php echo get_user_meta( get_current_user_id() )['nickname'][0]; ?></div>
			<?php else : ?>
				<div class="account-panel__avatar"><?php echo \Motta\Icon::get_svg( 'account' ); ?></div>
				<div class="account-panel__name"><?php echo esc_html__( 'Account', 'motta' ); ?></div>
			<?php endif; ?>
		</div>

		<div class="panel__content">
			<?php \Motta\Helper::account_links(); ?>
		</div>
	</div>
</div>