<?php get_header(); ?>
<?php if(get_option("wro_faction") == "alliance"): ?> 
<div class="content alliance-bg wro" ng-app="WRO">
<?php else: ?>
<div class="content horde-bg wro" ng-app="WRO">
<?php endif; ?>
    <div id="divUserAttnd" ng-controller="UserUICtrl as vm" class="container-fluid">
		<h1>Raid Information</h1>
	    <div class="row bottom-buffer">
	    	<div class="col-md-4">
		    	<range-select class="form-control" ng-model="vm.Range" ng-change="vm.ChangeRange()"></range-select>
		    </div>
		    <div class="col-md-6 col-md-offset-2">
		   		<div class="form-inline pull-right">
				    <div class="form-group">
				    	<label class="control-label">From</label>
						<div class="input-group datepicker-fix">
						    <input type="text" class="form-control date-input" placeholder="mm/dd/yyyy" uib-datepicker-popup
						    	ng-model="vm.Range.StartDate" is-open="fromDate.opened" ng-change="vm.ChangeRange()" ng-model-options="{ debounce: 500 }"/>
							<span class="input-group-btn">
								<button class="btn btn-default" ng-click="fromDate.opened = true">
									<i class="glyphicon glyphicon-calendar"></i>
								</button>
							</span>
						</div>
				    </div>
				    <div class="form-group">
				    	<label class="control-label">to</label>
						<div class="input-group datepicker-fix">
						    <input type="text" class="form-control date-input" placeholder="mm/dd/yyyy" uib-datepicker-popup
						    	ng-model="vm.Range.EndDate" is-open="toDate.opened" ng-change="vm.ChangeRange()" ng-model-options="{ debounce: 500 }"/>
							<span class="input-group-btn">
								<button class="btn btn-default" ng-click="toDate.opened = true">
									<i class="glyphicon glyphicon-calendar"></i>
								</button>
							</span>
						</div>
				    </div>
			    </div>
		    </div>
		</div>
		<div class="row">
			<div class="col-md-12">
			    <div class="panel panel-primary panel-dark">
			    	<div class="panel-heading">
						<span class="glyphicon glyphicon-book"></span> Attendance
					    <div class="btn-group pull-right" uib-dropdown style="margin-left: 5px;">
					    	<button type="button" class="btn btn-xs btn-default" uib-dropdown-toggle>
					    		<span class="glyphicon glyphicon-cog text-black"></span>
					    	</button>
					      	<ul class="uib-dropdown-menu" style="width: 18em">
					        	<li>
					        		<a href="javascript:void(0)">
					        			Show Inactive Players
					        			<input type="checkbox" class="pull-right" ng-model="vm.ShowInactivePlayers">
			        				</a>
				        		</li>
					        	<li>
					        		<a href="javascript:void(0)">
					        			Show Absolute Attendance
					        			<input type="checkbox" class="pull-right" ng-model="vm.ShowAbsoluteAttendance" ng-change="vm.ChangeAbsolute()">
				        			</a>
					        	</li>
                                <li>
					        		<a href="javascript:void(0)">
					        			Show Attendance Counts
					        			<input type="checkbox" class="pull-right" ng-model="vm.ShowCounts" ng-change="vm.ChangeCount()">
				        			</a>
					        	</li>
					     	</ul>
					    </div>
					    <div class="btn-group pull-right" uib-dropdown>
					    	<button type="button" class="btn btn-xs btn-default" uib-dropdown-toggle>
					    		<span ng-switch="vm.OrderMode" class="text-black">
					    			<span ng-switch-when="Name" class="glyphicon glyphicon-sort-by-alphabet"></span>
					    			<span ng-switch-when="-Name" class="glyphicon glyphicon-sort-by-alphabet-alt"></span>
					    			<span ng-switch-when="Average" class="glyphicon glyphicon-sort-by-order"></span>
					    			<span ng-switch-when="-Average" class="glyphicon glyphicon-sort-by-order-alt"></span>
					    			<span ng-switch-default class="glyphicon glyphicon-sort"></span>
					    		</span>
					    	</button>
					      	<ul class="uib-dropdown-menu">
					        	<li><a href="javascript:void(0)" ng-click="vm.OrderMode = 'Name'">Sort A-Z<span class="glyphicon glyphicon-sort-by-alphabet pull-right"></span></a></li>
					        	<li><a href="javascript:void(0)" ng-click="vm.OrderMode = '-Name'">Sort Z-A<span class="glyphicon glyphicon-sort-by-alphabet-alt pull-right"></span></a></li>
					        	<li><a href="javascript:void(0)" ng-click="vm.OrderMode = 'Average'">Sort 0-9<span class="glyphicon glyphicon-sort-by-order pull-right"></span></a></li>
					        	<li><a href="javascript:void(0)" ng-click="vm.OrderMode = '-Average'">Sort 9-0<span class="glyphicon glyphicon-sort-by-order-alt pull-right"></span></a></li>
					      </ul>
					    </div>
			    	</div>
			    	<div class="panel-body sublime">
			    		<ajax-content status="vm.AjaxContent.Breakdown" src="vm.BreakdownEntities">
					        <ul class="player-columns">
					        	<li ng-repeat="entity in vm.BreakdownEntities | inactivePlayers: vm.ShowInactivePlayers | orderBy: vm.OrderMode" ng-class="{ inactive: entity.Active == false }">
					        		<span class="pull-right"><span ng-bind="entity.Metric"></span><span ng-show="!vm.ShowCounts">%</span></span>
					        		<span ng-class="entity.ClassID" ng-bind="entity.Name" ng-click="vm.ShowDetails(entity)"></span>
					        	</li>
					        </ul>
				        </ajax-content>
			    	</div>
			    </div>
		    </div>
	    </div>
	    <div class="row">
	    	<div class="col-md-12">
		        <div class="panel panel-epic">
		        	<div class="panel-heading">
		        		<span class="glyphicon glyphicon-tower"></span> Raid Loot
		        	</div>
		        	<ajax-content status="vm.AjaxContent.RaidLoot" src="vm.RaidLoot">
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
						        <tr pagination-id="tblRaidLoot" dir-paginate="row in vm.RaidLoot | orderBy:'-Date' | itemsPerPage: 10">
						            <td><span ng-class="row.ClassID" ng-bind="row.PlayerName"></span></td>
						            <td><span ng-class="row.ClassID" ng-bind="row.ClassName"></span></td>
						            <td><a ng-href="{{row.Item}}"></a></td>
						            <td><span ng-bind="row.Date | date: 'MM/dd/yyyy'"></span></td>
						        </tr>
						    </tbody>
				        </table>
	    		        <div class="panel-footer clearfix">
		        			<dir-pagination-controls on-page-change="vm.RefreshLootLinks()" pagination-id="tblRaidLoot"></dir-pagination-controls>
	    				</div>
		        	</ajax-content>
		        </div>
	        </div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load('visualization', '1.1', {packages: ['line']});
</script>
<script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<script type="text/javascript" src="http://wow.zamimg.com/widgets/power.js"></script>
<?php get_footer(); ?>