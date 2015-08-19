<?php
namespace Dispute;

class GetResolved {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->get_results("
			SELECT ds.ID, ds.AttendanceID, pl.ID as PlayerID, pl.Name, cl.ID as ClassID, cl.Name as ClassName, at.Points, ds.Points as DisputePoints, at.Date, ds.Comment
			FROM Dispute as ds
				JOIN Attendance as at on ds.AttendanceID = at.ID
				JOIN Player as pl ON at.PlayerID = pl.ID
				JOIN Class as cl ON pl.ClassID = cl.ID
			WHERE ds.Verdict IS NOT NULL
			ORDER BY ds.ID ASC;
		");

		return $result;
	}
}