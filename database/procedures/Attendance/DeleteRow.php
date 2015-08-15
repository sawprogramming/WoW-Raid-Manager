<?php
namespace Attendance;

class DeleteRow {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;
		$result = NULL;

    	$result = $wpdb->query($wpdb->prepare("
    		DELETE FROM Attendance
			WHERE ID = %d;
		", $id));
        
        return $result;
	}
}