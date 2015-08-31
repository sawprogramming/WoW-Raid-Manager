<?php get_header(); ?>
<div class="content" ng-app="WRO">
    <div id="divUserAttnd" ng-controller="UserUICtrl" class="container">
	    <div class="row">
		    <h1>Raid Information</h1>
		    <div class="panel panel-primary">
		    	<div class="panel-heading">
		    		Attendance
		    	</div>
		    	<div class="panel-body sublime">
		    		<ajax-content status="model.AjaxContent.Breakdown" src="model.BreakdownEntities">
				        <ul class="player-columns">
				        	<li ng-repeat="entity in model.BreakdownEntities | orderBy: 'Name'">
				        		<span class="pull-right"><span ng-bind="entity.AllTime"></span>%</span>
				        		<span ng-class="entity.ClassID" ng-bind="entity.Name" ng-click="ShowDetails(entity)"></span>
				        	</li>
				        </ul>
			        </ajax-content>
		    	</div>
		    </div>

	        <div class="panel panel-epic">
	        	<div class="panel-heading">
	        		Raid Loot
	        	</div>
	        	<ajax-content status="model.AjaxContent.RaidLoot" src="model.RaidLoot">
			        <table class="table">
				        <thead>
				        	<tr>
								<th>Player</th>
								<th>Class</th>
								<th>Item</th>
								<th>Date</th>
				        	</tr>
				        </thead>
					    <tbody>
					        <tr pagination-id="tblRaidLoot" dir-paginate="row in model.RaidLoot | orderBy:'-Date' | itemsPerPage: 10">
					            <td><span ng-class="row.ClassID" ng-bind="row.PlayerName"></span></td>
					            <td><span ng-class="row.ClassID" ng-bind="row.ClassName"></span></td>
					            <td><a ng-href="{{row.Item}}"></a></td>
					            <td><span ng-bind="row.Date | date: 'MM/dd/yyyy'"></span></td>
					        </tr>
					    </tbody>
			        </table>
    		        <div class="panel-footer clearfix">
	        			<dir-pagination-controls on-page-change="RefreshLootLinks()" pagination-id="tblRaidLoot"></dir-pagination-controls>
    				</div>
	        	</ajax-content>
	        </div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load('visualization', '1.1', {packages: ['line']});
</script>
<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<?php get_footer(); ?>