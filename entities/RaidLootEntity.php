<?php
class RaidLootEntity {
	public function __construct($id = NULL, $playerId = NULL, $item = NULL, $raidId = NULL, $date = NULL) {
		$this->ID = $id;
		$this->PlayerID = $playerId;
		$this->Item = $item;
		$this->RaidID = $raidId;
		$this->Date = $date;
	}

	public $ID;
	public $PlayerID;
	public $Item;
	public $RaidID;
	public $Date;
}