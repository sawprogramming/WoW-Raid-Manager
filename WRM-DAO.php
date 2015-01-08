<?php
class WRM_DAO {
	// Create functions
	public function AddClass($name) {
		global $wpdb;
		return WRM_DAO::Create($wpdb->prepare(
			"INSERT INTO WRM_Class (Name)
			 VALUES (%s)", $name));
	}
	public function AddRaid($name) {
		global $wpdb;
		return WRM_DAO::Create($wpdb->prepare(
			"INSERT INTO WRM_Raid (Name)
			 VALUES (%s)", $name));
	}
	public function AddPlayer($playerId, $classId) {
		global $wpdb;
		return WRM_DAO::Create($wpdb->prepare(
			"INSERT INTO WRM_Player (Name, ClassID)
			 VALUES (%s, %d)", $playerId, $classId));
	}
	public function AddLoot($playerId, $itemId, $bonusOne, $bonusTwo, $bonusThree, $raidId, $date){
		global $wpdb;
		return WRM_DAO::Create($wpdb->prepare(
			"INSERT INTO WRM_Loot (PlayerID, ItemID, RaidID, Date, BonusOne, BonusTwo, BonusThree)
			 VALUES (%d, %d, %d, %s, %d, %d, %d)", $playerId, $itemId, $raidId, $date, $bonusOne, $bonusTwo, $bonusThree));
	}
	public function AddAttnd($playerId, $points, $date) {
		global $wpdb;
		return WRM_DAO::Create($wpdb->prepare(
			"INSERT INTO WRM_Attendance (PlayerID, Points, Date)
			 VALUES (%d, %f, %s)", $playerId, $points, $date));
	}

	// Read functions
	public function GetUserAttndBrkdwn() {
		global $wpdb;
		$brkdwn = array();

		// query the database
		$results = $wpdb->get_results(
			"SELECT DISTINCT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName
			 FROM WRM_Player as pl JOIN WRM_Class as cl on pl.ClassID = cl.ID");

		// error checking
		if($results == [""]) return "No results were found.";

		// return results
		foreach($results as $player) {
			array_push($brkdwn, ["Name" => $player->Name, "ClassName" => $player->ClassName, 
				"I1" => WRM_DAO::GetAttndOver(14, $player->ID), "I2" => WRM_DAO::GetAttndOver(30, $player->ID),
				"I3" => WRM_DAO::GetPlayerAttnd($player->ID)]);
		}
		return json_encode($brkdwn);
	}
	public function GetClass() {
		return WRM_DAO::Read("SELECT * FROM WRM_Class");
	}
	public function GetRaids() {
		return WRM_DAO::Read("SELECT * FROM WRM_Raid");
	}
	public function GetPlayers() {
		return WRM_DAO::Read(
			"SELECT pl.ID, pl.Name, cl.Name as ClassName
			 FROM WRM_Player as pl JOIN WRM_Class as cl on pl.ClassID = cl.ID
			 ORDER BY pl.Name");
	}
	public function GetLoot() {
		return WRM_DAO::Read(
			"SELECT lt.ID as RowID, pl.Name, cl.Name as ClassName, lt.ItemID, lt.BonusOne, lt.BonusTwo, lt.BonusThree, rd.Name as RaidName, lt.Date
			FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
				JOIN WRM_Loot as lt on pl.ID = lt.PlayerID
				JOIN WRM_Raid as rd on rd.ID = lt.RaidID");
	}
	public function GetAttnd() {
		return WRM_DAO::Read(
			"SELECT at.ID as RowID, pl.Name, cl.Name as ClassName, at.Points, at.Date
			 FROM WRM_Player as pl JOIN WRM_Class as cl ON pl.ClassID = cl.ID
				JOIN WRM_Attendance as at on pl.ID = at.PlayerID");
	}

	// Update functions
	public function EditClass($id, $name) {
		global $wpdb;
		return WRM_DAO::Update($wpdb->prepare(
			"UPDATE WRM_Class
			 SET Name = %s,
			 WHERE ID = %d", $name, $id));
	}
	public function EditRaid($id, $name) {
		global $wpdb;
		return WRM_DAO::Update($wpdb->prepare(
			"UPDATE WRM_Raid
			 SET Name = %s,
			 WHERE ID = %d", $name, $id));
	}
	public function EditPlayer($playerId, $playerName, $classId) {
		global $wpdb;
		return WRM_DAO::Update($wpdb->prepare(
			"UPDATE WRM_Player
			 SET Name = %s, ClassID = %d
			 WHERE ID = %d", $playerName, $classId, $playerId));
	}
	public function EditLoot($rowId, $playerId, $itemId, $bonusOne, $bonusTwo, $bonusThree) {
		global $wpdb;
		return WRM_DAO::Update($wpdb->prepare(
			"UPDATE WRM_Loot
			 SET PlayerID = %d, ItemID = %d, BonusOne = %d, BonusTwo = %d, BonusThree = %d,
			 WHERE ID = %d", $playerId, $itemId, $bonusOne, $bonusTwo, $bonusThree, $rowId));
	}
	public function EditAttnd($rowId, $points, $date) {
		global $wpdb;
		return WRM_DAO::Update($wpdb->prepare(
			"UPDATE WRM_Attendance
			 SET Points = %f, Date = %s,
			 WHERE ID = %d", $points, $date, $rowId));
	}
	
	// Delete functions
	public function RmClass($classId) {
		global $wpdb;
		return WRM_DAO::Delete($wpdb->prepare(
			"DELETE FROM WRM_Class
			 WHERE ID = %d", $classId));
	}
	public function RmRaid($raidId) {
		global $wpdb;
		return WRM_DAO::Delete($wpdb->prepare(
			"DELETE FROM WRM_Raid
			 WHERE ID = %d", $raidId));
	}
	public function RmPlayer($playerId) {
		global $wpdb;
		return WRM_DAO::Delete($wpdb->prepare(
			"DELETE FROM WRM_Player
			 WHERE ID = %d", $playerId));
	}
	public function RmLoot($rowId) {
		global $wpdb;
		return WRM_DAO::Delete($wpdb->prepare(
			"DELETE FROM WRM_Loot
			 WHERE ID = %d", $rowId));
	}
	public function RmAttnd($rowId) {
		global $wpdb;
		return WRM_DAO::Delete($wpdb->prepare(
			"DELETE FROM WRM_Attendance
			 WHERE ID = %d", $rowId));
	}

	// Private functions
	private function Create($sql) {
		global $wpdb;

		// run the query
		$result = $wpdb->query($sql);

		// report errors
		return $result === false ? "ERROR: An error occurred while trying to insert the record into the database." : true;	
	}
	private function Read($sql) {
		global $wpdb;

		// query the database
		$results = $wpdb->get_results($sql);

		// error checking
		if($results == [""]) return "No results were found.";

		// return results
		return json_encode($results);
	}
	private function Update($sql) {
		global $wpdb;

		// run the query
		$result = $wpdb->query($sql);

		// report errors
		return $result === false ? "ERROR: An error occurred while trying to update the record in the database." : true;	
	}
	private function Delete($sql) {
		global $wpdb;

		// run the query
		$result = $wpdb->query($sql);

		// report errors
		return $result === false ? "ERROR: An error occurred while trying to remove the record from the database." : true;	
	}
	private function GetPlayerAttnd($playerID) {
		global $wpdb;

		// make the sql
		$sql = "SELECT SUM(att.Points) as Earned, Max.Total
	            FROM WRM_Player as pl 
	                JOIN WRM_Attendance as att ON pl.ID = att.PlayerID
	                JOIN (SELECT PlayerID, COUNT(Points) as Total
	                      FROM WRM_Attendance
	                      WHERE PlayerID = %d
	                      GROUP BY PlayerID) as Max ON Max.PlayerID = att.PlayerID
				WHERE att.PlayerID = %d
				GROUP BY att.PlayerID";

		// run the sql
		$result = $wpdb->get_row($wpdb->prepare($sql, $playerID, $playerID));
		return $result->Total == 0 ? 0 : ceil(($result->Earned / $result->Total)*100);
	}
	private function GetAttndOver($interval, $playerId) {
		global $wpdb;

		// quick error checking
		if($interval <= 0) return 0;

		// make the sql
		$sql = "SELECT SUM(att.Points) as Earned, Max.Total
	            FROM WRM_Player as pl 
	                JOIN WRM_Attendance as att ON pl.ID = att.PlayerID
	                JOIN (SELECT PlayerID, COUNT(Points) as Total
	                      FROM WRM_Attendance
	                      WHERE PlayerID = %d
							AND Date BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW()
						  GROUP BY PlayerID) as Max ON Max.PlayerID = att.PlayerID 
            	WHERE att.PlayerID = %d
					AND att.Date BETWEEN DATE_SUB(NOW(), INTERVAL %d DAY) AND NOW()
				GROUP BY att.PlayerID";

		// run the sql
		$result = $wpdb->get_row($wpdb->prepare($sql, $playerId, $interval, $playerId, $interval));
		return $result->Total == 0 ? 0 : ceil(($result->Earned / $result->Total)*100);
	}
} ?>