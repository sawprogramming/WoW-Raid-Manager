<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
require_once(plugin_dir_path(__FILE__)."../../../entities/AttendanceEntity.php");
use WRO\Entities            as Entities;
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class Update extends Procedures\StoredProcedure {
	public static function Run(Entities\AttendanceEntity $entity) {
		global $wpdb;
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->query($wpdb->prepare("
			UPDATE " . $attendanceTable->GetName() . "
			SET PlayerID = %u, Date = %s, Points = %f
			WHERE ID = %u;
		", $entity->PlayerID, $entity->Date, $entity->Points, $entity->ID));
	}
};