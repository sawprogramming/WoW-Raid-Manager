<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles) || die()); ?>
<table id="tblEditLoot" class="nowrap compact wrm">
    <thead>
        <tr>
            <th>Row</th>
            <th>Name</th>
            <th>Class</th>
            <th>Item</th>
            <th>Raid</th>
            <th>Date</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $factory = new DAOFactory();
	    $data = $factory->GetLootItemDAO()->GetAll();
        if($data != NULL) {
            foreach($data as $row) {
                echo "<tr>"
                    ."<td>$row->ID</td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->PlayerName</span></td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->ClassName</span></td>"
                    ."<td><a href=\"".WRM_Display::BuildLootUrl($row->Item)."\"></a></td>"
                    ."<td>$row->RaidName</td>"
                    ."<td>$row->Date</td>"
                    ."<td><button class=\"btn-del rmLoot\"></button></td>"
                    ."</tr>";
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $("#tblEditLoot").on("length.dt", function () { RefreshLootLinks(); });
    $("#tblEditLoot").on("page.dt", function () { RefreshLootLinks(); });
    $("#tblEditLoot").on("search.dt", function () { RefreshLootLinks(); });

    $(document).ready(function () {
        $("#tblEditLoot").DataTable({ "iDisplayLength": 15 });

        $("#tblEditLoot").on("click", ".rmLoot", function () {
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
            Admin_RmTableRow("#tblEditLoot", "wro_rmloot", rowId, message, nRow, button);
        });
    });
</script>