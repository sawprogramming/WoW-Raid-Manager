<?php

require_once(plugin_dir_path(__FILE__)."./Service.php");
require_once(plugin_dir_path(__FILE__)."../dao/PlayerDAO.php");
require_once(plugin_dir_path(__FILE__)."../entities/Player.php");

class PlayerService extends Service {
    public function Add() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new PlayerDAO();
        
        $result = $dao->Add(new Player(0, $_POST['name'], intval($_POST['classId'])));
        echo $result == NULL ? "An error occurred while attempting to enter this record into the database." : $result;
        die();
    }
    public function GetAll() {
        $dao = new PlayerDAO();
        
        echo json_encode($dao->GetAll());
        die();
    }
    public function Update() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new PlayerDAO();
        
        $result = $dao->Update(new Player($_POST['id'], $_POST['name'], intval($_POST['classId'])));
        echo $result == NULL ? "An error occurred while attempting to update this record in the database." : $result;
        die();
    }
    public function Delete() {
        self::Authenticate(['administrator', 'keymaster']) or die();
        $dao = new PlayerDAO();
        
        $result = $dao->Delete(intval($_POST['id']));
        echo $result == NULL ? "An error occurred while attempting to remove this record from the database." : $result;
        die();
    }
}
add_action('wp_ajax_wro_addplayer',    array('PlayerService', 'Add'));
add_action('wp_ajax_wro_getplayers',   array('PlayerService', 'GetAll'));
add_action('wp_ajax_wro_updateplayer', array('PlayerService', 'Update'));
add_action('wp_ajax_wro_rmplayer',     array('PlayerService', 'Delete'));