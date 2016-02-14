<?php
namespace WRO;
/**
 * Plugin Name: WoW Raid Organizer
 * Description: Modules for loot and attendance.
 * Version: 2.3.3
 * Author: Steven Williams
 * License: GPL2
 */
require_once(plugin_dir_path(__FILE__).'libs/PageTemplater.php');
require_once(plugin_dir_path(__FILE__)."./WowAPI.php");
require_once(plugin_dir_path(__FILE__)."./Logger.php");
require_once(plugin_dir_path(__FILE__)."./database/DatabaseInstaller.php");
require_once(plugin_dir_path(__FILE__)."./database/DatabaseSeeder.php");
require_once(plugin_dir_path(__FILE__)."./services/RaidLootService.php");
require_once(plugin_dir_path(__FILE__)."./services/RealmService.php");
require_once(plugin_dir_path(__FILE__)."./api/AttendanceController.php");
require_once(plugin_dir_path(__FILE__)."./api/PlayerController.php");
require_once(plugin_dir_path(__FILE__)."./api/RaidLootController.php");
require_once(plugin_dir_path(__FILE__)."./api/UserController.php");
require_once(plugin_dir_path(__FILE__)."./api/ClassController.php");
require_once(plugin_dir_path(__FILE__)."./api/OptionController.php");
require_once(plugin_dir_path(__FILE__)."./api/DisputeController.php");
require_once(plugin_dir_path(__FILE__)."./api/RaidTierController.php");
require_once(plugin_dir_path(__FILE__)."./api/ExpansionController.php");
require_once(plugin_dir_path(__FILE__)."./api/RealmController.php");
require_once(plugin_dir_path(__FILE__)."./dashboard.php");

class WRO {
    // Installation functions
    public function Install() {
        $dbInstaller   = new Database\DatabaseInstaller();
        $optionService = new Services\OptionService();

        // add options if they haven't been already
        add_option('wro_region',          'us',        '', 'yes');
        add_option('wro_faction',         'alliance',  '', 'yes');
        add_option('wro_default_realm',   'stormrage', '', 'yes');
        add_option('wro_loot_time',       time(),      '', 'yes');
        add_option('wro_loot_frequency',  'daily',     '', 'yes');
        add_option('wro_realm_time',      time(),      '', 'yes');
        add_option('wro_realm_frequency', 'daily',     '', 'yes');

        // database
        $dbInstaller->Install();
        Database\DatabaseSeeder::Seed();

        // schedule jobs
        wp_schedule_event($optionService->Get("wro_loot_time"),  $optionService->Get("wro_loot_frequency"),  'update_guild_loot');
        wp_schedule_event($optionService->Get("wro_realm_time"), $optionService->Get("wro_realm_frequency"), 'update_realm_list');
    }

    public function Uninstall() {
        $dbInstaller = new Database\DatabaseInstaller();

        if(false) {
            $dbInstaller->Uninstall();
        }
        wp_clear_scheduled_hook('update_guild_loot');
        wp_clear_scheduled_hook('update_realm_list');
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
        wp_enqueue_script('blah',        "$appUrl/libs/js/jquery-2.1.3.min.js");
        wp_enqueue_script('angular',     "$appUrl/libs/js/angular.min.js");
        wp_enqueue_script('angularmsgs', "$appUrl/libs/js/angular-messages.js");
        wp_enqueue_script('xregexp',     "$appUrl/libs/js/xregexp-min.js");
        wp_enqueue_script('ucb',         "$appUrl/libs/js/unicode-base.js");
        wp_enqueue_script('panything',   "$appUrl/libs/js/dirPagination.js");
        wp_enqueue_script('angularui',   "$appUrl/libs/js/ui-bootstrap-tpls-0.14.3.min.js");
        wp_enqueue_script('toastr',      "$appUrl/libs/js/angular-toastr.tpls.min.js");
        self::AddAngularScripts($appUrl);
        wp_enqueue_script('wrm',        "$appUrl/scripts/wrm.js");
        
        // add styles
        wp_enqueue_style('bootstrap',  "$appUrl/libs/css/bootstrap.min.css");
        wp_enqueue_style('toastr',     "$appUrl/libs/css/angular-toastr.min.css");
        wp_enqueue_style('wrm',        "$appUrl/css/wrm.css");
    }

