<?php
class DisputeEntity {
	public function __construct($id = NULL, $attendanceId = NULL, $points = NULL, $comment = NULL, $verdict = NULL) {
		$this->ID = $id;
		$this->AttendanceID = $attendanceId;
		$this->Points = $points;
		$this->Comment = $comment;
		$this->Verdict = $verdict;
	}

	public $ID;
	public $AttendanceID;
	public $Points;
	public $Comment;
	public $Verdict;
}