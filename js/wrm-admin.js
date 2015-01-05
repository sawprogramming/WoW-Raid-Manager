function Admin_AddClickHandlers() {
	Admin_AddPlayer();
	Admin_RmPlayer();

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

function Admin_AddPlayer() {
	$('#btnAdminAddPlayer').click(function() {
		var name = $.trim($('#txtAdminAddPlayer').val());
		var classId = $('#slAdminAddPlayer').val();

		// error checking
		if(classId == 0) { alert("You must select a class for the player."); return; }
		if(name.length < 3) { alert("Name is too short to be a proper name in WoW"); return; }
		if(!/^([A-Za-z]+)$/.test(name)) { alert("Name contains non-alphabetical characters."); return; }

		// disable the fields so the user doesn't change them while the request is being processed
		$('#txtAdminAddPlayer').attr("disabled", "disabled");
		$('#slAdminAddPlayer').attr("disabled", "disabled");
		$('#btnAdminAddPlayer').attr("disabled", "disabled");

		// make the ajax call
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: { 'action': 'wrm_addplayer', 'name': name.charAt(0).toUpperCase() + name.slice(1), 'classId': classId },
			success: function () {
				$('#txtAdminAddPlayer').val('');
				$('#slAdminAddPlayer').val('0');
			}, 
			complete: function () {
				$('#txtAdminAddPlayer').removeAttr("disabled");
				$('#slAdminAddPlayer').removeAttr("disabled");
				$('#btnAdminAddPlayer').removeAttr("disabled");
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
		$("<div>" + message + "</div>").dialog({
			title: "Confirm Removal",
			resizable: false,
			modal: true,
			close: function() { 
				$(button).prop("disabled", false);
				$(this).dialog("close"); 
			},
			buttons: {
				"Remove" : function() {
					$.ajax({
						url: ajax_object.ajax_url,
						type: 'POST',
						data: { 'action': 'wrm_delplayer', 'id': plId },
						success: function() { 
							$("#adminPlayers").dataTable().dataTable().fnDeleteRow(nRow);
						},
						complete: function() { $(button).prop("disabled", false); }
					});

					$(this).dialog("close");
				},
				"Cancel" : function() {
					$(this).dialog("close");
					$(button).prop("disabled", false);
				}
			}
		});
	});
}