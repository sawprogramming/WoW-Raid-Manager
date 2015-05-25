<?php
include_once (plugin_dir_path(__FILE__)."../dao/ItemDAO.php");
include_once (plugin_dir_path(__FILE__)."../entities/ItemEntity.php");

class ItemService {
	public function __construct() {
		$this->dao = new ItemDAO();		
	}

	public function Add(ItemEntity $entity) {
		return $this->dao->Add($entity);
	}

	public function Get($id) {
		return $this->dao->Get($id);
	}

	public function GetAll() {
		return $this->dao->GetAll();
	}

	private $dao;
}