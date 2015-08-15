<?php
namespace Attendance;

class DeletePlayer {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query($wpdb->prepare("
			DELETE FROM Attendance 
			WHERE PlayerID = %d;
		", $id));

		return $result;
	}
}