<?php
namespace WRO\Database;
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
require_once(plugin_dir_path(__FILE__)."tables/ClassTable.php");
require_once(plugin_dir_path(__FILE__)."tables/RealmTable.php");
require_once(plugin_dir_path(__FILE__)."tables/PlayerTable.php");
require_once(plugin_dir_path(__FILE__)."tables/DisputeTable.php");
require_once(plugin_dir_path(__FILE__)."tables/RaidLootTable.php");
require_once(plugin_dir_path(__FILE__)."tables/RaidTierTable.php");
require_once(plugin_dir_path(__FILE__)."tables/ExpansionTable.php");
require_once(plugin_dir_path(__FILE__)."tables/AttendanceTable.php");
require_once(plugin_dir_path(__FILE__)."tables/ImportHistoryTable.php");
use Exception;

class DatabaseImporter {
	public function __construct() {
		// *** ORDER IS IMPORTANT HERE ***
		$this->_tables = array(
			new Tables\RealmTable(),
			new Tables\ExpansionTable(),
			new Tables\RaidTierTable(),
			new Tables\ClassTable(),
			new Tables\PlayerTable(),
			new Tables\AttendanceTable(),
			new Tables\RaidLootTable(),
			new Tables\DisputeTable(),
			new Tables\ImportHistoryTable()
		);
	}

	public function ImportFromCsv() {
		global $wpdb;
		$archive = new \ZipArchive();

        if($archive->open(WRO_PATH . "wro_backup.zip") === TRUE) {
    		if($archive->extractTo(WRO_PATH . "backup/") === TRUE) {
				// make sure all tables' backups are here before restoring
				foreach($this->_tables as $table) {
					if(!file_exists(WRO_PATH . "backup/" . $table->GetName() . ".csv")) {
						throw new Exception("Missing a backup file for the " . $table->GetName() . " table!");
					}
				}

				// truncate tables (FKs complain too much during this, so they get disabled for the duration)
				$wpdb->query("SET FOREIGN_KEY_CHECKS = 0;");
				for($i = count($this->_tables) - 1; $i >= 0; --$i) {	
					$this->_tables[$i]->TruncateTable();
				}
				$wpdb->query("SET FOREIGN_KEY_CHECKS = 1;");

				// import the tables' backups
				foreach($this->_tables as $table) {
					$inFile = WRO_PATH . "backup/" . $table->GetName() . ".csv";

					$sql = "
						LOAD DATA LOCAL INFILE '" . $inFile . "'
						INTO TABLE " . $table->GetName() . " 
						FIELDS TERMINATED BY ','
						LINES TERMINATED BY '\n'
						IGNORE 1 LINES;
					";
					if($wpdb->query($sql) === FALSE) {
						throw new Exception($wpdb->last_error);
					}

					unlink($inFile);
				}
    		} else throw new Exception("Could not extract the backups from the archive.");
			$archive->close();
        } else throw new Exception("Could not open the backup archive.");

        rmdir(WRO_PATH . "backup");
		return true;
	}

	private $_tables;
};