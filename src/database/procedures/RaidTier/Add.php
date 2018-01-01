<?php
namespace WRO\Database\Procedures\RaidTier;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidTierTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/RaidTierEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Add extends Procedures\StoredProcedure {
	public static function Run(Entities\RaidTierEntity $entity) {
		global $wpdb;
		$raidTierTable = new Tables\RaidTierTable();

		if($entity->EndDate == NULL) {
			return $wpdb->query($wpdb->prepare("
				INSERT INTO " . $raidTierTable->GetName() . " (ExpansionID, Name, StartDate)
	        	VALUES (%d, %s, %s);
	    	", $entity->ExpansionID, $entity->Name, $entity->StartDate));
		} else {
			return $wpdb->query($wpdb->prepare("
				INSERT INTO " . $raidTierTable->GetName() . " (ExpansionID, Name, StartDate, EndDate)
	        	VALUES (%d, %s, %s, %s);
	    	", $entity->ExpansionID, $entity->Name, $entity->StartDate, $entity->EndDate));
		}
	}
};