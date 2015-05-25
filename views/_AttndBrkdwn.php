<table id="tblUserAttnd" class="nowrap compact" datatable="ng" dt-options="model.dtOptions">
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
        <tr ng-repeat="entity in model.BreakdownEntities">
            <td><span ng-class="entity.ClassID" ng-bind="entity.Name"></span></td>
            <td><span ng-class="entity.ClassID" ng-bind="entity.ClassName"></span></td>
            <td><span ng-bind="entity.TwoWeek"></span>%</td>
            <td><span ng-bind="entity.Month"></span>%</td>
            <td><span ng-bind="entity.AllTime"></span>%</td>
        </tr>
    </tbody>
</table>