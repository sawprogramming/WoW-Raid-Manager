<?php
include_once plugin_dir_path(__FILE__)."../database/procedures/Class/GetAll.php";

class ClassDAO  {   
    public function GetAll() {
        return HeroClass\GetAll::Run();
    }
}
