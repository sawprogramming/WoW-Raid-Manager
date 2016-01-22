<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."../dao/RealmDAO.php");
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class RealmService {
    // Initializes the member dao to a new RealmDAO
    public function __construct() {
        $this->dao_ = new DAO\RealmDAO();
    }

    // Returns all of the RealmEntity objects from the database for the passed in region.
    public function GetAll($region) {
        return $this->dao_->GetAll($region);
    }

    public function UpdateRealmList() {
    	$wowApi = new \WRO\WowApi();

    	// add US realms
    	$json = $wowApi->GetRealmList("us");
        $json = $json->realms;
    	foreach($json as $entity) {
    		$this->dao_->Add(new Entities\RealmEntity(
    			$entity->slug,
    			$entity->name,
    			"us"
    		));
    	}

    	// add EU realms
    	$json = $wowApi->GetRealmList("eu");
        $json = $json->realms;
    	foreach($json as $entity) {
    		$this->dao_->Add(new Entities\RealmEntity(
    			$entity->slug,
    			$entity->name,
    			"eu"
    		));
    	}
    }

    private $dao_;
};