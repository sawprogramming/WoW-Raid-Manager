<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../entities/ImportHistoryEntity.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/Add.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/Get.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/GetAll.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/Update.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/DeletePlayer.php");
use WRO\Entities                          as Entities;
use WRO\Database\Procedures\ImportHistory as Procedures;

class ImportHistoryDAO {
    public function Add(Entities\ImportHistoryEntity $entity) {
        return Procedures\Add::Run($entity);
    }

    public function Get($playerId) {
        return Procedures\Get::Run($playerId);
    }

    public function GetAll() {        
        return Procedures\GetAll::Run();
    }

    public function DeletePlayer($id) {
        return Procedures\DeletePlayer::Run($id);
    }

    public function Update(Entities\ImportHistoryEntity $entity) {
        return Procedures\Update::Run($entity);
    }
};