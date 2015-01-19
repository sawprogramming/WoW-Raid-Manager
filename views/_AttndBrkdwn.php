<table id="tblUserAttnd" class="nowrap compact wrm">
	<thead>
        <tr>
            <th>Name</th>
            <th>Class</th>
            <th>Last 2 Weeks</th>
            <th>Last 30 Days</th>
            <th>All Time</th>
        </tr>
	</thead>
    <tbody>
        <?php 
        $factory = new DAOFactory();
        
	    $data = $factory->GetAttndDAO()->GetBreakdown();
        if($data != NULL) {
            foreach($data as $player) {
                echo "<tr>"
                    ."<td><span class=\"".WRM_Display::GetClassName($player["ClassID"])."\">".$player["Name"]."</span></td>"
                    ."<td><span class=\"".WRM_Display::GetClassName($player["ClassID"])."\">".$player["ClassName"]."</span></td>"
                    ."<td>".$player["I1"]."%</td>"
                    ."<td>".$player["I2"]."%</td>"
                    ."<td>".$player["I3"]."%</td>"
                    ."</tr>";
            }
        }
        
        echo $html;
        ?>
    </tbody>
</table>