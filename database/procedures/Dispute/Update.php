<?php
namespace Dispute;
include_once plugin_dir_path(__FILE__)."../../../entities/DisputeEntity.php";

class Update {
	private function __construct() {}
	
	public function Run(\DisputeEntity $entity) {
		global $wpdb;

		return $wpdb->query($wpdb->prepare("
			UPDATE Dispute
			SET Verdict = %d
			WHERE ID = %d;
		", $entity->Verdict, $entity->ID));
	}
}