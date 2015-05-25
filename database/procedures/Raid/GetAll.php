<?php
namespace Raid;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT *
				FROM Raid;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}