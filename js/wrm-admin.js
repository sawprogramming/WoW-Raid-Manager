// General functions
function Admin_RmTableRow(tableId, actionName, primaryKey, message, nRow, buttonId) {
	$("<div>" + message + "</div>").dialog({
		title: "Confirm Removal",
		resizable: false,
		modal: true,
		close: function() { 
			$(buttonId).prop("disabled", false);
			$(this).dialog("destroy").remove();
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

				$(this).dialog("destroy").remove();
			},
			"Cancel" : function() {
				$(buttonId).prop("disabled", false);
				$(this).dialog("destroy").remove();
			}
		}
	});
}
function Admin_AddClickHandlers() {
	Admin_AddPlayer();
	Admin_AddEditAttnd();
	Admin_RmPlayer();
	Admin_RmEditAttnd();
	Admin_RmLoot();
	Admin_AddRaidAttnd();
	Admin_EditEditAttnd();

	$(".delNewAtt").click(function() {
		var row = $(this).closest('tr'); 
		var nRow = row[0];
		$("#tblRaidAttendance").dataTable().dataTable().fnDeleteRow(nRow);
	});

	$("#btnManualSql").click(function() {
		$.ajax({
			url: ajax_object.ajax_url,
			data: { "action": "wrm_freesql", "sql": $("#txtManualSql").val() },
			success: function(response, status, junk) {
				$("#txtManualSql").val("");
				$(response).dialog({
					title: "Query Results",
					resizable: false,
					width: 800,
					modal: true,
					buttons: { "Ok" : function() {	$(this).dialog("destroy").remove();	} }
				});
			}
		});
	});

	$.ajax({
		url: ajax_object.ajax_url,
		type: "GET",
		data: { "action": "wrm_raids" },
		success: function(response, status, junk) {
			var raid, raids = JSON.parse(response);

			// clear the options
			$("#slRmRaid option").remove();
			$("#slRmRaid").append($("<option></option>").val(0).text("Raid Name..."));

			// add the raids
			$.each(raids, function(index, value) {
				$("#slRmRaid").append($("<option></option>").val(value.ID).text(value.Name));
			});
		}
	});
}
function Admin_AddSliders() {
	$("#tblRaidAttendance > tbody > tr").each(function(index) {
		Admin_CreateAttndSlider("#" + $("td:eq(2) div", this).attr("id"));
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

	Admin_CreateAttndSlider("#EditAttndSlider");
}
function Admin_AddDatePickers() {
	Admin_CreateDatePicker("#dpEditAttnd");
	Admin_CreateDatePicker("#dpRaidAttnd");
}
function Admin_CreateAttndSlider(id) {
	$(id).slider({
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
	}).addClass("present");
}
function Admin_CreateDatePicker(id, date) {
	var today = new Date();

	$(id).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "yy-mm-dd",
    }).datepicker("setDate", date == null ? today : date);
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
	$("#tblEditPlayers").on("click", ".del", function() {
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
	$("#tblEditLoot").on("click", ".rmLoot", function() {
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

// Daily Raid Attendance functions
function Admin_AddRaidAttnd() {
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
			data: { 'action': 'wrm_addgrpatt', 'results': results, 'date': $("#dpRaidAttnd").val() },
			complete: function() {
				$('#btnSaveAttendance').removeAttr("disabled");
			}
		});
	});
}

// Edit Attendance Records functions
function Admin_AddEditAttnd() {
	$("#btnEditAttnd").click(function() {
		var name, points, date, month, day;

		// fill variables
		name = $.trim($('#txtEditAttnd').val()).toLowerCase();
		name = name.charAt(0).toUpperCase() + name.slice(1);
		points = $("#EditAttndSlider").slider("value");
		date = $("#dpEditAttnd").val();

		// error checking
		if(!(/^([A-Za-z]+)$/.test(name) || /^(\d+)$/.test(name))) { alert("Name field isn't a player name or ID."); return; } 
		if(!/^(\d{4}[\/-]\d{2}[\/-]\d{2})$/.test(date)) { alert("Date isn't in proper format (yyyy/mm/dd)."); return; }

		// disable the fields so the user doesn't change them while the request is being processed
		$('#txtEditAttnd').prop("disabled", true);
		$('#EditAttndSlider').prop("disabled", true);
		$('#dpEditAttnd').prop("disabled", true);
		$('#btnEditAttnd').prop("disabled", true);

		// make the call
		$.ajax({
			url: ajax_object.ajax_url,
			type: "POST",
			data: { "action": "wrm_addattnd", "name": name, "points": points, "date": date },
			success: function(response, status, junk) {
				if(response.search("ERROR") == -1) {
					name = $("#txtEditAttnd").val('');
					points = $("#EditAttndSlider").slider("value", 1);
					date = $("#dpEditAttnd").val();
				}
				else alert(response);
			},
			complete: function() {
				$('#txtEditAttnd').prop("disabled", false);
				$('#EditAttndSlider').prop("disabled", false);
				$('#dpEditAttnd').prop("disabled", false);
				$('#btnEditAttnd').prop("disabled", false);
			}
		});
	});
}
function Admin_RmEditAttnd() {
	$("#tblEditAttnd").on("click", ".rmEditAttnd", function() {
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
function Admin_EditEditAttnd() {
	$("#tblEditAttnd").on("click", ".editEditAttnd", function() {
		var row, nRow, rowId, plName, plClass, rowDate, button = this, html;

		// disable the button so the user doesn't resend the request
		$(button).prop("disabled", true);

		// get the info from the row
		row = $(button).closest('tr');
		nRow = row[0];
		rowId = $(row).find('td:eq(0)').text();
		plName = $(row).find('td:eq(1)').text();
		plClass = $(row).find('td:eq(2) span').attr("class");
		rowDate = $(row).find('td:eq(4)').text();

		// build edit box
		html = "<div>" 
			+ "<table class=\"wrm\" style=\"width: 100%\">"
			+ "<tr><td>Row:</td><td>" + rowId + "</td></tr>"
			+ "<tr><td>Name:</td><td><span class=\"" + plClass + "\">" + plName + "</span></td></tr>"
			+ "<tr><td>Points:</td><td><div id=\"boxpoints\"></div></td></tr>"
			+ "<tr><td>Date:</td><td><input id=\"boxdate\"></td></tr>"
			+ "</table>"
			+ "</div>";

		$(html).dialog({
			title: "Confirm Removal",
			resizable: false,
			width: 350,
			modal: true,
			open: function() {
				Admin_CreateAttndSlider("#boxpoints");
				Admin_CreateDatePicker("#boxdate", rowDate);

				$("#boxpoints").slider("value", parseFloat($(row).find('td:eq(3)').text()));
				$("#boxdate").blur();
			},
			close: function() { 
				$(button).prop("disabled", false);
				$(this).dialog("destroy").remove();
			},
			buttons: {
				"Save Changes" : function() {
					var points = $("#boxpoints").slider("value"), date = $("#boxdate").val();
					$.ajax({
						url: ajax_object.ajax_url,
						type: "POST",
						data: { "action": "wrm_editattnd", "id": rowId, "points": points, "date": date },
						success: function(response, status, junk) {
							if(response.search("ERROR") != -1) alert(response);
						},
						complete: function() { $(button).prop("disabled", false); }
					});
					$(this).dialog("destroy").remove();
				},
				"Cancel" : function() {
					$(button).prop("disabled", false);
					$(this).dialog("destroy").remove();
				}
			}
		});
	});
}