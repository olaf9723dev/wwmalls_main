<?php

/**
 * Class Ert_Testimonials_Widget.
 */
class Ert_Performance_Widget extends SiteOrigin_Widget {

	/**
	 * Ert_Testimonials_Widget constructor.
	 */
	function __construct() {
		parent::__construct(
			'ert-perfomance-widget',
			__( 'Estatik Performance Blocks', 'ert' ),
			array(
				'description' => __( 'Display Statistics.', 'ert'),
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
				'label' => __( 'Statistics Blocks', 'so-widgets-bundle' ),
				'item_name'  => __( 'Item', 'so-widgets-bundle' ),
				'item_label' => array(
					'selector'     => "[id*='type-name']",
					'update_event' => 'change',
					'value_method' => 'val'
				),
				'fields' => array(
					'title' => array(
						'type' => 'text',
						'label' => __( 'Block Title', 'so-widgets-bundle' ),
					),
					'description' => array(
						'type' => 'textarea',
						'label' => __( 'Description', 'so-widgets-bundle' ),
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

			echo $args['before_widget']; ?>

			<?php if ( ! empty( $instance['title'] ) ) : ?>
				<?php echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title']; ?>
			<?php endif; ?>

            <div class="container">
                <div class="row">
                    <?php foreach ( $template_vars['blocks'] as $block ) : ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg ert-perfomance__item">
                            <?php if ( ! empty( $block['title'] ) ) : ?>
                                <h4><?php echo $block['title']; ?></h4>
                            <?php endif; ?>
                            <?php if ( ! empty( $block['description'] ) ) : ?>
                                <p><?php echo $block['description']; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

			<?php echo $args['after_widget'];

			return ob_get_clean();
		}
	}
}
