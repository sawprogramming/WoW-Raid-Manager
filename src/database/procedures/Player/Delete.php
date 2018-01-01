<?php
namespace WRO\Database\Procedures\Player;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/PlayerTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Delete extends Procedures\StoredProcedure {
	public static function Run($id) {
		global $wpdb;
		$playerTable = new Tables\PlayerTable();

		return $wpdb->query($wpdb->prepare("
			DELETE FROM " . $playerTable->GetName() . "
			WHERE ID = %d;
		", $id));
	}
};