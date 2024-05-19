<?php
if (!class_exists('EMALLSHOP_SIDEBAR')) {

	class EMALLSHOP_SIDEBAR{
		
		public $sidebars  = array();
		public $stored = "emallshop_sidebars";
		public $paths  = array();

		function __construct() {
			$this->title = esc_html__('Add Custom Widget Area', 'emallshop');
			$this->stored = 'emallshop_sidebars';

			add_action('load-widgets.php', array(&$this, 'enqueue_assets') , 4);
			add_action('load-widgets.php', array(&$this, 'add_sidebar'), 99);

			add_action('widgets_init', array(&$this, 'registerSidebars') , 900 );

			// ajax
			add_action('wp_ajax_delete_custom_sidebar', array(&$this, 'delete_sidebar') , 50);
		}

		public function registerSidebars() {
			if (empty($this->sidebars)) {
				$this->sidebars = get_option($this->stored);
			}
			$args = array(
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="widget-head"><h3 class="widget-title">',
				'after_title'   => '</h3></div>'
			);
			if (is_array($this->sidebars)) {
				foreach ($this->sidebars as $sidebar) {
					$args['class'] = 'emallshop-custom';
					$args['name']  = $sidebar;
					$args['id']  = sanitize_title($sidebar);
					register_sidebar($args);
				}
			}
		}

		public function enqueue_assets() {
			add_action('admin_print_scripts', array(&$this, 'add_field') );
			wp_enqueue_script('es-sidebar-script' , EMALLSHOP_ADMIN_URI . '//assets/js/custom_sidebar.js');
			wp_enqueue_style( 'es-sidebar-style' , EMALLSHOP_ADMIN_URI . '/assets/css/custom_sidebar.css');
		}

		public function add_field() {
            $add_widget_js = "\n<script type='text/html' id='tmpl-add-widget'>";
			$add_widget_js .= "\n  <form class='form-add-widget' method='POST'>";
			$add_widget_js .= "\n  <h3>". $this->title ."</h3>";
			$add_widget_js .= "\n    <p><input size='30' type='text' value='' placeholder = '". esc_attr__('Enter Name for new Widget Area', 'emallshop') ."' name='form-add-widget' /></p>";
			$add_widget_js .= "\n    <input class='button button-primary' type='submit' value='". esc_attr__('Add Widget Area', 'emallshop') ."' />";
			$add_widget_js .= "\n    <input type='hidden' name='custom-sidebar-nonce' value='". wp_create_nonce('custom-sidebar-nonce') ."' />";
			$add_widget_js .= "\n  </form>";
			$add_widget_js .= "\n</script>\n";
			echo apply_filters( 'emallshop_template_add_widget_js', $add_widget_js ); // WPCS: XSS OK.
		}

		public function add_sidebar() {
            if (!empty($_POST['form-add-widget'])) {
                $this->sidebars = get_option($this->stored);
                $name = $this->get_name($_POST['form-add-widget']);
                if (empty($this->sidebars)) {
                    $this->sidebars = array($name);
                } else {
                    $this->sidebars = array_merge($this->sidebars, array($name));
                }
                update_option($this->stored, $this->sidebars);
                wp_redirect(admin_url('widgets.php'));
                die();
            }
		}

		public function delete_sidebar() {

            check_ajax_referer('custom-sidebar-nonce');

			if (empty($_POST['name'])) return;

			$name = stripslashes($_POST['name']);
			$this->sidebars = get_option($this->stored);

			if (($key = array_search($name, $this->sidebars)) !== false) {
				unset($this->sidebars[$key]);
				update_option($this->stored, $this->sidebars);
			}

			die('widget-deleted');
		}

		public function get_name($name) {
			global $wp_registered_sidebars;
			$take = array();

			if (empty($this->sidebars)) $this->sidebars = array();
			if (empty($wp_registered_sidebars)) return $name;

            foreach ($wp_registered_sidebars as $sidebar) {
				$take[] = $sidebar['name'];
		    }
			$take = array_merge($take, $this->sidebars);

		    if (in_array($name, $take)) {

                 $counter = substr($name, -1);

                if (!is_numeric($counter))  {
					$newName = $name . " 1";
                } else {
					$newName = substr($name, 0, -1) . ((int) $counter + 1);
                }
                $name = $this->get_name($newName);
		    }
		    return $name;
		}

	}
}