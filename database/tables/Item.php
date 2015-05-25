<?php
namespace Tables;
include_once(ABSPATH.'wp-admin/includes/upgrade.php');

class ItemTable {
	private function __construct() {}

	public static function CreateTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
    		CREATE TABLE IF NOT EXISTS Item (
			    ID double NOT NULL,
                Context tinytext NULL,
			    ItemLevel smallint NOT NULL,
			    PRIMARY KEY  (ID,Context(255))
            ) $charset_collate;
        ");
	}

	public static function DropTable() {
		global $wpdb;

		$wpdb->get_results("DROP TABLE IF EXISTS Item;");
	}
}