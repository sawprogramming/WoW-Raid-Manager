// Setup functions
function Admin_AddClickHandlers() {
	$("#btnManualSql").click(function() {
		$.ajax({
			url: ajax_object.ajax_url,
			data: { "action": "wro_freesql", "sql": $("#txtManualSql").val() },
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

	$("#btnUpdateGuildLoot").click(function () {
	    $.ajax({
	        url: ajax_object.ajax_url,
	        data: { "action": "wro_updateguildloot" },
	        beforeSend: function (blah, blah) {
	            $("<div>Request sent!</div>").dialog({
	                title: "Acknowledgement",
	                resizble: false,
	                width: 800,
	                modal: true,
	                buttons: { "Ok" : function() {	$(this).dialog("destroy").remove();	} }
	            });
	        }
	    });
	});
}

// UI Creation functions
function CreateAttndSlider(id) {
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
function CreateDatePicker(id, date) {
	var today = new Date();

	$(id).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: "yy-mm-dd",
    }).datepicker("setDate", date == null ? today : date);
}

// Helper functions
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
					success: function (response, status, junk) {
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