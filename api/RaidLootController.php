<?php
namespace WRO\API;
require_once(plugin_dir_path(__FILE__)."../services/RaidLootService.php");
use Exception;
use WRO\Services as Services;

class RaidLootController {
	public function __construct() {
		$this->service_ = new Services\RaidLootService();
	}

	public function Reroute () {

		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->Get();
				break;
			case "DELETE":
				if(current_user_can('remove_users')) {
					$this->DeleteRow();
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

	private function DeleteRow() {
		try {
			$id = intval($_REQUEST['id']);
		} catch (Exception $e) {

		}

		echo(json_encode($this->service_->DeleteRow($id)));
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
					$result = $this->service_->GetAll();
					break;
				case 'range':
					$startDate = $endDate = null;

					if(isset($_REQUEST['startDate'])) $startDate = (new \DateTime($_REQUEST['startDate']))->format('Y-m-d');
					if(isset($_REQUEST['endDate']))   $endDate   = (new \DateTime($_REQUEST['endDate']))->format('Y-m-d');

					$result = $this->service_->GetInRange($startDate, $endDate);
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

	private $service_;
};
add_action('wp_ajax_wro_raidloot', array(new RaidLootController(), 'Reroute'));
add_action('wp_ajax_nopriv_wro_raidloot', array(new RaidLootController(), 'Reroute'));