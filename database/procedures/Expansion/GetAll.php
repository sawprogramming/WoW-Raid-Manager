<?php
namespace WRO\Database\Procedures\Expansion;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/ExpansionTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAll extends Procedures\StoredProcedure {
	public static function Run() {
		global $wpdb;
		$expansionTable = new Tables\ExpansionTable();

		return $wpdb->get_results("
			SELECT *
        	FROM " . $expansionTable->GetName() . "
        	ORDER BY StartDate ASC;
		");
	}
};