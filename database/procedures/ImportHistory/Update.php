<?php
namespace ImportHistory;
include_once plugin_dir_path(__FILE__)."../../../entities/ImportHistoryEntity.php";

class Update {
	private function __construct() {}

	public static function Run(\ImportHistoryEntity $entity) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query($wpdb->prepare("
            UPDATE ImportHistory
            SET LastImported = %f
            WHERE PlayerID = %d
        ", $entity->LastImported, $entity->PlayerID));

		return $result;
	}
}