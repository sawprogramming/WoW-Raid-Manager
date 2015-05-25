<table id="tblUserLoot" class="nowrap compact" datatable="ng" dt-options="model.dtOptions">
    <thead>
        <tr>
            <th>Player</th>
            <th>Item</th>
            <th>Raid</th>
            <th>Date</th>
        </tr>
    </thead>
	<tbody>
        <tr ng-repeat="row in model.RaidLoot">
            <td><span ng-class="row.ClassID" ng-bind="row.PlayerName"></span></td>
            <td><a ng-href="{{row.Item}}"></a></td>
            <td><span ng-bind="row.RaidName"></span></td>
            <td><span ng-bind="row.Date"></span></td>
        </tr>
	</tbody>
</table>

<script type="text/javascript">
   $("#tblUserLoot").on("length.dt", function () { RefreshLootLinks(); });
   $("#tblUserLoot").on("page.dt", function () { RefreshLootLinks(); });
   $("#tblUserLoot").on("search.dt", function () { RefreshLootLinks(); });
</script>