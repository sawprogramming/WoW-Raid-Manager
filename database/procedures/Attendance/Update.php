<?php
namespace Attendance;
include_once plugin_dir_path(__FILE__)."../../../entities/AttendanceEntity.php";

class Update {
	public function __construct() {}

	public function Run(\AttendanceEntity $entity) {
		global $wpdb;
		$result = NULL;

		$result = $wpdb->query($wpdb->prepare("
			UPDATE Attendance
			SET PlayerID = %d, Date = %s, Points = %f
			WHERE ID = %d;
		", $entity->PlayerID, $entity->Date, $entity->Points, $entity->ID));

		return $result;
	}
}