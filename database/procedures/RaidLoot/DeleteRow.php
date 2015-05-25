<?php
namespace RaidLoot;

class DeleteRow {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		try {
        	$result = $wpdb->query("
        		DELETE FROM RaidLoot 
        		WHERE ID = {$id};
    		");
        } catch (Exception $e) {

        }
        
        return $result;
	}
}