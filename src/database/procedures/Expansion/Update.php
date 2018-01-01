<?php
namespace WRO\Database\Procedures\Expansion;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/ExpansionTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/ExpansionEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Update extends Procedures\StoredProcedure {
	public static function Run(Entities\ExpansionEntity $entity) {
		global $wpdb;
		$result = NULL;
		$expansionTable = new Tables\ExpansionTable();

		if($entity->EndDate === NULL) {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE " . $expansionTable->GetName() . "
				SET Name = %s, 
				    StartDate = %s,
				    EndDate = NULL
				WHERE ID = %d;
			", $entity->Name, $entity->StartDate, $entity->ID));
		} else {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE " . $expansionTable->GetName() . "
				SET Name = %s, 
				    StartDate = %s,
				    EndDate = %s
				WHERE ID = %d;
			", $entity->Name, $entity->StartDate, $entity->EndDate, $entity->ID));
		}

		return $result;
	}
};