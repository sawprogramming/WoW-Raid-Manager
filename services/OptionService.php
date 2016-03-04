<?php
namespace WRO\Services;

class OptionService {
    public function __construct() {
        self::$validKeys_ = array_merge(self::$validKeys_, self::$realmKeys_);
        self::$validKeys_ = array_merge(self::$validKeys_, self::$lootKeys_);
    }

    // Returns the value for the key passed.
    public function Get($key) {
        if($this->IsValidKey($key)) return get_option($key);
        else                        return FALSE;
    }

    // Returns all of the Key,Value pairs from the database for this plugin.
    public function GetAll() {
        $pairs = [];

        foreach(self::$validKeys_ as $key) {
            $pairs[$key] = $this->Get($key);
        }

        $pairs["wro_loot_time"]   = (int)$pairs["wro_loot_time"];
        $pairs["wro_realm_time"]  = (int)$pairs["wro_realm_time"];
        $pairs["wro_drop_tables"] = (int)$pairs["wro_drop_tables"];
        return $pairs;
    }

    // Updates the Value for the Key.
    public function Update($pairs) {
        foreach($pairs as $pair) {
        	if($this->IsValidKey($pair->key)) {
                update_option($pair->key, $pair->value, '', 'yes');

                // update jobs if they changed
                if(in_array($pair->key, self::$realmKeys_)) {
                    wp_reschedule_event($this->Get("wro_realm_time"), $this->Get("wro_realm_frequency"), 'update_realm_list');
                } else if(in_array($pair->key, self::$lootKeys_)) {
                    wp_reschedule_event($this->Get("wro_loot_time"),  $this->Get("wro_loot_frequency"),  'update_guild_loot');
                }
            }
        }
    }

    // Returns whether or not the passed in key belongs to this plugin. 
    private function IsValidKey($key) {
        return in_array($key, self::$validKeys_);
    }

    private static $realmKeys_ = ["wro_realm_time", "wro_realm_frequency"];
    private static $lootKeys_  = ["wro_loot_time", "wro_loot_frequency"];
    private static $validKeys_ = ["wro_faction", "wro_region", "wro_default_realm", "wro_drop_tables"];
};