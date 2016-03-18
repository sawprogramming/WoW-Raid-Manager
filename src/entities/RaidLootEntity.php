<?php
namespace WRO\Entities;

class RaidLootEntity {
	public function __construct($id = NULL, $playerId = NULL, $item = NULL, $date = NULL) {
		$this->ID = $id;
		$this->PlayerID = $playerId;
		$this->Item = $item;
		$this->Date = $date;
	}

	public $ID;
	public $PlayerID;
	public $Item;
	public $Date;
}