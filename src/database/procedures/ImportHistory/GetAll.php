<?php
namespace WRO\Database\Procedures\ImportHistory;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/ImportHistoryTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAll extends Procedures\StoredProcedure {
	public static function Run() {
		global $wpdb;
		$importHistoryTable = new Tables\ImportHistoryTable();

		return $wpdb->get_results("
			SELECT *
			FROM " . $importHistoryTable->GetName() . ";
		");
	}
};