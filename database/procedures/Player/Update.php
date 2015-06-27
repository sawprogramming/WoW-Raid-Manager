<?php
namespace Player;
include_once plugin_dir_path(__FILE__)."../../../entities/PlayerEntity.php";

class Update {
	private function __construct() {}

	public static function Run(\PlayerEntity $entity) {
		global $wpdb;
		$result = NULL;

		if($entity->UserID === NULL) {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE Player
				SET UserID = NULL,
					ClassID = %d, 
				    Name = %s,
				    Icon = %s
				WHERE ID = %d;
			", $entity->ClassID, $entity->Name, $entity->Icon, $entity->ID));
		} else {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE Player
				SET UserID = %d,
					ClassID = %d, 
				    Name = %s,
				    Icon = %s
				WHERE ID = %d;
			", $entity->UserID, $entity->ClassID, $entity->Name, $entity->Icon, $entity->ID));
		}

		return $result;
	}
}