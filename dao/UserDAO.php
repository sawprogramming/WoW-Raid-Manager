<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../database/procedures/User/GetAll.php");
use WRO\Database\Procedures\User as Procedures;

class UserDAO  {   
    public function GetAll() {
        return Procedures\GetAll::Run();
    }
};