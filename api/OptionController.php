<?php
namespace WRO\API;
require_once(plugin_dir_path(__FILE__)."../services/OptionService.php");
use Exception;
use WRO\Services as Services;

class OptionController {
	public function __construct() {
		$this->service_ = new Services\OptionService();
	}

	public function Reroute() {
		switch($_SERVER['REQUEST_METHOD']) {
			case "GET":
				if(current_user_can("list_users")) {
					$this->Get();
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

	private function Get() {
		$result = null;

		try {
			if(isset($_REQUEST['key'])) $result = $this->service_->Get($_REQUEST['key']);
			else                        $result = $this->service_->GetAll();

			if($result === FALSE) throw new Exception("That key doesn't belong to this plugin.");
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// success
		status_header(201);
		echo(json_encode($result));
		die();
	}

	private function Update() {
		$result = null;

		try {
			if(!isset($_REQUEST['pairs'])) throw new Exception("Missing an object of key/value pairs named 'pairs'.");
			else                           $result = $this->service_->Update(json_decode(stripslashes($_REQUEST['pairs'])));

			if($result === FALSE) throw new Exception("That key doesn't belong to this plugin.");
		} catch (Exception $e) {
			status_header(422);
			echo($e->getMessage());
			die();
		}

		// success
		status_header(201);
		echo(json_encode($result));
		die();
	}

	private $service_;
};
add_action('wp_ajax_wro_option', array(new OptionController(), 'Reroute'));