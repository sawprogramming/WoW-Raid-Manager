<?php
namespace WRO\API;
require_once(plugin_dir_path(__FILE__)."../services/ExpansionService.php");
use Exception;
use WRO\Services as Services;
use WRO\Entities as Entities;

class ExpansionController {
	public function __construct() {
		$this->service_ = new Services\ExpansionService();
	}

	public function Reroute () {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->GetAll();
				break;
			case "POST":
				if(current_user_can("create_users")) {
					$this->Add();
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
		$result = $data = NULL;
		$entity = new Entities\ExpansionEntity();

		try {
			// decode data
			if(!isset($_REQUEST['entity'])) {
				throw new Exception("Could not find parameter with name 'entity'.");
			}
			$data = json_decode(stripslashes($_REQUEST['entity']));

			// create entity
			if(!(isset($data->Name, $data->StartDate) && array_key_exists("EndDate", $data))) {
				throw new Exception("The entity object was missing required fields.");
			}
			$entity->Name = $data->Name;
			$entity->StartDate = $data->StartDate;
			$entity->EndDate = $data->EndDate;

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

	private function GetAll() {
		echo(json_encode($this->service_->GetAll()));
		die();
	}

	private $service_;
};
add_action('wp_ajax_wro_expansion', array(new ExpansionController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_expansion', array(new ExpansionController(), 'Reroute'));