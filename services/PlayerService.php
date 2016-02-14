<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."../WowAPI.php");
require_once(plugin_dir_path(__FILE__)."RaidLootService.php");
require_once(plugin_dir_path(__FILE__)."../dao/PlayerDAO.php");
require_once(plugin_dir_path(__FILE__)."AttendanceService.php");
require_once(plugin_dir_path(__FILE__)."OptionService.php");
require_once(plugin_dir_path(__FILE__)."ImportHistoryService.php");
require_once(plugin_dir_path(__FILE__)."../entities/PlayerEntity.php");
use Exception;
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class PlayerService {
    // Initializes the member dao to a new PlayerDAO
    public function __construct() { 
        $this->dao_ = new DAO\PlayerDAO();
    }

    // Parameters:
    //   unsigned long id - ID to retrieve Player record for.
    //
    // Returns the matching PlayerEntity object from the database with the passed in ID. 
    public function Get($id) {  
        return $this->dao_->Get($id); 
    }

    // Returns all of the PlayerEntity objects from the database.
    public function GetAll() {
        return $this->dao_->GetAll();
    }

    // Adds a new Player record to the database with the values from the passed in PlayerEntity object.
    // The ID and Icon members are set by the function; the values passed in will not be used.
    // 
    // Will throw an Exception if the Icon cannot be retrieved.
    //   Happens if the Name doesn't exist on the given realm or if Blizzard's server is down.
    // 
    // Parameters:
    //   PlayerEntity (uses the following fields)
    //     Name    - Value for the Name field.
    //     UserID  - Value for the UserID field (can be null).
    //     ClassID - Value for the ClassID field.
    //
    // Returns the ID of the added Player if successful.
    public function Add(Entities\PlayerEntity $entity) {
        $wowApi        = new \WRO\WowAPI();
        $optionService = new OptionService();

        $icon = $wowApi->GetCharIcon($entity->Name, $entity->Realm);
        $entity->Icon = $icon;
        $entity->Region = $optionService->Get("wro_region");
        
        return $this->dao_->Add($entity);
    }

    // Updates the Player record in the database corresponding to the passed in in PlayerEntity object.
    // The Icon member is set by the function; the value passed in will not be used.
    // 
    // Will throw an Exception if the Icon cannot be retrieved.
    //   Happens if the Name doesn't exist on the given realm or if Blizzard's server is down.
    //
    // Parameters:
    //   PlayerEntity (uses the following fields)
    //     ID      - ID of Player record to update.
    //     Name    - New Name for Player record.
    //     UserID  - New UserID for Player record (can be null).
    //     ClassID - New ClassID for Player record.
    // 
    // Returns the ID of the updated Player if successful.
    public function Update(Entities\PlayerEntity $entity) {
        $wowApi        = new \WRO\WowAPI();
        $optionService = new OptionService();

        $icon = $wowApi->GetCharIcon($entity->Name, $entity->Realm);
        $entity->Icon = $icon;
        $entity->Region = $optionService->Get("wro_region");
        
        return $this->dao_->Update($entity);
    }

    // Deletes the Player record from the database for the ID passed in.
    // Consequently deletes that Player's RaidLoot, Dispute, Attendance, ImportHistory records.
    //
    // Parameters:
    //   unsigned long id - Player record to delete.
    //
    // Returns success or failure?
    public function Delete($id) {
        $raidLootSvc      = new RaidLootService();
        $attendanceSvc    = new AttendanceService();
        $importHistorySvc = new ImportHistoryService();

        $raidLootSvc->DeletePlayer($id);
        $attendanceSvc->DeletePlayer($id);
        $importHistorySvc->DeletePlayer($id);
        
        return $this->dao_->Delete($id);
    }

    // Updates the Icon field for each Player in the database.
    //
    // Returns nothing (void)
    public function RefreshPlayerIcons() {
        $newPlayers = array();
        $players    = $this->GetAll();
        $wowApi     = new \WRO\WowAPI();

        foreach($players as $player) {
            array_push($newPlayers, new Entities\PlayerEntity(
                $player->ID,
                $player->UserID,
                $player->ClassID,
                $player->Realm,
                $player->Name
            ));
        }

        foreach($newPlayers as $entity) {
            $this->dao_->Update($entity);
        }
    }

    private $dao_;
};