    // AJAX functions   
    public function UpdateGuildLoot() { 
        Logger::Write("update_guild_loot started.");
        $raidLootSvc = new Services\RaidLootService();
        $raidLootSvc->FetchLoot();
        Logger::Write("update_guild_loot finished.");
    }
    public function UpdateRealmList() { 
        Logger::Write("update_realm_list started.");
        $realmSvc = new Services\RealmService();
        $realmSvc->UpdateRealmList();
        Logger::Write("update_realm_list finished.");
    }

    private static function AddAngularScripts($appUrl) {
        $plugin_url = $ajax_object = NULL;

        // define localization
        $plugin_url = array(
            'images'    => $appUrl."/images", 
            'libs'      => $appUrl."/libs", 
            'app'       => $appUrl."/scripts/app"
        );

        $ajax_object = array(
            'ajax_url' => admin_url('admin-ajax.php')
        );

        // register scripts
        wp_register_script('app',            "$appUrl/scripts/app/app.js");
        wp_register_script('ajaxContent',    "$appUrl/scripts/app/common/directives/ajax-content/ajaxContent.js");
        wp_register_script('ajaxForm',       "$appUrl/scripts/app/common/directives/ajax-form/ajaxForm.js");
        wp_register_script('UserSvc',        "$appUrl/scripts/app/common/services/UserSvc.js");
        wp_register_script('ClassSvc',       "$appUrl/scripts/app/common/services/ClassSvc.js");
        wp_register_script('OptionSvc',      "$appUrl/scripts/app/common/services/OptionSvc.js");
        wp_register_script('RealmSvc',       "$appUrl/scripts/app/common/services/RealmSvc.js");
        wp_register_script('DisputeSvc',     "$appUrl/scripts/app/dispute/services/DisputeSvc.js");
        wp_register_script('AttendanceSvc',  "$appUrl/scripts/app/attendance/services/AttendanceSvc.js");
        wp_register_script('AttendanceCtrl', "$appUrl/scripts/app/attendance/controllers/AttendanceCtrl.js");
        wp_register_script('PlayerSvc',      "$appUrl/scripts/app/player/services/PlayerSvc.js");
        wp_register_script('PlayerCtrl',     "$appUrl/scripts/app/player/controllers/PlayerCtrl.js");
        wp_register_script('RaidLootSvc',    "$appUrl/scripts/app/raid-loot/services/RaidLootSvc.js");
        wp_register_script('RaidLootCtrl',   "$appUrl/scripts/app/raid-loot/controllers/RaidLootCtrl.js");
        wp_register_script('ExpansionSvc',   "$appUrl/scripts/app/expansion/ExpansionSvc.js");
        wp_register_script('RaidTierSvc',    "$appUrl/scripts/app/raid-tier/RaidTierSvc.js");
        wp_register_script('UserUICtrl',     "$appUrl/scripts/app/user-ui/controllers/UserUICtrl.js");
        wp_register_script('DashboardCtrl',  "$appUrl/scripts/app/dashboard/controllers/DashboardCtrl.js");

        // localize scripts
        wp_localize_script('app',            'plugin_url',  $plugin_url);
        wp_localize_script('ajaxContent',    'plugin_url',  $plugin_url);
        wp_localize_script('ajaxForm',       'plugin_url',  $plugin_url);
        wp_localize_script('UserSvc',        'ajax_object', $ajax_object);
        wp_localize_script('ClassSvc',       'ajax_object', $ajax_object);
        wp_localize_script('OptionSvc',      'ajax_object', $ajax_object);
        wp_localize_script('RealmSvc',       'ajax_object', $ajax_object);
        wp_localize_script('DisputeSvc',     'ajax_object', $ajax_object);
        wp_localize_script('AttendanceSvc',  'ajax_object', $ajax_object);
        wp_localize_script('AttendanceCtrl', 'plugin_url',  $plugin_url);
        wp_localize_script('PlayerSvc',      'ajax_object', $ajax_object);
        wp_localize_script('PlayerCtrl',     'plugin_url',  $plugin_url);
        wp_localize_script('ExpansionSvc',   'ajax_object', $ajax_object);
        wp_localize_script('RaidLootSvc',    'ajax_object', $ajax_object);
        wp_localize_script('RaidTierSvc',    'ajax_object', $ajax_object);
        wp_localize_script('RaidLootCtrl',   'plugin_url',  $plugin_url);
        wp_localize_script('UserUICtrl',     'plugin_url',  $plugin_url);
        wp_localize_script('DashboardCtrl',  'plugin_url',  $plugin_url);

        // enqueue scripts
        wp_enqueue_script('app');
        wp_enqueue_script('InactivePlayers',  "$appUrl/scripts/app/user-ui/inactivePlayers.filter.js");
        wp_enqueue_script('DateSvc',          "$appUrl/scripts/app/common/services/DateSvc.js");
        wp_enqueue_script('TimeSvc',          "$appUrl/scripts/app/common/services/TimeSvc.js");
        wp_enqueue_script('rangeSelect',      "$appUrl/scripts/app/common/directives/rangeSelect.js");
        wp_enqueue_script('userSelect',       "$appUrl/scripts/app/common/directives/userSelect.js");
        wp_enqueue_script('classSelect',      "$appUrl/scripts/app/common/directives/classSelect.js");
        wp_enqueue_script('realmSelect',      "$appUrl/scripts/app/common/directives/realmSelect.js");
        wp_enqueue_script('playerSelect',     "$appUrl/scripts/app/player/directives/playerSelect.js");
        wp_enqueue_script('ajaxContent');
        wp_enqueue_script('ajaxForm');
        wp_enqueue_script('UserSvc');
        wp_enqueue_script('ClassSvc');
        wp_enqueue_script('RealmSvc');
        wp_enqueue_script('OptionSvc');
        wp_enqueue_script('DisputeSvc');
        wp_enqueue_script('AttendanceSvc');
        wp_enqueue_script('AttendanceCtrl');
        wp_enqueue_script('PlayerSvc');
        wp_enqueue_script('PlayerCtrl');
        wp_enqueue_script('RaidLootSvc');
        wp_enqueue_script('ExpansionSvc');
        wp_enqueue_script('RaidLootCtrl');
        wp_enqueue_script('RaidTierSvc');
        wp_enqueue_script('UserUICtrl');
        wp_enqueue_script('DashboardCtrl');
        wp_enqueue_script('AddAttendanceModalCtrl',    "$appUrl/scripts/app/attendance/controllers/AddAttendanceModalCtrl.js");
        wp_enqueue_script('DeleteAttendanceModalCtrl', "$appUrl/scripts/app/attendance/controllers/DeleteAttendanceModalCtrl.js");
        wp_enqueue_script('EditAttendanceModalCtrl',   "$appUrl/scripts/app/attendance/controllers/EditAttendanceModalCtrl.js");
        wp_enqueue_script('AddPlayerModalCtrl',        "$appUrl/scripts/app/player/controllers/AddPlayerModalCtrl.js");
        wp_enqueue_script('DeletePlayerModalCtrl',     "$appUrl/scripts/app/player/controllers/DeletePlayerModalCtrl.js");
        wp_enqueue_script('EditPlayerModalCtrl',       "$appUrl/scripts/app/player/controllers/EditPlayerModalCtrl.js");
        wp_enqueue_script('DeleteRaidLootModalCtrl',   "$appUrl/scripts/app/raid-loot/controllers/DeleteRaidLootModalCtrl.js");
        wp_enqueue_script('ApproveDisputeModalCtrl',   "$appUrl/scripts/app/dispute/controllers/ApproveDisputeModalCtrl.js");
        wp_enqueue_script('RejectDisputeModalCtrl',    "$appUrl/scripts/app/dispute/controllers/RejectDisputeModalCtrl.js");
    }
};
register_activation_hook(__FILE__, array('WRO\WRO', 'Install'));
register_deactivation_hook(__FILE__, array('WRO\WRO', 'Uninstall'));
add_action('update_guild_loot', array('WRO\WRO', 'UpdateGuildLoot'));
add_action('update_realm_list', array('WRO\WRO', 'UpdateRealmList'));
add_action('wp_enqueue_scripts', array('WRO\WRO', 'EnqueueScriptsStyles'));
add_action('admin_enqueue_scripts', array('WRO\WRO', 'AdminEnqueueScriptsStyles'));
add_action('plugins_loaded', array('PageTemplater', 'get_instance')); ?>