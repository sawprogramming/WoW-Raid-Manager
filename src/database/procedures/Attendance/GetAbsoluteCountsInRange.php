<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAbsoluteCountsInRange extends Procedures\StoredProcedure {	
	public static function Run($startDate, $endDate) {
		global $wpdb;
		$result          = null;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$attendanceTable = new Tables\AttendanceTable();

		// ugly solution because we have to go through WordPress
		if($startDate === NULL && $endDate === NULL) {
			$result = $wpdb->get_results("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, COUNT(Points) as Count, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
                WHERE Points > 0
	            GROUP BY at.PlayerID
			");
		} else if ($startDate === NULL && $endDate !== NULL) {
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, COUNT(Points) as Count, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
				WHERE Date <= %s
                    AND Points > 0
	            GROUP BY at.PlayerID
			", $endDate, $endDate));
		} else if ($startDate !== NULL && $endDate === NULL) {
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, COUNT(Points) as Count, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
				WHERE Date >= %s
                    AND Points > 0
	            GROUP BY at.PlayerID
			", $startDate, $startDate));
		} else {
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, COUNT(Points) as Count, pl.Active
				FROM " . $attendanceTable->GetName() . " as at
					JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
					JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
				WHERE Date BETWEEN %s AND %s
                    AND Points > 0
	            GROUP BY at.PlayerID
			", $startDate, $endDate, $startDate, $endDate));
		}

		// set types
		foreach($result as $player) {
			$player->Active = (bool)$player->Active;
			$player->Count  = (int)$player->Count;
		}

		return $result;
	}
};