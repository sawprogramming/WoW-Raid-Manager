<?php
namespace Attendance;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT at.ID, pl.ID as PlayerID, pl.Name, cl.ID as ClassID, cl.Name as ClassName, at.Points, at.Date
				FROM Attendance as at
					JOIN Player as pl ON at.PlayerID = pl.ID
					JOIN Class as cl ON pl.ClassID = cl.ID;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}