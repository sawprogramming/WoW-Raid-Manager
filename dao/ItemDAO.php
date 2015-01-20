<?php

require_once(plugin_dir_path( __FILE__ ) . "./DAO.php");

class ItemDAO extends DAO {
    function __construct() {
        parent::__construct();
        $this->tableName = self::$dbPrefix."Item";
    }
    
    // special operations
    public function GetItemLevel($id, $context) {
        global $wpdb;
        $result = NULL;
        
        $sql = "SELECT ItemLevel FROM $this->tableName WHERE ID = %d AND Context ";
        
        if($context == NULL) $result = $wpdb->get_row($wpdb->prepare($sql."IS NULL", $id));
        else                 $result = $wpdb->get_row($wpdb->prepare($sql."= %s", $id, $context));
        
        return $result == NULL ? NULL : $result->ItemLevel;
    }
    
    // table operations
    public function CreateTable() {
        global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
        
        $wpdb->show_errors();
        dbDelta("
            CREATE TABLE IF NOT EXISTS $this->tableName (
			    ID double NOT NULL,
                Context tinytext NULL,
			    ItemLevel smallint NOT NULL,
			    PRIMARY KEY  (ID,Context(255))
            ) $charset_collate;");
    }
    public function DropTable() {
        self::ExecuteNonQuery("DROP TABLE IF EXISTS $this->tableName");
    }
    
    // CRUD
    public function Add($obj) {
        global $wpdb;
        
        if($obj->Context == NULL) 
            return $this->ExecuteNonQuery($wpdb->prepare("
            INSERT INTO $this->tableName (ID, ItemLevel) VALUES (%d, %d)", $obj->ID, $obj->Level));
        else 
            return $this->ExecuteNonQuery($wpdb->prepare("
            INSERT INTO $this->tableName (ID, Context, ItemLevel) VALUES (%d, %s, %d)", $obj->ID, $obj->Context, $obj->Level));
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
            SET ID = %d, Context = %s, Level = %d
            WHERE ID = %d AND Context = %s", $obj->ID, $obj->Context, $obj->Level, $obj->ID, $obj->Context));
    }
}
