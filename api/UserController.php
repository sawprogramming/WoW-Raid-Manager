<?php
require_once(plugin_dir_path(__FILE__)."../services/UserService.php");

class UserController {
	public function __construct() {
		$this->service = new UserService();
	}

	public function Reroute() {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				if(current_user_can("list_users")) {
					$this->GetAll();
				}
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
add_action('wp_ajax_wro_user', array(new UserController(), 'Reroute'));
