<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../database/procedures/Class/GetAll.php");
use WRO\Database\Procedures\HeroClass as Procedures;

class ClassDAO  {   
    public function GetAll() {
        return Procedures\GetAll::Run();
    }
};