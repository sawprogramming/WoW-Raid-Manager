<?php
namespace WRO\Database\Procedures\Player;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/PlayerTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/PlayerEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Update extends Procedures\StoredProcedure {
	public static function Run(Entities\PlayerEntity $entity) {
		global $wpdb;
		$result = NULL;
		$playerTable = new Tables\PlayerTable();

		if($entity->UserID === NULL) {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE " . $playerTable->GetName() . "
				SET UserID = NULL,
					ClassID = %u, 
				    Name = %s,
				    Icon = %s,
				    Active = %d
				WHERE ID = %u;
			", $entity->ClassID, $entity->Name, $entity->Icon, $entity->Active, $entity->ID));
		} else {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE " . $playerTable->GetName() . "
				SET UserID = %u,
					ClassID = %u, 
				    Name = %s,
				    Icon = %s,
				    Active = %d
				WHERE ID = %u;
			", $entity->UserID, $entity->ClassID, $entity->Name, $entity->Icon, $entity->Active, $entity->ID));
		}

		return $result;
	}
};