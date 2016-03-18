<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."../dao/ExpansionDAO.php");
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class ExpansionService {
    // Initializes the member dao to a new ExpansionDAO
    public function __construct() {
        $this->dao_ = new DAO\ExpansionDAO();
    }

    // Returns all of the ExpansionEntity objects from the database.
    public function GetAll() {
    	return $this->dao_->GetAll();
    }

    public function Add(Entities\ExpansionEntity $entity) {
        return $this->dao_->Add($entity);
    }

    public function Update(Entities\ExpansionEntity $entity) {
        return $this->dao_->Update($entity);
    }

    private $dao_;
};