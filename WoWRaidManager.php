<?php
/**
 * Plugin Name: WoW Raid Manager
 * Description: Modules for loot and attendance.
 * Version: 0.0.1
 * Author: Criminal-SR
 * License: GPL2
 */
require(plugin_dir_path( __FILE__ ) . 'PageTemplater.php');
require(plugin_dir_path( __FILE__ ) . 'WRM-DAO.php');
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
			ItemID  int(10) NOT NULL,
			BonusOne int(10),
			BonusTwo int(10),
			BonusThree int(10),
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
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			// add the player
			$result = WRM::DbTransaction($wpdb->prepare(
				"INSERT INTO WRM_Player (Name, ClassID)
				 VALUES (%s, %d)", $_POST['name'], intval($_POST['classId'])));

			// return values
			if($result) echo $wpdb->insert_id;
			else        echo "ERROR: An error occurred while trying to insert the record into the database.";
		}
		die();
	}
	public function RmPlayer() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			// remove the player
			WRM::DbTransaction($wpdb->prepare(
				"DELETE FROM WRM_Player
				 WHERE ID = %d", intval($_POST['id'])));
		}
		die();
	}
	public function AddGroupAttendance() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			$results = $_POST['results'];
			$today = $_POST['date'];

			$wpdb->query("START TRANSACTION");
			foreach($results as $player){
				$result = $wpdb->query($wpdb->prepare(
					"INSERT INTO WRM_Attendance (PlayerID, Points, Date)
					VALUES (%d, %f, %s)", intval($player["id"]), floatval($player["points"]), $today));

				// stop the transaction if anything failed
				if(!$result) {
					$wpdb->query("ROLLBACK");
					return;
				}
			}
			$wpdb->query("COMMIT");
		}
		die();
	}
	public function RmAttnd() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			// remove the attendance record
			WRM::DbTransaction($wpdb->prepare(
				"DELETE FROM WRM_Attendance
				 WHERE ID = %d", intval($_POST['id'])));
		}
		die();
	}
	public function RmLoot() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			// remove the loot record
			WRM::DbTransaction($wpdb->prepare(
				"DELETE FROM WRM_Loot
				 WHERE ID = %d", intval($_POST['id'])));
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
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			// edit the attendance record
			$result = WRM::DbTransaction($wpdb->prepare(
				"UPDATE WRM_Attendance
				 SET Points = %f, Date = %s
				 WHERE ID = %d", floatval($_POST['points']), $_POST['date'], intval($_POST['id'])));

			if($result === false) echo "ERROR: An error occurred while updating the database.";
			else if($result)      echo "Row updated!";
			else                  echo "No values were changed in the database.";
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

	// Utility functions
	public function GetClassName($classId) {
		switch($classId){
			case 1: return "druid";
			case 2: return "hunter";
			case 3: return "mage";
			case 4: return "paladin";
			case 5: return "priest";
			case 6: return "rogue";
			case 7: return "shaman";
			case 8: return "warlock";
			case 9: return "warrior";
			case 10: return "deathknight";
			case 11: return "monk";
		}
	} 
	public function BuildLootUrl($itemId, $bonusOne, $bonusTwo, $bonusThree) {
		$itemUrl = "http://www.wowhead.com/item=".$itemId;

		if($bonusOne != NULL) {
			$itemUrl .= "&bonus=".$bonusOne;
			if($bonusTwo != NULL) {
				$itemUrl .= ":".$bonusTwo;
				if($bonusThree != NULL) $itemUrl .= ":".$bonusThree;
			}
		}

		return $itemUrl;
	}
	public function DbTransaction($sql) {
		global $wpdb;

		$wpdb->query("START TRANSACTION");
		$result = $wpdb->query($sql);
		if($result) $wpdb->query("COMMIT");
		else        $wpdb->query("ROLLBACK");

		return $result;
	}
	public function GetAttendanceOver($interval, $playerId) {
		global $wpdb;
		$results;

		// make the sql
		$sql = "SELECT SUM(att.Points) as Earned, Max.Total
	            FROM WRM_Player as pl 
	                JOIN WRM_Attendance as att ON pl.ID = att.PlayerID
	                JOIN (SELECT PlayerID, COUNT(Points) as Total
	                      FROM WRM_Attendance
	                      WHERE PlayerID = %d";
		if($interval > 0) $sql .= " AND Date BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW() ";
		$sql .= " GROUP BY PlayerID) as Max ON Max.PlayerID = att.PlayerID 
	              WHERE att.PlayerID = %d ";
		if($interval > 0) $sql .= " AND att.Date BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW() ";
		$sql .= " GROUP BY att.PlayerID";

		// run the sql
		if($interval < 0) $results = $wpdb->get_row($wpdb->prepare($sql, $playerId, $playerId));
		else 			  $results = $wpdb->get_row($wpdb->prepare($sql, $playerId, $interval, $playerId, $interval));

		if($results->Total == 0) return '0%';
		return ceil(($results->Earned / $results->Total)*100).'%';
	} 

	// Display functions
	public function UserAttndTbl() {
		$html = "<table id=\"tblUserAttnd\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Name</th><th>Class</th><th>Last 2 Weeks</th><th>Last 30 Days</th><th>All Time</th></tr></thead>"
	           ."<tbody>".WRM::GetUserAttndRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function UserLootTbl() {
		$html = "<table id=\"tblUserLoot\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Player</th><th>Item</th><th>Raid</th></tr></thead>"
	           ."<tbody>".WRM::GetUserLootRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function EditPlayerTbl() {
		$html = "<table id=\"tblEditPlayers\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>ID</th><th>Name</th><th>Class</th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM::GetEditPlayerRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function EditLootTbl() {
		$html = "<table id=\"tblEditLoot\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Row</th><th>Name</th><th>Class</th><th>Item</th><th>Raid</th><th>Date</th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM::GetEditLootRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function RaidAttndTbl() {
		$html = "<table id=\"tblRaidAttendance\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Name</th><th>Class</th><th>Points<br /><div id=\"divNewAttBulk\"></div></th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM::GetRaidAttndRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function EditAttndTbl() {
		$html = "<table id=\"tblEditAttnd\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Row</th><th>Name</th><th>Class</th><th>Points</th><th>Date</th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM::GetEditAttndRows()."</tbody>"
	           ."</table>";
		return $html;
	}

	// Database GET functions
	public function GetUserAttndRows() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT DISTINCT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName
			 FROM WRM_Player as pl JOIN WRM_Class as cl on pl.ClassID = cl.ID");

		foreach($results as $player) {
			$html .= "<tr>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td>".WRM::GetAttendanceOver(14, $player->ID)."</td>"
	                ."<td>".WRM::GetAttendanceOver(30, $player->ID)."</td>"
	                ."<td>".WRM::GetAttendanceOver(-1, $player->ID)."</td>"
	                ."</tr>";
		}

		return $html;
	}
	public function GetUserLootRows() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT pl.ClassID, pl.Name, it.ID, it.ItemID, it.BonusOne, it.BonusTwo, it.BonusThree, rd.Name as RaidName
			 FROM WRM_Player as pl JOIN WRM_Loot as it ON pl.ID = it.PlayerID JOIN WRM_Raid as rd ON it.RaidID = rd.ID");

		foreach($results as $loot) {
			$html .= "<tr>"
	                ."<td><span class=\"".WRM::GetClassName($loot->ClassID)."\">$loot->Name</span></td>"
	                ."<td><a href=\"".WRM::BuildLootURL($loot->ItemID, $loot->BonusOne, $loot->BonusTwo, $loot->BonusThree)."\"></a></td>"
	                ."<td>".$loot->RaidName."</td>"
	                ."</tr>";
		}

		return $html;
	}
	public function GetEditPlayerRows() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT pl.ID, pl.Name, cl.ID as ClassID, cl.Name as ClassName
			 FROM WRM_Player as pl JOIN WRM_Class as cl on pl.ClassID = cl.ID
			 ORDER BY pl.Name");

		foreach($results as $player) {
			$html .= "<tr>"
	                ."<td>$player->ID</td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td><button class=\"del\">DELETE</button></td>"
	                ."</tr>";
		}

		return $html;
	}
	public function GetEditLootRows() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT lt.ID as RowID, pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, lt.Date, lt.ItemID, lt.BonusOne, lt.BonusTwo, lt.BonusThree, rd.Name as RaidName
			FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
				JOIN WRM_Loot as lt on pl.ID = lt.PlayerID
				JOIN WRM_Raid as rd on rd.ID = lt.RaidID");

		foreach($results as $player) {
			$html .= "<tr>"
	                ."<td>$player->RowID</td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td><a href=\"".WRM::BuildLootUrl($player->ItemID, $player->BonusOne, $player->BonusTwo, $player->BonusThree)."\"></a></td>"
	                ."<td>$player->RaidName</td>"
	                ."<td>$player->Date</td>"
	                ."<td><button class=\"rmLoot\">DELETE</button></td>"
	                ."</tr>";
		}

		return $html;
	}
	public function GetRaidAttndRows() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName
			FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
			ORDER BY pl.Name");

		foreach($results as $player) {
			$html .= "<tr>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td><div id=\"divNewAttSl".$player->ID."\"></div></td>"
	                ."<td><button value=\"$player->ID\" class=\"delNewAtt\">DELETE</button></td>"
	                ."</tr>";
		}

		return $html;
	} 
	public function GetEditAttndRows() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT at.ID as RowID, pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, at.Date, at.Points
			FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
				JOIN WRM_Attendance as at on pl.ID = at.PlayerID");

		foreach($results as $player) {
			$html .= "<tr>"
	                ."<td>$player->RowID</td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td><div id=\"divEditAttSl".$player->RowID."\">$player->Points</div></td>"
	                ."<td>$player->Date</td>"
	                ."<td><button value=\"$player->RowID\" class=\"rmEditAttnd\">DELETE</button>"
	                .    "<button class=\"editEditAttnd\">EDIT</button></td>"
	                ."</tr>";
		}

		return $html;
	}
	public function GetRaids() {
		global $wpdb;
		return $wpdb->get_results("SELECT ID, Name FROM WRM_Raid");
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