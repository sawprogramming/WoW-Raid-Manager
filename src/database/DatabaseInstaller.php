<?php
namespace WRO\Database;
require_once(plugin_dir_path(__FILE__)."tables/ClassTable.php");
require_once(plugin_dir_path(__FILE__)."tables/RealmTable.php");
require_once(plugin_dir_path(__FILE__)."tables/PlayerTable.php");
require_once(plugin_dir_path(__FILE__)."tables/DisputeTable.php");
require_once(plugin_dir_path(__FILE__)."tables/RaidLootTable.php");
require_once(plugin_dir_path(__FILE__)."tables/RaidTierTable.php");
require_once(plugin_dir_path(__FILE__)."tables/ExpansionTable.php");
require_once(plugin_dir_path(__FILE__)."tables/AttendanceTable.php");
require_once(plugin_dir_path(__FILE__)."tables/ImportHistoryTable.php");

class DatabaseInstaller {
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

	public function Install() {
		for($i = 0; $i < count($this->_tables); ++$i) {	
			$this->_tables[$i]->CreateTable();
		}
	}

	public function Uninstall() {
		global $wpdb;
		$wpdb->query("SET FOREIGN_KEY_CHECKS = 0;");
		for($i = count($this->_tables) - 1; $i >= 0; --$i) {	
			$this->_tables[$i]->DropTable();
		}
		$wpdb->query("SET FOREIGN_KEY_CHECKS = 1;");
	}

	private $_tables;
};