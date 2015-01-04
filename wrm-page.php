<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#playerLoot").DataTable({ "iDisplayLength": 50 });
		$("#playerAttendance").DataTable({ "iDisplayLength": 50 });

		<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles)) { ?>
		AddAdminClickHandlers();
		AddAdminSliders();
		$("#divAdminTabs").tabs();
		$("#adminPlayers").DataTable({ "iDisplayLength": 50 });
		$("#adminAttendanceForm").DataTable({ "iDisplayLength": 50 });
		$("#tblEditAttendance").DataTable({ "iDisplayLength": 50, "order" : [[ 0, "desc" ]] })
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
					<li><a href="#tabs-2">Loot Controls</a></li>
					<li><a href="#tabs-3">Daily Raid Attendance</a></li>
					<li><a href="#tabs-4">Edit Attendance Records</a></li>
				</ul>
				<div id="tabs-1">
					<div id="addPlayerForm" align="center">
						<label for="txtAdminAddPlayer" style="display: inline-block;">Add New Player:</label>
						<input id="txtAdminAddPlayer" type="text" maxlength="12" placeholder="Raider Name..." />
						<select id="slAdminAddPlayer">
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
						<button id="btnAdminAddPlayer">Add</button>
					</div> <br />
					<table id="adminPlayers" class="nowrap compact" cellspacing="0" width="100%" style="background: #272822 !important;">
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
				<div id="tabs-2"></div>
				<div id="tabs-3">
					<div style="display: inline-block; float: right;">
						<button id="btnSaveAttendance">Save</button>
					</div> <br /> <br />
					<div id="divNewAttendance">
						<table id="adminAttendanceForm" class="nowrap compact" cellspacing="0" width="100%" style="background: #272822; color: #FFFFFF">
							<thead>
								<tr>
									<th>Name</th>
									<th>Class</th>
									<th>Points<br /><div id="divNewAttBulk"></div></th>
									<th>Options</th>
								</tr>
							</thead>
							<tbody>
								<?php $blah = new WRM(); echo $blah->CreateAttendanceForm(); ?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="tabs-4">
					<div style="display: inline-block; float: right;">
						<button id="btnSaveEditAttendance">Save</button>
					</div> <br /> <br />
					<table id="tblEditAttendance" class="nowrap compact" cellspacing="0" width="100%" style="background: #272822 !important; color: #FFFFFF">
						<thead>
							<tr>
								<th>Row</th>
								<th>Name</th>
								<th>Class</th>
								<th>Date</th>
								<th>Points</th>
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