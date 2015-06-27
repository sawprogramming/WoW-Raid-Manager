<?php
add_action('admin_menu', 'install_wro_dashboard');

function install_wro_dashboard() {
	add_menu_page("Raid Settings", "Raid", "manage_options", "raid", "dashboard_page");
	add_submenu_page("raid", "Raid Settings - Dashboard",  "Dashboard",  "manage_options", "raid",       "dashboard_page");
	add_submenu_page("raid", "Raid Settings - Players",    "Players",    "manage_options", "players",     "players_page");
	add_submenu_page("raid", "Raid Settings - Attendance", "Attendance", "manage_options", "attendance", "attendance_page");
	add_submenu_page("raid", "Raid Settings - Loot",       "Raid Loot",  "manage_options", "loot",       "loot_page");
}

function players_page() {
	readfile(__DIR__.'/views/_AdminPlayers.html');
	die();
}

function attendance_page() {
	readfile(__DIR__.'/views/_AdminAttendance.html');
	die();
}

function loot_page() {
	readfile(__DIR__.'/views/_AdminLoot.html');
	die();
}

function dashboard_page() {
	readfile(__DIR__.'/views/AdminDashboard.html');
	die();
}