// Setup functions
function Admin_SetupPlayer() {
	SetupAddPlayer();
	SetupRmPlayer();

	$("#tblEditPlayers").DataTable({ "iDisplayLength": 15, "order" : [[ 0, "desc" ]] });
}

// Add/Remove Players functions
function SetupAddPlayer() {
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
function SetupRmPlayer() {
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