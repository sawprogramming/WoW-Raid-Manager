<?php
add_action('admin_menu', 'install_wro_dashboard');

function install_wro_dashboard() {
	add_menu_page("Raid Settings", "Raid", "manage_options", "raid", "dashboard_page");
	add_submenu_page("raid", "Raid Settings - Dashboard",  "Dashboard",  "manage_options", "raid",       "dashboard_page");
	add_submenu_page("raid", "Raid Settings - Players",    "Players",    "manage_options", "players",    "players_page");
	add_submenu_page("raid", "Raid Settings - Attendance", "Attendance", "manage_options", "attendance", "attendance_page");
	add_submenu_page("raid", "Raid Settings - Loot",       "Raid Loot",  "manage_options", "loot",       "loot_page");
}

function players_page() {
	readfile(__DIR__.'/views/AdminPlayers.html');
	die();
}

function attendance_page() {
	readfile(__DIR__.'/views/AdminAttendance.html');
	die();
}

function loot_page() {
	readfile(__DIR__.'/views/AdminLoot.html');
	die();
}

function dashboard_page() {
	readfile(__DIR__.'/views/AdminDashboard.html');
	die();
}