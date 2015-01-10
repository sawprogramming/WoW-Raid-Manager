WoW-Raid-Manager
================

WordPress plugin to keep track of World of Warcraft raid loot and attendance.

Installation instructions:
if($_SERVER["REQUEST_URI"] == "/attendance/") {
	wp_enqueue_script('blah', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/libs/js/jquery-2.1.3.min.js');
	wp_enqueue_script('datatables', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/libs/js/jquery.dataTables.min.js');
	wp_enqueue_script('jqui', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/libs/js/jquery-ui.min.js');
	wp_enqueue_script('wrm', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/js/wrm.js');
	wp_enqueue_style('datatables', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/libs/css/jquery.dataTables.min.css');
	wp_enqueue_style('jqui', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/libs/css/jquery-ui.min.css');
	wp_enqueue_style('wrm', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/css/wrm.css');
	
	if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) {
		wp_register_script('wrm-admin', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/js/wrm-admin.js', array('wrm'));
		wp_localize_script('wrm-admin', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
		wp_enqueue_script('wrm-admin');
		
		wp_enqueue_script('wrm-admin-player', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/js/admin-player.js', array('wrm-admin'));
		wp_enqueue_script('wrm-admin-loot', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/js/admin-loot.js', array('wrm-admin'));
		wp_enqueue_script('wrm-admin-attnd', 'http://criminal-sr.com/wp-content/plugins/WoWRaidManager/js/admin-attnd.js', array('wrm-admin'));
	}
}
