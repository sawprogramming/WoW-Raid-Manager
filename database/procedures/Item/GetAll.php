<?php
namespace Item;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT * FROM Item;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}