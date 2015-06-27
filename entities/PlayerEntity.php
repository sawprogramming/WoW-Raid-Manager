<?php
class PlayerEntity {
	public function __construct($id = NULL, $userId = NULL, $classId = NULL, $name = NULL, $icon = NULL) {
		$this->ID = $id;
		$this->UserID = $userId;
		$this->ClassID = $classId;
		$this->Name = $name;
		$this->Icon = $icon;
	}

	public $ID;
	public $UserID;
	public $ClassID;
	public $Name;
	public $Icon;
}