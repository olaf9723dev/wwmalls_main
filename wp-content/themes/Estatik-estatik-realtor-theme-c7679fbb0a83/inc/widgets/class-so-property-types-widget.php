<?php

/**
 * Class Ert_Testimonials_Widget.
 */
class Ert_Property_Types_Widget extends SiteOrigin_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-property-types-widget',
			__( 'Estatik Property Types Blocks', 'ert' ),
			array(
				'description' => __( 'Display Properties Types Block with Images.', 'ert'),
				'has_preview' => false,
			),
			array(

			),
			false,
			plugin_dir_path( __FILE__ )
		);
	}

	/**
	 * Return widget form settings.
	 *
	 * @return array
	 */
	function get_widget_form() {

		return array(
			'title' => array(
				'type' => 'text',
				'label' => __( 'Title', 'so-widgets-bundle' ),
			),
			'blocks' => array(
				'type' => 'repeater',
				'label' => __( 'Types Blocks', 'so-widgets-bundle' ),
				'item_name'  => __( 'Type', 'so-widgets-bundle' ),
				'item_label' => array(
					'selector'     => "[id*='type-name']",
					'update_event' => 'change',
					'value_method' => 'val'
				),
				'fields' => array(

					'name' => array(
						'type' => 'text',
						'label' => __( 'Block Title', 'so-widgets-bundle' ),
					),

					'link' => array(
						'type' => 'link',
						'label' => __( 'Link', 'so-widgets-bundle' ),
					),

					'image' => array(
						'type' => 'media',
						'label' => __( 'Image', 'so-widgets-bundle' ),
					),
				)
			),
		);
	}

	/**
	 * Return template variables.
	 *
	 * @param $instance
	 * @param $args
	 *
	 * @return array
	 */
	function get_template_variables( $instance, $args ) {

		return wp_parse_args( $instance, array(
			'blocks' => array(),
			'title' => '',
		) );
	}

	/**
	 * @param $instance
	 * @param $args
	 * @param $template_vars
	 * @param $css_name
	 *
	 * @return string
	 */
	public function get_html_content( $instance, $args, $template_vars, $css_name ) {

		if ( ! empty( $template_vars['blocks'] ) ) {

		    ob_start();

			$i = 0;

			echo $args['before_widget']; ?>

			<?php if ( ! empty( $instance['title'] ) ) : ?>
				<?php echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title']; ?>
			<?php endif; ?>

			<div class="row">
				<?php foreach ( $template_vars['blocks'] as $block ) : $i++; ?>

				<div class="col-md-6 ert-type__block <?php echo $i == 1 || $i == 4 ? 'ert-type__block--dark' : ''; ?>">

					<?php if ( ! empty( $block['link'] ) ) : ?>
						<a href="<?php echo sow_esc_url( $block['link'] ); ?>">
					<?php endif; ?>

					<?php if ( ! empty( $block['image'] ) && ( $image = wp_get_attachment_image_url( $block['image'], 'medium' ) ) ) : ?>
					<div class="ert-type__block--inner" style="background: url(<?php echo $image; ?>); background-repeat:no-repeat; background-size: cover;">
					<?php else: ?>
					<div class="ert-type__block">
					<?php endif; ?>

					<?php if ( ! empty( $block['name'] ) ) : ?>
						<h4><span><?php echo $block['name']; ?></span></h4>
					<?php endif; ?>

					</div>

					<?php if ( ! empty( $block['link'] ) ) : ?>
						</a>
					<?php endif; ?>

					</div>
					<?php if ( $i >=4 ) $i = 0; ?>
				<?php endforeach; ?>
			</div>

			<?php echo $args['after_widget'];

			return ob_get_clean();
		}
	}
}
