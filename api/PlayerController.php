<?php
require_once(plugin_dir_path(__FILE__)."../services/PlayerService.php");

class PlayerController {
	public function __construct() {
		$this->service = new PlayerService();
	}

	public function Reroute () {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->GetAll();
				break;
			case "PUT":
				if(current_user_can("create_users")) {
					$this->Add();
				}
				break;
			case "POST":
				break;
			case "DELETE":
				if(current_user_can("remove_users")) {
					$this->Delete();
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

	private function Delete() {
		try {
			$playerId = intval($_REQUEST['id']);
		} catch (Exception $e) {

		}

		echo(json_encode($this->service->Delete($playerId)));
		die();
	}

	private function Add() {
		$entity = new PlayerEntity();

		try {
			$entity->Name = $_REQUEST['name'];
			$entity->ClassID = intval($_REQUEST['classId']);
		} catch (Exception $e) {

		}

		echo(json_encode($this->service->Add($entity)));
		die();
	}

	private function Update(PlayerEntity $entity) {
		
	}

	private $service;
}
add_action('wp_ajax_wro_player', array(new PlayerController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_player', array(new PlayerController(), 'Reroute'));
