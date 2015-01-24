<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles) || die()); ?>
<div align="center">
	<label for="dpRaidAttnd" style="display: inline-block;">Date:</label>
	<input type="text" id="dpRaidAttnd" placeholder="yyyy/mm/dd" maxlength="10">
	<button id="btnSaveAttendance" style="float: right;">Save</button>
</div> <br />

<table id="tblRaidAttendance" class="nowrap compact wrm">
    <thead>
        <tr>
            <th>Name</th>
            <th>Class</th>
            <th>Points<br /><div id="divNewAttBulk"></div></th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $factory = new DAOFactory();
	    $data = $factory->GetPlayerDAO()->GetAll();
        if($data != NULL) {
            foreach($data as $player) {
                echo "<tr>"
                    ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
                    ."<td><div id=\"divNewAttSl".$player->ID."\"></div></td>"
                    ."<td><button value=\"$player->ID\" class=\"btn-del delNewAtt\"></button></td>"
                    ."</tr>";
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        $("#tblRaidAttendance").DataTable({ "iDisplayLength": 50 });

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
                data: { 'action': 'wro_addgrpatt', 'results': results, 'date': $("#dpRaidAttnd").val() },
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
    });
</script>