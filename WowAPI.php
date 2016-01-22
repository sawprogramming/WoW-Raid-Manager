<?php
namespace WRO;
use Exception;
use WRO\Services as Services;

class WowApi {
    function __construct() {
        $optionService = new Services\OptionService();

        $this->region = $optionService->Get("wro_region");
        $this->realm  = $optionService->Get("wro_default_realm");
    }
    
    // public functions
    public function GetCharIcon($name) {
        $charUrl = self::BuildCharUrl($name);

        if(($json = @file_get_contents($charUrl)) === FALSE) {
            throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $this->realm  . ".");
        }
        $obj = json_decode($json);

        return $obj->thumbnail;
    }

    public function GetCharFeed($name) {
        $charUrl = self::BuildCharUrl($name);
        
        if(($json = @file_get_contents($charUrl."?fields=feed")) === FALSE) {
            throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $this->realm  . ".");
        }
        
        return json_decode($json);
    }
    
    public function GetRealmList($region) {
        $json = file_get_contents("http://" . $region . ".battle.net/api/wow/realm/status");
        return $json === FALSE ? NULL : json_decode($json);
    }

    // helper functions
    private function BuildItemUrl($itemId) { return "http://" . $this->region . ".battle.net/api/wow/item/" . $itemId; }
    private function BuildCharUrl($name)   { return "http://" . $this->region . ".battle.net/api/wow/character/" . $this->realm . "/" . $name; }
    
    // members
    private $region;
    private $realm;
};