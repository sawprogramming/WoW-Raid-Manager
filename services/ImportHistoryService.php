<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."../dao/ImportHistoryDAO.php");
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class ImportHistoryService {
    // Initializes the member dao to a new ImportHistoryDAO
    public function __construct() {
        $this->dao_ = new DAO\ImportHistoryDAO();
    }

    // Returns all of the AttendanceEntity objects from the database.
    public function GetAll() {
        return $this->dao_->GetAll();
    }

    // Parameters:
    //   unsigned long id - PlayerID to retrieve ImportHistory record for.
    //
    // Returns the matching ImportHistory object from the database with the passed in ID. 
    public function Get($id) {
        return $this->dao_->Get($id);
    }

    // Deletes the ImportHistory record for the passed in PlayerID.
    //
    // Parameters:
    //   unsigned long id - PlayerID whose record is to be deleted.
    //
    // Returns ?
    public function DeletePlayer($id) {
        return $this->dao_->DeletePlayer($id);
    }

    // Adds a new ImportHistory record to the database with the values from the passed in ImportHistoryEntity object.
    // The ID member is set by the function; the value passed in will not be used.
    //
    // Parameters:
    //   ImportHistoryEntity (uses the following fields)
    //     PlayerID     - PlayerID to create a ImportHistory record for.
    //     LastImported - Timestamp for the new import.
    //
    // Returns ?
    public function Add(Entities\ImportHistoryEntity $entity) {
    	return $this->dao_->Add($entity);
    }

    // Updates the ImportHistory record in the database corresponding to the passed in ImportHistoryEntity object.
    //
    // Parameters:
    //   ImportHistoryEntity (uses the following fields)
    //     PlayerID     - PlayerID to update the timestamp for.
    //     LastImported - Timestamp for most recent import
    // 
    // Returns ?
    public function Update(Entities\ImportHistoryEntity $entity) {
    	return $this->dao_->Update($entity);
    }

    private $dao_;
};