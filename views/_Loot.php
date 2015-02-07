<table id="tblUserLoot" class="nowrap compact wrm">
    <thead>
        <tr>
            <th>Player</th>
            <th>Item</th>
            <th>Raid</th>
            <th>Date</th>
        </tr>
    </thead>
	<tbody>
        <?php
        $factory = new DAOFactory();
	    $data = $factory->GetLootItemDAO()->GetAll();
        if($data != NULL) {
	        foreach($data as $row) {
	    	    echo "<tr>"
	    	        ."<td><span class=\"".WRM_Display::GetClassName($row->ClassID)."\">$row->PlayerName</span></td>"
	    	        ."<td><a href=\"".WRM_Display::BuildLootUrl($row->Item)."\"></a></td>"
	    	        ."<td>$row->RaidName</td>"
	    	        ."<td>$row->Date</td>"
	    	        ."</tr>";
	        }
        }
        ?>
	</tbody>
</table>

<script type="text/javascript">
    $("#tblUserLoot").on("length.dt", function () { RefreshLootLinks(); });
    $("#tblUserLoot").on("page.dt", function () { RefreshLootLinks(); });
    $("#tblUserLoot").on("search.dt", function () { RefreshLootLinks(); });
</script>