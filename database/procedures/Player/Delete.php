<?php
namespace Player;

class Delete {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query("
			DELETE FROM Player
			WHERE ID = {$id};
		");

		return $result;
	}
}