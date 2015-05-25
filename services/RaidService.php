<?php
include_once (plugin_dir_path(__FILE__)."../dao/RaidDAO.php");
include_once (plugin_dir_path(__FILE__)."../entities/RaidEntity.php");

class RaidService {
    public function __construct() {
        $this->dao = new RaidDAO();
    }

    public function Add(RaidEntity $entity) {
        return $this->dao->Add($entity);
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    public function Delete($id) {
        return $this->dao->Delete($id);
    }

    private $dao;
}