<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetChart extends Procedures\StoredProcedure {	
	public static function Run($id) {
		global $wpdb;
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->get_results($wpdb->prepare("
			SELECT Date, FLOOR(Points * 100) as Points, (
				SELECT FLOOR((SUM(Points) / COUNT(Points)) * 100)
				FROM " . $attendanceTable->GetName() . " 
				WHERE Date <= at.Date 
					AND PlayerID = %d
				) as PlayerAverage, (
				SELECT FLOOR((SUM(Points) / COUNT(Points)) * 100)
				FROM " . $attendanceTable->GetName() . " 
				WHERE Date <= at.Date 
				) as RaidAverage
			FROM " . $attendanceTable->GetName() . " AS at
			WHERE PlayerID = %d
			ORDER BY Date ASC;
		", $id, $id));
	}
};