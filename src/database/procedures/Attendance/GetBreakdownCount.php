<?php
namespace WRO\Database\Procedures\Attendance;
require_once(plugin_dir_path(__FILE__)."../StoredProcedure.php");
require_once(plugin_dir_path(__FILE__)."../../tables/RaidTierTable.php");
require_once(plugin_dir_path(__FILE__)."../../tables/AttendanceTable.php");
use WRO\Database\Tables     as Tables;
use WRO\Database\Procedures as Procedures;

class GetBreakdownCount extends Procedures\StoredProcedure {	
	public static function Run($id) {
		global $wpdb;
		$classTable      = new Tables\ClassTable();
		$playerTable     = new Tables\PlayerTable();
		$raidTierTable   = new Tables\RaidTierTable();
		$attendanceTable = new Tables\AttendanceTable();

		return $wpdb->get_row($wpdb->prepare("
			SELECT pl.ID, pl.Name, pl.Region, pl.ClassID, cl.Name as ClassName, pl.Icon, 
                IFNULL(twp.TwoWeekPresent, 0) as TwoWeekPresent, IFNULL(twa.TwoWeekAbsent, 0) as TwoWeekAbsent, IFNULL(twl.TwoWeekLate, 0) as TwoWeekLate, IFNULL(twt.TwoWeekTotal, 0) as TwoWeekTotal,
                IFNULL(mp.MonthPresent, 0) as MonthPresent,      IFNULL(ma.MonthAbsent, 0) as MonthAbsent,      IFNULL(ml.MonthLate, 0) as MonthLate,      IFNULL(mt.MonthTotal, 0) as MonthTotal,
                IFNULL(atp.AllTimePresent, 0) as AllTimePresent, IFNULL(ata.AllTimeAbsent, 0) as AllTimeAbsent, IFNULL(atl.AllTimeLate, 0) as AllTimeLate, att.AllTimeTotal,
                IFNULL(tip.TierPresent, 0) as TierPresent,       IFNULL(tia.TierAbsent, 0) as TierAbsent,       IFNULL(til.TierLate, 0) as TierLate,       IFNULL(tit.TierTotal, 0) as TierTotal
			FROM " . $playerTable->GetName() . " as pl
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as TwoWeekPresent
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW()
					  		AND PlayerID = %d
                            AND Points = 1.00
                      ) as twp ON pl.ID = twp.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(POINTS) as TwoWeekAbsent
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW()
					  		AND PlayerID = %d
                            AND Points = 0.00
                      ) as twa ON pl.ID = twa.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(POINTS) as TwoWeekLate
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW()
					  		AND PlayerID = %d
                            AND Points > 0.00 AND Points < 1.00
                      ) as twl ON pl.ID = twl.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(POINTS) as TwoWeekTotal
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW()
					  		AND PlayerID = %d) as twt ON pl.ID = twt.PlayerID



				LEFT JOIN (SELECT PlayerID, COUNT(Points) as MonthPresent
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
					  	    AND PlayerID = %d
                            AND Points = 1.00
                      ) as mp ON pl.ID = mp.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as MonthAbsent
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
					  	    AND PlayerID = %d
                            AND Points = 0.00
                       ) as ma ON pl.ID = ma.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as MonthLate
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
					  	    AND PlayerID = %d
                            AND Points > 0.00 AND Points < 1.00
                      ) as ml ON pl.ID = ml.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as MonthTotal
					  FROM " . $attendanceTable->GetName() . "
					  WHERE Date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
					  	    AND PlayerID = %d) as mt ON pl.ID = mt.PlayerID



				LEFT JOIN (SELECT PlayerID, COUNT(Points) as AllTimePresent
					  FROM " . $attendanceTable->GetName() . "
					  WHERE PlayerID = %d
                            AND Points = 1.00
                      ) as atp ON pl.ID = atp.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as AllTimeAbsent
					  FROM " . $attendanceTable->GetName() . "
					  WHERE PlayerID = %d
                            AND Points = 0.00
                      ) as ata ON pl.ID = ata.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as AllTimeLate
					  FROM " . $attendanceTable->GetName() . "
					  WHERE PlayerID = %d
                            AND Points > 0.00 AND Points < 1.00
                      ) as atl ON pl.ID = atl.PlayerID
				JOIN (SELECT PlayerID, COUNT(Points) as AllTimeTotal
					  FROM " . $attendanceTable->GetName() . "
					  WHERE PlayerID = %d) as att ON pl.ID = att.PlayerID




				LEFT JOIN (SELECT PlayerID, COUNT(Points) as TierPresent
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Date BETWEEN 
						  	(SELECT StartDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1) AND
						  	IFNULL((SELECT EndDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1), NOW())
						  	AND PlayerID = %d
                            AND Points = 1.00
                          ) as tip ON pl.ID = tip.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as TierAbsent
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Date BETWEEN 
						  	(SELECT StartDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1) AND
						  	IFNULL((SELECT EndDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1), NOW())
						  	AND PlayerID = %d
                            AND Points = 0.00
                          ) as tia ON pl.ID = tia.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as TierLate
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Date BETWEEN 
						  	(SELECT StartDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1) AND
						  	IFNULL((SELECT EndDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1), NOW())
						  	AND PlayerID = %d
                            AND Points > 0.00 AND Points < 1.00
                            ) as til ON pl.ID = til.PlayerID
				LEFT JOIN (SELECT PlayerID, COUNT(Points) as TierTotal
						  FROM " . $attendanceTable->GetName() . "
						  WHERE Date BETWEEN 
						  	(SELECT StartDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1) AND
						  	IFNULL((SELECT EndDate FROM " . $raidTierTable->GetName() . " ORDER BY ID DESC LIMIT 1), NOW())
						  	AND PlayerID = %d) as tit ON pl.ID = tit.PlayerID

				JOIN " . $classTable->GetName() . " as cl ON pl.ClassID = cl.ID
			WHERE pl.ID = %d;
		", $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id));
	}
};