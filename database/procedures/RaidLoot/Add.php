<?php
namespace RaidLoot;
include_once plugin_dir_path(__FILE__)."../../../entities/RaidLootEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\RaidLootEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
    			INSERT INTO RaidLoot (PlayerID, Item, RaidID, Date)
            	VALUES (%d, %s, %d, %s);
        	", $entity->PlayerID, $entity->Item, $entity->RaidID, $entity->Date));
		} catch (Exception $e) {

		}

		return $result;
	}
}