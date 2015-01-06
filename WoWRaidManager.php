<?php
/**
 * Plugin Name: WoW Raid Manager
 * Description: Modules for loot and attendance.
 * Version: 0.0.1
 * Author: Criminal-SR
 * License: GPL2
 */
require(plugin_dir_path( __FILE__ ) . 'PageTemplater.php');
class WRM {
	// Installation functions
	public function Install() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		
		WRM::Uninstall();
		// create class table
		$sql = "CREATE TABLE WRM_Class (
			ID  tinyint(2) NOT NULL AUTO_INCREMENT,
			Name tinytext NOT NULL,
			PRIMARY KEY  ID (ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create raid table
		$sql = "CREATE TABLE WRM_Raid (
			ID  tinyint(3) NOT NULL AUTO_INCREMENT,
			Name tinytext NOT NULL,
			PRIMARY KEY  ID (ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create player table
		$sql = "CREATE TABLE WRM_Player (
			ID  smallint(5) NOT NULL AUTO_INCREMENT,
			ClassID  tinyint(3),
			Name tinytext NOT NULL,
			PRIMARY KEY  ID (ID),
			FOREIGN KEY (ClassID) REFERENCES WRM_Class(ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create loot table
		$sql = "CREATE TABLE WRM_Loot (
			ID  int(10) NOT NULL AUTO_INCREMENT,
			PlayerID  smallint(5),
			ItemID  int(10) NOT NULL,
			BonusOne int(10),
			BonusTwo int(10),
			BonusThree int(10),
			RaidID  tinyint(3),
			Date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  ID (ID),
			FOREIGN KEY (PlayerID) REFERENCES WRM_Player(ID),
			FOREIGN KEY (RaidID) REFERENCES WRM_Raid(ID)
		) $charset_collate;";
		dbDelta($sql);
		
		// create attendance table
		$sql = "CREATE TABLE WRM_Attendance (
			ID  int(10) NOT NULL AUTO_INCREMENT,
			PlayerID  smallint(5),
			Date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			Points  float(3, 2),
			PRIMARY KEY  ID (ID),
			FOREIGN KEY (PlayerID) REFERENCES WRM_Player(ID)
		) $charset_collate;";
		dbDelta($sql);

		WRM::Seed();
	}
	public function Uninstall() {
		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS WRM_Attendance");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Loot");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Player");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Raid");
		$wpdb->query("DROP TABLE IF EXISTS WRM_Class");	
	}
	public function Seed(){
		global $wpdb;

		// Seed Class table
		$wpdb->query("INSERT INTO WRM_Class (Name) 
			          VALUES ('Druid'), ('Hunter'), ('Mage'), ('Paladin'), ('Priest'), ('Rogue'), ('Shaman'), ('Warlock'), ('Warrior'), ('Death Knight'), ('Monk')");

		// Seed Raid table
		$wpdb->query("INSERT INTO WRM_Raid (Name)
			          VALUES ('Highmaul')");

		// Seed Player table
		$wpdb->query("INSERT INTO WRM_Player (ClassID, Name)
			          VALUES ('1', 'Bigplayqtay'), ('1', 'Rejuvqtay'), ('1', 'Saytah'),
			                 ('2', 'Huntaruz'), ('2', 'Jurasu'),
			                 ('3', 'Oximore'), ('3', 'Wolfy'),
			                 ('4', 'Dabou'), ('4', 'Jairulnait'),
			                 ('5', 'Indifer'), ('5', 'Omitted'), ('5', 'Yumae'),
			                 ('6', 'Greeting'), ('6', 'Shadowburger'),
			                 ('7', 'Fossy'), ('7', 'Oracni'), ('7', 'Pomsta'), ('7', 'Youmi'),
			                 ('8', 'Abysselysium'), ('8', 'Glafkos'), ('8', 'Zelant'),
			                 ('9', 'Envoy'), ('9', 'Oggy'), ('9', 'Sgtwasabi'),
			                 ('10', 'Rausch'),
			                 ('11', 'Hitmonchan'), ('11', 'Infleaux')");

		// Seed Attendance Table
		$wpdb->query("INSERT INTO WRM_Attendance (PlayerID, Points) 
			          VALUES ('1', '1'), ('1', '0'), ('4', '1'), ('6', '1'), ('8', '1'), ('10', '1'), ('13', '1'), ('15', '1'), ('19', '1'), ('22', '1'), ('25', '1'), ('26', '1')");

		// Seed Loot Table
		$wpdb->query("INSERT INTO WRM_Loot (PlayerID, ItemID, BonusOne, BonusTwo, BonusThree, RaidID)
			          VALUES ('26', '113591', '562', '565', '567', '1'),
			                 ('18', '113591', '562', '565', NULL, '1'),
			                 ('14', '113591', '562', NULL , NULL, '1')");
	}		

	// AJAX functions
	public function AddPlayer() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			// add the player
			$wpdb->query("START TRANSACTION");
			$result = $wpdb->query($wpdb->prepare(
				"INSERT INTO WRM_Player (Name, ClassID)
				 VALUES (%s, %d)", $_POST['name'], intval($_POST['classId'])));
			if($result) $wpdb->query("COMMIT");
			else        $wpdb->query("ROLLBACK");

			// return the id
			$row = $wpdb->get_row($wpdb->prepare(
				"SELECT ID 
				FROM WRM_Player 
				WHERE Name = %s AND ClassID = %d", $_POST['name'], intval($_POST['classId'])));

			echo $row->ID;
		}
		wp_die();
	}
	public function RmPlayer() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			$wpdb->query("START TRANSACTION");
			$result = $wpdb->query($wpdb->prepare(
				"DELETE FROM WRM_Player
				 WHERE ID = %d", intval($_POST['id'])));
			if($result) $wpdb->query("COMMIT");
			else        $wpdb->query("ROLLBACK");
		}
		wp_die();
	}
	public function AddGroupAttendance() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			$results = $_POST['results'];

			$wpdb->query("START TRANSACTION");
			foreach($results as $player){
				$result = $wpdb->query($wpdb->prepare(
					"INSERT INTO WRM_Attendance (PlayerID, Points)
					VALUES (%d, %f)", intval($player["id"]), floatval($player["points"])));

				// stop the transaction if anything failed
				if(!$result) {
					$wpdb->query("ROLLBACK");
					return;
				}
			}
			$wpdb->query("COMMIT");
		}
		wp_die();
	}
	public function RmAttnd() {
		global $wpdb;

		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			$wpdb->query("START TRANSACTION");
			$result = $wpdb->query($wpdb->prepare(
				"DELETE FROM WRM_Attendance
				 WHERE ID = %d", intval($_POST['id'])));
			if($result) $wpdb->query("COMMIT");
			else        $wpdb->query("ROLLBACK");
		}
		wp_die();
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

	// Database functions
	public function GetPlayers() {
		global $wpdb;

		$html = "";

		$results = $wpdb->get_results(
			"SELECT pl.ID, pl.Name, cl.ID as ClassID, cl.Name as ClassName
			 FROM WRM_Player as pl JOIN WRM_Class as cl on pl.ClassID = cl.ID
			 ORDER BY pl.Name");

		foreach($results as $player) {
			$html .= "<tr style=\"background: rgba(0,0,0,0);\">";
			$html .= "<td>$player->ID</td>";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>";
			$html .= "<td><button class=\"del\">DELETE</button></td>";
			$html .= "</tr>";
		}

		return $html;
	}
	public function GetLoot() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT pl.ClassID, pl.Name, it.ID, it.ItemID, it.BonusOne, it.BonusTwo, it.BonusThree, rd.Name as RaidName
			 FROM WRM_Player as pl JOIN WRM_Loot as it ON pl.ID = it.PlayerID JOIN WRM_Raid as rd ON it.RaidID = rd.ID");

		foreach($results as $loot) {
			$html .= "<tr style=\"background: rgba(0,0,0,0);\">";
			$html .= "<td><span class=\"".WRM::GetClassName($loot->ClassID)."\">$loot->Name</span></td>";
			$html .= "<td><a href=\"".WRM::BuildLootURL($loot->ItemID, $loot->BonusOne, $loot->BonusTwo, $loot->BonusThree)."\"></a></td>";
			$html .= "<td>".$loot->RaidName."</td>";
			$html .= "</tr>";
		}

		return $html;
	}
	public function GetAttendanceOver($interval, $playerId) {
		global $wpdb;

		if($interval < 0) 
			$results = $wpdb->get_row($wpdb->prepare(
			"SELECT pl.Name, SUM(att.Points) as Earned, Max.Total
			 FROM WRM_Player as pl 
			 	INNER JOIN WRM_Attendance as att 
			 		ON pl.ID = att.PlayerID
			 	INNER JOIN (
			 		SELECT PlayerID, COUNT(Points) as Total
		 		    FROM WRM_Attendance
			 		WHERE PlayerID = %d
			 		GROUP BY PlayerID) as Max 
						ON Max.PlayerID = att.PlayerID
			 WHERE att.PlayerID = %d
			 	GROUP BY att.PlayerID", $playerId, $playerId));
		else
			$results = $wpdb->get_row($wpdb->prepare(
				"SELECT pl.Name, SUM(att.Points) as Earned, Max.Total
				 FROM WRM_Player as pl 
				 	INNER JOIN WRM_Attendance as att 
				 		ON pl.ID = att.PlayerID
				 	INNER JOIN (
				 		SELECT PlayerID, COUNT(Points) as Total
			 		    FROM WRM_Attendance
				 		WHERE PlayerID = %d
				 		  	AND Date BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW()
				 		GROUP BY PlayerID) as Max 
							ON Max.PlayerID = att.PlayerID
				 WHERE att.PlayerID = %d
				 	AND att.Date BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW()
				 	GROUP BY att.PlayerID", $playerId, $interval, $playerId, $interval));

		if($results->Total == 0) return '0%';
		return ceil(($results->Earned / $results->Total)*100).'%';
	} 
	public function GetPlayersAttendance() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT DISTINCT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName
			 FROM WRM_Player as pl JOIN WRM_Class as cl on pl.ClassID = cl.ID");

		foreach($results as $player) {
			$html .= "<tr style=\"background: rgba(0,0,0,0);\"><td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td><td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>";
			$html .= "<td>".WRM::GetAttendanceOver(14, $player->ID)."</td><td>".WRM::GetAttendanceOver(30, $player->ID)."</td><td>".WRM::GetAttendanceOver(-1, $player->ID)."</td>";
		}

		return $html;
	}
	public function RaidAttendanceForm() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName
			FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
			ORDER BY pl.Name");

		foreach($results as $player) {
			$html .= "<tr style=\"background: rgba(0,0,0,0);\">";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>";
            $html .= "<td><div id=\"divNewAttSl".$player->ID."\"></div></td>";
			$html .= "<td><button value=\"$player->ID\" class=\"delNewAtt\">DELETE</button></td>";
			$html .= "</tr>";
		}

		return $html;
	} 
	public function EditAttendanceForm() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT at.ID as RowID, pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, at.Date, at.Points
			FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
				JOIN WRM_Attendance as at on pl.ID = at.PlayerID");

		foreach($results as $player) {
			$html .= "<tr style=\"background: rgba(0,0,0,0);\">";
			$html .= "<td>$player->RowID</td>";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>";
			$html .= "<td><div id=\"divEditAttSl".$player->RowID."\">$player->Points</div></td>";
			$html .= "<td>$player->Date</td>";
			$html .= "<td><button value=\"$player->RowID\" class=\"rmEditAttnd\">DELETE</button></td>";
			$html .= "</tr>";
		}

		return $html;
	}
	public function EditLootForm() {
		global $wpdb;
		$html = "";

		$results = $wpdb->get_results(
			"SELECT lt.ID as RowID, pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName, lt.Date, lt.ItemID, lt.BonusOne, lt.BonusTwo, lt.BonusThree, rd.Name as RaidName
			FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
				JOIN WRM_Loot as lt on pl.ID = lt.PlayerID
				JOIN WRM_Raid as rd on rd.ID = lt.RaidID");

		foreach($results as $player) {
			$html .= "<tr style=\"background: rgba(0,0,0,0);\">";
			$html .= "<td>$player->RowID</td>";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->Name</span></td>";
			$html .= "<td><span class=\"".WRM::GetClassName($player->ClassID)."\">$player->ClassName</span></td>";
            $html .= "<td><a href=\"".WRM::BuildLootUrl($player->ItemID, $player->BonusOne, $player->BonusTwo, $player->BonusThree)."\"></a></td>";
            $html .= "<td>$player->RaidName</td>";
			$html .= "<td>$player->Date</td>";
			$html .= "<td><button value=\"$player->RowID\">DELETE</button></td>";
			$html .= "</tr>";
		}

		return $html;
	}
}
register_activation_hook(__FILE__, array('WRM', 'Install'));
register_deactivation_hook(__FILE__, array('WRM', 'Uninstall'));
add_action('wp_ajax_wrm_addplayer', array('WRM', 'AddPlayer'));
add_action('wp_ajax_wrm_rmplayer', array('WRM', 'RmPlayer'));
add_action('wp_ajax_wrm_rmattnd', array('WRM', 'RmAttnd'));
add_action('wp_ajax_wrm_addgrpatt', array('WRM', 'AddGroupAttendance'));
add_action('plugins_loaded', array('PageTemplater', 'get_instance')); ?>