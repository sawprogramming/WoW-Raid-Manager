<?php
namespace Attendance;

class GetAllById {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results($wpdb->prepare("
				SELECT at.ID, pl.ID as PlayerID, pl.Name, cl.ID as ClassID, cl.Name as ClassName, at.Points, at.Date
				FROM Attendance as at
					JOIN Player as pl ON at.PlayerID = pl.ID
					JOIN Class as cl ON pl.ClassID = cl.ID
				WHERE at.PlayerID = %d;
			", $id));
		} catch (Exception $e) {

		}

		return $result;
	}
}