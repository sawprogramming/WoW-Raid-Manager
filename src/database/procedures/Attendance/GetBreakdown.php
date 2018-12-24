<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidTierTable.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetBreakdown extends Procedures\StoredProcedure {	
	public static function Run($id) {
		global $wpdb;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$raidTierTable   = new Tables\RaidTierTable();
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->get_row($wpdb->prepare("
			SELECT pl.ID, pl.Name, pl.Region, pl.ClassID, cl.Name as ClassName, pl.Icon, IFNULL(tw.TwoWeek, 0) as TwoWeek, IFNULL(m.Month, 0) as Month, at.AllTime, IFNULL(ti.Tier, 0) as Tier
			FROM " . $playerTable->GetName() . " as pl
				LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as TwoWeek
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW()
					  		AND PlayerID = %d) as tw ON pl.ID = tw.PlayerID
				LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as Month
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
					  	    AND PlayerID = %d) as m ON pl.ID = m.PlayerID
				JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as AllTime
					  FROM " . $attendanceTable->GetName() . "
					  WHERE PlayerID = %d) as at ON pl.ID = at.PlayerID
				LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as Tier
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Date BETWEEN 
						  	(SELECT StartDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1) AND
						  	IFNULL((SELECT EndDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1), NOW())
						  	AND PlayerID = %d) as ti ON pl.ID = ti.PlayerID
				JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
			WHERE pl.ID = %d;
		", $id, $id, $id, $id, $id));
	}
};