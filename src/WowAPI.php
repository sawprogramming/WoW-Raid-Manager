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
        $charUrl = $this->BuildCharUrl($name, $realm);

        // build the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $charUrl . "?" . $this->GetSuffix());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // run the request and make sure it ran successfully
        $response = curl_exec($ch);
        try {
            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $realm  . ".");
            }
        } finally { curl_close($ch); }

        return json_decode($response)->thumbnail;
    }

    public function GetCharFeed($name, $realm) {
        $charUrl = $this->BuildCharUrl($name, $realm);

        // build the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $charUrl . "?fields=feed&" . $this->GetSuffix());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        // run the request and make sure it ran successfully
        $response = curl_exec($ch);
        try {
            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                throw new Exception("Character '". $name . "' could not be found on " . $this->region . "-" . $realm  . ".");
            }
        } finally { curl_close($ch); }
        
        return json_decode($response);
    }
    
    public function GetRealmList($region) {
        // build the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            "https://" . $region . ".api.blizzard.com/wow/realm/status?" . $this->GetSuffix());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // run the request and make sure it ran successfully
        $response = curl_exec($ch);
        try {
            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                return NULL;
            }
        } finally { curl_close($ch); }
        
        return json_decode($response);
    }

    // helper functions ///////////////////////////////////////////////////////////////////////////////////////////////
    private function BuildCharUrl($name, $realm) {
        return "https://" . $this->region . ".api.blizzard.com/wow/character/" . $realm . "/" . urlencode($name); 
    }

    private function GetSuffix() {
        return "locale=en_US&access_token=" . $this->GetOAuthToken();
    }

    private function GetOAuthToken() {
        // build the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            "https://" . $this->region . ".battle.net/oauth/token?grant_type=client_credentials&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        // run the request and make sure it ran successfully
        $response = curl_exec($ch);
        try {
            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                throw new Exception("Unable to retrieve access token for API.");
            }
        } finally { curl_close($ch); }

        return json_decode($response)->access_token;
    }
};