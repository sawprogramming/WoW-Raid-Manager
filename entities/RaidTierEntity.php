<?php
namespace WRO\Entities;

class RaidTierEntity {
	public function __construct($id = NULL, $expansionID = NULL, $name = NULL, $startDate = NULL, $endDate = NULL) {
		$this->ID          = $id;
		$this->ExpansionID = $expansionID;
		$this->Name        = $name;
		$this->StartDate   = $startDate;
		$this->EndDate     = $endDate;
	}

	public $ID;
	public $ExpansionID;
	public $Name;
	public $StartDate;
	public $EndDate;
};