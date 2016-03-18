<?php 
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."ExpansionTable.php");

class RaidTierTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$expansionTable = new ExpansionTable();
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE " . $this->GetName() . " (
			    ID            bigint(20)   unsigned   NOT NULL   AUTO_INCREMENT,
			    ExpansionID   bigint(20)   unsigned   NOT NULL,
			    Name          tinytext     NOT NULL,
			    StartDate     date         NOT NULL,
			    EndDate       date         NULL,
			    PRIMARY KEY  (ID)
            ) " . $charset_collate . ";
        ");

 		$wpdb->query("ALTER TABLE " . $this->GetName() . " ADD FOREIGN KEY (ExpansionID) REFERENCES " . $expansionTable->GetName() . "(ID);");
	}
};