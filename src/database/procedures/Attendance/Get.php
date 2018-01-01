<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Get extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->get_row($wpdb->prepare("
			SELECT at.ID, pl.ID as PlayerID, pl.Name, cl.ID as ClassID, cl.Name as ClassName, at.Points, at.Date
			FROM " . $attendanceTable->GetName() . " as at
				JOIN " . $playerTable->GetName() . " as pl ON at.PlayerID = pl.ID
				JOIN " .  $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
			WHERE at.ID = %d;
		", $id));
	}
};