<?php
require_once(plugin_dir_path(__FILE__)."../dao/RaidLootDAO.php");
require_once(plugin_dir_path(__FILE__)."../dao/ItemDAO.php");
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
        $itemDAO     = new ItemDAO();
        $raidLootDAO = new RaidLootDAO();
        $playerSvc   = new PlayerService();
        $importDao   = new ImportHistoryDAO();
        
        // fetch the news for each raider
        $raiders = $playerSvc->GetAll();
        foreach($raiders as $raider) {
            $data = $ilvl = $lastImported = NULL;
            
            // only process process data we haven't read
            $data = $wowApi->GetCharFeed($raider->Name);
            $lastImported = $importDao->Get($raider->ID);
            if(!($lastImported == NULL) && $data->lastModified < $lastImported) continue;
            
            // add relevant loot from feed to LootItem table
            foreach($data->feed as $event) {
                if(!($lastImported == NULL) && $event->timestamp < $lastImported) break;
                if($event->type != "LOOT") continue;
                               
                // check first context if it has one
                if(isset($event->availableContexts) && $event->availableContexts != [""]) 
                    $ilvl = $itemDAO->Get(new ItemEntity($event->itemId, $event->availableContexts[0], 0))->Level;
                else
                    $ilvl = $itemDAO->Get(new ItemEntity($event->itemId, NULL, 0))->Level;
                
                // cache it if it already isn't
                if($ilvl == NULL) {
                    // add the item to our database since it wasn't in there (saves API calls)
                    $itemLevels = $wowApi->GetItemLevel($event->itemId);
                    if($itemLevels == NULL) continue;
                    foreach($itemLevels as $item) $itemDAO->Add($item);
                    
                    $ilvl = $itemLevels[0]->Level;
                }
                
                // skip non relevant loot
                if($ilvl < 655) continue;
                
                $raidLootDAO->Add(new RaidLootEntity(0, $raider->ID, $event->itemId, ($ilvl <= 655 ? 1 : 2), date("Y-m-d", $event->timestamp / 1000)));
            }
            
            // record that we read this data
            if($lastImported === NULL) $importDao->Add(new ImportHistoryEntity(0, $raider->ID, $data->lastModified));
            else                       $importDao->Update(new ImportHistoryEntity(0, $raider->ID, $data->lastModified));
        }
    }

    private $dao;
}