<?php
include_once plugin_dir_path(__FILE__)."../entities/DisputeEntity.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Dispute/Add.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Dispute/GetAll.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Dispute/GetResolved.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Dispute/GetUnresolved.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Dispute/Update.php";

class DisputeDAO {
	public function Add(DisputeEntity $entity) {
		return Dispute\Add::Run($entity);
	}

	public function GetAll() {
		return Dispute\GetAll::Run();
	}

	public function GetResolved() {
		return Dispute\GetResolved::Run();
	}

	public function GetUnresolved() {
		return Dispute\GetUnresolved::Run();
	}

	public function Update(DisputeEntity $entity) {
		return Dispute\Update::Run($entity);
	}
}