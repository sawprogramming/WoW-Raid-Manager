<?php
include_once (plugin_dir_path(__FILE__)."../dao/PlayerDAO.php");
include_once (plugin_dir_path(__FILE__)."../dao/AttendanceDAO.php");
include_once (plugin_dir_path(__FILE__)."../dao/RaidLootDAO.php");
include_once (plugin_dir_path(__FILE__)."../entities/PlayerEntity.php");
include_once (plugin_dir_path(__FILE__)."../WowAPI.php");

class PlayerService {
    public function __construct() {
        $this->dao = new PlayerDAO();
    }

    public function Add(PlayerEntity $entity) {
        $wowApi = new WowAPI();

        $icon = $wowApi->GetCharIcon($entity->Name);
        $entity->Icon = $icon;
        
        return $this->dao->Add($entity);
    }

    public function Delete($id) {
        $raidLootDAO = new RaidLootDAO();
        $attendanceDAO = new AttendanceDAO();

        $raidLootDAO->DeletePlayer($id);
        $attendanceDAO->DeletePlayer($id);
        return $this->dao->Delete($id);
    }
    
    public function Get($id) {
        return $this->dao->Get($id);
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    public function RefreshPlayerIcons() {
        $wowApi = new WowAPI();
        $players = $this->dao->GetAll();
        $newPlayers = array();

        foreach($players as $player) {
            try {
                $icon = $wowApi->GetCharIcon($player->Name);
            } catch (Exception $e) {
                continue;
            }

            array_push($newPlayers, new PlayerEntity(
                $player->ID,
                $player->UserID,
                $player->ClassID,
                $player->Name,
                $icon
            ));
        }

        foreach($newPlayers as $entity) {
            $this->dao->Update($entity);
        }
    }

    public function Update(PlayerEntity $entity) {
        $wowApi = new WowAPI();

        $icon = $wowApi->GetCharIcon($entity->Name);
        $entity->Icon = $icon;
        
        return $this->dao->Update($entity);
    }

    private $dao;
}