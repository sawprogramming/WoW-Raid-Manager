<?php
namespace Raid;

class Delete {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
				DELETE FROM Raid
				WHERE ID = %d;
			", $id));
		} catch (Exception $e) {

		}

		return $result;
	}
}