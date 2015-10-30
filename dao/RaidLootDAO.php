<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../entities/RaidLootEntity.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/Add.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/GetAll.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/DeleteRow.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/DeletePlayer.php");
use WRO\Entities                     as Entities;
use WRO\Database\Procedures\RaidLoot as Procedures;

class RaidLootDAO {
	public function Add(Entities\RaidLootEntity $entity) {
		return Procedures\Add::Run($entity);
	}

	public function DeletePlayer($id) {
		return Procedures\DeletePlayer::Run($id);
	}
	
	public function DeleteRow($id) {
		return Procedures\DeleteRow::Run($id);
	}

	public function GetAll() {
		return Procedures\GetAll::Run();
	}
};