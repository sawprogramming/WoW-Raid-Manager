<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../entities/RaidTierEntity.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/RaidTier/Add.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/RaidTier/GetAll.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/RaidTier/Update.php");
use WRO\Entities                     as Entities;
use WRO\Database\Procedures\RaidTier as Procedures;

class RaidTierDAO {
	public function Add(Entities\RaidTierEntity $entity) {
		return Procedures\Add::Run($entity);
	}

	public function GetAll() {
		return Procedures\GetAll::Run();
	}

	public function Update(Entities\RaidTierEntity $entity) {
		return Procedures\Update::Run($entity);
	}
};