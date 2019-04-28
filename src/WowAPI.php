<?php
namespace WRO;
use Exception;
use WRO\Services as Services;

class WowApi {    
    // members ////////////////////////////////////////////////////////////////////////////////////////////////////////
    private $realm;

    // constructor(s) /////////////////////////////////////////////////////////////////////////////////////////////////
    function __construct() {
        $optionService = new Services\OptionService();

        $this->clientId     = $optionService->Get("wro_blizzard_client_id");
        $this->clientSecret = $optionService->Get("wro_blizzard_client_secret");
        $this->region       = $optionService->Get("wro_region");
    }
    
    // methods ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function GetCharIcon($name, $realm) {
        $charUrl = self::BuildCharUrl($name, $realm);

        if(($json = @file_get_contents($charUrl . "?" . $this->GetSuffix())) === FALSE) {
            throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $realm  . ".");
        }
        $obj = json_decode($json);

        return $obj->thumbnail;
    }

    public function GetCharFeed($name, $realm) {
        $charUrl = self::BuildCharUrl($name, $realm);
        
        if(($json = @file_get_contents($charUrl . "?fields=feed&" . $this->GetSuffix())) === FALSE) {
            throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $realm  . ".");
        }
        
        return json_decode($json);
    }
    
    public function GetRealmList($region) {
        $json = file_get_contents("https://" . $region . ".api.blizzard.com/wow/realm/status?" . $this->GetSuffix());
        return $json === FALSE ? NULL : json_decode($json);
    }

    // helper functions ///////////////////////////////////////////////////////////////////////////////////////////////
    private function BuildCharUrl($name, $realm) {
        return "https://" . $this->region . ".api.blizzard.com/wow/character/" . $realm . "/" . $name; 
    }

    private function GetSuffix() {
        return "locale=en_US&access_token=" . $this->GetOAuthToken();
    }

    private function GetOAuthToken() {
        $tokenUrl = "https://" . $this->region . ".battle.net/oauth/token?grant_type=client_credentials&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret;
        
        if(($json = @file_get_contents($tokenUrl)) === FALSE) {
            throw new Exception("Unable to retrieve access token for API using URL '" . $tokenUrl . "'.");
        }
        $obj = json_decode($json);

        return $obj->access_token;
    }
};