<?php

require_once(plugin_dir_path( __FILE__ ) . "./AttndDAO.php");
require_once(plugin_dir_path( __FILE__ ) . "./HeroClassDAO.php");
require_once(plugin_dir_path( __FILE__ ) . "./ItemDAO.php");
require_once(plugin_dir_path( __FILE__ ) . "./LootImportDAO.php");
require_once(plugin_dir_path( __FILE__ ) . "./LootItemDAO.php");
require_once(plugin_dir_path( __FILE__ ) . "./PlayerDAO.php");
require_once(plugin_dir_path( __FILE__ ) . "./RaidDAO.php");

class DAOFactory {
    public function GetAttndDAO()      { return new AttndDAO();      }
    public function GetHeroClassDAO()  { return new HeroClassDAO();  }
    public function GetItemDAO()       { return new ItemDAO();       }
    public function GetLootImportDAO() { return new LootImportDAO(); }
    public function GetLootItemDAO()   { return new LootItemDAO();   }
    public function GetPlayerDAO()     { return new PlayerDAO();     }
    public function GetRaidDAO()       { return new RaidDAO();       }
}
