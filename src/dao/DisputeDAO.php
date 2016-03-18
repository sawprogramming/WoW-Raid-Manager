<?php
namespace WRO\DAO;
require_once(plugin_dir_path(__FILE__)."../entities/DisputeEntity.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Dispute/Add.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Dispute/Delete.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Dispute/Update.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Dispute/GetAll.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Dispute/GetResolved.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Dispute/DeletePlayer.php");
require_once(plugin_dir_path(__FILE__)."../database/procedures/Dispute/GetUnresolved.php");
use WRO\Entities                    as Entities;
use WRO\Database\Procedures\Dispute as Procedures;

class DisputeDAO {
	public function Add(Entities\DisputeEntity $entity) {
		return Procedures\Add::Run($entity);
	}

	public function Delete($id) {
		return Procedures\Delete::Run($id);
	}

	public function DeletePlayer($id) {
		return Procedures\DeletePlayer::Run($id);
	}

	public function GetAll() {
		return Procedures\GetAll::Run();
	}

	public function GetResolved() {
		return Procedures\GetResolved::Run();
	}

	public function GetUnresolved() {
		return Procedures\GetUnresolved::Run();
	}

	public function Update(Entities\DisputeEntity $entity) {
		return Procedures\Update::Run($entity);
	}
};