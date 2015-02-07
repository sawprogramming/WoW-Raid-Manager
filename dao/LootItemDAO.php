<?php

require_once(plugin_dir_path( __FILE__ ) . "./DAO.php");

class LootItemDAO extends DAO {
    function __construct() { 
        parent::__construct();
        $this->tableName = self::$dbPrefix."LootItem";
    }
    
    // table operations
    public function CreateTable() {
        global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        dbDelta("
            CREATE TABLE IF NOT EXISTS $this->tableName (
			    ID  int(10) NOT NULL AUTO_INCREMENT,
			    PlayerID  smallint(5) NULL,
			    Item tinytext NOT NULL,
			    RaidID  tinyint(3) NULL,
			    Date date NOT NULL,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (PlayerID) REFERENCES ".self::$dbPrefix."Player(ID),
			    FOREIGN KEY (RaidID) REFERENCES ".self::$dbPrefix."Raid(ID)
            ) $charset_collate;");
    }
    public function DropTable() {
        self::ExecuteNonQuery("DROP TABLE IF EXISTS $this->tableName");
    }
    
    // CRUD
    public function Add($obj) {
        global $wpdb;
        return $this->ExecuteNonQuery($wpdb->prepare("
            INSERT INTO $this->tableName (PlayerID, Item, RaidID, Date)
            VALUES (%d, %s, %d, %s)", $obj->PlayerID, $obj->Item, $obj->RaidID, $obj->Date));
    }
    public function Get($key) {
        global $wpdb;
        return $this->ExecuteQuery($wpdb->prepare("
            SELECT *
            FROM $this->tableName
            WHERE ID = %d", $key));     
    }
    public function GetAll() {       
        return $this->ExecuteQuery("
            SELECT li.ID, li.PlayerID, pl.Name as PlayerName, pl.ClassID, cl.Name as ClassName, li.Item, li.RaidID, rd.Name as RaidName, li.Date
            FROM $this->tableName as li
                JOIN ".self::$dbPrefix."Player as pl ON li.PlayerID = pl.ID
                JOIN ".self::$dbPrefix."Raid as rd ON li.RaidID = rd.ID
                JOIN ".self::$dbPrefix."HeroClass as cl ON pl.ClassID = cl.ID");
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
            SET PlayerID = %d, Item = %s, RaidID = %d, Date = %s
            WHERE ID = %d", $obj->PlayerID, $obj->Item, $obj->RaidID, $obj->Date, $obj->ID));
    }
}
