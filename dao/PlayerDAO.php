<?php

require_once(plugin_dir_path( __FILE__ ) . "./DAO.php");

class PlayerDAO extends DAO {
    function __construct() { 
        parent::__construct();
        $this->tableName = self::$dbPrefix."Player";
    }
    
    // special operations
    public function GetId($name) {
        global $wpdb;
        return $this->ExecuteQuery($wpdb->prepare("
            SELECT ID 
            FROM $this->tableName
            WHERE Name = %s", $name));
    }
    
    // table operations
    public function CreateTable() {
        global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        dbDelta("
            CREATE TABLE IF NOT EXISTS $this->tableName (
			    ID  smallint(5) NOT NULL AUTO_INCREMENT,
			    ClassID  tinyint(3),
			    Name tinytext NOT NULL,
			    PRIMARY KEY  ID (ID),
			    FOREIGN KEY (ClassID) REFERENCES ".self::$dbPrefix."HeroClass(ID)
            ) $charset_collate;");
    }
    public function DropTable() {
        self::ExecuteNonQuery("DROP TABLE IF EXISTS $this->tableName");
    }
    
    // CRUD
    public function Add($obj) {
        global $wpdb;
        return $this->ExecuteNonQuery($wpdb->prepare("
            INSERT INTO $this->tableName (Name, ClassID)
            VALUES (%s, %d)", $obj->Name, $obj->ClassID));
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
            SELECT pl.ID, pl.Name, pl.ClassID, cl.Name as ClassName
            FROM $this->tableName as pl
                JOIN ".self::$dbPrefix."HeroClass as cl ON pl.ClassID = cl.ID");
    }
    public function Delete($key) {
        global $wpdb;
        
        // need to delete from a few tables because foreign key constraint
        $this->ExecuteNonQuery($wpdb->prepare("DELETE FROM ".self::$dbPrefix."LootItem WHERE PlayerID = %d", $key));
        $this->ExecuteNonQuery($wpdb->prepare("DELETE FROM ".self::$dbPrefix."LootImport WHERE PlayerID = %d", $key));
        $this->ExecuteNonQuery($wpdb->prepare("DELETE FROM ".self::$dbPrefix."Attendance WHERE PlayerID = %d", $key));
        return $this->ExecuteNonQuery($wpdb->prepare("DELETE FROM $this->tableName WHERE ID = %d", $key));
    }
    public function Update($obj) {
        global $wpdb;
        return $this->ExecuteNonQuery($wpdb->prepare("
            UPDATE $this->tableName
            SET Name = %s, ClassID = %d
            WHERE ID = %d", $obj->Name, $obj->ClassID, $obj->ID));
    }
}
