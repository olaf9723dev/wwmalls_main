<?php

/**
 * @var $settings
 * @var $this Ert_Testimonials_Widget
 * @var $testimonials array
 * @var $args array
 */

$testimonials = ! empty( $args['testimonials'] ) ? $args['testimonials'] : $testimonials;

if ( ! empty( $testimonials ) ) :

	wp_enqueue_style( 'es-slick-style' );

	echo ! empty( $args['before_widget'] ) ? $args['before_widget'] : '';

	if ( ! empty( $instance['title'] ) ) : ?>
		<?php echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title']; ?>
	<?php endif; ?>

	<div class="ert-testimonials js-ert-testimonials">
		<?php foreach ( $testimonials as $testimonial ) :
            if ( is_array( $testimonial['url'] ) ) $testimonial['url'] = $testimonial['url']['url']; ?>
			<div class="ert-testimonials__item">

				<?php if ( ! empty( $testimonial['image'] ) ) :
					$show_image_link = ! empty( $testimonial['link_image'] ) && ! empty( $testimonial['url'] ); ?>

                    <?php if ( $show_image_link ) : ?>
                        <a href="<?php echo function_exists( 'sow_esc_url' ) ? sow_esc_url( $testimonial['url'] ) : esc_url( $testimonial['url'] ) ?>" <?php if ( ! empty( $testimonial['new_window'] ) ) { echo 'target="_blank" rel="noopener noreferrer"'; } ?>>
                    <?php endif; ?>

                    <?php if ( is_array( $testimonial['image'] ) ) : ?>
                        <div class="ert-testimonial__image" style="background-repeat: no-repeat; background-size: cover; background-image: url(<?php echo $testimonial['image']['url']; ?>); "></div>
                    <?php elseif ( function_exists( 'sow_esc_url' ) ) : ?>
					    <div class="ert-testimonial__image" style="background-repeat: no-repeat; background-size: cover; background-image: url(<?php echo $this->testimonial_user_image_url( $testimonial['image'] ); ?>); "></div>
					<?php endif; ?>

					<?php if ( $show_image_link ) : ?>
                        </a>
                    <?php endif; ?>
				<?php endif; ?>

				<?php if ( ! empty( $testimonial['name'] ) ) : ?>
					<h4 class="ert-testimonial__name">
						<?php if ( ! empty( $testimonial['link_name'] ) && ! empty( $testimonial['url'] ) ) : ?>
                            <a href="<?php echo function_exists( 'sow_esc_url' ) ? sow_esc_url( $testimonial['url'] ) : esc_url( $testimonial['url'] ) ?>" <?php if( ! empty( $new_window ) ) { echo 'target="_blank" rel="noopener noreferrer"'; } ?>>
                        <?php endif; ?>
                        <?php echo esc_html( $testimonial['name'] ) ?>
                        <?php if ( ! empty( $testimonial['link_name'] ) && ! empty( $testimonial['url'] ) ) : ?>
                            </a>
					    <?php endif; ?>
                    </h4>
				<?php endif; ?>

				<?php if ( ! empty( $testimonial['sub_name'] ) ) : ?>
					<span class="ert-testimonial__sub-name">
						<?php echo $testimonial['sub_name']; ?>
					</span>
				<?php endif; ?>

				<?php if ( ! empty( $testimonial['text'] ) ) : ?>
					<div class="ert-testimonial__content">
						<?php echo wp_kses_post( $testimonial['text'] ) ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php echo ! empty( $args['after_widget'] ) ? $args['after_widget'] : '';
endif;
