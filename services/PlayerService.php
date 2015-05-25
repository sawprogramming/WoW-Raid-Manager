<?php
include_once (plugin_dir_path(__FILE__)."../dao/PlayerDAO.php");
include_once (plugin_dir_path(__FILE__)."../dao/AttendanceDAO.php");
include_once (plugin_dir_path(__FILE__)."../dao/RaidLootDAO.php");
include_once (plugin_dir_path(__FILE__)."../entities/PlayerEntity.php");

class PlayerService {
    public function __construct() {
        $this->dao = new PlayerDAO();
    }

    public function Add(PlayerEntity $entity) {
        return $this->dao->Add($entity);
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    public function Update(PlayerEntity $entity) {
        return $this->dao->Update($entity);
    }

    public function Delete($id) {
        $raidLootDAO = new RaidLootDAO();
        $attendanceDAO = new AttendanceDAO();

        $raidLootDAO->DeletePlayer($id);
        $attendanceDAO->DeletePlayer($id);
        return $this->dao->Delete($id);
    }

    private $dao;
}