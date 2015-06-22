<?php
namespace Attendance;

class GetChart {
	private function __construct() {}
	
	public static function Run($id) {
		global $wpdb;
		$results = NULL;

		try {
			$results = $wpdb->get_results($wpdb->prepare("
				SELECT Date, FLOOR(Points * 100) as Points, (
					SELECT FLOOR((SUM(Points) / COUNT(Points)) * 100)
					FROM Attendance 
					WHERE Date <= at.Date 
						AND PlayerID = %d
					) as PlayerAverage, (
					SELECT FLOOR((SUM(Points) / COUNT(Points)) * 100)
					FROM Attendance 
					WHERE Date <= at.Date 
					) as RaidAverage
				FROM Attendance AS at
				WHERE PlayerID = %d
				ORDER BY Date ASC;
			", $id, $id));
		} catch (Exception $e) {
			die();
		}

		return $results;
	}
}