// General functions
function Admin_RmTableRow(tableId, actionName, primaryKey, message, nRow, buttonId) {
	$("<div>" + message + "</div>").dialog({
		title: "Confirm Removal",
		resizable: false,
		modal: true,
		close: function() { 
			$(buttonId).prop("disabled", false);
			$(this).dialog("close"); 
		},
		buttons: {
			"Remove" : function() {
				$.ajax({
					url: ajax_object.ajax_url,
					type: 'POST',
					data: { 'action': actionName, 'id': primaryKey },
					success: function() { 
						$(tableId).dataTable().dataTable().fnDeleteRow(nRow);
					},
					complete: function() { $(buttonId).prop("disabled", false); }
				});

				$(this).dialog("close");
			},
			"Cancel" : function() {
				$(this).dialog("close");
				$(buttonId).prop("disabled", false);
			}
		}
	});
}
function Admin_AddClickHandlers() {
	Admin_AddPlayer();
	Admin_RmPlayer();
	Admin_RmEditAttnd();
	Admin_RmLoot();

	$(".delNewAtt").click(function() {
		var row = $(this).closest('tr'); 
		var nRow = row[0];
		$("#tblRaidAttendance").dataTable().dataTable().fnDeleteRow(nRow);
	});

	$("#btnSaveAttendance").click(function() {
		var results = [], pid, ppoints;
		
		// disable button so the user doesn't resend the request
		$('#btnSaveAttendance').attr("disabled", "disabled");

		// get the attendance points for each player
		$("#tblRaidAttendance > tbody > tr").each(function (index) {
			pid = $("td:eq(3) button", this).val();
			ppoints = $("#" + $("td:eq(2) div", this).attr("id")).slider("value");

			results[index] = { id: pid, points: ppoints };
		});

		// make the ajax call
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: { 'action': 'wrm_addgrpatt', 'results': results },
			complete: function() {
				$('#btnSaveAttendance').removeAttr("disabled");
			}
		});
	});
}
function Admin_AddSliders() {
	$("#tblRaidAttendance > tbody > tr").each(function(index) {
		var sliderId = $("td:eq(2) div", this).attr("id");

		$('#' + sliderId).slider({
			range: "min",
			value: 1,
			min: 0,
			max: 1,
			step: 0.25,
			slide: function (event, ui) {
				switch(ui.value) {
					case 0.25: $(this).removeClass("absent mlate slate present").addClass("llate"); break;
					case 0.5:  $(this).removeClass("absent llate slate present").addClass("mlate"); break;
					case 0.75: $(this).removeClass("absent llate mlate present").addClass("slate"); break;
					case 1:    $(this).removeClass("absent llate mlate slate").addClass("present"); break;
				}
			},
			change: function (event, ui) {
				switch(ui.value) {
					case 0.25: $(this).removeClass("absent mlate slate present").addClass("llate"); break;
					case 0.5:  $(this).removeClass("absent llate slate present").addClass("mlate"); break;
					case 0.75: $(this).removeClass("absent llate mlate present").addClass("slate"); break;
					case 1:    $(this).removeClass("absent llate mlate slate").addClass("present"); break;
				}
			}
		});
		$('#' + sliderId).addClass("present");
	});

	$("#divNewAttBulk").slider({
		range: "min",
		value: 1,
		min: 0,
		max: 1,
		step: 0.25,
		slide: function (event, ui) {
			switch(ui.value) {
				case 0.25: $(this).removeClass("absent mlate slate present").addClass("llate"); break;
				case 0.5:  $(this).removeClass("absent llate slate present").addClass("mlate"); break;
				case 0.75: $(this).removeClass("absent llate mlate present").addClass("slate"); break;
				case 1:    $(this).removeClass("absent llate mlate slate").addClass("present"); break;
			}

			$("#tblRaidAttendance > tbody > tr").each(function() {
				 $("#" + $("td:eq(2) div", this).attr("id")).slider("value", ui.value);
			});
		}
	}).addClass("present");
}

