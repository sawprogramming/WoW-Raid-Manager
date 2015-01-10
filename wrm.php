<?php
/**
 * Plugin Name: WoW Raid Manager
 * Description: Modules for loot and attendance.
 * Version: 0.0.1
 * Author: Criminal-SR
 * License: GPL2
 */
require_once(plugin_dir_path( __FILE__ ) . 'libs/PageTemplater.php');
require_once(plugin_dir_path( __FILE__ ) . 'display.php');
require_once(plugin_dir_path( __FILE__ ) . 'dao.php');
class WRM {
	// Installation functions
	public function Install() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		
		// create class table
		$sql = "CREATE TABLE IF NOT EXISTS WRM_Class (
			ID  tinyint(2) NOT NULL AUTO_INCREMENT,
			Name tinytext NOT NULL,
			PRIMARY KEY  ID (ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create raid table
		$sql = "CREATE TABLE IF NOT EXISTS WRM_Raid (
			ID  tinyint(3) NOT NULL AUTO_INCREMENT,
			Name tinytext NOT NULL,
			PRIMARY KEY  ID (ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create player table
		$sql = "CREATE TABLE IF NOT EXISTS WRM_Player (
			ID  smallint(5) NOT NULL AUTO_INCREMENT,
			ClassID  tinyint(3),
			Name tinytext NOT NULL,
			PRIMARY KEY  ID (ID),
			FOREIGN KEY (ClassID) REFERENCES WRM_Class(ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create loot table
		$sql = "CREATE TABLE IF NOT EXISTS WRM_Loot (
			ID  int(10) NOT NULL AUTO_INCREMENT,
			PlayerID  smallint(5),
			Item tinytext NOT NULL,
			RaidID  tinyint(3),
			Date date NOT NULL,
			PRIMARY KEY  ID (ID),
			FOREIGN KEY (PlayerID) REFERENCES WRM_Player(ID),
			FOREIGN KEY (RaidID) REFERENCES WRM_Raid(ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create attendance table
		$sql = "CREATE TABLE IF NOT EXISTS WRM_Attendance (
			ID  int(10) NOT NULL AUTO_INCREMENT,
			PlayerID  smallint(5),
			Date date NOT NULL,
			Points  float(3, 2),
			PRIMARY KEY  ID (ID),
			FOREIGN KEY (PlayerID) REFERENCES WRM_Player(ID)
		) $charset_collate;";
		dbDelta($sql);
	}
	public function Uninstall() {
		global $wpdb;

/*		$wpdb->query("DROP TABLE IF EXISTS WRM_Attendance");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Loot");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Player");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Raid");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Class");	*/
	}	

	// AJAX functions
	public function AddPlayer() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			echo WRM_DAO::AddPlayer($_POST['name'], intval($_POST['classId']));
		}
		die();
	}
	public function RmPlayer() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			echo WRM_DAO::RmPlayer(intval($_POST['id']));
		}
		die();
	}
	public function AddGroupAttendance() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			$results = $_POST['results'];
			$today = $_POST['date'];

			foreach($results as $player) WRM_DAO::AddAttnd(intval($player["id"]), floatval($player["points"]), $today);
		}
		die();
	}
	public function RmAttnd() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			WRM_DAO::RmAttnd(intval($_POST['id']));
		}
		die();
	}
	public function RmLoot() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			echo WRM_DAO::RmLoot(intval($_POST['id']));
		}
		die();
	}
	public function AddAttnd() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			$id;
			$name = $_POST['name'];

			// get the player id from the name
			if(preg_match('/^([A-Za-z]+)$/', $name)) {
				// find the id if this was a name
				$row = $wpdb->get_results($wpdb->prepare("SELECT ID FROM WRM_Player WHERE Name = %s", $name));
				if(count($row) > 1)  { echo "ERROR: Could not find a unique player with that name."; die(); }
				if(count($row) == 0) { echo "ERROR: No players exist with that name.";               die(); }

				$id = intval($row[0]->ID);
			}
			else if(preg_match('/^([0-9]+)$/', $name)) {
				$id = intval($name);

				// is this a valid id?
				$row = $wpdb->get_row($wpdb->prepare("SELECT Name FROM WRM_Player WHERE ID = %d", $id));
				if($row == NULL) { echo "ERROR: Could not find a player with that ID."; die(); }
			}
			else { echo "ERROR: Name was not valid (should be a string of characters or an ID number)."; die(); }

			// insert the record
			$result = WRM::DbTransaction($wpdb->prepare(
				"INSERT INTO WRM_Attendance (PlayerID, Points, Date)
				VALUES (%d, %f, %s)", $id, floatval($_POST['points']), $_POST['date']));
			if($result) echo $wpdb->insert_id;
			else        echo "ERROR: An error occurred while trying to insert the record into the database.";
		}
		die();
	}
	public function EditAttnd() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			echo WRM_DAO::EditAttnd(intval($_POST['id']), floatval($_POST['points'], $_POST['date']));
		}
		die();
	}
	public function FreeSql() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			global $wpdb;
			
			// run the sql
			$results = $wpdb->get_results(str_replace("\\", "", $_GET['sql']));
			
			if($results != NULL) {
				// table header
				$html = "<table id=\"tblManualSql\" class=\"wrm\"><thead>";
				foreach($results[0] as $key => $value) $html .= "<th>".$key."</th>"; 
				$html .= "</thead><tbody>";

				// table body
				foreach($results as $row) {
					$html .= "<tr>";
					foreach($row as $data) $html .= "<td>".$data."</td>";
					$html .= "</tr>";
				}
				$html .= "</tbody></table>";

				echo $html;
			} else echo "Query did not return any results.\n";
		}
		die();
	}
	public function Raids() {
		echo WRM_DAO::GetRaids();
		die();
	}
}
register_activation_hook(__FILE__, array('WRM', 'Install'));
register_deactivation_hook(__FILE__, array('WRM', 'Uninstall'));
add_action('wp_ajax_wrm_addplayer', array('WRM', 'AddPlayer'));
add_action('wp_ajax_wrm_rmplayer', array('WRM', 'RmPlayer'));
add_action('wp_ajax_wrm_rmattnd', array('WRM', 'RmAttnd'));
add_action('wp_ajax_wrm_addattnd', array('WRM', 'AddAttnd'));
add_action('wp_ajax_wrm_rmloot', array('WRM', 'RmLoot'));
add_action('wp_ajax_wrm_editattnd', array('WRM', 'EditAttnd'));
add_action('wp_ajax_wrm_addgrpatt', array('WRM', 'AddGroupAttendance'));
add_action('wp_ajax_wrm_freesql', array('WRM', 'FreeSql'));
add_action('wp_ajax_wrm_raids', array('WRM', 'Raids'));
add_action('plugins_loaded', array('PageTemplater', 'get_instance')); ?>