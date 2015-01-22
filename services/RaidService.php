<?php

require_once(plugin_dir_path(__FILE__)."./Service.php");
require_once(plugin_dir_path(__FILE__)."../dao/RaidDAO.php");
require_once(plugin_dir_path(__FILE__)."../entities/Raid.php");

class RaidService extends Service {
    public function Add() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new RaidDAO();
        
        $result = $dao->Add(new Raid(0, $_POST['name']));
        echo $result == NULL ? "An error occurred while attempting to enter this record into the database." : $result;
        die();
    }
    public function GetAll() {
        $dao = new RaidDAO();
        
        echo json_encode($dao->GetAll());
        die();
    }
    public function Update() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new RaidDAO();
        
        $result = $dao->Update(new Raid(intval($_POST['id']), $_POST['name']));
        echo $result == NULL ? "An error occurred while attempting to update this record in the database." : $result;
        die();
    }
    public function Delete() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new RaidDAO();
        
        $result = $dao->Delete(intval($_POST['id']));
        echo $result == NULL ? "An error occurred while attempting to remove this record from the database." : $result;
        die();
    }
}
add_action('wp_ajax_wro_addraid',    array('RaidService', 'Add'));
add_action('wp_ajax_wro_getraids',   array('RaidService', 'GetAll'));
add_action('wp_ajax_wro_updateraid', array('RaidService', 'Update'));
add_action('wp_ajax_wro_rmraid',     array('RaidService', 'Delete'));