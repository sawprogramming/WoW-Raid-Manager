<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."PlayerService.php");
require_once(plugin_dir_path(__FILE__)."../dao/UserDAO.php");
require_once(plugin_dir_path(__FILE__)."../dao/DisputeDAO.php");
require_once(plugin_dir_path(__FILE__)."AttendanceService.php");
require_once(plugin_dir_path(__FILE__)."../entities/DisputeEntity.php");
require_once(plugin_dir_path(__FILE__)."../entities/AttendanceEntity.php");
use Exception;
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class DisputeService {   
    // Initializes the member dao to a new DisputeDAO
    public function __construct() {
        $this->dao_ = new DAO\DisputeDAO();
    }
    
    // Returns all of the DisputeEntity objects from the database.
    public function GetAll() {
        return $this->dao_->GetAll();
    }

    // Returns all of the DisputeEntity objects that have a non-null Verdict from the database.
    public function GetResolved() {
        return $this->dao_->GetResolved();
    }

    // Returns all of the DisputeEntity objects that have a null Verdict from the database.
    public function GetUnresolved() {
        return $this->dao_->GetUnresolved();
    }

    // Deletes any Dispute records from the database which have the same AttendanceID value as the one passed in.
    //
    // Parameters:
    //   unsigned long id - AttendanceID to delete matching Dispute records for
    //
    // Returns success or failure?
    public function Delete($id) {
        return $this->dao_->Delete($id);
    }

    // Deletes any Dispute records from the database which belong to Attendance records belonging to the passed in PlayerID.
    //
    // Parameters:
    //   unsigned long id - PlayerID to delete matching Dispute records for
    //
    // Returns success or failure?
    public function DeletePlayer($id) {
        return $this->dao_->DeletePlayer($id);
    }

    // Checks to see if the Attendance record matching the passed in DisputeEntity belongs to the current user.
    // 
    // Parameters:
    //   DisputeEntity (uses the following fiels)
    //     AttendanceID - ID of Attendance record to check ownership of. 
    //
    // Returns true if the AttendanceID matches a record that belongs to the current user.
    public function Authorized(Entities\DisputeEntity $entity) {
        $playerSvc     = new PlayerService();
        $attendanceSvc = new AttendanceService();

        $players      = $playerSvc->GetAll();
        $current_user = wp_get_current_user();
        $record       = $attendanceSvc->Get($entity->AttendanceID);

        // don't allow anonymous users to dispute records for players with no UserID
        if($current_user->ID == 0) return false;

        // make sure the disputed attendance record is for this user
        for($i = 0; $i < count($players); ++$i) {
            if($players[$i]->ID == $record->PlayerID) {
                if($players[$i]->UserID == $current_user->ID) {
                    return true;
                }
                break;
            }
        }

        return false;
    }

    // Attempts to add the passed in DisputeEntity to the Dispute table.
    // The ID member is set by the function; the value passed in will not be used.
    // 
    // Will throw an Exception if there is already a pending dispute for the AttendanceID passed in.
    //
    // Parameters:
    //   DisputeEntity (uses the following fields)
    //     Points       - Points the user wants.
    //     Comment      - Comment on why the user is making a Dispute.
    //     AttendanceID - ID of Attendance record being disputed.
    //
    // Returns ?
    public function Add(Entities\DisputeEntity $entity) {
        $attendanceSvc = new AttendanceService();
        $disputes      = $this->dao_->GetUnresolved();

        // if the dispute is for the same amount of points, ignore it
        if($attendanceSvc->Get($entity->AttendanceID)->Points == $entity->Points) {
            throw new Exception("You were already awarded that many points!");   
        }

        // if there is a pending dispute, don't let another one be created
        for($i = 0; $i < count($disputes); ++$i) {
            if($disputes[$i]->AttendanceID == $entity->AttendanceID) {
                throw new Exception("This record already has a pending dispute!");
            }
        }

        return $this->dao_->Add($entity);
    }

    // Approves the Dispute record corresponding to the passed in entity, marking the Verdict as True and changing
    // the Points for the corresponding Attendance record to that of the Dispute.
    //
    // Parameters:
    //   DisputeEntity (uses the following fields)
    //     ID           - Dispute to approve.
    //     Points       - New Points value for the Attendance record.
    //     AttendanceID - Attendance record to update Points for. 
    //     
    // Returns nothing (void)
    public function Approve(Entities\DisputeEntity $entity) {
        $attendanceSvc = new AttendanceService();

        $entity->Verdict = true;
        $attendanceSvc->UpdatePoints(new Entities\AttendanceEntity(
            $entity->AttendanceID,
            NULL,
            NULL,
            $entity->Points
        ));

        $this->dao_->Update($entity);
    }

    // Rejects the Dispute record corresponding to the passed in entity, marking the Verdict as False.
    //
    // Parameters:
    //   DisputeEntity (uses the following fields)
    //     ID - Dispute to reject.
    //     
    // Returns nothing (void)
    public function Reject(Entities\DisputeEntity $entity) {
        $entity->Verdict = false;
        $this->dao_->Update($entity);
    }

    private $dao_;
};