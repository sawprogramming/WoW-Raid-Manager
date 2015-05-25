<?php
namespace Attendance;
include_once plugin_dir_path(__FILE__)."../../../entities/AttendanceEntity.php";

class Add {
	private function __construct() {}
	
	public function Run(\AttendanceEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO Attendance (PlayerID, Date, Points)
				VALUES (%d, %s, %f);
			", $entity->PlayerID, $entity->Date, $entity->Points));
		} catch (Exception $e) {

		}

		return $result;
	}
}