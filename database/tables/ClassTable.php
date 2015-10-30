<?php
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."Table.php");

class ClassTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE IF NOT EXISTS " . $this->GetName() . " (
                ID     tinyint(2)   unsigned   NOT NULL   AUTO_INCREMENT,
			    Name   tinytext                NOT NULL,
			    PRIMARY KEY  ID (ID)
            ) " . $charset_collate . ";
        ");
	}
};