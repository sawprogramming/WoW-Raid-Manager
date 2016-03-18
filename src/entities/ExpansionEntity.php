<?php
namespace WRO\Entities;

class ExpansionEntity {
	public function __construct($id = NULL, $name = NULL, $startDate = NULL, $endDate = NULL) {
		$this->ID          = $id;
		$this->Name        = $name;
		$this->StartDate   = $startDate;
		$this->EndDate     = $endDate;
	}

	public $ID;
	public $Name;
	public $StartDate;
	public $EndDate;
};