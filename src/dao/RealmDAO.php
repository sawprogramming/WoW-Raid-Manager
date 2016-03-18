<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../entities/RealmEntity.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Realm/Add.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Realm/GetAll.php");
use WRO\Entities                  as Entities;
use WRO\Database\Procedures\Realm as Procedures;

class RealmDAO  {   
    public function GetAll($region) {
        return Procedures\GetAll::Run($region);
    }

    public function Add(Entities\RealmEntity $entity) {
    	return Procedures\Add::Run($entity);
    }
};