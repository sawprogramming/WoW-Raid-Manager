<?php
require_once(plugin_dir_path(__FILE__)."../services/DisputeService.php");

class DisputeController {
	public function __construct() {
		$this->service = new DisputeService();
	}

	public function Reroute () {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				if(current_user_can("list_users")) {
					$this->Get();
				}
				break;
			case "POST":
				$this->Add();
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
			// single record
			$data = json_decode(stripslashes($_REQUEST['entity']));

			if(!isset($data->AttendanceID, $data->Points)) {
				throw new Exception("Missing expected properties for the Dispute Entity.");
			}

			$entity = new DisputeEntity(
				NULL,
				$data->AttendanceID,
				$data->Points,
				isset($data->Comment) ? $data->Comment : NULL
			);
			
			// a user can only dispute their own records
			if(!$this->service->Authorized($entity)) {
				status_header(403);
				echo("You can only dispute records that belong to you.");
				die();
			}

			if($this->service->Add($entity) === FALSE) {
				throw new Exception($wpdb->last_error);
			}

		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// success
		status_header(201);
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
					$result = $this->service->GetAll();
					break;
				case 'resolved':
					$result = $this->service->GetResolved();
					break;
				case 'unresolved':
					$result = $this->service->GetUnresolved();
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
		$entity = new DisputeEntity();

		try {
			// decode request
			if(!isset($_REQUEST['entity'])) {
				throw new Exception("Could not find parameter with name 'entity'.");
			}
			$data = json_decode(stripslashes($_REQUEST['entity']));

			// create entity
			if(!isset($data->ID, $data->Points, $data->Verdict, $data->AttendanceID)) {
				throw new Exception("The entity object was missing required fields.");
			}
			$entity->ID = $data->ID;
			$entity->Points = $data->Points;
			$entity->Verdict = $data->Verdict;
			$entity->AttendanceID = $data->AttendanceID;

			// update record
			if($entity->Verdict == true) {
				if(($result = $this->service->Approve($entity)) === FALSE) {
					throw new Exception($wpdb->last_error);
				}
			} else {
				if(($result = $this->service->Reject($entity)) === FALSE) {
					throw new Exception($wpdb->last_error);
				}
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// return result
		status_header(204);
		die();
	}

	private $service;
}
add_action('wp_ajax_wro_dispute', array(new DisputeController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_dispute', array(new DisputeController(), 'Reroute'));