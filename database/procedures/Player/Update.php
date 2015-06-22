<?php
namespace Player;
include_once plugin_dir_path(__FILE__)."../../../entities/PlayerEntity.php";

class Update {
	private function __construct() {}

	public static function Run(\PlayerEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
				UPDATE Player
				SET ClassID = %d, 
				    Name = %s,
				    Icon = %s
				WHERE ID = %d;
			", $entity->ClassID, $entity->Name, $entity->Icon, $entity->ID));
		} catch (Exception $e) {

		}

		return $result;
	}
}