<?php
namespace WRO\Database\Procedures\Realm;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RealmTable.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Add extends Procedures\StoredProcedure {
	public static function Run(Entities\RealmEntity $entity) {
		global $wpdb;
		$realmTable = new Tables\RealmTable();

		return $result = $wpdb->query($wpdb->prepare("
			INSERT IGNORE INTO " . $realmTable->GetName() . " (Slug, Name, Region)
			VALUES (%s, %s, %s);
		", $entity->Slug, $entity->Name, $entity->Region));
	}
};