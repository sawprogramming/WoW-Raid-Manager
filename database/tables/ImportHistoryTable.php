<?php
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."Table.php");

class ImportHistoryTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE " . $this->GetName() . " (
                ID             bigint(20)   unsigned   NOT NULL   AUTO_INCREMENT,
			    PlayerID       bigint(20)   unsigned   NOT NULL,
			    LastImported   double       unsigned   NULL,
			    PRIMARY KEY  (ID)
            ) " . $charset_collate . ";
        ");
	}
};