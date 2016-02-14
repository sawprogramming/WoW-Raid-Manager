<?php
namespace WRO\Database\Procedures\Player;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/PlayerTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/PlayerEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Add extends Procedures\StoredProcedure {
	public static function Run(Entities\PlayerEntity $entity) {
		global $wpdb;
		$result = NULL;
		$playerTable = new Tables\PlayerTable();

		if($entity->UserID === NULL) {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO " . $playerTable->GetName() . " (ClassID, Name, Icon, Region, Realm)
				VALUES (%u, %s, %s, %s, %s);
			", $entity->ClassID, $entity->Name, $entity->Icon, $entity->Region, $entity->Realm));
		} else {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO " . $playerTable->GetName() . " (UserID, ClassID, Name, Icon, Region, Realm)
				VALUES (%u, %u, %s, %s, %s, %s);
			", $entity->UserID, $entity->ClassID, $entity->Name, $entity->Icon, $entity->Region, $entity->Realm));
		}

		return $result;
	}
};