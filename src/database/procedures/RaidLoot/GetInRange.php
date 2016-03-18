<?php
namespace WRO\Database\Procedures\RaidLoot;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidLootTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetInRange extends Procedures\StoredProcedure {	
	public static function Run($startDate, $endDate) {
		global $wpdb;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$raidLootTable   = new Tables\RaidLootTable();

		// ugly solution because we have to go through WordPress
		if($startDate === NULL && $endDate === NULL) {
			return $wpdb->get_results("
				SELECT pl.ID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, rl.Item, rl.Date
				FROM " . $raidLootTable->GetName() . " as rl
					JOIN " . $playerTable->GetName() . " as pl ON rl.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
	            ORDER BY rl.Date DESC
			");
		} else if ($startDate === NULL && $endDate !== NULL) {
			return $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, rl.Item, rl.Date
				FROM " . $raidLootTable->GetName() . " as rl
					JOIN " . $playerTable->GetName() . " as pl ON rl.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
				WHERE Date <= %s
	            ORDER BY rl.Date DESC
			", $endDate));
		} else if ($startDate !== NULL && $endDate === NULL) {
			return $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, rl.Item, rl.Date
				FROM " . $raidLootTable->GetName() . " as rl
					JOIN " . $playerTable->GetName() . " as pl ON rl.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
				WHERE Date >= %s
	            ORDER BY rl.Date DESC
			", $startDate));
		} else {
			return $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, rl.Item, rl.Date
				FROM " . $raidLootTable->GetName() . " as rl
					JOIN " . $playerTable->GetName() . " as pl ON rl.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
				WHERE Date BETWEEN %s AND %s
	            ORDER BY rl.Date DESC
			", $startDate, $endDate));
		}
	}
};