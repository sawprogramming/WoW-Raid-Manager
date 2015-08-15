<?php
namespace RaidLoot;
include_once plugin_dir_path(__FILE__)."../../../entities/RaidLootEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\RaidLootEntity $entity) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query($wpdb->prepare("
			INSERT INTO RaidLoot (PlayerID, Item, Date)
        	VALUES (%d, %s, %s);
    	", $entity->PlayerID, $entity->Item, $entity->Date));

		return $result;
	}
}