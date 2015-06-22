<?php
require_once(plugin_dir_path(__FILE__)."../services/AttendanceService.php");

class AttendanceController {
	public function __construct() {
		$this->service = new AttendanceService();
	}

	public function Reroute () {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->Get();
				break;
			case "PUT":
				if(current_user_can("create_users")) {
					$this->Add();
				}
				break;
			case "POST":
				if(current_user_can("edit_users")) {
					$this->Update();
				}
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

	private function Add() {
		$result = NULL;

		if($_REQUEST['dailyEntities']) {
			$data = json_decode(stripslashes($_REQUEST['dailyEntities']));
			$entities = array();

			foreach($data as $entity) {
				array_push($entities, new AttendanceEntity(
					NULL,
					$entity->ID,
					$entity->Date,
					$entity->Points
				));
			}

			$result = $this->service->AddGroupAttnd($entities);
		} else if($_REQUEST['entity']) {
			$data = json_decode(stripslashes($_REQUEST['entity']));

			$entity = new AttendanceEntity(
				NULL,
				$data->PlayerID,
				$data->Date,
				$data->Points
			);

			$result = $this->service->Add($entity);
		}

		echo json_encode($result);
		die();
	}

	private function Delete() {
		$result = NULL;

		try {
			$id = $_REQUEST['id'];
		} catch (Exception $e) {

		}

		$result = $this->service->Delete($id);

		echo json_encode($result);
		die();
	}

	private function Update() {
		$result = NULL;

		try {
			$data = json_decode(stripslashes($_REQUEST['entity']));
			$entity = new AttendanceEntity(
				$data->ID,
				$data->PlayerID,
				$data->Date,
				$data->Points
			);
			$result = $this->service->Update($entity);
		} catch (Exception $e) {

		}

		echo json_encode($result);
		die();
	}

	private function Get() {
		$result = NULL;

		if($_REQUEST['func']) {
			switch($_REQUEST['func']) {
				case 'all': 
					if($_REQUEST['id']) {
						$result = $this->service->GetAllById($_REQUEST['id']);
					} else {
						$result = $this->service->GetAll();
					}
					break;
				case 'breakdown': 
					$result = $this->service->GetBreakdown();
					break;
				case 'chart':
					$result = $this->service->GetChart($_REQUEST['id']);
					break;
				default:
					break;
			}
		}

		echo json_encode($result);
		die();
	}

	private $service;
}
add_action('wp_ajax_wro_attendance', array(new AttendanceController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_attendance', array(new AttendanceController(), 'Reroute'));
