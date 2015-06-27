<?php
include_once (plugin_dir_path(__FILE__)."../dao/UserDAO.php");

class UserService {
    public function __construct() {
        $this->dao = new UserDAO();
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    private $dao;
}