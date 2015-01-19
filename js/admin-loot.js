// Setup functions
function Admin_SetupLoot() {
	SetupRmLoot();

	$("#tblEditLoot").DataTable({ "iDisplayLength": 15 });
}

// Edit Loot Records functions
function SetupRmLoot() {
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