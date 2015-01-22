<?php

require_once(plugin_dir_path(__FILE__)."./Service.php");
require_once(plugin_dir_path(__FILE__)."../dao/DAOFactory.php");
require_once(plugin_dir_path(__FILE__)."../entities/Attnd.php");

class AttndService extends Service {   
    public function Add() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $id;
        $factory = new DAOFactory();
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
        echo $result == NULL ? "An error occurred while attempting to enter this record into the database." : $result;
        die();
    }
    public function AddGroupAttnd() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new AttndDAO();
        $results = $_POST['results'];
        $today   = $_POST['date'];
        
        // add attendance to db
        foreach($results as $player) {
            $dao->Add(new Attnd(0, intval($player["id"]), $today, floatval($player["points"])));
        }
        die();
    }
    public function GetAll() {
        $dao = new AttndDAO();
        
        echo json_encode($dao->GetAll());
        die();
    }
    public function Update() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new AttndDAO();
        
        $result = $dao->Update(new Attnd(intval($_POST['id']), 0, $_POST['date'], floatval($_POST['points'])));
        echo $result == NULL ? "An error occurred while attempting to update this record in the database." : $result;
        die();
    }
    public function Delete() {
        self::Authenticate(['administrator', 'keymaster']) or die();       
        $dao = new AttndDAO();
        
        $result = $dao->Delete(intval($_POST['id']));
        echo $result == NULL ? "An error occurred while attempting to remove this record from the database." : $result;
        die();
    }
}
add_action('wp_ajax_wro_addattnd',    array('AttndService', 'Add'));
add_action('wp_ajax_wro_getattnd',    array('AttndService', 'GetAll'));
add_action('wp_ajax_wro_updateattnd', array('AttndService', 'Update'));
add_action('wp_ajax_wro_rmattnd',     array('AttndService', 'Delete'));
add_action('wp_ajax_wro_addgrpatt',   array('AttndService', 'AddGroupAttnd'));
