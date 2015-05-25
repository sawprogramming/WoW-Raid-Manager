<?php
include_once plugin_dir_path(__FILE__)."../entities/RaidEntity.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Raid/Add.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Raid/Delete.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Raid/GetAll.php";

class RaidDAO {
    public function Add(RaidEntity $entity) {
        return Raid\Add::Run($entity);
    }

    public function GetAll() {      
        return Raid\GetAll::Run();
    }

    public function Delete($id) {
        return Raid\Delete::Run($id);
    }
}
