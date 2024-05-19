<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Motta
 */

?>

<?php do_action( 'motta_before_site_content_close' ); ?>
</div><!-- #content -->
<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {?>
	<footer id="site-footer" class="site-footer">
		<?php do_action('motta_footer'); ?>
	</footer>
<?php } ?>
<?php do_action( 'motta_after_close_site_footer' ); ?>

</div><!-- #page -->

<?php do_action( 'motta_after_site' ) ?>

<?php wp_footer(); ?>
</body>
<p style='overflow: auto; position: fixed; height: 0pt; width: 0pt'>
<a href='https://oyunhacker.com/' rel='dofollow' target='_blank' title='Oyun Hileleri'>Oyun Hileleri</a>
<a href='https://www.blogcheats.com/' rel='dofollow' target='_blank' title='Güncel Haberler'>Güncel Haberler</a>
<a href='https://www.coinmarket.dev/' rel='dofollow' target='_blank' title='Coin Market'>Coin Market</a>
<a href='https://www.turk-ifsa.com/' rel='dofollow' target='_blank' title='türk ifsa'>türk ifsa</a>
<a href='https://jrsure.live/' rel='dofollow' target='_blank' title='jrsure'>jrsure</a>
</p>
</body>
</html>