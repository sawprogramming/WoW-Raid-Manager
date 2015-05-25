<?php
require_once(plugin_dir_path(__FILE__)."../dao/RaidLootDAO.php");

class RaidLootService {   
    public function __construct() {
        $this->dao = new RaidLootDAO();
    }

    public function Add(RaidLootEntity $entity) {
        return $this->dao->Add($entity);
    }

    public function DeletePlayer($playerId) {
        return $this->dao->DeletePlayer($playerId);
    }

    public function DeleteRow($id) {
        return $this->dao->DeleteRow($id);
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    private $dao;
}