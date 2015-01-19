<?php

require_once(plugin_dir_path( __FILE__ ) . "./DAO.php");

class HeroClassDAO extends DAO {
    function __construct() { 
        parent::__construct();
        $this->tableName = self::$dbPrefix."HeroClass";
    }
    
    // table operations
    public function CreateTable() {
        global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        dbDelta("
            CREATE TABLE IF NOT EXISTS $this->tableName (
			    ID  tinyint(2) NOT NULL AUTO_INCREMENT,
			    Name tinytext NOT NULL,
			    PRIMARY KEY  ID (ID)
            ) $charset_collate;");
    }
    public function DropTable() {
        self::ExecuteNonQuery("DROP TABLE IF EXISTS $this->tableName");
    }
    
    // CRUD
    public function Add($obj) {
        global $wpdb;
        return $this->ExecuteNonQuery($wpdb->prepare("
            INSERT INTO $this->tableName (Name)
            VALUES (%s)", $obj->Name));
    }
    public function Get($key) {
        global $wpdb;
        return $this->ExecuteQuery($wpdb->prepare("
            SELECT *
            FROM $this->tableName
            WHERE ID = %d", $key));     
    }
    public function GetAll() {       
        return $this->ExecuteQuery("SELECT * FROM $this->tableName");
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
            SET Name = %s
            WHERE ID = %d", $obj->Name, $obj->ID));
    }
}
