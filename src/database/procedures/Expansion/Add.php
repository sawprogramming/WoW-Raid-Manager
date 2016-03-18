<?php
namespace WRO\Database\Procedures\Expansion;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/ExpansionTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/ExpansionEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Add extends Procedures\StoredProcedure {
	public static function Run(Entities\ExpansionEntity $entity) {
		global $wpdb;
		$expansionTable = new Tables\ExpansionTable();

		if($entity->EndDate == NULL) {
			return $wpdb->query($wpdb->prepare("
				INSERT INTO " . $raidTierTable->GetName() . " (Name, StartDate)
	        	VALUES (%s, %s);
	    	", $entity->Name, $entity->StartDate));
		} else {
			return $wpdb->query($wpdb->prepare("
				INSERT INTO " . $raidTierTable->GetName() . " (Name, StartDate, EndDate)
	        	VALUES (%s, %s, %s);
	    	", $entity->Name, $entity->StartDate, $entity->EndDate));
		}
	}
};