<?php
namespace RaidLoot;

class DeletePlayer {
	private function __construct() {}

	public function Run($id) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query("
			DELETE FROM RaidLoot 
			WHERE PlayerID = {$id};
		");

		return $result;
	}
}