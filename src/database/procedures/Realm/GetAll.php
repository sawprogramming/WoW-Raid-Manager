<?php
namespace WRO\Database\Procedures\Realm;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RealmTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetAll extends Procedures\StoredProcedure {
	public static function Run($region) {
		global $wpdb;
		$realmTable = new Tables\RealmTable();

		return $wpdb->get_results($wpdb->prepare("
			SELECT *
			FROM " .    $realmTable->GetName() .  "
			WHERE Region = %s;
		", $region));
	}
};