<?php
namespace Tables;

class DatabaseSeeder {
	private function __construct() {}

	public static function Seed() {
		self::SeedClassTable();
	}

	private static function SeedClassTable() {
		global $wpdb;

		$wpdb->query("
			INSERT INTO Class (Name)
			VALUES ('Druid'),  ('Hunter'), ('Mage'), ('Paladin'), ('Priest'),
			       ('Rogue'), ('Shaman'), ('Warlock'), ('Warrior'), ('Death Knight'),
			       ('Monk');
		");
	}
}