<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class DeletePlayer extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->query($wpdb->prepare("
			DELETE FROM " . $attendanceTable->GetName() . " 
			WHERE PlayerID = %d;
		", $id));
	}
};