<?php
namespace WRO\API;
require_once(plugin_dir_path(__FILE__)."../services/ClassService.php");
use WRO\Services as Services;

class ClassController {
	public function __construct() {
		$this->service_ = new Services\ClassService();
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
		echo(json_encode($this->service_->GetAll()));
		die();
	}

	private $service_;
};
add_action('wp_ajax_wro_class', array(new ClassController(), 'Reroute'));