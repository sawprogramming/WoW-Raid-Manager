<?php
namespace WRO\Database\Procedures\Dispute;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/DisputeTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class DeletePlayer extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$disputeTable    = new Tables\DisputeTable();
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->query($wpdb->prepare("
			DELETE ds
			FROM " .        $disputeTable->GetName() . " as ds
			    JOIN " . $attendanceTable->GetName() . " as at ON ds.AttendanceID = at.ID
			WHERE at.PlayerID = %d;
		", $id));
	}
};