<?php
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."PlayerTable.php");

class AttendanceTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$playerTable     = new PlayerTable();
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE IF NOT EXISTS " . $this->GetName() . " (
                ID         bigint(20)    unsigned   NOT NULL   AUTO_INCREMENT,
			    PlayerID   bigint(20)    unsigned   NOT NULL,
			    Date       date                     NOT NULL,
			    Points     float(3, 2)   unsigned,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (PlayerID) REFERENCES " . $playerTable->GetName() . "(ID)
            ) " . $charset_collate . ";
        ");
	}
};