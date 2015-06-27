<?php
namespace HeroClass;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT *
            	FROM Class
            	ORDER BY Name ASC;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}