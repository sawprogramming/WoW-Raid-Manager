<?php
namespace Player;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT pl.ID, pl.ClassID, cl.Name as ClassName, pl.Name
				FROM Player as pl
					JOIN Class as cl ON pl.ClassID = cl.ID;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}