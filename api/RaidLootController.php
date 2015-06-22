<?php
require_once(plugin_dir_path(__FILE__)."../services/RaidLootService.php");

class RaidLootController {
	public function __construct() {
		$this->service = new RaidLootService();
	}

	public function Reroute () {

		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->GetAll();
				break;
			case "POST":
				break;
			case "DELETE":
				if(current_user_can('remove_users')) {
					$this->DeleteRow();
				}
				break;
			default:
				die();
				break;
		}
	}

	private function DeleteRow() {
		try {
			$id = intval($_REQUEST['id']);
		} catch (Exception $e) {

		}

		echo(json_encode($this->service->DeleteRow($id)));
		die();
	}

	private function GetAll() {
		echo(json_encode($this->service->GetAll()));
		die();
	}

	private $service;
}
add_action('wp_ajax_wro_raidloot', array(new RaidLootController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_raidloot', array(new RaidLootController(), 'Reroute'));
