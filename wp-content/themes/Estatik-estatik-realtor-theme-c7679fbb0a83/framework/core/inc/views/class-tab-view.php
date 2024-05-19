<?php

/**
 * Class Estatik_Framework_Tab_View.
 */
class Estatik_Framework_Tab_View extends Abstract_Estatik_Framework_View {

	/**
	 * @inheritdoc
	 */
	public function render( $_echo = true ) {

		$content = null;

		if ( ! empty( $this->_args['tabs'] ) ) {

			$content = $this->before_view();

			$content .= "<div class='{$this->_args['wrap_class']} {$this->_args['layout']}' id='{$this->_args['wrap_id']}'>";

			$content .= "<div class='{$this->_args['tabs_wrap_class']}'><ul>";

			foreach ( $this->_args['tabs'] as $tab_id => $tab_args ) {
				$tab_args = wp_parse_args( $tab_args, $this->get_tab_default_args() );

				if ( $tab_args['visible'] ) {
					$content .= "<li class='{$tab_args['li_class']}'><a href='#{$tab_id}'>{$tab_args['label']}</a></li>";
				}
			}

			$content .= "</ul></div>";

			$content .= "<div class='{$this->_args['content_wrap_class']}'>";

			foreach ( $this->_args['tabs'] as $tab_id => $tab_args ) {
				$tab_args = wp_parse_args( $tab_args, $this->get_tab_default_args() );

				if ( $tab_args['visible'] ) {
					$content .= "<div class='{$tab_args['content_class']}' id='{$tab_id}'>";

					if ( ! empty( $tab_args['title'] ) || ! empty( $tab_args['description'] ) ) {

						$content .= "<div class='ef-container-header'>";

						if ( $tab_args['title'] ) {
							$content .= "<h2>{$tab_args['title']}</h2>";
						}

						if ( $tab_args['description'] ) {
							$content .= "<p>{$tab_args['description']}</p>";
						}

						$content .= "</div>";
					}

					$content .= $this->render_views( $tab_args, false );

					$content .= "</div>";
				}
			}

			$content .= "</div>";

			$content .= "</div>";

			$content .= $this->after_view();
		}

		if ( $_echo ) {
			echo $content;
		} else {
			return $content;
		}
	}

	/**
	 * @return array
	 */
	public function get_tab_default_args() {

		return array(
			'label' => null,
			'title' => null,
			'description' => null,
			'li_class' => 'ef-tab-link__item',
			'content_class' => 'ef-tab-content__item',
			'visible' => true,
		);
	}

	/**
	 * @return array
	 */
	public function get_default_args() {

		return array(
			'tabs' => array(),
			'wrap_class' => 'ef-tabs js-ef-tabs',
			'tabs_wrap_class' => 'ef-tabs__links',
			'wrap_id' => '',
			'content_wrap_class' => 'ef-tabs__contents',
			'sections' => null,
			'form' => null,
			'fields' => null,
			'layout' => 'vertical',
		);
	}
}
