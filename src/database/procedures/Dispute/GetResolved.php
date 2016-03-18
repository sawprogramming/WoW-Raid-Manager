<?php
namespace WRO\Database\Procedures\Dispute;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/DisputeTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetResolved extends Procedures\StoredProcedure {
	public static function Run() {
		global $wpdb;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$disputeTable    = new Tables\DisputeTable();
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->get_results("
			SELECT ds.ID, ds.AttendanceID, pl.ID as PlayerID, pl.Name, cl.ID as ClassID, cl.Name as ClassName, at.Points, ds.Points as DisputePoints, at.Date, ds.Comment
			FROM " .     $disputeTable->GetName() .    " as ds
				JOIN " . $attendanceTable->GetName() . " as at ON ds.AttendanceID = at.ID
				JOIN " . $playerTable->GetName() .     " as pl ON at.PlayerID = pl.ID
				JOIN " . $classTable->GetName() .      " as cl ON pl.ClassID = cl.ID
			WHERE ds.Verdict IS NOT NULL
			ORDER BY ds.ID ASC;
		");
	}
};