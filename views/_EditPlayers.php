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