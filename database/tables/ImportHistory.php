<?php
namespace Tables;
include_once(ABSPATH.'wp-admin/includes/upgrade.php');

class ImportHistoryTable {
	private function __construct() {}

	public static function CreateTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE IF NOT EXISTS ImportHistory (
                ID tinyint(2) NOT NULL AUTO_INCREMENT,
			    PlayerID tinyint(2) NOT NULL,
			    LastImported double NULL,
			    PRIMARY KEY  ID (ID)
            ) $charset_collate;
        ");
	}

	public static function DropTable() {
		global $wpdb;

		$wpdb->get_results("DROP TABLE IF EXISTS ImportHistory;");
	}
}