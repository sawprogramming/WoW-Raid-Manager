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
			    ID  smallint(5) NOT NULL AUTO_INCREMENT,
			    ClassID  tinyint(3),
			    Name tinytext NOT NULL,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (ClassID) REFERENCES Class(ID)
            ) $charset_collate;
        ");
	}

	public static function DropTable() {
		global $wpdb;

		$wpdb->get_results("DROP TABLE IF EXISTS Player;");
	}
}