<?php
namespace Attendance;

class Get {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("
			SELECT at.ID, pl.ID as PlayerID, pl.Name, cl.ID as ClassID, cl.Name as ClassName, at.Points, at.Date
			FROM Attendance as at
				JOIN Player as pl ON at.PlayerID = pl.ID
				JOIN Class as cl ON pl.ClassID = cl.ID
			WHERE at.ID = %d;
		", $id));
	}
}