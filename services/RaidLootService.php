<?php
require_once(plugin_dir_path(__FILE__)."../dao/RaidLootDAO.php");
require_once(plugin_dir_path(__FILE__)."../dao/ImportHistoryDAO.php");
require_once(plugin_dir_path(__FILE__)."../services/PlayerService.php");



class RaidLootService {   
    public function __construct() {
        $this->dao = new RaidLootDAO();
    }

    public function Add(RaidLootEntity $entity) {
        return $this->dao->Add($entity);
    }

    public function DeletePlayer($playerId) {
        return $this->dao->DeletePlayer($playerId);
    }

    public function DeleteRow($id) {
        return $this->dao->DeleteRow($id);
    }

    public function GetAll() {
        return $this->dao->GetAll();
    }

    public function FetchLoot() {
        $wowApi      = new WowApi();
        $raidLootDAO = new RaidLootDAO();
        $playerSvc   = new PlayerService();
        $importDao   = new ImportHistoryDAO();
        
        // fetch the news for each raider
        $raiders = $playerSvc->GetAll();
        foreach($raiders as $raider) {
            $data = $lastImported = NULL;
            
            // only process process data we haven't read
            $data = $wowApi->GetCharFeed($raider->Name);
            $lastImported = $importDao->Get($raider->ID);
            if(!($lastImported == NULL) && $data->lastModified < $lastImported->LastImported) continue;

            // add relevant loot from feed to LootItem table
            foreach($data->feed as $event) {
                if(!($lastImported == NULL) && $event->timestamp < $lastImported->LastImported) break;

                // only add loot events from current expansion's raids
                if($event->type == "LOOT" && $event->itemId > 113598 && strpos($event->context, "raid") !== false) {
                    $entity = new RaidLootEntity(
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

                    $raidLootDAO->Add($entity);
                }         
                
            }
            
            // record that we read this data
            if($lastImported === NULL) $importDao->Add(new ImportHistoryEntity(0, $raider->ID, $data->lastModified));
            else                       $importDao->Update(new ImportHistoryEntity(0, $raider->ID, $data->lastModified));
        }
    }

    private $dao;
}