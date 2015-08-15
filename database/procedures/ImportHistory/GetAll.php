<?php
namespace ImportHistory;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->get_results("
			SELECT *
			FROM ImportHistory;
		");

		return $result;
	}
}