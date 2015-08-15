<?php
namespace ImportHistory;

class DeletePlayer {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query($wpdb->prepare("
			DELETE FROM ImportHistory
			WHERE PlayerID = %d;
		", $id));

		return $result;
	}
}