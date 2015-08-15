<?php
namespace RaidLoot;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;


		$result = $wpdb->get_results("
			SELECT li.ID, li.PlayerID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, li.Item, li.Date
        	FROM RaidLoot as li
            	JOIN Player as pl ON li.PlayerID = pl.ID
            	JOIN Class as cl ON pl.ClassID = cl.ID
        	ORDER BY li.ID DESC;
		");


		return $result;
	}
}