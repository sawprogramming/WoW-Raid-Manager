<?php
namespace WRO\Database\Procedures\RaidLoot;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidLootTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/RaidLootEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Add extends Procedures\StoredProcedure {
	public static function Run(Entities\RaidLootEntity $entity) {
		global $wpdb;
		$raidLootTable = new Tables\RaidLootTable();

		return $wpdb->query($wpdb->prepare("
			INSERT INTO " . $raidLootTable->GetName() . " (PlayerID, Item, Date)
        	VALUES (%u, %s, %s);
    	", $entity->PlayerID, $entity->Item, $entity->Date));
	}
};