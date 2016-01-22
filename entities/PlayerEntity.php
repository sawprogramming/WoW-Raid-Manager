<?php
namespace WRO\Entities;

class PlayerEntity {
	public function __construct($id = NULL, $userId = NULL, $classId = NULL, $name = NULL, $icon = NULL, $active = NULL) {
		$this->ID = $id;
		$this->UserID = $userId;
		$this->ClassID = $classId;
		$this->Name = $name;
		$this->Icon = $icon;
		$this->Active = $active;
	}

	public $ID;
	public $UserID;
	public $ClassID;
	public $Name;
	public $Icon;
	public $Active;
}