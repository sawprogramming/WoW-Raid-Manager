<?php
namespace Tables;
include_once(ABSPATH.'wp-admin/includes/upgrade.php');

class PlayerTable {
	private function __construct() {}

	public static function CreateTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
			CREATE TABLE IF NOT EXISTS Player (
			    ID  smallint(5) unsigned NOT NULL AUTO_INCREMENT,
			    UserID bigint(20) unsigned NULL,
			    ClassID  tinyint(3),
			    Name tinytext NOT NULL,
			    Icon text NULL,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (ClassID) REFERENCES Class(ID),
			    FOREIGN KEY (UserID) REFERENCES wp_users(ID)
            ) $charset_collate;
        ");
	}

	public static function DropTable() {
		global $wpdb;

		$wpdb->get_results("DROP TABLE IF EXISTS Player;");
	}
}