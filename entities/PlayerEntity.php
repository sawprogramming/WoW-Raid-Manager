<?php
class PlayerEntity {
	public function __construct($id = NULL, $classId = NULL, $name = NULL, $icon = NULL) {
		$this->ID = $id;
		$this->ClassID = $classId;
		$this->Name = $name;
		$this->Icon = $icon;
	}

	public $ID;
	public $ClassID;
	public $Name;
	public $Icon;
}