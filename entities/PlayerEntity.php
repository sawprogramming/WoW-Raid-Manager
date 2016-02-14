<?php
namespace WRO\Entities;

class PlayerEntity {
	public function __construct($id = NULL, $userId = NULL, $classId = NULL, $realmId = NULL, $name = NULL, $icon = NULL, $active = NULL) {
		$this->ID      = $id;
		$this->UserID  = $userId;
		$this->ClassID = $classId;
		$this->RealmID = $realmId;
		$this->Name    = $name;
		$this->Icon    = $icon;
		$this->Active  = $active;
	}

	public $ID;
	public $UserID;
	public $ClassID;
	public $RealmID;
	public $Name;
	public $Icon;
	public $Active;
}