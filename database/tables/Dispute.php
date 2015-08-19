<?php
namespace Tables;
include_once(ABSPATH.'wp-admin/includes/upgrade.php');

class DisputeTable {
	private function __construct() {}

	public static function CreateTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE IF NOT EXISTS Dispute (
                ID  int(10) NOT NULL AUTO_INCREMENT,
			    AttendanceID int(10) NOT NULL,
			    Points  float(3, 2) NOT NULL,
			    Comment tinytext NULL,
			    Verdict bool NULL,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (AttendanceID) REFERENCES Attendance(ID)
            ) $charset_collate;");
	}

	public static function DropTable() {
		global $wpdb;

		$wpdb->get_results("DROP TABLE IF EXISTS Dispute;");
	}
}