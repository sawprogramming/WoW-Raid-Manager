<?php
namespace Attendance;

class GetBreakdown {
	private function __construct() {}
	
	public static function Run() {
		global $wpdb;
		$results = NULL;

		$results = $wpdb->get_results("
			SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, pl.Icon, IFNULL(tw.TwoWeek, 0) as TwoWeek, IFNULL(m.Month, 0) as Month, at.AllTime
			FROM Player as pl
				LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as TwoWeek
					  FROM Attendance
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW()
					  GROUP BY PlayerID) as tw ON pl.ID = tw.PlayerID
				LEFT JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as Month
					  FROM Attendance
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
					  GROUP BY PlayerID) as m ON pl.ID = m.PlayerID
					JOIN (SELECT PlayerID, FLOOR((SUM(Points) / COUNT(Points)) * 100) as AllTime
					  FROM Attendance
					  GROUP BY PlayerID) as at ON pl.ID = at.PlayerID
				JOIN Class as cl ON pl.ClassID = cl.ID;
		");

		return $results;
	}
}