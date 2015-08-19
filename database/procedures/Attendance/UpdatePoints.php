<?php
namespace Attendance;
include_once plugin_dir_path(__FILE__)."../../../entities/AttendanceEntity.php";

class UpdatePoints {
	public function __construct() {}

	public function Run(\AttendanceEntity $entity) {
		global $wpdb;

		return $wpdb->query($wpdb->prepare("
			UPDATE Attendance
			SET Points = %f
			WHERE ID = %d;
		", $entity->Points, $entity->ID));
	}
}