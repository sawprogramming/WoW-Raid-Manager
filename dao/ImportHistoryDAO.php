<?php
include_once plugin_dir_path(__FILE__)."../entities/ImportHistoryEntity.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/Add.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/DeletePlayer.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/Get.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/GetAll.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/ImportHistory/Update.php";

class ImportHistoryDAO {
    public function Add(ImportHistoryEntity $entity) {
        return ImportHistory\Add::Run($entity);
    }

    public function Get($playerId) {
        return ImportHistory\Get::Run($playerId);
    }

    public function GetAll() {        
        return ImportHistory\GetAll::Run();
    }

    public function DeletePlayer($id) {
        return ImportHistory\DeletePlayer::Run($id);
    }

    public function Update(ImportHistoryEntity $entity) {
        return ImportHistory\Update::Run($entity);
    }
}
