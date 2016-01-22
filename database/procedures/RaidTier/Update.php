<?php
namespace WRO\Database\Procedures\RaidTier;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidTierTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/RaidTierEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Update extends Procedures\StoredProcedure {
	public static function Run(Entities\RaidTierEntity $entity) {
		global $wpdb;
		$result = NULL;
		$raidTierTable = new Tables\RaidTierTable();

		if($entity->EndDate === NULL) {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE " . $raidTierTable->GetName() . "
				SET ExpansionID = %u,
				    Name = %s, 
				    StartDate = %s,
				    EndDate = NULL
				WHERE ID = %u;
			", $entity->ExpansionID, $entity->Name, $entity->StartDate, $entity->ID));
		} else {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE " . $raidTierTable->GetName() . "
				SET ExpansionID = %u,
				    Name = %s, 
				    StartDate = %s,
				    EndDate = %s
				WHERE ID = %u;
			", $entity->ExpansionID, $entity->Name, $entity->StartDate, $entity->EndDate, $entity->ID));
		}

		return $result;
	}
};