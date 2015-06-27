<?php
namespace Player;
include_once plugin_dir_path(__FILE__)."../../../entities/PlayerEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\PlayerEntity $entity) {
		global $wpdb;
		$result = NULL;

		if($entity->UserID === NULL) {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO Player (ClassID, Name, Icon)
				VALUES (%d, %s, %s);
			", $entity->ClassID, $entity->Name, $entity->Icon));
		} else {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO Player (UserID, ClassID, Name, Icon)
				VALUES (%d, %d, %s, %s);
			", $entity->UserID, $entity->ClassID, $entity->Name, $entity->Icon));
		}

		return $result;
	}
}