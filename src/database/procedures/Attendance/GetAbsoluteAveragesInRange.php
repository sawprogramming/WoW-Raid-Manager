<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAbsoluteAveragesInRange extends Procedures\StoredProcedure {	
	public static function Run($startDate, $endDate) {
		global $wpdb;
		$result          = null;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$attendanceTable = new Tables\AttendanceTable();

		// ugly solution because we have to go through WordPress
		if($startDate === NULL && $endDate === NULL) {
			$result = $wpdb->get_results("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, FLOOR((present.Total / COUNT(Points)) * 100) as Average, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
					LEFT JOIN (SELECT PlayerID, COUNT(Points) as Total
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Points > 0
						  GROUP BY PlayerID) as present ON at.PlayerID = present.PlayerID
	            GROUP BY at.PlayerID
			");
		} else if ($startDate === NULL && $endDate !== NULL) {
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, FLOOR((present.Total / COUNT(Points)) * 100) as Average, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
					LEFT JOIN (SELECT PlayerID, COUNT(Points) as Total
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Points > 0
						  	AND Date <= %s
						  GROUP BY PlayerID) as present ON at.PlayerID = present.PlayerID
				WHERE Date <= %s
	            GROUP BY at.PlayerID
			", $endDate, $endDate));
		} else if ($startDate !== NULL && $endDate === NULL) {
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, FLOOR((present.Total / COUNT(Points)) * 100) as Average, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
					LEFT JOIN (SELECT PlayerID, COUNT(Points) as Total
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Points > 0
						  	AND Date >= %s
						  GROUP BY PlayerID) as present ON at.PlayerID = present.PlayerID
				WHERE Date >= %s
	            GROUP BY at.PlayerID
			", $startDate, $startDate));
		} else {
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, FLOOR((present.Total / COUNT(Points)) * 100) as Average, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
					LEFT JOIN (SELECT PlayerID, COUNT(Points) as Total
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Points > 0
						  	AND Date BETWEEN %s and %s
						  GROUP BY PlayerID) as present ON at.PlayerID = present.PlayerID
				WHERE Date BETWEEN %s AND %s
	            GROUP BY at.PlayerID
			", $startDate, $endDate, $startDate, $endDate));
		}

		// set types
		foreach($result as $player) {
			$player->Active  = (bool)$player->Active;
			$player->Average = (int)$player->Average;
		}

		return $result;
	}
};