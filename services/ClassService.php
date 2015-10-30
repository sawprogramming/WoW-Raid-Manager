<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."../dao/ClassDAO.php");
use WRO\DAO as DAO;

class ClassService {
    // Initializes the member dao to a new ClassDAO
    public function __construct() {
        $this->dao_ = new DAO\ClassDAO();
    }

    // Returns all of the ClassEntity objects from the database.
    public function GetAll() {
        return $this->dao_->GetAll();
    }

    private $dao_;
};