<?php
namespace RaidLoot;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT li.ID, li.PlayerID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, li.Item, li.RaidID, rd.Name as RaidName, li.Date
            	FROM RaidLoot as li
                	JOIN Player as pl ON li.PlayerID = pl.ID
                	JOIN Raid as rd ON li.RaidID = rd.ID
                	JOIN Class as cl ON pl.ClassID = cl.ID
            	ORDER BY li.ID DESC;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}