<?php
namespace Dispute;
include_once plugin_dir_path(__FILE__)."../../../entities/DisputeEntity.php";

class Add {
	private function __construct() {}
	
	public function Run(\DisputeEntity $entity) {
		global $wpdb;

		return $wpdb->query($wpdb->prepare("
			INSERT INTO Dispute (AttendanceID, Points, Comment)
			VALUES (%d, %f, %s);
		", $entity->AttendanceID, $entity->Points, $entity->Comment));
	}
}