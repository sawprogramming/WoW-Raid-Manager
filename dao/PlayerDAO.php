<?php
include_once plugin_dir_path(__FILE__)."../entities/PlayerEntity.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Player/Add.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Player/Delete.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Player/Get.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Player/GetAll.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Player/Update.php";

class PlayerDAO  {   
    public function Add(PlayerEntity $entity) {
        return Player\Add::Run($entity);
    }

    public function Get($id) {
        return Player\Get::Run($id);   
    }

    public function GetAll() {
        return Player\GetAll::Run();
    }

    public function Delete($id) {
        return Player\Delete::Run($id);
    }

    public function Update(PlayerEntity $entity) {
        return Player\Update::Run($entity);
    }
}
