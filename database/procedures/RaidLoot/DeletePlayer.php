<?php
namespace WRO\Database\Procedures\RaidLoot;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidLootTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class DeletePlayer extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$raidLootTable = new Tables\RaidLootTable();

		return $wpdb->query($wpdb->prepare("
			DELETE FROM " . $raidLootTable->GetName() . " 
			WHERE PlayerID = %u;
		", $id));
	}
};