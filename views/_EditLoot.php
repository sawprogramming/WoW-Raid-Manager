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
                    ."<td><button class=\"rmLoot\">DELETE</button></td>"
                    ."</tr>";
            }
        }
        ?>
    </tbody>
</table>