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