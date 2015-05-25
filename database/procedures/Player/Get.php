<?php
namespace Player;

class Get {
	private function __construct() {}

	public static function Run(int $id) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM Player
				WHERE ID = %d;
			", $id));
		} catch (Exception $e) {

		}

		return $result;
	}
}