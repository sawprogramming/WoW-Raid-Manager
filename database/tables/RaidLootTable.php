<?php 
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."PlayerTable.php");

class RaidLootTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$playerTable     = new PlayerTable();
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE " . $this->GetName() . " (
			    ID         bigint(20)   unsigned   NOT NULL   AUTO_INCREMENT,
			    PlayerID   bigint(20)   unsigned   NOT NULL,
			    Item       tinytext     NOT NULL,
			    Date       date         NOT NULL,
			    PRIMARY KEY  (ID)
            ) " . $charset_collate . ";
        ");
        
 		$wpdb->query("ALTER TABLE " . $this->GetName() . " ADD FOREIGN KEY (PlayerID) REFERENCES " . $playerTable->GetName() . "(ID);");
	}
};