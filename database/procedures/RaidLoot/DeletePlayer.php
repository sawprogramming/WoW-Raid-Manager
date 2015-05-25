<?php
namespace RaidLoot;

class DeletePlayer {
	private function __construct() {}

	public function Run($id) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query("
				DELETE FROM RaidLoot 
				WHERE PlayerID = {$id};
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}