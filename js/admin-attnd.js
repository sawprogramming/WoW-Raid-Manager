// Setup functions
function Admin_SetupAttnd() {
	SetupAddRaidAttnd();
	SetupAddEditAttnd();
	SetupRmEditAttnd();
	SetupEditEditAttnd();

	$("#tblRaidAttendance").DataTable({ "iDisplayLength": 50 });
	$("#tblEditAttnd").DataTable({ "iDisplayLength": 15, "order" : [[ 0, "desc" ]] });
}

// Daily Raid Attendance functions
function SetupAddRaidAttnd() {
	$("#tblRaidAttendance > tbody > tr").each(function(index) {
		CreateAttndSlider("#" + $("td:eq(2) div", this).attr("id"));
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

	$(".delNewAtt").click(function() {
		var row = $(this).closest('tr'); 
		var nRow = row[0];
		$("#tblRaidAttendance").dataTable().dataTable().fnDeleteRow(nRow);
	});

	CreateDatePicker("#dpRaidAttnd");
}

// Edit Attendance Records functions
function SetupAddEditAttnd() {
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
			success: function (response, status, junk) {
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
function SetupRmEditAttnd() {
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
function SetupEditEditAttnd() {
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
				CreateAttndSlider("#boxpoints");
				CreateDatePicker("#boxdate", rowDate);

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

	CreateDatePicker("#dpEditAttnd");
	CreateAttndSlider("#EditAttndSlider");
}