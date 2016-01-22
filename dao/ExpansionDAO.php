<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../entities/ExpansionEntity.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Expansion/Add.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Expansion/GetAll.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Expansion/Update.php");
use WRO\Entities                      as Entities;
use WRO\Database\Procedures\Expansion as Procedures;

class ExpansionDAO {
	public function Add(Entities\ExpansionEntity $entity) {
		return Procedures\Add::Run($entity);
	}

	public function GetAll() {
		return Procedures\GetAll::Run();
	}

	public function Update(Entities\ExpansionEntity $entity) {
		return Procedures\Update::Run($entity);
	}
};