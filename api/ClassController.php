<?php
require_once(plugin_dir_path(__FILE__)."../services/ClassService.php");

class ClassController {
	public function __construct() {
		$this->service = new ClassService();
	}

	public function Reroute() {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->GetAll();
				break;
			default:
				die();
				break;
		}
	}

	private function GetAll() {
		echo(json_encode($this->service->GetAll()));
		die();
	}

	private $service;
}
add_action('wp_ajax_wro_class', array(new ClassController(), 'Reroute'));
