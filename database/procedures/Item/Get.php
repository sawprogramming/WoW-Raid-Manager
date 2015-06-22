<?php
namespace Item;
include_once plugin_dir_path(__FILE__)."../../../entities/ItemEntity.php";

class Get {
	private function __construct() {}

	public static function Run(\ItemEntity $entity) {
		global $wpdb;
		$result = NULL;

		try {
			$result = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM Item
				WHERE ID = %d AND Context = %s;
			", $entity->ID, $entity->Context));
		} catch (Exception $e) {

		}

		return $result;
	}
}