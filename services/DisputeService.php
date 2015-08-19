<?php

require_once(plugin_dir_path(__FILE__)."../dao/UserDAO.php");
require_once(plugin_dir_path(__FILE__)."../dao/PlayerDAO.php");
require_once(plugin_dir_path(__FILE__)."../dao/DisputeDAO.php");
require_once(plugin_dir_path(__FILE__)."../dao/AttendanceDAO.php");
require_once(plugin_dir_path(__FILE__)."../entities/DisputeEntity.php");
require_once(plugin_dir_path(__FILE__)."../entities/AttendanceEntity.php");

class DisputeService {   
    public function __construct() {
        $this->dao = new DisputeDAO();
        $this->playerDao = new PlayerDAO();
        $this->attendanceDao = new AttendanceDAO();
    }
    
    public function Authorized($entity) {
        $players = $this->playerDao->GetAll();
        $current_user = wp_get_current_user();
        $record = $this->attendanceDao->Get($entity->AttendanceID);

        // make sure the disputed attendance record is for this user
        for($i = 0; $i < count($players); ++$i) {
            if($players[$i]->ID == $record->PlayerID) {
                if($players[$i]->UserID == $current_user->ID) {
                    return true;
                }
                break;
            }
        }

        return false;
    }

    public function Approve(DisputeEntity $entity) {
        $entity->Verdict = true;

        $this->attendanceDao->UpdatePoints(new AttendanceEntity(
            $entity->AttendanceID,
            NULL,
            NULL,
            $entity->Points
        ));

        $this->dao->Update($entity);
    }

    public function Add(DisputeEntity $entity) {
        return $this->dao->Add($entity);
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    public function GetResolved() {
        return $this->dao->GetResolved();
    }

    public function GetUnresolved() {
        return $this->dao->GetUnresolved();
    }

    public function Reject(DisputeEntity $entity) {
        $entity->Verdict = false;
        $this->dao->Update($entity);
    }

    private $dao;
    private $playerDao;
    private $attendanceDao;
}