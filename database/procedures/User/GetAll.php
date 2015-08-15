<?php
namespace User;

class GetAll {
	private function __construct() {}

	public static function Run() {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->get_results("
			SELECT ID, user_login as Username, user_nicename, display_name
        	FROM ".$wpdb->prefix."users;
		");

		return $result;
	}
}