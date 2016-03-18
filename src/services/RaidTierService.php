<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."../dao/RaidTierDAO.php");
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class RaidTierService {
    // Initializes the member dao to a new RaidTierDAO
    public function __construct() {
        $this->dao_ = new DAO\RaidTierDAO();
    }

    // Returns all of the RaidTierEntity objects from the database.
    public function GetAll() {
    	return $this->dao_->GetAll();
    }

    public function Add(Entities\RaidTierEntity $entity) {
        return $this->dao_->Add($entity);
    }

    public function Update(Entities\RaidTierEntity $entity) {
        return $this->dao_->Update($entity);
    }

    private $dao_;
};