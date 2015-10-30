<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetBreakdown extends Procedures\StoredProcedure {	
	public static function Run() {
		global $wpdb;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->get_results("
			SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, pl.Icon, IFNULL(tw.TwoWeek, 0) as TwoWeek, IFNULL(m.Month, 0) as Month, at.AllTime
			FROM " . $playerTable->GetName() . " as pl
				LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as TwoWeek
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW()
					  GROUP BY PlayerID) as tw ON pl.ID = tw.PlayerID
				LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as Month
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
					  GROUP BY PlayerID) as m ON pl.ID = m.PlayerID
				JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as AllTime
					  FROM " . $attendanceTable->GetName() . "
					  GROUP BY PlayerID) as at ON pl.ID = at.PlayerID
				JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID;
		");
	}
};