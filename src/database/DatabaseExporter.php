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

class DatabaseExporter {
	public function __construct() {
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

	public function ExportToCsv() {
		global $wpdb;
		$archive = new \ZipArchive();
		
		if($archive->open("../wp-content/plugins/WoWRaidOrganizer/wro_backup.zip", \ZipArchive::CREATE) === TRUE) {
			foreach($this->_tables as $table) {
				$csv     = "";
				$tuples  = $wpdb->get_results("SELECT * FROM " . $table->GetName() . ";");
				$columns = array_keys((array)$tuples[0]);

				// add column names to csv
				$csv .= $columns[0];
				for($i = 1; $i < count($columns); ++$i) {
					$csv .= "," . $columns[$i];
				}
				$csv .= "\n";

				// add rows to csv
				foreach($tuples as $tuple) {
					$addComma = false;
					foreach($tuple as $column => $value) {
						if($addComma) $csv .= ",";
						$csv .= $value == null ? "\\N" : $value;
						$addComma = true;
					}
					$csv .= "\n";
				}

				// add csv to archive
				if($archive->addFromString($table->GetName() . ".csv", $csv) === FALSE) {
					throw new Exception("Could not add " . $table->GetName() . ".csv to the archive.");
				}
			}

			if($archive->close() === FALSE) throw new Exception("Could not create the backup archive.");
		} else throw new Exception("Could not create the backup archive.");

		return true;
	}

	private $_tables;
};