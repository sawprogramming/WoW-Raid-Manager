<?php
namespace Player;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT pl.ID, pl.UserID, wp.user_login as Username, pl.ClassID, cl.Name as ClassName, pl.Name
				FROM Player as pl
					JOIN Class as cl ON pl.ClassID = cl.ID
					LEFT JOIN ".$wpdb->prefix."users as wp ON pl.UserID = wp.ID
				ORDER BY pl.ID ASC;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}