<?php
/**
 * Theme widgets for WooCommerce.
 *
 * @package Motta
 */

namespace Motta\Addons\Widgets;

use \Motta\Addons\Helper;

/**
 * Icon Box widget class.
 */
class IconBox extends \WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $default;


	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->defaults = array(
			'title'          => '',
			'box'         => array(),
		);

		if ( is_admin() ) {
			$this->admin_hooks();
		}

		parent::__construct(
			'motta-icon-box',
			esc_html__( 'Motta - Icon Box', 'motta-addons' ),
			array(
				'classname'                   => 'icon-box-widget',
				'customize_selective_refresh' => true,
			),
			array( 'width' => 560 )
		);
	}

	/**
	 * Admin hooks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, '_setting_fields_template' ) );
		add_action( 'admin_footer', array( $this, '_setting_fields_template' ) );
	}

	/**
	 * Output the widget content.
	 *
	 * @since 1.0.0
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments
	 * @param array $instance Saved values from database
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		echo $args['before_widget'];

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		if ( ! empty( $instance['box'] ) ) {
			?><div class="motta-icon-box-widget"><?php
				foreach ( $instance['box'] as $box ) {
					?>
					<div class="motta-icon-box-widget__item">
						<?php if ( $box['icon'] ) : ?>
							<span class="motta-icon-box-widget__icon motta-svg-icon" <?php echo ! empty( $box['color'] ) ? 'style="background-color: rgba( '.$this->hex2RGB($box['color'], true).', 0.09 ); color: '.$box['color'].'; padding: 9px; border-radius: 100%; border: 0;"' : ''; ?>><?php echo \Motta\Icon::sanitize_svg( $box['icon'] ); ?></span>
						<?php endif; ?>

						<?php if ( $box['text'] ) : ?>
							<div class="motta-icon-box-widget__text"><?php echo wp_kses_post( $box['text'] ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $box['button'] ) ) : ?>
							<?php if( ! empty( $box['link'] ) ) : ?>
								<a href="<?php echo esc_url( $box['link'] ); ?>" class="motta-icon-box-widget__button motta-button motta-button--text"><?php echo esc_html( $box['button'] ); ?></a>
							<?php else : ?>
								<span href="<?php echo esc_url( $box['link'] ); ?>" class="motta-icon-box-widget__button motta-button motta-button--text"><?php echo esc_html( $box['button'] ); ?></span>
							<?php endif; ?>
						<?php endif; ?>
					</div>
					<?php
				}
			?></div><?php
		}

		echo $args['after_widget'];
	}

	/**
	 * Outputs the settings form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'motta-addons' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<div class="motta-icon-box__section">
			<div class="motta-icon-box__fields">
				<?php $this->_setting_fields( $instance['box'] ); ?>
			</div>

			<p class="motta-icon-box__actions">
				<button type="button" class="motta-icon-box__add-new button-link" data-name="<?php echo esc_attr( $this->get_field_name( 'box' ) ); ?>" data-count="<?php echo count( $instance['box'] ) ?>">+ <?php esc_html_e( 'Add a new box', 'motta-addons' ) ?></button>
			</p>
		</div>

		<?php
	}

	/**
	 * Get the setting array fields.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_fields_settings() {
		if ( isset( $this->box_settings ) ) {
			return $this->box_settings;
		}

		$this->box_settings = array(
			'icon' => array(
				'type' => 'textarea',
				'label' => __( 'Icon', 'motta-addons' ),
			),
			'color' => array(
				'type' => 'color',
				'label' => __( 'Icon Color', 'motta-addons' ),
			),
			'text' => array(
				'type' => 'text',
				'label' => __( 'Text', 'motta-addons' ),
			),
			'button' => array(
				'type' => 'text',
				'label' => __( 'Button', 'motta-addons' ),
			),
			'link' => array(
				'type' => 'text',
				'label' => __( 'Button Link', 'motta-addons' ),
			),
		);

		return $this->box_settings;
	}

	/**
	 * Display sets of filter setting fields
	 *
	 * @since 1.0.0
	 *
	 * @param string $context
	 */
	protected function _setting_fields( $fields = array(), $context = 'display' ) {
		$box_settings = $this->get_fields_settings();
		$_fields   = 'display' == $context ? $fields : array( 1 );

		foreach ( $_fields as $index => $field ) :
			$title = ! empty( $field['text'] ) ? $field['text'] : esc_html__( 'Item', 'motta-addons' );
			?>
			<div class="motta-icon-box__field">
				<div class="motta-icon-box__field-top">
					<button type="button" class="motta-icon-box__field-toggle">
						<span class="motta-icon-box__field-toggle-indicator" aria-hidden="true"></span>
					</button>

					<div class="motta-icon-box__field-title"><?php echo $title; ?></div>

					<div class="motta-icon-box__field-actions">
						<button type="button" class="motta-icon-box__remove button-link button-link-delete">
							<span class="screen-reader-text"><?php esc_html_e( 'Remove', 'motta-addons' ) ?></span>
							<span class="dashicons dashicons-no-alt"></span>
						</button>
					</div>
				</div>
				<div class="motta-icon-box__field-options">
					<?php
					foreach ( $box_settings as $name => $options ) {
						$options['name']  = 'display' == $context ? "box[$index][$name]" : '{{data.name}}[{{data.count}}][' . $name . ']';
						$options['value'] = ! empty( $field[ $name ] ) ? $field[ $name ] : '';
						$options['class'] = 'motta-icon-box__field-option';
						$options['attributes'] = array( 'data-option' => 'box:' . $name );
						$options['__instance'] = $field;

						$this->setting_field( $options, $context );
					}
					?>
				</div>
			</div>
			<?php
		endforeach;
	}

	/**
	 * Render setting field
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param string $context
	 */
	protected function setting_field( $args, $context = 'display' ) {
		$args = wp_parse_args( $args, array(
			'name'        => '',
			'label'       => '',
			'type'        => 'text',
			'value'       => '',
			'class'       => '',
			'input_class' => '',
			'attributes'  => array(),
			'options'     => array(),
			'__instance'  => null,
		) );

		// Build field attributes.
		$field_attributes = array(
			'class' => $args['class'],
			'data-option' => $args['name'],
		);

		if ( ! empty( $args['attributes'] ) ) {
			foreach ( $args['attributes'] as $attr_name => $attr_value ) {
				$field_attributes[ $attr_name ] = is_array( $attr_value ) ? implode( ' ', $attr_value ) : $attr_value;
			}
		}

		$field_attributes_string = '';

		foreach ( $field_attributes as $name => $value ) {
			$field_attributes_string .= " $name=" . '"' . esc_attr( $value ) . '"';
		}

		// Build input attributes.
		$input_attributes = array(
			'id' => 'display' == $context ? $this->get_field_id( $args['name'] ) : '',
			'name' => 'display' == $context ? $this->get_field_name( $args['name'] ) : $args['name'],
			'class' => 'widefat ' . $args['input_class'],
		);

		if( 'color' == $args['type'] ) {
			$input_attributes[ 'class' ] = $input_attributes[ 'class' ] . ' motta-color-widget';
		}

		if ( ! empty( $args['options'] ) && 'select' != $args['type'] ) {
			foreach ( $args['options'] as $attr_name => $attr_value ) {
				$input_attributes[ $attr_name ] = is_array( $attr_value ) ? implode( ' ', $attr_value ) : $attr_value;
			}
		}

		$input_attributes_string = '';

		foreach ( $input_attributes as $name => $value ) {
			$input_attributes_string .= " $name=" . '"' . esc_attr( $value ) . '"';
		}

		// Render field.
		echo '<p ' . $field_attributes_string . '>';

		switch ( $args['type'] ) {
			case 'color':
				?>
				<label for="<?php echo esc_attr( $input_attributes['id'] ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
				<input type="text" value="<?php echo esc_attr( $args['value'] ); ?>" <?php echo $input_attributes_string ?> />
				<?php
				break;

			case 'textarea':
				?>
				<label for="<?php echo esc_attr( $input_attributes['id'] ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
				<textarea <?php echo $input_attributes_string ?>><?php echo esc_textarea( $args['value'] ) ?></textarea>
				<?php
				break;

			default:
				?>
				<label for="<?php echo esc_attr( $input_attributes['id'] ); ?>"><?php echo esc_html( $args['label'] ); ?></label>
				<input type="<?php echo esc_attr( $args['type'] ) ?>" value="<?php echo esc_attr( $args['value'] ); ?>" <?php echo $input_attributes_string ?>/>
				<?php
				break;
		}

		echo '</p>';
	}

	/**
	 * Updates a particular instance of a widget
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                      = $new_instance;
		$instance['title']             = strip_tags( $instance['title'] );

		// Reorder filters.
		if ( isset( $instance['box'] ) ) {
			$instance['box'] = array();

			foreach ( $new_instance['box'] as $box ) {
				array_push( $instance['box'], $box );
			}
		}

		return $instance;
	}

	/**
	 * Enqueue scripts in the backend.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_scripts( $hook ) {
		if ( 'widgets.php' != $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_style( 'motta-icon-box-admin', MOTTA_ADDONS_URL . 'inc/widgets/icon-box/assets/css/icon-box-admin.css', array(), '20210311' );
		wp_enqueue_script( 'motta-icon-box-widget-admin', MOTTA_ADDONS_URL . 'inc/widgets/icon-box/assets/js/icon-box-admin.js', array( 'wp-util' ), '20210311', true );
	}

	/**
	 * Underscore template for filter setting fields
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function _setting_fields_template() {
		global $pagenow;

		if ( 'widgets.php' != $pagenow && 'customize.php' != $pagenow ) {
			return;
		}
		?>

        <script type="text/template" id="tmpl-motta-icon-box">
			<?php $this->_setting_fields( array(), 'template' ); ?>
        </script>

		<?php
	}

	public function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
	}
}
