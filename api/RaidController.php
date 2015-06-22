<?php
include_once (plugin_dir_path(__FILE__)."../services/RaidService.php");

class RaidController {
	public function __construct() {
		$this->service = new RaidService();
	}

	public function Reroute () {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->GetAll();
				break;
			case "PUT":
				break;
			case "DELETE":
				if(current_user_can("remove_users")) {
					$this->DeleteRow();
				}
				break;
			default:
				die();
				break;
		}
	}

	private function Add() {
		$entity = new RaidEntity();

		try {
			$entity->Name = $_REQUEST['name'];
		} catch (Exception $e) {

		}

		echo(json_encode($this->service->Add($entity)));
		die();
	}

	private function Delete() {
		try {
			$id = intval($_REQUEST['id']);
		} catch (Exception $e) {

		}

		echo(json_encode($this->service->Delete($id)));
		die();
	}

	private function GetAll() {
		echo(json_encode($this->service->GetAll()));
		die();
	}

	private $service;
}
add_action('wp_ajax_wro_raid', array(new RaidController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_raid', array(new RaidController(), 'Reroute'));
