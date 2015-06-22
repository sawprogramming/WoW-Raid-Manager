<?php
namespace ImportHistory;
include_once plugin_dir_path(__FILE__)."../../../entities/ImportHistoryEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\ImportHistoryEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO ImportHistory (PlayerID, LastImported)
            	VALUES (%d, %d);
        	", $obj->PlayerID, $obj->LastImported));
		} catch (Exception $e) {

		}

		return $result;
	}
}