<?php
namespace RaidLoot;

class DeleteRow {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

    	$result = $wpdb->query("
    		DELETE FROM RaidLoot 
    		WHERE ID = {$id};
		");
        
        return $result;
	}
}