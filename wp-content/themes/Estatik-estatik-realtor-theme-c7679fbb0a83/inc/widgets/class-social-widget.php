<?php

/**
 * Social icons widget.
 */
class Ert_WP_Social_Widget extends WP_Widget {

    /**
     * @return void
     */
    public function __construct() {
        parent::__construct( 'es_social_widget' , __( 'Estatik Social Icons', 'es-plugin' ) );
    }

    /**
     * @return array
     */
    public static function get_social_networks() {
        return array(
            'facebook' => __( 'Facebook', 'ert' ),
            'twitter' => __( 'Twitter', 'ert' ),
            'instagram' => __( 'Instagram', 'ert' ),
            'linkedin' => __( 'Linkedin', 'ert' ),
            'youtube' => __( 'Youtube', 'ert' ),
        );
    }

    /**
     * Display widget form.
     *
     * @param array $instance
     *
     * @return string|void
     */
    public function form( $instance ) {
        $instance = wp_parse_args( $instance, array(
            'title' => '',
        ) ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <?php foreach ( static::get_social_networks() as $key => $name ) : ?>
            <p>
                <label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $name; ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo ! empty( $instance[ $key ] ) ? $instance[ $key ] : ''; ?>" />
            </p>
        <?php endforeach;
    }

    /**
     * Display widget handler.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', ! empty( $instance['title'] ) ? $instance['title'] : '', $this );
        echo ! empty( $args['before_widget'] ) ? $args['before_widget'] : '';

        If ( ! empty ( $title ) ) {
            echo ! empty( $args['before_title'] ) ? $args['before_title'] : '';
            echo $title;
            echo ! empty( $args['after_title'] ) ? $args['after_title'] : '';
        }

        $data = array();

        if ( ! empty( $instance ) ) {
            foreach ( static::get_social_networks() as $key => $name ) {
                if ( ! empty( $instance[ $key ] ) ) {
                    $data[ $key ] = $instance[ $key ];
                }
            }
        }

        include locate_template( 'template-parts/blocks/social-links.php' );

        echo ! empty( $args['after_widget'] ) ? $args['after_widget'] : '';
    }
}
