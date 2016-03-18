<?php
namespace WRO;
use Exception;
use WRO\Services as Services;

class WowApi {
    const SUFFIX = "locale=en_US&apikey=d2vp8vre4d28eru2ex46kp7anqegtqry";

    function __construct() {
        $optionService = new Services\OptionService();

        $this->region = $optionService->Get("wro_region");
    }
    
    // public functions
    public function GetCharIcon($name, $realm) {
        $charUrl = self::BuildCharUrl($name, $realm);

        if(($json = @file_get_contents($charUrl . "?" . WowApi::SUFFIX)) === FALSE) {
            throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $realm  . ".");
        }
        $obj = json_decode($json);

        return $obj->thumbnail;
    }

    public function GetCharFeed($name, $realm) {
        $charUrl = self::BuildCharUrl($name, $realm);
        
        if(($json = @file_get_contents($charUrl . "?fields=feed&" . WowApi::SUFFIX)) === FALSE) {
            throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $realm  . ".");
        }
        
        return json_decode($json);
    }
    
    public function GetRealmList($region) {
        $json = file_get_contents("https://" . $region . ".api.battle.net/wow/realm/status?" . WowApi::SUFFIX);
        return $json === FALSE ? NULL : json_decode($json);
    }

    // helper functions
    private function BuildCharUrl($name, $realm) { return "https://" . $this->region . ".api.battle.net/wow/character/" . $realm . "/" . $name; }
    
    // members
    private $realm;
};