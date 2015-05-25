<?php
namespace Attendance;

class DeleteRow {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

		try {
        	$result = $wpdb->query($wpdb->prepare("
        		DELETE FROM Attendance
    			WHERE ID = %d;
    		", $id));
        } catch (Exception $e) {

        }
        
        return $result;
	}
}