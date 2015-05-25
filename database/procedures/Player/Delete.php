<?php
namespace Player;

class Delete {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query("
				DELETE FROM Player
				WHERE ID = {$id};
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}