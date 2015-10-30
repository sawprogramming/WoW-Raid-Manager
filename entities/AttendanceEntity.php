<?php
namespace WRO\Entities;

class AttendanceEntity {
	public function __construct($id = NULL, $playerId = NULL, $date = NULL, $points = NULL) {
		$this->ID = $id;
		$this->PlayerID = $playerId;
		$this->Date = $date;
		$this->Points = $points;
	}

	public $ID;
	public $PlayerID;
	public $Date;
	public $Points;
}