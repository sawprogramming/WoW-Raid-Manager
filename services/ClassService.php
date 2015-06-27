<?php
include_once (plugin_dir_path(__FILE__)."../dao/ClassDAO.php");

class ClassService {
    public function __construct() {
        $this->dao = new ClassDAO();
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    private $dao;
}