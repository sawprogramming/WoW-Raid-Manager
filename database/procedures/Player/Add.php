<?php
namespace Player;
include_once plugin_dir_path(__FILE__)."../../../entities/PlayerEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\PlayerEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO Player (ClassID, Name)
				VALUES (%d, %s);
			", $entity->ClassID, $entity->Name));
		} catch (Exception $e) {

		}

		return $result;
	}
}