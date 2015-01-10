<?php
require_once(plugin_dir_path( __FILE__ ) . 'dao.php');
class WRM_Display {
	public function UserAttndTbl() {
		$html = "<table id=\"tblUserAttnd\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Name</th><th>Class</th><th>Last 2 Weeks</th><th>Last 30 Days</th><th>All Time</th></tr></thead>"
	           ."<tbody>".WRM_Display::GetUserAttndRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function UserLootTbl() {
		$html = "<table id=\"tblUserLoot\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Player</th><th>Item</th><th>Raid</th></tr></thead>"
	           ."<tbody>".WRM_Display::GetUserLootRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function EditPlayerTbl() {
		$html = "<table id=\"tblEditPlayers\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>ID</th><th>Name</th><th>Class</th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM_Display::GetEditPlayerRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function EditLootTbl() {
		$html = "<table id=\"tblEditLoot\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Row</th><th>Name</th><th>Class</th><th>Item</th><th>Raid</th><th>Date</th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM_Display::GetEditLootRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function RaidAttndTbl() {
		$html = "<table id=\"tblRaidAttendance\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Name</th><th>Class</th><th>Points<br /><div id=\"divNewAttBulk\"></div></th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM_Display::GetRaidAttndRows()."</tbody>"
	           ."</table>";
		return $html;
	}
	public function EditAttndTbl() {
		$html = "<table id=\"tblEditAttnd\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Row</th><th>Name</th><th>Class</th><th>Points</th><th>Date</th><th>Options</th></tr></thead>"
	           ."<tbody>".WRM_Display::GetEditAttndRows()."</tbody>"
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
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td>".WRM_Display::GetAttendanceOver(14, $player->ID)."</td>"
	                ."<td>".WRM_Display::GetAttendanceOver(30, $player->ID)."</td>"
	                ."<td>".WRM_Display::GetAttendanceOver(-1, $player->ID)."</td>"
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
	                ."<td><span class=\"".WRM_Display::GetClassName($loot->ClassID)."\">$loot->Name</span></td>"
	                ."<td><a href=\"".WRM_Display::BuildLootURL($loot->ItemID, $loot->BonusOne, $loot->BonusTwo, $loot->BonusThree)."\"></a></td>"
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
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
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
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td><a href=\"".WRM_Display::BuildLootUrl($player->ItemID, $player->BonusOne, $player->BonusTwo, $player->BonusThree)."\"></a></td>"
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
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
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
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	                ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	                ."<td><div id=\"divEditAttSl".$player->RowID."\">$player->Points</div></td>"
	                ."<td>$player->Date</td>"
	                ."<td><button value=\"$player->RowID\" class=\"rmEditAttnd\">DELETE</button>"
	                .    "<button class=\"editEditAttnd\">EDIT</button></td>"
	                ."</tr>";
		}

		return $html;
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
} ?>