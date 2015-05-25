<?php
include_once plugin_dir_path(__FILE__)."../entities/RaidLootEntity.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/Add.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/DeletePlayer.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/DeleteRow.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/RaidLoot/GetAll.php";


class RaidLootDAO {
	public function Add(RaidLootEntity $entity) {
		return RaidLoot\Add::Run($entity);
	}

	public function DeletePlayer($id) {
		return RaidLoot\DeletePlayer::Run($id);
	}
	
	public function DeleteRow($id) {
		return RaidLoot\DeleteRow::Run($id);
	}

	public function GetAll() {
		return RaidLoot\GetAll::Run();
	}
}