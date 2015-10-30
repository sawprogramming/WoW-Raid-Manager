<?php
namespace WRO\Services;
require_once(plugin_dir_path(__FILE__)."PlayerService.php");
require_once(plugin_dir_path(__FILE__)."../dao/RaidLootDAO.php");
require_once(plugin_dir_path(__FILE__)."ImportHistoryService.php");
use WRO\DAO      as DAO;
use WRO\Entities as Entities;

class RaidLootService {   
    // Initializes the member dao to a new RaidLootDAO
    public function __construct() {
        $this->dao_ = new DAO\RaidLootDAO();
    }

    // Returns all of the RaidLootEntity objects from the database.
    public function GetAll() {
        return $this->dao_->GetAll();
    }

    // Deletes the RaidLoot records that belong to the PlayerID passed in.
    //
    // Parameters:
    //   unsigned long id - PlayerID whose records are to be deleted.
    //
    // Returns ?
    public function DeletePlayer($playerId) {
        return $this->dao_->DeletePlayer($playerId);
    }

    // Deletes the Attendance record corresponding to the ID passed in.
    // 
    // Parameters:
    //   unsigned long id - RaidLoot record to delete.
    //
    // Returns ?
    public function DeleteRow($id) {
        return $this->dao_->DeleteRow($id);
    }

    // Adds a new RaidLoot record to the database with the values from the passed in RaidLootEntity object.
    // The ID member is set by the function; the value passed in will not be used.
    //
    // Parameters:
    //   RaidLootEntity (uses the following fields)
    //     Date     - Date on which loot was received.
    //     Item     - String of Blizzard's ItemID and BonusID(s) (e.g "124319&bonus=564:566")
    //     PlayerID - Player who received this loot.
    //
    // Returns ?
    public function Add(Entities\RaidLootEntity $entity) {
        return $this->dao_->Add($entity);
    }

    // Queries Blizzard's player feed for each Player in the Player table and adds relevent loot to the RaidLoot table.
    // Also updates ImportHistory records for each player to note when the last time we checked is (so we don't add items we've already added).
    // Relevant loot is being defined as:
    //   ItemID > most recent expansion's raid's lowest ItemID
    //   Item was obtained in the context of a raid (works for caches though!)
    //
    // Returns nothing (void)
    public function FetchLoot() {
        $wowApi    = new \WRO\WowApi();
        $playerSvc = new PlayerService();
        $importSvc = new ImportHistoryService();
        
        // fetch the news for each raider
        $raiders = $playerSvc->GetAll();
        foreach($raiders as $raider) {
            $data = $lastImported = NULL;
            
            // only process process data we haven't read
            $data = $wowApi->GetCharFeed($raider->Name);
            $lastImported = $importSvc->Get($raider->ID);
            if(!($lastImported == NULL) && $data->lastModified < $lastImported->LastImported) continue;

            // add relevant loot from feed to LootItem table
            foreach($data->feed as $event) {
                if(!($lastImported == NULL) && $event->timestamp < $lastImported->LastImported) break;

                // only add loot events from current expansion's raids
                if($event->type == "LOOT" && $event->itemId > 113598 && strpos($event->context, "raid") !== false) {
                    $entity = new Entities\RaidLootEntity(
                        0, 
                        $raider->ID, 
                        $event->itemId, 
                        date("Y-m-d", $event->timestamp / 1000)
                    );
                    
                    // add bonuses if they exist
                    if(!empty($event->bonusLists)) {
                        $entity->Item .= "&bonus=";
                        for($i = 0; $i < count($event->bonusLists); ++$i) {
                            if($i != 0)  $entity->Item .= ":";

                            $entity->Item .= $event->bonusLists[$i];
                        }
                    }

                    $this->Add($entity);
                }         
                
            }
            
            // record that we read this data
            if($lastImported === NULL) $importSvc->Add(new Entities\ImportHistoryEntity(0, $raider->ID, $data->lastModified));
            else                       $importSvc->Update(new Entities\ImportHistoryEntity(0, $raider->ID, $data->lastModified));
        }
    }

    private $dao_;
};