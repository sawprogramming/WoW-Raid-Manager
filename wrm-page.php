<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#tblUserLoot").DataTable({ "iDisplayLength": 15 });
		$("#tblUserAttnd").DataTable({ "iDisplayLength": 50 });

		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
		Admin_AddClickHandlers();
		Admin_AddSliders();
		Admin_AddDatePickers();
		$("#divAdminTabs").tabs();
		$("#tblEditPlayers").DataTable({ "iDisplayLength": 15, "order" : [[ 0, "desc" ]] });
		$("#tblRaidAttendance").DataTable({ "iDisplayLength": 50 });
		$("#tblEditAttnd").DataTable({ "iDisplayLength": 15, "order" : [[ 0, "desc" ]] });
		$("#tblEditLoot").DataTable({ "iDisplayLength": 15, "order" : [[ 0, "desc" ]] });
		<?php } ?>
	});
</script>

<div id="content" class="container">
	<div class="row" style="padding: 3px;">
		<div id="divUserAttnd">
			<h1>Attendance</h1>
			<?php echo WRM::UserAttndTbl(); ?>
		</div>

		<div id="divUserLoot">
			<h1>Loot Log</h1>
			<?php echo WRM::UserLootTbl(); ?>
		</div>
		
		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
		<div id="divAdmin" style="width: 100%;">
			<h1>Admin Controls</h1>
			<div id="divAdminTabs">
				<ul>
					<li><a href="#tabs-1">Add/Remove Players</a></li>
					<li><a href="#tabs-2">Edit Loot Records</a></li>
					<li><a href="#tabs-3">Daily Raid Attendance</a></li>
					<li><a href="#tabs-4">Edit Attendance Records</a></li>
				</ul>
				<div id="tabs-1">
					<div id="addPlayerForm" align="center">
						<label for="txtAddPlayer" style="display: inline-block;">Add New Player:</label>
						<input id="txtAddPlayer" type="text" maxlength="12" placeholder="Raider Name..." />
						<select id="slAddPlayer">
							<option value="0">Player class...</option>
							<option value="10" class="deathknight">Death Knight</option>
							<option value="1" class="druid">Druid</option>
							<option value="2" class="hunter">Hunter</option>
							<option value="3" class="mage">Mage</option>
							<option value="11" class="monk">Monk</option>
							<option value="4" class="paladin">Paladin</option>
							<option value="5">Priest</option>
							<option value="6" class="rogue">Rogue</option>
							<option value="7" class="shaman">Shaman</option>
							<option value="8" class="warlock">Warlock</option>
							<option value="9" class="warrior">Warrior</option>
						</select>
						<button id="btnAddPlayer">Add</button>
					</div> <br />
					<?php echo WRM::EditPlayerTbl(); ?>
				</div>
				<div id="tabs-2">
					<?php echo WRM::EditLootTbl(); ?>
				</div>
				<div id="tabs-3">
					<div align="center">
						<label for="dpRaidAttnd" style="display: inline-block;">Date:</label>
						<input type="text" id="dpRaidAttnd" placeholder="yyyy/mm/dd" maxlength="10">
						<button id="btnSaveAttendance" style="float: right;">Save</button>
					</div> <br />
					<div id="divNewAttendance">
						<?php echo WRM::RaidAttndTbl(); ?>
					</div>
				</div>
				<div id="tabs-4">
					<div id="EditAttndCtrls" align="center">
						<label for="txtEditAttnd" style="display: inline-block;">Manual Attendance Entry:</label>
						<input id="txtEditAttnd" type="text" maxlength="12" placeholder="Player Name or ID" />
						<div id="EditAttndSlider" style="display: inline-block; margin: 0px 6px 0px 6px;"></div>
						<input type="text" id="dpEditAttnd" placeholder="yyyy/mm/dd" maxlength="10">
						<button id="btnEditAttnd">Add</button>
					</div>
					<?php echo WRM::EditAttndTbl(); ?>
				</div>
			</div>
		</div>
		<?php } ?>