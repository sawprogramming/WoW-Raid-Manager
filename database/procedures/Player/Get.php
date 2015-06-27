<?php
namespace Player;

class Get {
	private function __construct() {}

	public static function Run($id) {
		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("
			SELECT pl.ID, pl.UserID, wp.user_login as Username, pl.ClassID, cl.Name as ClassName, pl.Name
			FROM Player as pl
				JOIN Class as cl ON pl.ClassID = cl.ID
				LEFT JOIN ".$wpdb->prefix."users as wp ON pl.UserID = wp.ID
			WHERE pl.ID = %d;
		", $id));
	}
}