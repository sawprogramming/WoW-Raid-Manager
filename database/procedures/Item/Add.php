<?php
namespace Item;
include_once plugin_dir_path(__FILE__)."../../../entities/ItemEntity.php";

class Add {
	private function __construct() {}

	public static function Run(\ItemEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->query($wpdb->prepare("
				INSERT INTO Item (ID, Context, ItemLevel)
				VALUES (%d, %s, %d);
			", $entity->ID, $entity->Context, $entity->Level));
		} catch (Exception $e) {

		}

		return $result;
	}
}