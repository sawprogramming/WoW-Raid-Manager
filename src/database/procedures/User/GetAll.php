<?php
namespace WRO\Database\Procedures\User;
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
use WRO\Database\Procedures as Procedures;

class GetAll extends Procedures\StoredProcedure {
	public static function Run() {
		global $wpdb;

		return $wpdb->get_results("
			SELECT ID, user_login as Username, user_nicename, display_name
        	FROM " . $wpdb->prefix . "users;
		");
	}
};