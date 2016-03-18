<?php
namespace WRO\Database\Procedures\HeroClass;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/ClassTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAll extends Procedures\StoredProcedure {
	public static function Run() {
		global $wpdb;
		$classTable = new Tables\ClassTable();

		return $wpdb->get_results("
			SELECT *
        	FROM " . $classTable->GetName() . "
        	ORDER BY Name ASC;
		");
	}
};