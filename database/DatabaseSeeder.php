<?php
namespace WRO\Database;
include_once plugin_dir_path(__FILE__)."tables/ClassTable.php";

class DatabaseSeeder {
	private function __construct() {}

	public static function Seed() {
		self::SeedClassTable();
	}

	private static function SeedClassTable() {
		global $wpdb;
		$classTable = new Tables\ClassTable();
		
		$wpdb->query("
			INSERT IGNORE INTO " . $classTable->GetName() . " (ID, Name)
			VALUES (1, 'Druid'),
				   (2, 'Hunter'),
				   (3, 'Mage'),
				   (4, 'Paladin'),
				   (5, 'Priest'),
			       (6, 'Rogue'),
			       (7, 'Shaman'),
			       (8, 'Warlock'),
			       (9, 'Warrior'),
			       (10, 'Death Knight'),
			       (11, 'Monk'),
			       (12, 'Demon Hunter');
		");
	}
};