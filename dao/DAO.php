<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

abstract class DAO {
    function __construct() {
        if(self::$dbPrefix == NULL) {
            $xml = simplexml_load_file(plugin_dir_path( __FILE__ ) . "../Constants.xml");
            self::$dbPrefix = $xml->DbPrefix;
        }
    }
    
    // table operations
    public abstract function CreateTable();
    public abstract function DropTable();
    
    // CRUD
    public abstract function Add($obj);
    public abstract function Get($key);
    public abstract function GetAll();
    public abstract function Delete($key);
    public abstract function Update($obj);
    
    protected static function ExecuteQuery($sql) {
        global $wpdb;
        
        return $wpdb->get_results($sql);
    }
    protected static function ExecuteNonQuery($sql) {
        global $wpdb;
        
        $result = $wpdb->query($sql);
        
        if($result === FALSE) return NULL;
        return "Rows affected: $result.";       
    }
    
    // members
    protected static $dbPrefix;
    protected $tableName;
}
