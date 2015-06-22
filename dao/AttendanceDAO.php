<?php
include_once plugin_dir_path(__FILE__)."../entities/AttendanceEntity.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/Add.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/DeletePlayer.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/DeleteRow.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/GetAll.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/GetAllById.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/GetChart.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/GetBreakdown.php";
include_once plugin_dir_path(__FILE__)."../database/procedures/Attendance/Update.php";

class AttendanceDAO {
	public function Add(AttendanceEntity $entity) {
		return Attendance\Add::Run($entity);
	}

	public function GetAll() {
		return Attendance\GetAll::Run();
	}

	public function GetAllById($id) {
		return Attendance\GetAllById::Run($id);
	}

	public function GetBreakdown() {
		return Attendance\GetBreakdown::Run();
	}

	public function GetChart($id) {
		return Attendance\GetChart::Run($id);
	}

	public function Update(AttendanceEntity $entity) {
		return Attendance\Update::Run($entity);
	}

	public function DeleteRow($id) {
		return Attendance\DeleteRow::Run($id);
	}

	public function DeletePlayer($id) {
		return Attendance\DeletePlayer::Run($id);
	}
}