<?php
class PlayerEntity {
	public function __construct($id = NULL, $classId = NULL, $name = NULL) {
		$this->ID = $id;
		$this->ClassID = $classId;
		$this->Name = $name;
	}

	public $ID;
	public $ClassID;
	public $Name;
}