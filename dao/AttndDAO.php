<?php

require_once(plugin_dir_path( __FILE__ ) . "./DAO.php");

class AttndDAO extends DAO {
    function __construct() { 
        parent::__construct();
        $this->tableName = self::$dbPrefix."Attendance";
    }
    
    // special operations
    public function GetBreakdown() {
        global $wpdb;
		$brkdwn = array();

		// query the database
		$results = $wpdb->get_results(
			"SELECT DISTINCT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName
			 FROM player as pl JOIN heroclass as cl on pl.ClassID = cl.ID");

		// error checking
		if($results == [""]) return NULL;

		// return results
		foreach($results as $player) {
			array_push($brkdwn, ["Name" => $player->Name, "ClassID" => $player->ClassID, "ClassName" => $player->ClassName, 
				"I1" => self::GetOver(14, $player->ID), "I2" => self::GetOver(30, $player->ID),
				"I3" => self::GetTotal($player->ID)]);
		}
		return $brkdwn;
    }
    
    // table operations
    public function CreateTable() {
        global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        dbDelta("
            CREATE TABLE IF NOT EXISTS $this->tableName (
                ID  int(10) NOT NULL AUTO_INCREMENT,
			    PlayerID  smallint(5),
			    Date date NOT NULL,
			    Points  float(3, 2),
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (PlayerID) REFERENCES ".self::$dbPrefix."Player(ID)
            ) $charset_collate;");
    }
    public function DropTable() {
        self::ExecuteNonQuery("DROP TABLE IF EXISTS $this->tableName");
    }
    
    // CRUD
    public function Add($obj) {
        global $wpdb;
        return $this->ExecuteNonQuery($wpdb->prepare("
            INSERT INTO $this->tableName (PlayerID, Date, Points)
            VALUES (%d, %s, %f)", $obj->PlayerID, $obj->Date, $obj->Points));
    }
    public function Get($key) {
        return $this->ExecuteQuery($wpdb->prepare("
            SELECT *
            FROM $this->tableName
            WHERE ID = %d", $key));     
    }
    public function GetAll() {
        return $this->ExecuteQuery("
            SELECT at.ID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, at.Points, at.Date
			FROM player as pl 
                JOIN heroclass as cl ON pl.ClassID = cl.ID
				JOIN attendance as at on pl.ID = at.PlayerID");
    }
    public function Delete($key) {
        global $wpdb;
        return $this->ExecuteNonQuery($wpdb->prepare("
            DELETE FROM $this->tableName 
            WHERE ID = %d", $key));
    }
    public function Update($obj) {
        global $wpdb;
        return $this->ExecuteNonQuery($wpdb->prepare("
            UPDATE $this->tableName
            SET Date = %s, Points = %f
            WHERE ID = %d", $obj->Date, $obj->Points, $obj->ID));
    }
    
    // private
    private function GetOver($interval, $playerId) {
        global $wpdb;

		// quick error checking
		if($interval <= 0) return 0;

		// make the sql
		$sql = "SELECT SUM(att.Points) as Earned, Max.Total
	            FROM Player as pl 
	                JOIN Attendance as att ON pl.ID = att.PlayerID
	                JOIN (SELECT PlayerID, COUNT(Points) as Total
	                      FROM Attendance
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
    private function GetTotal($playerID) {
		global $wpdb;

		// make the sql
		$sql = "SELECT SUM(att.Points) as Earned, Max.Total
	            FROM Player as pl 
	                JOIN Attendance as att ON pl.ID = att.PlayerID
	                JOIN (SELECT PlayerID, COUNT(Points) as Total
	                      FROM Attendance
	                      WHERE PlayerID = %d
	                      GROUP BY PlayerID) as Max ON Max.PlayerID = att.PlayerID
				WHERE att.PlayerID = %d
				GROUP BY att.PlayerID";

		// run the sql
		$result = $wpdb->get_row($wpdb->prepare($sql, $playerID, $playerID));
		return $result->Total == 0 ? 0 : ceil(($result->Earned / $result->Total)*100);
	}
}