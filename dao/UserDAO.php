<?php
include_once plugin_dir_path(__FILE__)."../database/procedures/User/GetAll.php";

class UserDAO  {   
    public function GetAll() {
        return User\GetAll::Run();
    }
}
