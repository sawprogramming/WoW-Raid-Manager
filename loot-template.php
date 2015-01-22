<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#tblUserLoot").DataTable({ "iDisplayLength": 15, "order": [[3, "desc"]] });

		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
	    Admin_AddClickHandlers();
	    $("#divAdminTabs").tabs();
	    <?php } ?>
	});
</script>

<div id="content" class="container">
	<div class="row" style="padding: 3px;">
		<div id="divUserLoot">
			<h1>Loot Log</h1>
			<?php include(plugin_dir_path( __FILE__ ) . "./views/_Loot.php"); ?>
		</div>
		
		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
		<div id="divAdmin" style="width: 100%;">
			<h1>Admin Controls</h1>
			<div id="divAdminTabs">
				<ul>
					<li><a href="#tabs-1">Add/Remove Players</a></li>
					<li><a href="#tabs-2">Edit Loot Records</a></li>
					<li><a href="#tabs-3">Miscellaneous</a></li>
				</ul>
				<div id="tabs-1"><?php include(plugin_dir_path(__FILE__)."./views/_EditPlayers.php"); ?></div>
				<div id="tabs-2"><?php include(plugin_dir_path(__FILE__)."./views/_EditLoot.php"); ?></div>
				<div id="tabs-3">
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
		<?php } ?>