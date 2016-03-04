<?php
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."ClassTable.php");
require_once(plugin_dir_path(__FILE__)."RealmTable.php");

class PlayerTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$classTable      = new ClassTable();
		$realmTable      = new RealmTable();
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
			CREATE TABLE " . $this->GetName() . " (
			    ID        bigint(20)   unsigned   NOT NULL   AUTO_INCREMENT,
			    UserID    bigint(20)   unsigned   NULL,
			    ClassID   tinyint(2)   unsigned   NOT NULL,
			    Name      tinytext                NOT NULL,
			    Icon      text                    NULL,
			    Active    bool                    NOT NULL   DEFAULT 1,
			    Region    varchar(2)              NOT NULL,
			    Realm     varchar(32)             NOT NULL,    
			    PRIMARY KEY  (ID)
            ) ". $charset_collate . ";
        ");

 		$wpdb->query("ALTER TABLE " . $this->GetName() . " ADD FOREIGN KEY (Realm,Region) REFERENCES " . $realmTable->GetName() . "(Slug,Region);");
 		$wpdb->query("ALTER TABLE " . $this->GetName() . " ADD FOREIGN KEY (ClassID) REFERENCES " . $classTable->GetName() . "(ID);");
 		$wpdb->query("ALTER TABLE " . $this->GetName() . " ADD FOREIGN KEY (UserID) REFERENCES " . $wpdb->prefix . "users(ID);");
	}
};