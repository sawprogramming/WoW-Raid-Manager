<?php
/**
 * Plugin Name: WoW Raid Organizer
 * Description: Modules for loot and attendance.
 * Version: 0.0.1
 * Author: Criminal-SR
 * License: GPL2
 */
require_once(plugin_dir_path( __FILE__ ) . 'libs/PageTemplater.php');
require_once(plugin_dir_path( __FILE__ ) . 'display.php');
require_once(plugin_dir_path( __FILE__ ) . "./dao/DAOFactory.php");
require_once (plugin_dir_path( __FILE__ ) . "./entities/Player.php");
require_once (plugin_dir_path( __FILE__ ) . "./entities/Attnd.php");
require_once (plugin_dir_path( __FILE__ ) . "./entities/Item.php");
require_once (plugin_dir_path( __FILE__ ) . "./entities/LootItem.php");
require_once (plugin_dir_path( __FILE__ ) . "./entities/LootImport.php");
require_once (plugin_dir_path( __FILE__ ) . "./WowAPI.php");

class WRM {
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
		self::UpdateGuildLoot();
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

	// AJAX functions
	public function AddPlayer() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
            $factory = new DAOFactory();     
            echo $factory->GetPlayerDAO()->Add(new Player(0, $_POST['name'], intval($_POST['classId'])));
		}
		die();
	}
	public function RmPlayer() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
            $factory = new DAOFactory();     
            echo $factory->GetPlayerDAO()->Delete(intval($_POST['id']));
		}
		die();
	}
	public function AddGroupAttendance() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
            $factory = new DAOFactory();  
			$results = $_POST['results'];
			$today = $_POST['date'];
            
            // add attendance to db
            $dao = $factory->GetAttndDAO();
			foreach($results as $player) {
                $dao->Add(new Attnd(0, intval($player["id"]), $today, floatval($player["points"])));
            }
		}
		die();
	}
	public function RmAttnd() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
            $factory = new DAOFactory();     
            echo $factory->GetAttndDAO()->Delete(intval($_POST['id']));
		}
		die();
	}
	public function RmLoot() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
            $factory = new DAOFactory();     
            echo $factory->GetLootItemDAO()->Delete(intval($_POST['id']));
		}
		die();
	}
	public function AddAttnd() {
		global $wpdb;
        $factory = new DAOFactory(); 
        
		// only allow authorized users to use this function
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
			$id;
			$name = $_POST['name'];

			// get the player id from the name
			if(preg_match('/^([A-Za-z]+)$/', $name)) {
				// find the id if this was a name
				$results = $factory->GetPlayerDAO()->GetId($name);
				if(count($results) > 1)  { echo "ERROR: Could not find a unique player with that name."; die(); }
				if(count($results) == 0) { echo "ERROR: No players exist with that name.";               die(); }

				$id = intval($results[0]->ID);
			}
			else if(preg_match('/^([0-9]+)$/', $name)) $id = intval($name);
			else { echo "ERROR: Name was not valid (should be a string of characters or an ID number)."; die(); }

			// insert the record
			$result = $factory->GetAttndDAO()->Add(new Attnd(0, $id, $_POST['date'], floatval($_POST['points'])));
			if(!$result) echo "ERROR: An error occurred while entering that data into the database. Maybe that PlayerID doesn't exist?";
		}
		die();
	}
	public function EditAttnd() {
		if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
            $factory = new DAOFactory();
			echo $factory->GetAttndDAO()->Update(new Attnd(intval($_POST['id']), 0, $_POST['date'], floatval($_POST['points'])));
		}
		die();
	}
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
	public function Raids() {
        $factory = new DAOFactory();     
        echo json_encode($factory->GetRaidDAO()->GetAll());
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
register_activation_hook(__FILE__, array('WRM', 'Install'));
register_deactivation_hook(__FILE__, array('WRM', 'Uninstall'));
add_action('wp_ajax_wrm_addplayer', array('WRM', 'AddPlayer'));
add_action('wp_ajax_wrm_rmplayer', array('WRM', 'RmPlayer'));
add_action('wp_ajax_wrm_rmattnd', array('WRM', 'RmAttnd'));
add_action('wp_ajax_wrm_addattnd', array('WRM', 'AddAttnd'));
add_action('wp_ajax_wrm_rmloot', array('WRM', 'RmLoot'));
add_action('wp_ajax_wrm_editattnd', array('WRM', 'EditAttnd'));
add_action('wp_ajax_wrm_addgrpatt', array('WRM', 'AddGroupAttendance'));
add_action('wp_ajax_wrm_freesql', array('WRM', 'FreeSql'));
add_action('wp_ajax_wrm_raids', array('WRM', 'Raids'));
add_action('plugins_loaded', array('PageTemplater', 'get_instance')); ?>