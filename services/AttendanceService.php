<?php

require_once(plugin_dir_path(__FILE__)."../dao/AttendanceDAO.php");
require_once(plugin_dir_path(__FILE__)."../entities/AttendanceEntity.php");

class AttendanceService {   
    public function __construct() {
        $this->dao = new AttendanceDAO();
    }
    
    public function Add(AttendanceEntity $entity) {
        return $this->dao->Add($entity);
    }

    public function AddGroupAttnd(array $entities) {
        foreach($entities as $entity) {
            $this->dao->Add($entity);
        }
        return true;
    }

    public function Get($id) {
        return $this->dao->Get($id);
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    public function GetAllById($id) {
        return $this->dao->GetAllById($id);
    }

    public function GetBreakdown() {
        return $this->dao->GetBreakdown();
    }

    public function GetChart($id) {
        return $this->dao->GetChart($id);
    }

    public function Update(AttendanceEntity $entity) {
        return $this->dao->Update($entity);
    }

    public function Delete($id) {
        return $this->dao->DeleteRow($id);
    }

    private $dao;
}