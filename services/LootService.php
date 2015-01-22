<?php

require_once(plugin_dir_path(__FILE__)."./Service.php");
require_once(plugin_dir_path(__FILE__)."../dao/LootItemDAO.php");

class LootItemService extends Service {   
    public function GetAll() {
        $dao = new LootItemDAO();
        
        echo json_encode($dao->GetAll());
        die();
    }
    public function Delete() {
        self::Authenticate(['administrator', 'keymaster']) or die();       
        $dao = new LootItemDAO();
        
        $result = $dao->Delete(intval($_POST['id']));
        echo $result == NULL ? "An error occurred while attempting to remove this record from the database." : $result;
        die();
    }
}
add_action('wp_ajax_wro_getloot', array('LootItemService', 'GetAll'));
add_action('wp_ajax_wro_rmloot',  array('LootItemService', 'Delete'));
