<?php
namespace ImportHistory;

class Get {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->get_row($wpdb->prepare("
			SELECT * FROM ImportHistory
			WHERE PlayerID = %d;
		", $id));

		return $result;
	}
}