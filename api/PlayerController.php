<?php
require_once(plugin_dir_path(__FILE__)."../services/PlayerService.php");

class PlayerController {
	public function __construct() {
		$this->service = new PlayerService();
	}

	public function Reroute () {
		switch($_SERVER['REQUEST_METHOD']) {
			case "DELETE":
				if(current_user_can("remove_users")) {
					$this->Delete();
				}
				break;
			case "GET":
				$this->GetAll();
				break;
			case "POST":
				if(current_user_can("create_users")) {
					$this->Add();
				}
				break;
			case "PUT":
				if(current_user_can("edit_users")) {
					$this->Update();
				}
				break;
			default:
				// return bad request error
				status_header(400);
				die();
				break;
		}

		// return permissions error
		status_header(403);
		die();
	}

	private function GetAll() {
		global $wpdb;
		$result = NULL;

		try {
			// get entities
			if(($result = $this->service->GetAll()) === FALSE) {
				throw new Exception("An error occurred while processing this database request. Please try again later.");
			}
		} catch (Exception $e) {
			status_header(503);
			echo($e->getMessage());
			die();
		}

		// return entities
		status_header(200);
		echo(json_encode($result));
		die();
	}

	private function Delete() {
		global $wpdb;
		$result = $id = NULL;

		try {
			// decode data
			if(!isset($_REQUEST['id'])) {
				throw new Exception("Could not find parameter with name 'id'.");
			}
			$id = intval($_REQUEST['id']);

			// delete player
			if(($result = $this->service->Delete($id)) === FALSE) {
				throw new Exception($wpdb->last_error);
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// return success status
		status_header(204);
		die();
	}

	private function Add() {
		global $wpdb;
		$result = $data = NULL;
		$entity = new PlayerEntity();

		try {
			// decode data
			if(!isset($_REQUEST['entity'])) {
				throw new Exception("Could not find parameter with name 'entity'.");
			}
			$data = json_decode(stripslashes($_REQUEST['entity']));

			// create entity
			if(!(isset($data->Name, $data->ClassID) && array_key_exists("UserID", $data))) {
				throw new Exception("The entity object was missing required fields.");
			}
			$entity->Name = $data->Name;
			$entity->UserID = $data->UserID;
			$entity->ClassID = $data->ClassID;

			// add record
			if(($result = $this->service->Add($entity)) === FALSE) {
				throw new Exception($wpdb->last_error);
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// return added entity
		status_header(201);
		echo(json_encode($this->service->Get($wpdb->insert_id)));
		die();
	}

	private function Update() {
		global $wpdb;
		$result = $data = NULL;
		$entity = new PlayerEntity();

		try {
			// decode data
			if(!isset($_REQUEST['entity'])) {
				throw new Exception("Could not find parameter with name 'entity'.");
			}
			$data = json_decode(stripslashes($_REQUEST['entity']));

			// create entity
			if(!(isset($data->ID, $data->ClassID, $data->Name) && array_key_exists("UserID", $data))) {
				throw new Exception("The entity object was missing required fields.");
			}
			$entity->ID = $data->ID;
			$entity->UserID = $data->UserID;
			$entity->ClassID = $data->ClassID;
			$entity->Name = $data->Name;

			// update record
			if(($result = $this->service->Update($entity)) === FALSE) {
				throw new Exception($wpdb->last_error);
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// return updated entity
		status_header(200);
		echo(json_encode($this->service->Get($entity->ID)));
		die();
	}

	private $service;
}
add_action('wp_ajax_wro_player', array(new PlayerController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_player', array(new PlayerController(), 'Reroute'));