<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../entities/PlayerEntity.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Player/Add.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Player/Get.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Player/GetAll.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Player/Delete.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Player/Update.php");
use WRO\Entities                   as Entities;
use WRO\Database\Procedures\Player as Procedures;

class PlayerDAO  {   
    public function Add(Entities\PlayerEntity $entity) {
        return Procedures\Add::Run($entity);
    }

    public function Get($id) {
        return Procedures\Get::Run($id);   
    }

    public function GetAll() {
        return Procedures\GetAll::Run();
    }

    public function Delete($id) {
        return Procedures\Delete::Run($id);
    }

    public function Update(Entities\PlayerEntity $entity) {
        return Procedures\Update::Run($entity);
    }
};