app.controller("AttendanceCtrl", function($scope, $modal, AttendanceSvc, PlayerSvc, DTOptionsBuilder) {
	$scope.model = {
		BreakdownEntities: [],
		AttendanceEntities: [],
		DailyEntities: [],
		ActiveTab: 0,
		DailyDate: new Date(),
		dtOptions: null,
		ddtOptions: null,
		dddtOptions: null
	};
	var vm = $scope.model;

	function RefreshBreakdown() {
		AttendanceSvc.GetBreakdown().then(
			function(response) {
				vm.BreakdownEntities = response.data;

				// transform ClassID to CSS class name
				angular.forEach(vm.BreakdownEntities, function(value, key) {
					value.ClassID = ClassIdToCss(parseInt(value.ClassID));
				});

				vm.dtOptions = DTOptionsBuilder.newOptions()
											   .withPaginationType('full_numbers')
											   .withDisplayLength(vm.BreakdownEntities.length);
			},
			function(errmsg) {

			}
		);
	}

	function RefreshDaily() {
		PlayerSvc.GetPlayers().then(
			function(response) {
				vm.DailyEntities = [];
				angular.forEach(response.data, function(value, key) {
					vm.DailyEntities.push({
						ID: value.ID,
						Name: value.Name,
						ClassID: ClassIdToCss(parseInt(value.ClassID)),
						ClassName: value.ClassName,
						Date: new Date(),
						Points: 1.00
					});
				});
			}
		);
	}

	function RefreshRecords() {
		AttendanceSvc.GetAll().then(
			function(response) {
				vm.AttendanceEntities = response.data;

				// transform ClassID to CSS class name
				angular.forEach(vm.AttendanceEntities, function(value, key) {
					value.ClassID = ClassIdToCss(parseInt(value.ClassID));
				});
			},
			function(errmsg) {

			}
		);
	}

	$scope.populate = function() {
		RefreshBreakdown();
		RefreshDaily();
		RefreshRecords();
		
		vm.ddtOptions = DTOptionsBuilder.newOptions()
			   .withPaginationType('full_numbers')
			   .withDisplayLength(15);

		vm.dddtOptions = DTOptionsBuilder.newOptions()
					   .withPaginationType('full_numbers')
					   .withDisplayLength(20);
	};
	$scope.populate();

	$scope.RemoveDaily = function(index) {
		vm.DailyEntities.splice(index, 1);
	};

	$scope.SaveDaily = function() {
		angular.forEach(vm.DailyEntities, function(value, key) {
			value.Date = vm.DailyDate;
		});

		AttendanceSvc.SaveGroupAttnd(vm.DailyEntities).then(
			function(response) {
				RefreshDaily();
				RefreshRecords();
			},
			function(errmsg) {

			}
		);
	}

	$scope.AddRecord = function() {
		var modalInstance = $modal.open({
			templateUrl: 'addRowModal.html',
			controller: 'AddAttndModalCtrl'
		});
	};

	$scope.EditRecord = function(index) { 
		var modalInstance = $modal.open({
			templateUrl: 'editRowModal.html',
			controller: 'EditAttndModalCtrl',
			resolve: {
				entity: function() {
					return vm.AttendanceEntities[index];
				}
			}
		});
	};

	$scope.DeleteRecord = function(index) { 
		var modalInstance = $modal.open({
			templateUrl: 'deleteRowModal.html',
			controller: 'DeleteAttndModalCtrl',
			resolve: {
				entities: function() {
					return vm.AttendanceEntities;
				},
				index: function() {
					return index;
				}
			}
		});
	};

	$scope.Refresh = function() {
		if(vm.ActiveTab == 0) {
			RefreshDaily();
		} else RefreshRecords();
	}

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();

		$scope.opened = true;
	};
});

app.controller("EditAttndModalCtrl", function($scope, $modalInstance, entity, AttendanceSvc) {
	$scope.reset = function() {
		$scope.row = {
			ID: entity.ID,
			PlayerID: entity.PlayerID,
			Name: entity.Name,
			ClassID: entity.ClassID,
			ClassName: entity.ClassName,
			Date: entity.Date,
			Points: entity.Points
		};
	};
	$scope.reset();

	$scope.save = function() {
		AttendanceSvc.UpdateRecord($scope.row).then(
			function(response) {
				// update the row on success
				entity.PlayerID = $scope.row.PlayerID;
				entity.Name = $scope.row.Name;
				entity.ClassID = $scope.row.ClassID;
				entity.ClassName = $scope.row.ClassName;
				entity.Date = $scope.row.Date;
				entity.Points = $scope.row.Points;
			},
			function(errmsg) {

			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});

app.controller("DeleteAttndModalCtrl", function($scope, $modalInstance, entities, index, AttendanceSvc) {
	$scope.row = entities[index];

	$scope.delete = function() {
		AttendanceSvc.DeleteRecord($scope.row.ID).then(
			function(response) {
				entities.splice(index, 1);
			},
			function(errmsg) {

			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});

app.controller("AddAttndModalCtrl", function($scope, $modalInstance, AttendanceSvc) {
	$scope.row = {
		ClassID: null,
		PlayerID: null,
		Date: new Date(),
		Points: null
	};

	$scope.save = function() {
		AttendanceSvc.AddRecord($scope.row).then(
			function(response) {

			},
			function(errmsg) {

			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();

		$scope.opened = true;
	};
});