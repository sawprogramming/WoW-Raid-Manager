<?php
require_once(plugin_dir_path(__FILE__)."../services/AttendanceService.php");

class AttendanceController {
	public function __construct() {
		$this->service = new AttendanceService();
	}

	public function Reroute () {
		switch($_SERVER['REQUEST_METHOD']) {
			case "DELETE":
				if(current_user_can("remove_users")) {
					$this->Delete();
				}
				break;
			case "GET":
				$this->Get();
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

	private function Add() {
		global $wpdb;

		try {
			// decode data
			if(!isset($_REQUEST['dailyEntities']) ^ isset($_REQUEST['entity'])) {
				throw new Exception("Missing the entities parameter.");
			}

			// daily attendance
			if($_REQUEST['dailyEntities']) {
				$entities = array();
				$data = json_decode(stripslashes($_REQUEST['dailyEntities']));

				foreach($data as $entity) {
					if(!isset($entity->ID, $entity->Date, $entity->Points)) {
						throw new Exception("Missing expected properties for Daily Attendance Entity.");
					}

					array_push($entities, new AttendanceEntity(
						NULL,
						$entity->ID,
						$entity->Date,
						$entity->Points
					));
				}

				if($this->service->AddGroupAttnd($entities) === FALSE) {
					throw new Exception($wpdb->last_error);
				}
			}

			// single record
			else {
				$data = json_decode(stripslashes($_REQUEST['entity']));

				if(!isset($data->PlayerID, $data->Date, $data->Points)) {
					throw new Exception("Missing expected properties for the Attendance Entity.");
				}

				$entity = new AttendanceEntity(
					NULL,
					$data->PlayerID,
					$data->Date,
					$data->Points
				);

				if($this->service->Add($entity) === FALSE) {
					throw new Exception($wpdb->last_error);
				}
			}	
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// success
		status_header(201);
		if($_REQUEST['entity']) {
			echo(json_encode($this->service->Get($wpdb->insert_id)));
		}
		die();
	}

	private function Delete() {
		$id = NULL;
		global $wpdb;

		try {
			// decode data
			if(!isset($_REQUEST['id'])) {
				throw new Exception("Could not find parameter with name 'id'.");
			}
			$id = $_REQUEST['id'];

			// delete record
			if($this->service->Delete($id) === FALSE) {
				throw new Exception($wpdb->last_error);
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// return success
		status_header(204);
		die();
	}

	private function Get() {
		global $wpdb;
		$result = NULL;

		try {
			// decode request
			if(!isset($_REQUEST['func'])) {
				throw new Exception("Could not find parameter with name 'func'.");
			}
			switch(strtolower($_REQUEST['func'])) {
				case 'all': 
					if(isset($_REQUEST['id'])) {
						$result = $this->service->GetAllById($_REQUEST['id']);
					} else {
						$result = $this->service->GetAll();
					}
					break;
				case 'breakdown':
					$result = $this->service->GetBreakdown();
					break;
				case 'chart':
					if(!isset($_REQUEST['id'])) {
						throw new Exception("Could not find parameter with name 'id'.");
					}
					$result = $this->service->GetChart($_REQUEST['id']);
					break;
				default:
					status_header(400);
					die();
					break;
			}

			if($result === FALSE) {
				throw new Exception($wpdb->last_error);
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// return results
		status_header(200);
		echo(json_encode($result));
		die();
	}

	private function Update() {
		global $wpdb;
		$result = $data = NULL;
		$entity = new AttendanceEntity();

		try {
			// decode request
			if(!isset($_REQUEST['entity'])) {
				throw new Exception("Could not find parameter with name 'entity'.");
			}
			$data = json_decode(stripslashes($_REQUEST['entity']));

			// create entity
			if(!isset($data->ID, $data->PlayerID, $data->Date, $data->Points)) {
				throw new Exception("The entity object was missing required fields.");
			}
			$entity->ID = $data->ID;
			$entity->Date = $data->Date;
			$entity->Points = $data->Points;
			$entity->PlayerID = $data->PlayerID;

			// update record
			if(($result = $this->service->Update($entity)) === FALSE) {
				throw new Exception($wpdb->last_error);
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// return result
		status_header(201);
		echo(json_encode($this->service->Get($entity->ID)));
		die();
	}

	private $service;
}
add_action('wp_ajax_wro_attendance', array(new AttendanceController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_attendance', array(new AttendanceController(), 'Reroute'));