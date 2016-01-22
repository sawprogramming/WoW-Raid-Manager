<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."DisputeService.php");
require_once(plugin_dir_path(__FILE__)."../dao/AttendanceDAO.php");
require_once(plugin_dir_path(__FILE__)."../entities/AttendanceEntity.php");
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class AttendanceService {   
    // Initializes the member dao to a new AttendanceDAO
    public function __construct() {
        $this->dao_ = new DAO\AttendanceDAO();
    }
    
    // Returns all of the AttendanceEntity objects from the database.
    public function GetAll() {
        return $this->dao_->GetAll();
    }

    // Parameters:
    //   unsigned long id - ID of the Player to retrieve the BreakdownEntity for.
    //
    // Returns: 
    //   Object[]
    //     TwoWeek  - Attendance percentage for the previous two weeks for this Player.
    //     Monthly  - Attendance percentage for the previous thirty days for this Player.
    //     AllTime  - Attendance percentage for all Attendance records for this Player.
    //     Tier     - Attendance percentage for the most recent tier for this Player.
    //     PlayerID - The Player these statistics are for.
    public function GetBreakdown($id) {
        return $this->dao_->GetBreakdown($id);
    }

    // Parameters:
    //   unsigned long id - ID of the Attendance record to retrieve.
    //
    // Returns the corresponding AttendanceEntity object from the database with the passed in ID. 
    public function Get($id) {
        return $this->dao_->Get($id);
    }

    // Parameters:
    //   unsigned long id - PlayerID to retrieve the Attendance records for.
    //
    // Returns all of the AttendanceEntity objects from the database belonging to the passed in PlayerID.
    public function GetAllById($id) {
        return $this->dao_->GetAllById($id);
    }

    // Parameters:
    //   Date startDate - Starting date of the range to retreive the average attendance percentage for.
    //   Date endDate   - Ending date of the range to retrieve the average attendance percentage for.
    //
    // Returns the average attendance percentage for each Player that was active during the supplied range.
    public function GetAveragesInRange($startDate, $endDate) {
        return $this->dao_->GetAveragesInRange($startDate, $endDate);
    }


    // Adds a new Attendance record to the database with the values from the passed in AttendanceEntity object.
    // The ID member is set by the function; the value passed in will not be used.
    //
    // Parameters:
    //   AttendanceEntity (uses the following fields)
    //     Date     - Value for the Date field.
    //     Points   - Value for the Points field.
    //     PlayerID - Value for the PlayerID field.
    //
    // Returns the ID of the added Attendance record if successful.
    public function Add(Entities\AttendanceEntity $entity) {
        return $this->dao_->Add($entity);
    }

    // Adds new Attendance records to the database with the values from the passed in AttendanceEntity objects.
    // The ID members are set by the function; the values passed in will not be used.
    // 
    // Parameters:
    //   AttendanceEntity[] (uses the following fields)
    //     Date     - Value for the Date field.
    //     Points   - Value for the Points field.
    //     PlayerID - Value for the PlayerID field.
    //
    // Returns nothing (void)
    public function AddGroupAttnd(array $entities) {
        foreach($entities as $entity) {
            $this->dao_->Add($entity);
        }
    }

    // Deletes the Attendance records that belong to the PlayerID passed in.
    // Consequently deletes that Player's Dispute records.
    //
    // Parameters:
    //   unsigned long id - PlayerID whose records are to be deleted.
    //
    // Returns ?
    public function DeletePlayer($id) {
        $disputeSvc = new DisputeService();

        $disputeSvc->DeletePlayer($id);

        return $this->dao_->DeletePlayer($id);
    }
 
    // Parameters:
    //   unsigned long id - The PlayerID to retrieve the graph information for.
    // 
    // Returns :
    //   Object[]
    //     Date          - Date for which the following fields are valid
    //     Points        - Points awarded to the Player on this Date
    //     PlayerId      - Player the record belongs to
    //     RaidAverage   - Raid's average attendance up to this Date
    //     PlayerAverage - Player's average attendance up to this Date
    public function GetChart($id) {
        return $this->dao_->GetChart($id);
    }

    // Updates the Attendance record in the database corresponding to the passed in AttendanceEntity object.
    //
    // Parameters:
    //   AttendanceEntity (uses the following fields)
    //     Date     - New value for the Date field.
    //     Points   - New value for the Points field.
    //     PlayerID - New value for the PlayerID field.
    // 
    // Returns ?
    public function Update(Entities\AttendanceEntity $entity) {
        return $this->dao_->Update($entity);
    }

    // Deletes the Attendance record corresponding to the ID passed in.
    // Consequently deletes any Dispute records that were for this Attendance record.
    // 
    // Parameters:
    //   unsigned long id - Attendance record to delete.
    //
    // Returns ?
    public function Delete($id) {
        $disputeSvc = new DisputeService();

        $disputeSvc->Delete($id);

        return $this->dao_->DeleteRow($id);
    }

    // Updates the points for an Attendance record in the database.
    //
    // Parameters:
    //   AttendanceEntity (uses the following fields)
    //     ID     - ID of the Attendance record to update.
    //     Points - New value for the Points field.
    //   
    // Returns ?
    public function UpdatePoints(Entities\AttendanceEntity $entity) {
        return $this->dao_->UpdatePoints($entity);
    }

    private $dao_;
};