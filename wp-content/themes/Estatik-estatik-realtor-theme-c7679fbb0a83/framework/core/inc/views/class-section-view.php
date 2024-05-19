<?php

/**
 * Class Estatik_Framework_Section_View.
 */
class Estatik_Framework_Section_View extends Abstract_Estatik_Framework_View {

	/**
	 * @inheritdoc
	 */
	public function render( $_echo = true ) {

		$content = $this->before_view();
		$content .= "<div class='{$this->_args['wrap_class']}' id='{$this->_args['wrap_id']}'>";

		if ( ! empty( $this->_args['title'] ) || ! empty( $this->_args['description'] ) ) {

			$content .= "<div class='ef-container-header'>";

			if ( $this->_args['title'] ) {
				$content .= "<h2>{$this->_args['title']}</h2>";
			}

			if ( ! empty( $this->_args['description'] ) ) {
				$content .= "<p>{$this->_args['description']}</p>";
			}

			$content .= "</div>";
		}

		$content .= $this->render_views( $this->_args, false );
		$content .= "</div>";
		$content .= $this->after_view();

		if ( $_echo ) {
			echo $content;
		} else {
			return $content;
		}
	}

	/**
	 * @return array
	 */
	public function get_default_args() {

		return array(
			'wrap_class' => 'ef-section js-ef-section',
			'wrap_id' => '',
			'sections' => null,
			'form' => null,
			'fields' => null,
		);
	}
}