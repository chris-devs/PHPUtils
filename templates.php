<?php
	class templates {
		private static $_instance = null;
		
		private function __construct() {
		}
		
		protected function __clone() {
		}
		
		static public function getInstance() {
			if (is_null(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		// this method allows us to easily adjust the templates location on any website
		function get_templates_dir() {			
			return dirname(dirname(__FILE__)) . '/templates/';
			
			/*
			// global $root_dir is defined
			global $root_dir;
			if ($root_dir) {
				return trim($root_dir, '/') . '/templates/';
			}
			
			// global $root_dir is not defined
			if (!$root_dir) {			
				return dirname(dirname(__FILE__)) . '/templates/';
			}
			*/
		}
		
		function parse_template_name($template = '') {		
			if (!$template) return;
			
			// parse template name, mode, filename, etc.
			$template_info = array();

			// full template path (without mode)
			// HINT: $template can contain path to template (e.g., 'users/user_info')
			$arr = explode('.', $template);
			$template_info['template_path'] = @$arr[0];

			// get template mode (if any)
			$template_info['template_mode'] = (@$arr[1]) ? $arr[1] : '';

			// get short template name (without path)
			$arr = explode('/', $template_info['template_path']);
			$template_info['template_name'] = @$arr[count($arr) - 1];

			// get my_template.mode (without path)
			$template_info['template_name_mode'] = @$template_info['template_name'];
			if (@$template_info['template_mode']) $template_info['template_name_mode'] .= '.' . $template_info['template_mode'];

			// get template filename
			$dir = $this->get_templates_dir();
			$template_info['template_file'] = $dir . $template_info['template_path'] . '.php';
			
			return $template_info;
		}
		
		// load_template() method loads and returns template as string;
		// $template is the template filename relative to the "templates" folder (without .php extension);
		// $template can also contain a mode (for example, my_template.mode)		
		// $data is any data to pass to the template;
		function load_template($template, $data = array()) {
			// parse template name, mode, filename, etc.
			$template_info = $this->parse_template_name($template);
			if (!is_array($template_info)) return;
						
			// add these values to $data array, so they will be available
			// within the template file
			$data['template_info'] = $template_info;
			
			/*foreach ($template_info as $k=>$v) {
				$data[$k] = $v;
			}*/
			
			// check template file (if exists)
			$filename = @$template_info['template_file'];
			if (!file_exists($filename)) return;
			
			// load template
			ob_start();
			include($filename);
			$content = ob_get_clean();
			
			// result
			return $content;
		}
		
		function print_template($template, $data = array()) {			
			echo $this->load_template($template, $data);
		}
	}
?>