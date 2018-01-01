<?php
namespace WRO\Database\Procedures\Dispute;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/DisputeTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/DisputeEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Update extends Procedures\StoredProcedure {	
	public static function Run(Entities\DisputeEntity $entity) {
		global $wpdb;
		$disputeTable = new Tables\DisputeTable();

		return $wpdb->query($wpdb->prepare("
			UPDATE " . $disputeTable->GetName() . "
			SET Verdict = %d
			WHERE ID = %d;
		", $entity->Verdict, $entity->ID));
	}
};