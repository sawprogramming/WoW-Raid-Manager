<?php echo wp_head(); ?>
<?php add_action('wp_enqueue_scripts', array('WRO', 'AddScripts')); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#tblUserAttnd").DataTable({ "iDisplayLength": 50 });
        $(".btn-del").button({ icons: { primary: "ui-icon-closethick" }, text: false });
        $(".btn-edit").button({ icons: { primary: "ui-icon-pencil" }, text: false });

		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
	    Admin_AddClickHandlers();
	    $("#divAdminTabs").tabs();
	    <?php } ?>
	});
</script>
<div id="content" style="width: 940px; margin: 0 auto;">
	    <div id="divUserAttnd">
		    <h1>Attendance</h1>
            <?php include(plugin_dir_path( __FILE__ ) . "./views/_AttndBrkdwn.php"); ?>
	    </div>
		
	    <?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
	    <div id="divAdmin" style="width: 100%;">
		    <h1>Admin Controls</h1>
		    <div id="divAdminTabs">
			    <ul>
				    <li><a href="#tabs-1">Add/Remove Players</a></li>
				    <li><a href="#tabs-2">Daily Raid Attendance</a></li>
				    <li><a href="#tabs-3">Edit Attendance Records</a></li>
				    <li><a href="#tabs-4">Miscellaneous</a></li>
			    </ul>
			    <div id="tabs-1"><?php include(plugin_dir_path(__FILE__)."./views/_EditPlayers.php"); ?></div>
			    <div id="tabs-2"><?php include(plugin_dir_path(__FILE__)."./views/_DailyAttnd.php"); ?></div>
			    <div id="tabs-3"><?php include(plugin_dir_path(__FILE__)."./views/_EditAttnd.php"); ?></div>
			    <div id="tabs-4">
				    <div style="display: inline-block; vertical-align: text-top; width: 45%;">
                        <?php include(plugin_dir_path(__FILE__)."./views/_EditRaid.php"); ?>
				    </div><br />
				    <div>
					    <fieldset>
						    <legend>Danger Zone</legend>
						    <div style="width: 25em">
							    <textarea id="txtManualSql" style="width: 100%; height: 200px"
								    placeholder="Any SQL entered here will be run without sanitization."></textarea>
							    <button id="btnManualSql" style="float: right;">Query</button>
						    </div>
					    </fieldset>
				    </div>
			    </div>
		    </div>
</div>
</div>
<?php } ?>
<?php echo wp_footer(); ?>
      