<?php
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."ClassTable.php");

class PlayerTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$classTable      = new ClassTable();
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
			CREATE TABLE IF NOT EXISTS " . $this->GetName() . " (
			    ID        bigint(20)   unsigned   NOT NULL   AUTO_INCREMENT,
			    UserID    bigint(20)   unsigned   NULL,
			    ClassID   tinyint(2)   unsigned   NOT NULL,
			    Name      tinytext                NOT NULL,
			    Icon      text                    NULL,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (ClassID) REFERENCES " . $classTable->GetName() . "(ID),
			    FOREIGN KEY (UserID)  REFERENCES " . $wpdb->prefix .          "users(ID)
            ) ". $charset_collate . ";
        ");
	}
};