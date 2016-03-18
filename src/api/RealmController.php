<?php
namespace WRO\API;
require_once(plugin_dir_path(__FILE__)."../services/RealmService.php");
use WRO\Services as Services;

class RealmController {
	public function __construct() {
		$this->service_ = new Services\RealmService();
	}

	public function Reroute() {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				$this->GetAll();
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
		try {
			// decode data
			if(!isset($_REQUEST['region'])) {
				throw new Exception("Could not find parameter with name 'region'.");
			}
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}
		
		echo(json_encode($this->service_->GetAll($_REQUEST['region'])));
		die();
	}

	private $service_;
};
add_action('wp_ajax_wro_realm', array(new RealmController(), 'Reroute'));