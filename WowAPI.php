<?php

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
    
    // helper functions
    private function BuildItemUrl($itemId) { return "http://$this->region.battle.net/api/wow/item/$itemId"; }
    private function BuildCharUrl($name) {   return "http://$this->region.battle.net/api/wow/character/$this->realm/$name"; }
    
    // members
    private $region;
    private $realm;
}
?>