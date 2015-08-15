<?php
/**
 * Plugin Name: WoW Raid Organizer
 * Description: Modules for loot and attendance.
 * Version: 2.1.5
 * Author: Steven Williams
 * License: GPL2
 */
require_once (plugin_dir_path(__FILE__).'libs/PageTemplater.php');
require_once (plugin_dir_path(__FILE__)."./WowAPI.php");
require_once (plugin_dir_path(__FILE__)."./database/DatabaseInstaller.php");
require_once (plugin_dir_path(__FILE__)."./services/RaidLootService.php");
require_once (plugin_dir_path(__FILE__)."./api/AttendanceController.php");
require_once (plugin_dir_path(__FILE__)."./api/PlayerController.php");
require_once (plugin_dir_path(__FILE__)."./api/RaidLootController.php");
require_once (plugin_dir_path(__FILE__)."./api/UserController.php");
require_once (plugin_dir_path(__FILE__)."./api/ClassController.php");
require_once (plugin_dir_path(__FILE__)."./dashboard.php");

class WRO {
    // Installation functions
    public function Install() {
        Tables\DatabaseInstaller::Install();
        wp_schedule_event(time(), 'daily', 'update_guild_loot');
    }

    public function Uninstall() {
        if(false) {
            Tables\DatabaseInstaller::Uninstall();
        }
        wp_clear_scheduled_hook('update_guild_loot');
    }

    public function AdminEnqueueScriptsStyles($hook) {
        if($hook == 'toplevel_page_raid' || $hook == 'raid_page_attendance' || $hook == 'raid_page_loot' || $hook == 'raid_page_players') {
            self::ActualEnqueue();
        }
    }

    public function EnqueueScriptsStyles() {
        $template = get_page_template_slug(get_queried_object_id()); 
        
        if($template == "../attnd-template.php" || $template == "../loot-template.php") {
            self::ActualEnqueue();
        }
    }
    
    public function ActualEnqueue() {
        $appUrl = plugins_url()."/WoWRaidOrganizer";
        
        // add scripts
        wp_enqueue_script('blah',       "$appUrl/libs/js/jquery-2.1.3.min.js");
        wp_enqueue_script('angular',    "$appUrl/libs/js/angular.min.js");
        wp_enqueue_script('panything',  "$appUrl/libs/js/dirPagination.js");
        wp_enqueue_script('angularui',  "$appUrl/libs/js/ui-bootstrap-tpls-0.13.0.min.js");
        self::AddAngularScripts($appUrl);
        wp_enqueue_script('wrm',        "$appUrl/scripts/wrm.js");
        
        // add styles
        wp_enqueue_style('bootstrap',  "$appUrl/libs/css/bootstrap.min.css");
        wp_enqueue_style('wrm',        "$appUrl/css/wrm.css");
    }

    // AJAX functions   
    public function UpdateGuildLoot() { 
        $raidLootSvc = new RaidLootService();

        $raidLootSvc->FetchLoot();
    }

    private static function AddAngularScripts($appUrl) {
        wp_enqueue_script('app',  "$appUrl/scripts/app.js");
        wp_enqueue_script('playerSelect', "$appUrl/scripts/app/directives/playerSelect.js");
        wp_enqueue_script('userSelect', "$appUrl/scripts/app/directives/userSelect.js");
        wp_enqueue_script('classSelect', "$appUrl/scripts/app/directives/classSelect.js");

        // user
        wp_register_script('UserSvc', "$appUrl/scripts/app/services/UserSvc.js");
        wp_localize_script('UserSvc', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('UserSvc');

        // class
        wp_register_script('ClassSvc', "$appUrl/scripts/app/services/ClassSvc.js");
        wp_localize_script('ClassSvc', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('ClassSvc');

        // attendance
        wp_register_script('AttendanceSvc', "$appUrl/scripts/app/services/AttendanceSvc.js");
        wp_localize_script('AttendanceSvc', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('AttendanceSvc');
        wp_enqueue_script('AttendanceCtrl',  "$appUrl/scripts/app/controllers/AttendanceCtrl.js");

        // player
        wp_register_script('PlayerSvc', "$appUrl/scripts/app/services/PlayerSvc.js");
        wp_localize_script('PlayerSvc', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('PlayerSvc');
        wp_enqueue_script('PlayerCtrl',  "$appUrl/scripts/app/controllers/PlayerCtrl.js");

        // raidloot
        wp_register_script('RaidLootSvc', "$appUrl/scripts/app/services/RaidLootSvc.js");
        wp_localize_script('RaidLootSvc', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('RaidLootSvc');
        wp_enqueue_script('RaidLootCtrl',  "$appUrl/scripts/app/controllers/RaidLootCtrl.js");

        // user ui
        wp_enqueue_script('UserUICtrl',  "$appUrl/scripts/app/controllers/UserUICtrl.js");

        // admin dashboard
        wp_enqueue_script('DashboardCtrl',  "$appUrl/scripts/app/controllers/DashboardCtrl.js");
    }
}
register_activation_hook(__FILE__, array('WRO', 'Install'));
register_deactivation_hook(__FILE__, array('WRO', 'Uninstall'));
add_action('update_guild_loot', array('WRO', 'UpdateGuildLoot'));
add_action('wp_enqueue_scripts', array('WRO', 'EnqueueScriptsStyles'));
add_action('admin_enqueue_scripts', array('WRO', 'AdminEnqueueScriptsStyles'));
add_action('plugins_loaded', array('PageTemplater', 'get_instance')); ?>