<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles) || die()); ?>
<div id="EditAttndCtrls" align="center">
	<label for="txtEditAttnd" style="display: inline-block;">Manual Attendance Entry:</label>
	<input id="txtEditAttnd" type="text" maxlength="12" placeholder="Player Name or ID" />
	<div id="EditAttndSlider" style="display: inline-block; margin: 0px 6px 0px 6px;"></div>
	<input type="text" id="dpEditAttnd" placeholder="yyyy/mm/dd" maxlength="10">
	<button id="btnEditAttnd">Add</button>
</div>

<table id="tblEditAttnd" class="nowrap compact wrm">
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
        <?php
        $factory = new DAOFactory();
	    $data = $factory->GetAttndDAO()->GetAll();
        if($data != NULL) {
            foreach($data as $row) {
                echo "<tr>"
                    ."<td>$row->ID</td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->PlayerName</span></td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->ClassName</span></td>"
                    ."<td><div id=\"divEditAttSl".$row->ID."\">$row->Points</div></td>"
                    ."<td>$row->Date</td>"
                    ."<td><button value=\"$row->ID\" class=\"rmEditAttnd\">DELETE</button>"
                    .    "<button class=\"editEditAttnd\">EDIT</button></td>"
                    ."</tr>";
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        $("#tblEditAttnd").DataTable({ "iDisplayLength": 15, "order": [[0, "desc"]] });

        $("#btnEditAttnd").click(function () {
            var name, points, date, month, day;

            // fill variables
            name = $.trim($('#txtEditAttnd').val()).toLowerCase();
            name = name.charAt(0).toUpperCase() + name.slice(1);
            points = $("#EditAttndSlider").slider("value");
            date = $("#dpEditAttnd").val();

            // error checking
            if (!(/^([A-Za-z]+)$/.test(name) || /^(\d+)$/.test(name))) { alert("Name field isn't a player name or ID."); return; }
            if (!/^(\d{4}[\/-]\d{2}[\/-]\d{2})$/.test(date)) { alert("Date isn't in proper format (yyyy/mm/dd)."); return; }

            // disable the fields so the user doesn't change them while the request is being processed
            $('#txtEditAttnd').prop("disabled", true);
            $('#EditAttndSlider').prop("disabled", true);
            $('#dpEditAttnd').prop("disabled", true);
            $('#btnEditAttnd').prop("disabled", true);

            // make the call
            $.ajax({
                url: ajax_object.ajax_url,
                type: "POST",
                data: { "action": "wro_addattnd", "name": name, "points": points, "date": date },
                success: function (response, status, junk) {
                    if (response.search("ERROR") == -1) {
                        name = $("#txtEditAttnd").val('');
                        points = $("#EditAttndSlider").slider("value", 1);
                        date = $("#dpEditAttnd").val();
                    }
                    else alert(response);
                },
                complete: function () {
                    $('#txtEditAttnd').prop("disabled", false);
                    $('#EditAttndSlider').prop("disabled", false);
                    $('#dpEditAttnd').prop("disabled", false);
                    $('#btnEditAttnd').prop("disabled", false);
                }
            });
        });

        $("#tblEditAttnd").on("click", ".rmEditAttnd", function () {
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
            Admin_RmTableRow("#tblEditAttnd", "wro_rmattnd", rowId, message, nRow, button);
        });

        $("#tblEditAttnd").on("click", ".editEditAttnd", function () {
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
                open: function () {
                    CreateAttndSlider("#boxpoints");
                    CreateDatePicker("#boxdate", rowDate);

                    $("#boxpoints").slider("value", parseFloat($(row).find('td:eq(3)').text()));
                    $("#boxdate").blur();
                },
                close: function () {
                    $(button).prop("disabled", false);
                    $(this).dialog("destroy").remove();
                },
                buttons: {
                    "Save Changes": function () {
                        var points = $("#boxpoints").slider("value"), date = $("#boxdate").val();
                        $.ajax({
                            url: ajax_object.ajax_url,
                            type: "POST",
                            data: { "action": "wro_updateattnd", "id": rowId, "points": points, "date": date },
                            success: function (response, status, junk) {
                                if (response.search("ERROR") != -1) alert(response);
                            },
                            complete: function () { $(button).prop("disabled", false); }
                        });
                        $(this).dialog("destroy").remove();
                    },
                    "Cancel": function () {
                        $(button).prop("disabled", false);
                        $(this).dialog("destroy").remove();
                    }
                }
            });
        });

        CreateDatePicker("#dpEditAttnd");
        CreateAttndSlider("#EditAttndSlider");
    });
</script>