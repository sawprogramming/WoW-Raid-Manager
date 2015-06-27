<?php
namespace User;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_results("
				SELECT ID, user_login as Username, user_nicename, display_name
            	FROM wp_users;
			");
		} catch (Exception $e) {

		}

		return $result;
	}
}