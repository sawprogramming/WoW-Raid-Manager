<?php
namespace WRO\Database\Procedures\ImportHistory;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/ImportHistoryTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Get extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$importHistoryTable = new Tables\ImportHistoryTable();

		return $wpdb->get_row($wpdb->prepare("
			SELECT * FROM " . $importHistoryTable->GetName() . "
			WHERE PlayerID = %d;
		", $id));
	}
};