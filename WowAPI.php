<?php

include_once (plugin_dir_path( __FILE__ )."./entities/ItemEntity.php");

class WowApi {
    function __construct() {
        $this->region = "us";
        $this->realm  = "Stormrage";
    }
    
    // public functions
    public function GetCharIcon($name) {
        $charUrl = self::BuildCharUrl($name);

        if(($json = @file_get_contents($charUrl)) === FALSE) {
            throw new Exception("Character '$name' could not be found on $this->region-$this->realm.");
        }
        $obj = json_decode($json);

        return $obj->thumbnail;
    }

    public function GetCharFeed($name) {
        $charUrl = self::BuildCharUrl($name);
        
        $json = file_get_contents($charUrl."?fields=feed");
        
        return $json === FALSE ? NULL : json_decode($json);
    }

    public function GetItemLevel($itemId) {
        $results = array();
        $itemUrl = self::BuildItemUrl($itemId);
        $json = $data = $subData = NULL;
        
        if(($json = file_get_contents($itemUrl)) === FALSE) return NULL;
        $data = json_decode($json);
        
        // skip non weapon or armor loot
        if(isset($data->itemClass) && $data->itemClass != 2 && $data->itemClass != 4) return NULL;
        if(isset($data->equippable) && $data->equippable == false) return NULL;
        
        if(isset($data->availableContexts) && $data->availableContexts != [""]) {
            // get ItemLevel for all contexts
            foreach($data->availableContexts as $context) {
                if(($json = file_get_contents($itemUrl."/$context")) === FALSE) continue;
                $subData = json_decode($json);
                
                array_push($results, new ItemEntity($itemId, $context, $subData->itemLevel)); 
            }
        } else array_push($results, new ItemEntity($itemId, NULL, $data->itemLevel));
        
        return $results;
    }
    
    // helper functions
    private function BuildItemUrl($itemId) { return "http://$this->region.battle.net/api/wow/item/$itemId"; }
    private function BuildCharUrl($name) {   return "http://$this->region.battle.net/api/wow/character/$this->realm/$name"; }
    
    // members
    private $region;
    private $realm;
}
?>