<?php
namespace WRO\Database\Procedures\RaidTier;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidTierTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAll extends Procedures\StoredProcedure {
	public static function Run() {
		global $wpdb;
		$raidTierTable = new Tables\RaidTierTable();

		return $wpdb->get_results("
			SELECT *
        	FROM " . $raidTierTable->GetName() . "
        	ORDER BY StartDate ASC;
		");
	}
};