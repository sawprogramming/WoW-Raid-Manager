<?php if(array_intersect(array('administrator', 'keymaster'), wp_get_current_user()->roles) || die()); ?>
<fieldset>
	<legend>Raid Table Controls</legend>
	<table>
		<tbody>
			<tr>
				<td>Add Raid:</td>
				<td><input id="txtAddRaid" type="text" placeholder="Raid Name..."></td>
				<td><button id="btnAddRaid">Add</button></td>
			</tr>
			<tr>
				<td>Remove Raid:</td>
				<td><select id="slRmRaid"></select></td>
				<td><button id="btnRmRaid">Delete</button></td>
			</tr>
		</tbody>
	</table>
</fieldset>

<script type="text/javascript">
    $(document).ready(function () {
        RefreshSlRaid();

        $("#btnAddRaid").click(function () {
            // disable fields while processing
            $("#slRmRaid").prop("disabled", true);
            $("#btnRmRaid").prop("disabled", true);
            $("#txtAddRaid").prop("disabled", true);
            $("#btnAddRaid").prop("disabled", true);

            // delete raid and refresh select menu if success
            $.ajax({
                url: ajax_object.ajax_url,
                type: "POST",
                data: { "action": "wro_addraid", "name": $("#txtAddRaid").val() },
                success: function (response, status, junk) {
                    RefreshSlRaid();
                    $("#txtAddRaid").val('');
                },
                complete: function () {
                    $("#slRmRaid").prop("disabled", false);
                    $("#btnRmRaid").prop("disabled", false);
                    $("#txtAddRaid").prop("disabled", false);
                    $("#btnAddRaid").prop("disabled", false);
                }
            });
        });

        $("#btnRmRaid").click(function () {
            // disable fields while processing
            $("#slRmRaid").prop("disabled", true);
            $("#btnRmRaid").prop("disabled", true);

            // delete raid and refresh select menu if success
            $.ajax({
                url: ajax_object.ajax_url,
                type: "POST",
                data: { "action": "wro_rmraid", "id": parseInt($('#slRmRaid').val(), 10) },
                success: function (response, status, junk) { RefreshSlRaid(); },
                complete: function () {
                    $("#slRmRaid").prop("disabled", false);
                    $("#btnRmRaid").prop("disabled", false);
                }
            });
        });
    });

    function RefreshSlRaid() {
        // disable fields while processing
        $("#slRmRaid").prop("disabled", true);
        $("#btnRmRaid").prop("disabled", true);

        $.ajax({
            url: ajax_object.ajax_url,
            type: "GET",
            data: { "action": "wro_getraids" },
            success: function (response, status, junk) {
                var raid, raids = JSON.parse(response);

                // clear the options
                $("#slRmRaid option").remove();
                $("#slRmRaid").append($("<option></option>").val(0).text("Raid Name..."));

                // add the raids
                $.each(raids, function (index, value) {
                    $("#slRmRaid").append($("<option></option>").val(value.ID).text(value.Name));
                });
            },
            complete: function() {
                $("#slRmRaid").prop("disabled", false);
                $("#btnRmRaid").prop("disabled", false);
            }
        });
    }
</script>