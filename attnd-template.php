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
			        <ul class="player-columns">
			        	<li ng-repeat="entity in model.BreakdownEntities | orderBy: 'Name'">
			        		<span class="pull-right"><span ng-bind="entity.AllTime"></span>%</span>
			        		<span ng-class="entity.ClassID" ng-bind="entity.Name" ng-click="ShowDetails(entity)"></span>
			        	</li>
			        </ul>
		    	</div>
		    </div>

	        <div class="panel panel-epic">
	        	<div class="panel-heading">
	        		Raid Loot
	        	</div>
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
				            <td><span ng-bind="row.Date"></span></td>
				        </tr>
				    </tbody>
		        </table>
		        <div class="panel-footer clearfix">
		        	<dir-pagination-controls on-page-change="RefreshLootLinks()" pagination-id="tblRaidLoot" class="pull-right"></dir-pagination-controls>
	        	</div>
	        </div>

			<script type="text/ng-template" id="playerBreakdownModal.html">
				<div class="modal-body">
					<button type="button" class="close" aria-label="Close" ng-click="cancel()">
						<span aria-hidden="true">&times;</span>
					</button>
			    	<div class="row">
			    		<div class="col-md-2">
			    			<figure style="text-align: center;">
			    				<img ng-src="http://us.battle.net/static-render/us/{{model.BreakdownEntity.Icon}}" height="128" width="128"/>
			    				<figcaption>
			    					<b ng-class="model.BreakdownEntity.ClassID" ng-bind="model.BreakdownEntity.Name"></b>
								</figcaption>
							</figure>
			    		</div>
			    		<div class="col-md-2">
			    			<b>Attendance Stats</b>
							<hr class="no-margin" />
			    			<p class="half-margin">
			    				<b>2 Week:</b>
			    				<span class="pull-right">
			    					<span ng-bind="model.BreakdownEntity.TwoWeek"></span>%
								</span>
							</p>
			    			<p class="half-margin">
			    				<b>Month:</b>
			    				<span class="pull-right">
			    					<span ng-bind="model.BreakdownEntity.Month"></span>%
								</span>
							</p>
			    			<p class="half-margin">
			    				<b>All Time:</b>
			    				<span class="pull-right">
			    					<span ng-bind="model.BreakdownEntity.AllTime"></span>%
								</span>
							</p>
						</div>
			    		<div class="col-md-8">
			    			<div id="calendar_basic" align="left"></div>
			    		</div>
			    	</div>
			    </div>
			    <table class="table table-striped">
			        <thead>
			        	<tr>
			        		<th>Date</th>
			        		<th>Points</th>
			        		<th>Options</th>
			        	</tr>
					</thead>
					<tbody>
				        <tr dir-paginate="entity in model.AttendanceEntities | itemsPerPage: 5">
				            <td><span ng-bind="entity.Date"></span></td>
				            <td><span ng-bind="entity.Points"></span></td>
				            <td>
			    				<div class="btn-sm btn-danger pull-right" popover-template="'disputeTemplate.html'" popover-placement="left" ng-click="Dispute(entity)">
				    				Dispute &ne;
				    			</div>
			    			</td>
				        </tr>
			        </tbody>
			    </table>
			    <dir-pagination-controls class="pull-right"></dir-pagination-controls><br /><br /><br />
			</script>
			<script type="text/ng-template" id="disputeTemplate.html">
				<form style="width: 350px;" name="formDispute">
					<label class="control-label">Arrival Time:</label>
					<div class="btn-group btn-group-sm">
		                <label class="btn btn-danger"  ng-model="entity.Dispute.Points" btn-radio="0.00">Absent</label>
		                <label class="btn btn-default" ng-model="entity.Dispute.Points" btn-radio="0.25">10:00+</label>
		                <label class="btn btn-warning" ng-model="entity.Dispute.Points" btn-radio="0.50">9:00+</label>
		                <label class="btn btn-primary" ng-model="entity.Dispute.Points" btn-radio="0.75">7:40+</label>
		                <label class="btn btn-success" ng-model="entity.Dispute.Points" btn-radio="1.00">Present</label>
					</div>
					<div class="help-block error-text" ng-if="formDispute.$submitted && entity.Dispute.Points == null">
						Please indicate when you arrived.
					</div><br /><br />

					<div class="form-group">
						<label class="control-label">Comment:</label>
						<textarea rows="5" class="form-control" ng-model="entity.Dispute.Comment" maxlength="255"></textarea>
					</div>
					<button class="btn-sm btn-primary pull-right" ng-click="SubmitDispute(formDispute, entity)">Submit</button>
				</form>
			</script>	
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