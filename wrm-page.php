<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#playerLoot").DataTable({ "iDisplayLength": 10 });
		$("#playerAttendance").DataTable({ "iDisplayLength": 50 });

		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
		Admin_AddClickHandlers();
		Admin_AddSliders();
		Admin_AddDatePickers();
		$("#divAdminTabs").tabs();
		$("#tblEditPlayers").DataTable({ "iDisplayLength": 10, "order" : [[ 0, "desc" ]] });
		$("#tblRaidAttendance").DataTable({ "iDisplayLength": 50 });
		$("#tblEditAttnd").DataTable({ "iDisplayLength": 10, "order" : [[ 0, "desc" ]] });
		$("#tblEditLoot").DataTable({ "iDisplayLength": 10, "order" : [[ 0, "desc" ]] });
		<?php } ?>
	});
</script>

<div id="pageheader" class="titleclass">
	<div class="container">
		<?php get_template_part('templates/page', 'header'); ?>
	</div><!--container-->
</div><!--titleclass-->

<div id="content" class="container">
	<div class="row" style="padding: 3px;">
		<h1>Attendance</h1>
		<div id="attendance" style="margin-bottom: 20px;">
			<table id="playerAttendance" class="nowrap compact" cellspacing="0" width="100%" style="background: rgba(0,0,0,0)">
				<thead>
					<tr>
						<th>Name</th>
						<th>Class</th>
						<th>Last 2 Weeks</th>
						<th>Last 30 Days</th>
						<th>All Time</th>
					</tr>
				</thead>
				<tbody>
					<?php $blah = new WRM(); echo $blah->GetPlayersAttendance(); ?>
				</tbody>
			</table>
		</div>

		<h1>Loot Log</h1>
		<div id="loot" style="margin-bottom: 20px;">
			<table id="playerLoot" class="nowrap compact" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Player</th>
						<th>Item</th>
						<th>Raid</th>
					</tr>
				</thead>
				<tbody>
					<?php $blah = new WRM(); echo $blah->GetLoot(); ?>
				</tbody>
			</table>
		</div>
		
		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
		<h1>Admin Controls</h1>
		<div id="adminPanel" style="width: 100%;">
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
					<table id="tblEditPlayers" class="nowrap compact" cellspacing="0" width="100%" style="background: #272822; color: #FFFFFF">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Class</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
							<?php $blah = new WRM(); echo $blah->GetPlayers(); ?>
						</tbody>
					</table>
				</div>
				<div id="tabs-2">
					<table id="tblEditLoot" class="nowrap compact" cellspacing="0" width="100%" style="background: #272822; color: #FFFFFF">
						<thead>
							<tr>
								<th>Row</th>
								<th>Name</th>
								<th>Class</th>
								<th>Item</th>
								<th>Raid</th>
								<th>Date</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
							<?php $blah = new WRM(); echo $blah->EditLootForm(); ?>
						</tbody>
					</table>
				</div>
				<div id="tabs-3">
					<div style="display: inline-block; float: right;">
						<button id="btnSaveAttendance">Save</button>
					</div> <br /> <br />
					<div id="divNewAttendance">
						<table id="tblRaidAttendance" class="nowrap compact" cellspacing="0" width="100%" style="background: #272822; color: #FFFFFF">
							<thead>
								<tr>
									<th>Name</th>
									<th>Class</th>
									<th>Points<br /><div id="divNewAttBulk"></div></th>
									<th>Options</th>
								</tr>
							</thead>
							<tbody>
								<?php $blah = new WRM(); echo $blah->RaidAttendanceForm(); ?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="tabs-4">
					<div id="EditAttndCtrls" align="center">
						<label for="txtEditAttnd" style="display: inline-block;">Manual Attendance Entry:</label>
						<input id="txtEditAttnd" type="text" maxlength="12" placeholder="Player Name or ID" />
						<div id="EditAttndSlider" style="display: inline-block; margin: 0px 6px 0px 6px;"></div>
						<input type="text" id="dpEditAttnd" placeholder="mm/dd/yyyy" maxlength="10">
						<button id="btnEditAttnd">Add</button>

					</div>
					<table id="tblEditAttnd" class="nowrap compact" cellspacing="0" width="100%" style="background: #272822; color: #FFFFFF">
						<thead>
							<tr>
								<th>Row</th>
								<th>Name</th>
								<th>Class</th>
								<th>Points</th>
								<th>Date</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
							<?php $blah = new WRM(); echo $blah->EditAttendanceForm(); ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php } ?>