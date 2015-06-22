<?php
include_once plugin_dir_path(__FILE__)."../entities/ItemEntity.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Item/Add.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Item/Get.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Item/GetAll.php";

class ItemDAO {
    public function Add(ItemEntity $entity) {
        return Item\Add::Run($entity);
    }

    public function Get(ItemEntity $entity) {
        return Item\Get::Run($entity);
    }

    public function GetAll() {
        return Item\GetAll::Run();
    }
}
