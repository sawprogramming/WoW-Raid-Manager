<?php
namespace WRO\Database\Procedures\RaidLoot;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidLootTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAll extends Procedures\StoredProcedure {
	public static function Run() {
		global $wpdb;
		$classTable    = new Tables\ClassTable();
		$playerTable   = new Tables\PlayerTable();
		$raidLootTable = new Tables\RaidLootTable();

		return $wpdb->get_results("
			SELECT li.ID, li.PlayerID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, li.Item, li.Date
        	FROM "     . $raidLootTable->GetName() . " as li
            	JOIN " . $playerTable->GetName() .   " as pl ON li.PlayerID = pl.ID
            	JOIN " . $classTable->GetName() .    " as cl ON pl.ClassID = cl.ID
        	ORDER BY li.ID DESC;
		");
	}
};