// Add/Remove Players functions
function Admin_AddPlayer() {
	$('#btnAddPlayer').click(function() {
		var name, classId, className, cssClass, table;

		// fill variables
		name = $.trim($('#txtAddPlayer').val()).toLowerCase();
		classId = parseInt($('#slAddPlayer').val());
		table = $("#tblEditPlayers").DataTable();

		// error checking
		if(classId == 0) { alert("You must select a class for the player."); return; }
		if(name.length < 3) { alert("Name is too short to be a proper name in WoW"); return; }
		if(!/^([A-Za-z]+)$/.test(name)) { alert("Name contains non-alphabetical characters."); return; }

		// fill other variables now that basic error checking is done
		name = name.charAt(0).toUpperCase() + name.slice(1);
		className = ClassIdToName(classId);
		cssClass = ClassIdToCss(classId);

		// disable the fields so the user doesn't change them while the request is being processed
		$('#txtAddPlayer').prop("disabled", true);
		$('#slAddPlayer').prop("disabled", true);
		$('#btnAddPlayer').prop("disabled", true);

		// make the ajax call
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: { 'action': 'wrm_addplayer', 'name': name, 'classId': classId },
			success: function (response, status, junk) {
				$('#txtAddPlayer').val('');
				$('#slAddPlayer').val('0');

				table.row.add([
					response,
					"<span class=\"" + cssClass + "\">" + name + "</span>",
					"<span class=\"" + cssClass + "\">" + className + "</span>",
					"<button class=\"del\">DELETE</button>"
					]).draw().nodes().to$().attr("style", "background: rgba(0,0,0,0);");
			}, 
			complete: function () {
				$('#txtAddPlayer').prop("disabled", false);
				$('#slAddPlayer').prop("disabled", false);
				$('#btnAddPlayer').prop("disabled", false);
			}
		});
	});
}
function Admin_RmPlayer() {
	$(".del").click(function() {
		var row, nRow, message, plName, plId, plClass, button = this;

		// disable the button so the user doesn't resend the request
		$(button).prop("disabled", true);

		// get the info from the row
		row = $(button).closest('tr');
		nRow = row[0];
		plId = $(row).find('td:eq(0)').text();
		plName = $(row).find('td:eq(1)').text();
		plClass = $(row).find('td:eq(2) span').attr("class");

		// build the confirmation message
		message = "Are you sure want to remove " +
		          "<span class=\"" + plClass + "\">" + plName + "</span>?";

		// show confirmation box
		Admin_RmTableRow("#tblEditPlayers", "wrm_rmplayer", plId, message, nRow, button);
	});
}

// Edit Loot Records functions
function Admin_RmLoot() {
	$(".rmLoot").click(function() {
		var row, nRow, message, rowId, plName, plClass, rowDate, plItem, button = this;

		// disable the button so the user doesn't resend the request
		$(button).prop("disabled", true);

		// get the info from the row
		row = $(button).closest('tr');
		nRow = row[0];
		rowId = $(row).find('td:eq(0)').text();
		plName = $(row).find('td:eq(1)').text();
		plClass = $(row).find('td:eq(2) span').attr("class");
		plItem = $(row).find('td:eq(3)').html();
		rowDate = $(row).find('td:eq(5)').text();

		// build the confirmation message
		message = "Are you sure want to remove the loot record for " +
		          "<span class=\"" + plClass + "\">" + plName + "</span> " + 
		          "on " + rowDate + " for " + plItem + "?";

		// show confirmation box
		Admin_RmTableRow("#tblEditLoot", "wrm_rmloot", rowId, message, nRow, button);
	});
}

// Edit Attendance Records functions
function Admin_RmEditAttnd() {
	$(".rmEditAttnd").click(function() {
		var row, nRow, message, rowId, plName, plClass, rowDate, button = this;

		// disable the button so the user doesn't resend the request
		$(button).prop("disabled", true);

		// get the info from the row
		row = $(button).closest('tr');
		nRow = row[0];
		rowId = $(row).find('td:eq(0)').text();
		plName = $(row).find('td:eq(1)').text();
		plClass = $(row).find('td:eq(2) span').attr("class");
		rowDate = $(row).find('td:eq(4)').text();

		// build the confirmation message
		message = "Are you sure want to remove the attendance record for " +
		          "<span class=\"" + plClass + "\">" + plName + "</span> " + 
		          "on " + rowDate + "?";

		// show confirmation box
		Admin_RmTableRow("#tblEditAttnd", "wrm_rmattnd", rowId, message, nRow, button);
	});
}