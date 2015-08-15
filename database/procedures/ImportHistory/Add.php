<?php
namespace ImportHistory;
include_once plugin_dir_path(__FILE__)."../../../entities/ImportHistoryEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\ImportHistoryEntity $entity) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query($wpdb->prepare("
			INSERT INTO ImportHistory (PlayerID, LastImported)
        	VALUES (%d, %f);
    	", $entity->PlayerID, $entity->LastImported));

		return $result;
	}
}