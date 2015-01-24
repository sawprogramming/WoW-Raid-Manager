<?php
/**
 * Plugin Name: WoW Raid Organizer
 * Description: Modules for loot and attendance.
 * Version: 0.0.1
 * Author: Criminal-SR
 * License: GPL2
 */
require_once (plugin_dir_path(__FILE__).'libs/PageTemplater.php');
require_once (plugin_dir_path(__FILE__).'display.php');
require_once (plugin_dir_path(__FILE__)."./dao/DAOFactory.php");
require_once (plugin_dir_path(__FILE__)."./entities/Item.php");
require_once (plugin_dir_path(__FILE__)."./entities/LootItem.php");
require_once (plugin_dir_path(__FILE__)."./entities/LootImport.php");
require_once (plugin_dir_path(__FILE__)."./WowAPI.php");
require_once (plugin_dir_path(__FILE__)."./services/PlayerService.php");
require_once (plugin_dir_path(__FILE__)."./services/RaidService.php");
require_once (plugin_dir_path(__FILE__)."./services/AttndService.php");
require_once (plugin_dir_path(__FILE__)."./services/LootService.php");

class WRO {
    // Installation functions
    public function Install() {
        $factory = new DAOFactory();
        
        // create tables
        $factory->GetItemDAO()->CreateTable();
        $factory->GetHeroClassDAO()->CreateTable();
        $factory->GetRaidDAO()->CreateTable();
        $factory->GetPlayerDAO()->CreateTable();
        $factory->GetLootImportDAO()->CreateTable();
        $factory->GetLootItemDAO()->CreateTable();
        $factory->GetAttndDAO()->CreateTable();
        
        // seed tables
        //self::UpdateGuildLoot();
    }
    public function Uninstall() {
        $factory = new DAOFactory();
        
        if(false) {
            $factory->GetItemDAO()->DropTable();
            $factory->GetHeroClassDAO()->DropTable();
            $factory->GetRaidDAO()->DropTable();
            $factory->GetPlayerDAO()->DropTable();
            $factory->GetLootItemDAO()->DropTable();
            $factory->GetAttndDAO()->DropTable();
            $factory->GetLootImportDAO()->DropTable();
        }
    }	
    public function EnqueueScriptsStyles() {
        $template = get_page_template_slug(get_queried_object_id()); 
        
        if($template == "../attnd-template.php" || $template == "../loot-template.php") {
            $appUrl = plugins_url()."/WoWRaidManager";
            
            // add scripts
            wp_enqueue_script('blah',       "$appUrl/libs/js/jquery-2.1.3.min.js");
            wp_enqueue_script('datatables', "$appUrl/libs/js/jquery.dataTables.min.js");
            wp_enqueue_script('jqui',       "$appUrl/libs/js/jquery-ui.min.js");
            wp_enqueue_script('wrm',        "$appUrl/js/wrm.js");
            if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
                wp_register_script('wrm-admin', "$appUrl/js/wrm-admin.js", array('wrm'), '', true);
                wp_localize_script('wrm-admin', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
                wp_enqueue_script('wrm-admin');
            }
            
            // add styles
            wp_enqueue_style('datatables', "$appUrl/libs/css/jquery.dataTables.min.css");
            wp_enqueue_style('jqui',       "$appUrl/libs/css/jquery-ui.min.css");
            wp_enqueue_style('wrm',        "$appUrl/css/wrm.css");
        }
    }
    
    // AJAX functions
    public function FreeSql() {
        if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
            global $wpdb;
            
            // run the sql
            $results = $wpdb->get_results(str_replace("\\", "", $_GET['sql']));
            
            if($results != NULL) {
                // table header
                $html = "<table id=\"tblManualSql\" class=\"wrm\"><thead>";
                foreach($results[0] as $key => $value) $html .= "<th>".$key."</th>"; 
                $html .= "</thead><tbody>";
                
                // table body
                foreach($results as $row) {
                    $html .= "<tr>";
                    foreach($row as $data) $html .= "<td>".$data."</td>";
                    $html .= "</tr>";
                }
                $html .= "</tbody></table>";
                
                echo "<div>".$html."</div>";
            } else echo "<div>Query did not return any results.</div>";
        }
        die();
    }

    // WoW API
    public function UpdateGuildLoot() { 
        $factory       = new DAOFactory();
        $wowApi        = new WowApi();
        $itemDAO       = $factory->GetItemDAO();
        $lootItemDAO   = $factory->GetLootItemDAO();
        $lootImportDAO = $factory->GetLootImportDAO();
        
        // fetch the news for each raider
        $raiders = $factory->GetPlayerDAO()->GetAll();
        foreach($raiders as $raider) {
            $data = $ilvl = $lastImported = NULL;
            
            // only process process data we haven't read
            $data = $wowApi->GetCharFeed($raider->Name);
            $lastImported = $lootImportDAO->GetLastImported($raider->ID);
            if(!($lastImported == NULL) && $data->lastModified < $lastImported) continue;
            
            // add relevant loot from feed to LootItem table
            foreach($data->feed as $event) {
                if(!($lastImported == NULL) && $event->timestamp < $lastImported) break;
                if($event->type != "LOOT") continue;
                
                if(($ilvl = $itemDAO->GetItemLevel($event->itemId, NULL)) == NULL) {
                    // add the item to our database since it wasn't in there (saves API calls)
                    $itemLevels = $wowApi->GetItemLevel($event->itemId);
                    foreach($itemLevels as $item) $itemDAO->Add($item);
                    
                    $ilvl = $itemLevels[0]->Level;
                }
                
                // skip non relevant loot
                if($ilvl < 655) continue;
                
                $lootItemDAO->Add(new LootItem(0, $raider->ID, $event->itemId, 1, date("Y-m-d", $event->timestamp / 1000)));
            }
            
            // record that we read this data
            if($lastImported === NULL) $lootImportDAO->Add(new LootImport(0, $raider->ID, $data->lastModified));
            else                       $lootImportDAO->Update(new LootImport(0, $raider->ID, $data->lastModified));
        }
    }
}
register_activation_hook(__FILE__, array('WRO', 'Install'));
register_deactivation_hook(__FILE__, array('WRO', 'Uninstall'));;
add_action('wp_ajax_wro_freesql', array('WRO', 'FreeSql'));
add_action('wp_enqueue_scripts', array('WRO', 'EnqueueScriptsStyles'));
add_action('plugins_loaded', array('PageTemplater', 'get_instance')); ?>
