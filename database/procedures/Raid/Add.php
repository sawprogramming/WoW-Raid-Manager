<?php
namespace Raid;
include_once plugin_dir_path(__FILE__)."../../../entities/RaidEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\RaidEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO Raid (Name)
				VALUES (%s);
			", $entity->Name));
		} catch (Exception $e) {

		}

		return $result;
	}
}