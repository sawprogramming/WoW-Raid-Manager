<?php
namespace ImportHistory;
include_once plugin_dir_path(__FILE__)."../../../entities/ImportHistoryEntity.php";

class Update {
	private function __construct() {}

	public static function Run(PlayerEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
	            UPDATE ImportHistory
	            SET LastImported = %d
	            WHERE PlayerID = %d
            ", $obj->LastImported, $obj->PlayerID));
		} catch (Exception $e) {

		}

		return $result;
	}
}