<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles) || die()); ?>
<div id="addPlayerForm" align="center">
	<label for="txtAddPlayer" style="display: inline-block;">Add New Player:</label>
	<input id="txtAddPlayer" type="text" maxlength="12" placeholder="Raider Name..." />
	<select id="slAddPlayer">
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
	<button id="btnAddPlayer">Add</button>
</div> <br />

<table id="tblEditPlayers" class="nowrap compact wrm">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Class</th>
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
                    ."<td>$player->ID</td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->Name</span></td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($player->ClassID)."\">$player->ClassName</span></td>"
                    ."<td><button class=\"del\">DELETE</button></td>"
                    ."</tr>";
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        $("#tblEditPlayers").DataTable({ "iDisplayLength": 15, "order": [[0, "desc"]] });

        $('#btnAddPlayer').click(function () {
            var name, classId, className, cssClass, table;

            // fill variables
            name = $.trim($('#txtAddPlayer').val()).toLowerCase();
            classId = parseInt($('#slAddPlayer').val());
            table = $("#tblEditPlayers").DataTable();

            // error checking
            if (classId == 0) { alert("You must select a class for the player."); return; }
            if (name.length < 3) { alert("Name is too short to be a proper name in WoW"); return; }
            if (!/^([A-Za-z]+)$/.test(name)) { alert("Name contains non-alphabetical characters."); return; }

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
                data: { 'action': 'wro_addplayer', 'name': name, 'classId': classId },
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


        $("#tblEditPlayers").on("click", ".del", function () {
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
            Admin_RmTableRow("#tblEditPlayers", "wro_rmplayer", plId, message, nRow, button);
        });
    });
</script>