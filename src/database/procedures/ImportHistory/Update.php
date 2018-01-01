<?php
namespace WRO\Database\Procedures\ImportHistory;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/ImportHistoryTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/ImportHistoryEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Update extends Procedures\StoredProcedure {
	public static function Run(Entities\ImportHistoryEntity $entity) {
		global $wpdb;
		$importHistoryTable = new Tables\ImportHistoryTable();

		return $wpdb->query($wpdb->prepare("
            UPDATE " . $importHistoryTable->GetName() . "
            SET LastImported = %f
            WHERE PlayerID = %d
        ", $entity->LastImported, $entity->PlayerID));
	}
};