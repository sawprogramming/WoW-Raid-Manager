<?php
namespace WRO\Database\Tables;
require_once(plugin_dir_path(__FILE__)."Table.php");

class RealmTable extends Table {
	public function CreateTable() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        \dbDelta("
            CREATE TABLE IF NOT EXISTS " . $this->GetName() . " (
                Slug     varchar(32)   NOT NULL,
			    Name     tinytext      NOT NULL,
			    Region   varchar(2)    NOT NULL,
			    PRIMARY KEY  (Slug, Region)
            ) " . $charset_collate . ";
        ");
	}
};