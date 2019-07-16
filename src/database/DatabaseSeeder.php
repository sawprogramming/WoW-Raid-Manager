<?php
namespace WRO\Database;
include_once plugin_dir_path(__FILE__)."tables/ClassTable.php";
include_once plugin_dir_path(__FILE__)."tables/RaidTierTable.php";

class DatabaseSeeder {
	private function __construct() {}

	public static function Seed() {
		// ** ORDER IS IMPORTANT HERE ** 
		self::SeedClassTable();
		self::SeedExpansionTable();
		self::SeedRaidTierTable();
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

	private static function SeedExpansionTable() {
		global $wpdb;
		$expansionTable = new Tables\ExpansionTable();
		
		$wpdb->query("
			INSERT IGNORE INTO " . $expansionTable->GetName() . " (ID, Name, StartDate, EndDate)
			VALUES  (1, 'World of Warcraft',      '2004-11-23', '2007-01-15'),
	                (2, 'The Burning Crusade',    '2007-01-16', '2008-11-12'),
			        (3, 'Wrath of the Lich King', '2008-11-13', '2010-12-06'),
			        (4, 'Cataclysm',              '2010-12-07', '2012-09-24'),
	   		        (5, 'Mists of Pandaria',      '2012-09-25', '2014-11-12'),
			        (6, 'Warlords of Draenor',    '2014-12-02', '2016-08-29'),
                    (7, 'Legion',                 '2016-08-30', '2018-08-13'),
                    (8, 'Battle for Azeroth',     '2018-08-14', NULL);
		");
	}

	private static function SeedRaidTierTable() {
		global $wpdb;
		$raidTierTable = new Tables\RaidTierTable();
		
		$wpdb->query("
			REPLACE INTO " . $raidTierTable->GetName() . " (ID, ExpansionID, Name, StartDate, EndDate)
			VALUES (1,  1, 'Molten Core',                 '2004-11-23', '2005-07-11'),
			       (2,  1, 'Blackwing Lair',              '2005-07-12', '2006-01-02'),
			       (3,  1, 'Zul\'Gurub',                  '2005-09-13', '2006-01-02'),
			       (4,  1, 'Ahn\'Qiraj',                  '2006-01-03', '2006-06-19'),
			       (5,  1, 'Naxxramas',                   '2006-06-20', '2007-01-15'),
                                                          
		           (6,  2, 'Karazhan',                    '2007-01-16', '2007-05-21'),
		           (7,  2, 'Tempest Keep',                '2007-01-16', '2007-05-21'),
		           (8,  2, 'Serpentshrine Cavern',        '2007-01-16', '2007-05-21'),
		           (9,  2, 'Hyjal Summit',                '2007-01-16', '2007-05-21'),
		           (10, 2, 'The Black Temple',            '2007-05-22', '2008-03-24'),
		           (11, 2, 'Zul\'Aman',                   '2007-11-13', '2008-03-24'),
		           (12, 2, 'Sunwell Plateau',             '2008-03-25', '2008-11-12'),
                                                          
				   (13, 3, 'Naxxramas',                   '2008-11-13', '2009-04-13'),
				   (14, 3, 'Ulduar',                      '2009-04-14', '2009-08-03'),
				   (15, 3, 'Trial of the Crusader',       '2009-08-04', '2009-12-07'),
				   (16, 3, 'Icecrown Citadel',            '2009-12-08', '2010-12-06'),
                                                          
		   		   (17, 4, 'Blackwing Descent',           '2010-12-07', '2011-06-27'),
		   		   (18, 4, 'Bastion of Twilight',         '2010-12-07', '2011-06-27'),
		   		   (19, 4, 'Throne of the Four Winds',    '2010-12-07', '2011-06-27'),
		   		   (20, 4, 'Firelands',                   '2011-06-28', '2011-11-28'),
		   		   (21, 4, 'Dragon Soul',                 '2011-11-29', '2012-09-24'),
                                                          
	   		       (22, 5, 'Mogu\'Shan Vaults',           '2012-09-25', '2013-03-04'),
	   		       (23, 5, 'Heart of Fear',               '2012-09-25', '2013-03-04'),
	   		       (24, 5, 'Terrace of Endless Spring',   '2012-09-25', '2013-03-04'),
	   		       (25, 5, 'Throne of Thunder',           '2013-03-05', '2013-09-09'),
	   		       (26, 5, 'Siege of Orgrimmar',          '2013-09-10', '2014-11-12'),
                                                          
				   (27, 6, 'Highmaul',                    '2014-12-02', '2015-02-02'),
			       (28, 6, 'Blackrock Foundry',           '2015-02-03', '2015-06-22'),
			       (29, 6, 'Hellfire Citadel',            '2015-06-23', '2016-08-29'),
                                                          
                   (30, 7, 'The Emerald Nightmare',       '2016-09-20', '2017-01-09'),
                   (31, 7, 'Trial of Valor',              '2016-10-25', '2017-01-09'),
                   (32, 7, 'Nighthold',                   '2017-01-10', '2017-06-19'),
                   (33, 7, 'Tomb of Sargeras',            '2017-06-20', '2017-08-28'),
                   (34, 7, 'Antorus, The Burning Throne', '2017-08-29', '2018-08-13'),

                   (35, 8, 'Uldir',                       '2018-09-04', '2019-01-21'),
                   (36, 8, 'Dazar\'alor',                 '2019-01-22', '2019-07-08'),
                   (37, 8, 'Eternal Palace',              '2019-07-09', NULL)
		");
	}
};