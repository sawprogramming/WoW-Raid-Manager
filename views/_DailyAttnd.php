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
                    ."<td><button value=\"$player->ID\" class=\"delNewAtt\">DELETE</button></td>"
                    ."</tr>";
            }
        }
        ?>
    </tbody>
</table>