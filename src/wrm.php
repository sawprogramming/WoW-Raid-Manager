<?php
namespace WRO;
/**
 * Plugin Name: WoW Raid Organizer
 * Description: Modules for loot and attendance.
 * Version: 2.3.7
 * Author: Steven Williams
 * License: GPL2
 */
require_once(plugin_dir_path(__FILE__).'libs/PageTemplater.php');
require_once(plugin_dir_path(__FILE__)."./WowAPI.php");
require_once(plugin_dir_path(__FILE__)."./Logger.php");
require_once(plugin_dir_path(__FILE__)."./database/DatabaseInstaller.php");
require_once(plugin_dir_path(__FILE__)."./database/DatabaseSeeder.php");
require_once(plugin_dir_path(__FILE__)."./database/DatabaseExporter.php");
require_once(plugin_dir_path(__FILE__)."./database/DatabaseImporter.php");
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
use Exception;

define("WRO_PATH", str_replace("\\", "/", plugin_dir_path(__FILE__)));

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
        add_option('wro_loot_itemid',     '113598',    '', 'yes');
        add_option('wro_realm_time',      time(),      '', 'yes');
        add_option('wro_realm_frequency', 'daily',     '', 'yes');
        add_option('wro_drop_tables',     '0',         '', 'yes');

        // database
        $dbInstaller->Install();
        Database\DatabaseSeeder::Seed();

        // schedule jobs
        wp_schedule_event($optionService->Get("wro_loot_time"),  $optionService->Get("wro_loot_frequency"),  'update_guild_loot');
        wp_schedule_event($optionService->Get("wro_realm_time"), $optionService->Get("wro_realm_frequency"), 'update_realm_list');
    }

    public function Uninstall() {
        $dbInstaller   = new Database\DatabaseInstaller();
        $optionService = new Services\OptionService();

        // drop tables if that's what the user wanted
        if($optionService->Get("wro_drop_tables")) {
            $dbInstaller->Uninstall();
        }

        // clear scheduled jobs
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
        
        // add script libraries
        wp_enqueue_script('blah',        "$appUrl/libs/js/jquery-2.1.3.min.js");
        wp_enqueue_script('angular',     "$appUrl/libs/js/angular.min.js");
        wp_enqueue_script('angularmsgs', "$appUrl/libs/js/angular-messages.js");
        wp_enqueue_script('xregexp',     "$appUrl/libs/js/xregexp-min.js");
        wp_enqueue_script('ucb',         "$appUrl/libs/js/unicode-base.js");
        wp_enqueue_script('panything',   "$appUrl/libs/js/dirPagination.js");
        wp_enqueue_script('angularui',   "$appUrl/libs/js/ui-bootstrap-tpls-0.14.3.min.js");
        wp_enqueue_script('toastr',      "$appUrl/libs/js/angular-toastr.tpls.min.js");
        wp_enqueue_script('upload',      "$appUrl/libs/js/ng-file-upload.min.js");
        
        // add style libraries
        wp_enqueue_style('bootstrap',  "$appUrl/libs/css/bootstrap.min.css");
        wp_enqueue_style('toastr',     "$appUrl/libs/css/angular-toastr.min.css");

        // add WRO scripts/styles
        self::EnqueueWroFiles($appUrl);
    }

    // jobs  
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

    // AJAX functions
    public function GetDatabaseBackup() {
        $dbExporter = new Database\DatabaseExporter();
        try {
            $dbExporter->ExportToCsv();
            $file = "../wp-content/plugins/WoWRaidOrganizer/wro_backup.zip";

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            unlink($file);
        } catch(Exception $e) {
            status_header(500);
            echo($e->getMessage());
        }
        die();
    }
    public function RestoreDatabase() {
        $extracted = false;
        $dbImporter  = new Database\DatabaseImporter();

        // get uploaded file
        $filename    = $_FILES['file']['name'];
        $destination = '../wp-content/plugins/WoWRaidOrganizer/wro_backup.zip';
        move_uploaded_file($_FILES['file']['tmp_name'], $destination);

        // do the importing
        try {
           $dbImporter->ImportFromCsv();
        } catch (Exception $e) {
            status_header(422);
            echo($e->getMessage());
        }

        unlink($destination);
        die();
    }

    private static function ListFiles($dir) {
        $files     = array();
        $file_list = array_diff(scandir($dir), array('..', '.'));

        foreach($file_list as $file) {
            $file_path = $dir . "/" . $file;
            if(is_dir($file_path)) $files = array_merge($files, WRO::ListFiles($file_path));
            else                   $files[$file] = $file_path;
        }

        return $files;
    }

    private static function EnqueueWroFiles($appUrl) {
        $plugin_url = array(
            'images' => $appUrl."/images", 
            'libs'   => $appUrl."/libs", 
            'app'    => $appUrl."/scripts/app"
        );

        $ajax_object = array(
            'ajax_url' => admin_url('admin-ajax.php')
        );

        // add minified files if they exist
        if(file_exists(WRO_PATH . 'scripts/wro.min.js')) {
            // css
            wp_enqueue_style('wrm', "$appUrl/css/wro.min.css");

            // js
            wp_register_script("wro.min.js", $appUrl . "/scripts/wro.min.js");
            wp_localize_script("wro.min.js", "plugin_url",  $plugin_url);
            wp_localize_script("wro.min.js", "ajax_object", $ajax_object);
            wp_enqueue_script("wro.min.js");
        } 
        
        // otherwise add the individual files
        else {
            $angular_files = WRO::ListFiles(WRO_PATH . "scripts/app");
            
            wp_enqueue_style('wrm',  "$appUrl/css/wrm.css");
            wp_enqueue_script('wrm', "$appUrl/scripts/wrm.js");


            // add the module first then remove it from the list
            wp_register_script("app.module.js", str_replace(WRO_PATH, $appUrl . "/", $angular_files["app.module.js"]));
            wp_localize_script("app.module.js", "plugin_url", $plugin_url);
            wp_enqueue_script("app.module.js");
            unset($angular_files["app.module.js"]);

            // add the rest of the scripts
            foreach($angular_files as $file => $path) {
                if(pathinfo($path)["extension"] == "js") {
                    wp_register_script($file, str_replace(WRO_PATH, $appUrl . "/", $path));

                    wp_localize_script($file, "plugin_url",  $plugin_url);
                    wp_localize_script($file, "ajax_object", $ajax_object);

                    wp_enqueue_script($file);
                }
            }
        }
    }
};
register_activation_hook(__FILE__, array('WRO\WRO', 'Install'));
register_deactivation_hook(__FILE__, array('WRO\WRO', 'Uninstall'));
add_action('update_guild_loot', array('WRO\WRO', 'UpdateGuildLoot'));
add_action('update_realm_list', array('WRO\WRO', 'UpdateRealmList'));
add_action('wp_ajax_wro_backup_dl', array('WRO\WRO', 'GetDatabaseBackup'));
add_action('wp_ajax_wro_restore_ul', array('WRO\WRO', 'RestoreDatabase'));
add_action('wp_enqueue_scripts', array('WRO\WRO', 'EnqueueScriptsStyles'));
add_action('admin_enqueue_scripts', array('WRO\WRO', 'AdminEnqueueScriptsStyles'));
add_action('plugins_loaded', array('PageTemplater', 'get_instance')); ?>