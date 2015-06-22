app.controller("UserUICtrl", function($scope, $modal, AttendanceSvc, RaidLootSvc) {
	$scope.model = {
		BreakdownEntities: [],
		RaidLoot: []
	};
	var vm = $scope.model;

    function RefreshLootLinks() {
        setTimeout(function () {
            $WowheadPower.refreshLinks();
        }, 25);
    }
    $scope.RefreshLootLinks = RefreshLootLinks;

	$scope.populate = function() {
		AttendanceSvc.GetBreakdown().then(
			function(response) {
				vm.BreakdownEntities = response.data;

				// transform ClassID to CSS class name
				angular.forEach(vm.BreakdownEntities, function(value, key) {
					value.ClassID = ClassIdToCss(parseInt(value.ClassID));
				});
			},
			function(errmsg) {

			}
		);

		RaidLootSvc.GetAll().then(
			function(response) {
				vm.RaidLoot = response.data;

				// transform data into proper stuff for view
				angular.forEach(vm.RaidLoot, function(value, key) {
					value.ClassID = ClassIdToCss(parseInt(value.ClassID));
					value.Item = BuildLootURL(value.Item);
				});


			   RefreshLootLinks();
			},
			function(errmsg) {

			}
		);
	};
	$scope.populate();

	$scope.ShowDetails = function(item) {
		var modalInstance = $modal.open({
			templateUrl: 'playerBreakdownModal.html',
			controller: 'PlayerBreakdownModalCtrl',
			size: 'lg',
			resolve: {
				entity: function() {
					return item;
				}
			}
		});
	};
});

app.controller("PlayerBreakdownModalCtrl", function($scope, $modalInstance, entity, AttendanceSvc) {
	$scope.model = {
		BreakdownEntity: entity,
		AttendanceEntities: null,
		ChartData: null
	};
	var vm = $scope.model;

	$scope.populate = function() {
		var chart, chartOptions; 

		AttendanceSvc.GetAllById(entity.ID).then(
			function(response) {
				vm.AttendanceEntities = response.data;
			},
			function(errmsg) {

			}
		);

		AttendanceSvc.GetChart(entity.ID).then(
			function(response) {
				var sqlDate;
				var temp = response.data;

				// setup chart data
				vm.ChartData = new google.visualization.DataTable();
		      	vm.ChartData.addColumn('date', 'Date');
				vm.ChartData.addColumn('number', 'Your Attendance');
				vm.ChartData.addColumn('number', 'Your Average Attendance');
				vm.ChartData.addColumn('number', 'Raid Attendance');

				// add chart data
				angular.forEach(temp, function(value, key) {
					sqlDate = value.Date.split(/[- :]/);
					vm.ChartData.addRow([new Date(sqlDate[0], sqlDate[1] - 1, sqlDate[2]), parseInt(value.Points), parseInt(value.PlayerAverage), parseInt(value.RaidAverage)]);
				});

				// draw chart
				chart = new google.charts.Line(document.getElementById('calendar_basic'));
				chartOptions = {
					width: '100%',
					height: 255,
					legend: {
						position: 'none'
					},
					vAxis: { 
						viewWindowMode: 'explicit',
						viewWindow: {
							max: 100,
							min: 0
						}
					},
					axisTitlesPosition: 'none'
				};
				chart.draw(vm.ChartData, chartOptions);
			},
			function(errmsg) {

			}
		);
	};
	$scope.populate();

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});