<?php
namespace Tables;
require_once (plugin_dir_path(__FILE__)."./tables/Attendance.php");
require_once (plugin_dir_path(__FILE__)."./tables/ImportHistory.php");
require_once (plugin_dir_path(__FILE__)."./tables/Item.php");
require_once (plugin_dir_path(__FILE__)."./tables/Player.php");
require_once (plugin_dir_path(__FILE__)."./tables/Raid.php");
require_once (plugin_dir_path(__FILE__)."./tables/RaidLoot.php");
require_once (plugin_dir_path(__FILE__)."./tables/Class.php");

class DatabaseInstaller {
	private function __construct() {}

	public static function Install() {
		ClassTable::CreateTable();
		ItemTable::CreateTable();
		RaidTable::CreateTable();
		PlayerTable::CreateTable();
		AttendanceTable::CreateTable();
		RaidLootTable::CreateTable();
		ImportHistoryTable::CreateTable();
	}

	public static function Uninstall() {
		ImportHistoryTable::DropTable();
		RaidLootTable::DropTable();
		AttendanceTable::DropTable();
		PlayerTable::DropTable();
		RaidTable::DropTable();
		ItemTable::DropTable();
		ClassTable::DropTable();
	}
}