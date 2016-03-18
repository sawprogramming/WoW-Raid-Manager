<?php
namespace WRO\Database\Procedures\Dispute;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/DisputeTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Delete extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$disputeTable = new Tables\DisputeTable();

		return $wpdb->query($wpdb->prepare("
			DELETE FROM " . $disputeTable->GetName() . " 
			WHERE AttendanceID = %u;
		", $id));
	}
};