<?php
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."AttendanceTable.php");

class DisputeTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$attendanceTable = new AttendanceTable();
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE IF NOT EXISTS " . $this->GetName() . " (
                ID             bigint(20)    unsigned   NOT NULL   AUTO_INCREMENT,
			    AttendanceID   bigint(20)    unsigned   NOT NULL,
			    Points         float(3, 2)              NOT NULL,
			    Comment        tinytext                 NULL,
			    Verdict        bool                     NULL,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (AttendanceID) REFERENCES " . $attendanceTable->GetName() . "(ID)
            ) " . $charset_collate . ";
        ");
	}
};