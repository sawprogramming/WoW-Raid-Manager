<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidTierTable.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAllByTier extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$raidTierTable   = new Tables\RaidTierTable();
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->get_results($wpdb->prepare("
			SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, pl.Icon, IFNULL(tw.Tier, 0) as Tier
				FROM " . $playerTable->GetName() . " as pl
					LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as Tier
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Date BETWEEN 
						  	(SELECT StartDate FROM " . $raidTierTable->GetName() . " WHERE ID = %u) AND
						  	IFNULL((SELECT EndDate FROM " . $raidTierTable->GetName() . " WHERE ID = %u), NOW())
						  GROUP BY PlayerID) as tw ON pl.ID = tw.PlayerID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID;
		", $id, $id));
	}
};