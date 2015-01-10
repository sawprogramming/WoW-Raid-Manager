<?php
require_once(plugin_dir_path( __FILE__ ) . 'dao.php');
class WRM_Display {
	// User tables
	public function UserAttndTbl() {
		$html = "<table id=\"tblUserAttnd\" class=\"nowrap compact wrm\">"
		       ."<thead><tr><th>Name</th><th>Class</th><th>Last 2 Weeks</th><th>Last 30 Days</th><th>All Time</th></tr></thead>"
		       ."<tbody>";

	    $data = json_decode(WRM_DAO::GetUserAttndBrkdwn());
	    foreach($data as $player) {
	    	$html .= "<tr>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	    	        ."<td>$player->I1%</td>"
	    	        ."<td>$player->I2%</td>"
	    	        ."<td>$player->I3%</td>"
	    	        ."</tr>";
	    }

	    $html .= "</tbody>"
	            ."</table>";

		return $html;
	}
	public function UserLootTbl() {
		$html = "<table id=\"tblUserLoot\" class=\"nowrap compact wrm\">"
	           ."<thead><tr><th>Player</th><th>Item</th><th>Raid</th></tr></thead>"
	           ."<tbody>";

	    $data = json_decode(WRM_DAO::GetLoot());
	    foreach($data as $row) {
	    	$html .= "<tr>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->PlayerName</span></td>"
	    	        ."<td><a href=\"".WRM_Display::BuildLootUrl($row->Item)."\"></a></td>"
	    	        ."<td>$row->RaidName</td>"
	    	        ."</tr>";
	    }

	    $html .= "</tbody>"
	            ."</table>";

		return $html;
	}

	// Admin tables
	public function EditPlayerTbl() {
		$html = "<table id=\"tblEditPlayers\" class=\"nowrap compact wrm\">"
		       ."<thead><tr><th>ID</th><th>Name</th><th>Class</th><th>Options</th></tr></thead>"
		       ."<tbody>";

	    $data = json_decode(WRM_DAO::GetPlayers());
	    foreach($data as $player) {
	    	$html .= "<tr>"
	    	        ."<td>$player->ID</td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	    	        ."<td><button class=\"del\">DELETE</button></td>"
	                ."</tr>";
	    }

	    $html .= "</tbody>"
	            ."</table>";

		return $html;
	}
	public function EditLootTbl() {
		$html = "<table id=\"tblEditLoot\" class=\"nowrap compact wrm\">"
		       ."<thead><tr><th>Row</th><th>Name</th><th>Class</th><th>Item</th><th>Raid</th><th>Date</th><th>Options</th></tr></thead>"
		       ."<tbody>";

	    $data = json_decode(WRM_DAO::GetLoot());
	    foreach($data as $row) {
	    	$html .= "<tr>"
	    	        ."<td>$row->ID</td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->PlayerName</span></td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->ClassName</span></td>"
	    	        ."<td><a href=\"".WRM_Display::BuildLootUrl($row->Item)."\"></a></td>"
	    	        ."<td>$row->RaidName</td>"
	    	        ."<td>$row->Date</td>"
	    	        ."<td><button class=\"rmLoot\">DELETE</button></td>"
	    	        ."</tr>";
	    }

	    $html .= "</tbody>"
	            ."</table>";

		return $html;
	}
	public function RaidAttndTbl() {
		$html = "<table id=\"tblRaidAttendance\" class=\"nowrap compact wrm\">"
		       ."<thead><tr><th>Name</th><th>Class</th><th>Points<br /><div id=\"divNewAttBulk\"></div></th><th>Options</th></tr></thead>"
		       ."<tbody>";

	    $data = json_decode(WRM_DAO::GetPlayers());
	    foreach($data as $player) {
	    	$html .= "<tr>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
	    	        ."<td><div id=\"divNewAttSl".$player->ID."\"></div></td>"
	    	        ."<td><button value=\"$player->ID\" class=\"delNewAtt\">DELETE</button></td>"
	    	        ."</tr>";
	    }

	    $html .= "</tbody>"
	            ."</table>";

		return $html;
	}
	public function EditAttndTbl() {
		$html = "<table id=\"tblEditAttnd\" class=\"nowrap compact wrm\">"
		       ."<thead><tr><th>Row</th><th>Name</th><th>Class</th><th>Points</th><th>Date</th><th>Options</th></tr></thead>"
		       ."<tbody>";

	    $data = json_decode(WRM_DAO::GetAttnd());
	    foreach($data as $row) {
	    	$html .= "<tr>"
	    	        ."<td>$row->ID</td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->PlayerName</span></td>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->ClassName</span></td>"
	    	        ."<td><div id=\"divEditAttSl".$row->ID."\">$row->Points</div></td>"
	    	        ."<td>$row->Date</td>"
	    	        ."<td><button value=\"$row->ID\" class=\"rmEditAttnd\">DELETE</button>"
	    	        .    "<button class=\"editEditAttnd\">EDIT</button></td>"
	    	        ."</tr>";
	    }

	    $html .= "</tbody>"
	            ."</table>";

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
	public function BuildLootUrl($item) {
		return "http://www.wowhead.com/item=".$item;
	}
} ?>