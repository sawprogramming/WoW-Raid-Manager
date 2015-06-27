app.controller("DashboardCtrl", function($scope, $modal, AttendanceSvc, PlayerSvc) {
	$scope.model = {
		DailyEntities: [],
		DailyDate: new Date()
	};
	var vm = $scope.model;

	$scope.RefreshDaily = function() {
		PlayerSvc.GetPlayers().then(
			function(response) {
				var data = response.data;

				// add players to daily entities
				vm.DailyEntities = [];
				for(var i = 0; i < data.length; ++i) {
					vm.DailyEntities.push({
						Points: 1.00,
						ID: data[i].ID,
						Date: new Date(),
						Name: data[i].Name,
						ClassID: data[i].ClassID,
						ClassName: data[i].ClassName,
						ClassStyle: ClassIdToCss(parseInt(data[i].ClassID))
					});
				}
			},
			function(errmsg) {

			}
		);
	}
	$scope.RefreshDaily();

	$scope.RemoveDaily = function(player) {
		vm.DailyEntities.splice(vm.DailyEntities.indexOf(player), 1);
	};

	$scope.SaveDaily = function() {
		for(var i = 0; i < vm.DailyEntities.length; ++i) {
			vm.DailyEntities[i].Date = vm.DailyDate;
		}

		AttendanceSvc.SaveGroupAttnd(vm.DailyEntities).then(
			function(response) {
				$scope.RefreshDaily();
			},
			function(errmsg) {

			}
		);
	}

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};
});

