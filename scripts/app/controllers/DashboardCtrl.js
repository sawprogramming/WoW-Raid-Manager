app.controller("DashboardCtrl", function($scope, $modal, AttendanceSvc, toastr, PlayerSvc, DisputeSvc) {
	$scope.model = {
		Tab: 0,
		DailyEntities: [],
		DisputeEntities: [],
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

		DisputeSvc.GetUnresolved().then(
			function(response) {
				var data = response.data;

				vm.DisputeEntities = [];
				for(var i = 0; i < data.length; ++i) {
					vm.DisputeEntities.push({
						Points: data[i].Points,
						ID: data[i].ID,
						Date: data[i].Date,
						Name: data[i].Name,
						Comment: data[i].Comment,
						ClassID: data[i].ClassID,
						ClassName: data[i].ClassName,
						AttendanceID: data[i].AttendanceID,
						DisputePoints: data[i].DisputePoints,
						ClassStyle: ClassIdToCss(parseInt(data[i].ClassID))
					});
				}
			},
			function(errmsg) {

			}
		);
	}
	$scope.RefreshDaily();

	$scope.ApproveDispute = function(entity) {
		var modalInstance = $modal.open({
			templateUrl: 'approveDisputeModal.html',
			controller: 'ApproveDisputeModalCtrl',
			resolve: {
				entity: function() {
					return entity;
				},
				disputes: function() {
					return vm.DisputeEntities;
				}
			}
		});
	};

	$scope.RejectDispute = function(entity) {
		var modalInstance = $modal.open({
			templateUrl: 'rejectDisputeModal.html',
			controller: 'RejectDisputeModalCtrl',
			resolve: {
				entity: function() {
					return entity;
				},
				disputes: function() {
					return vm.DisputeEntities;
				}
			}
		});
	};

	$scope.RemoveDaily = function(player) {
		vm.DailyEntities.splice(vm.DailyEntities.indexOf(player), 1);
	};

	$scope.SaveDaily = function(form) {
		if(!form.$invalid) {
			for(var i = 0; i < vm.DailyEntities.length; ++i) {
				vm.DailyEntities[i].Date = vm.DailyDate;
			}

			AttendanceSvc.SaveGroupAttnd(vm.DailyEntities).then(
				function(response) {
					$scope.RefreshDaily();
					toastr.success("Attendance saved!");
				},
				function(errmsg) {
					toastr.error(errmsg.data, errmsg.statusText, { 
						closeButton: true,
						progressBar: true,
						timeOut: 30000,
				 	});
				}
			);
		}
	}

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};
});

app.controller("RejectDisputeModalCtrl", function($scope, $modalInstance, entity, disputes, DisputeSvc, toastr) {
	var rejectEntity = {
		ID: entity.ID,
		Verdict: false,
		Points: entity.Points,
		AttendanceID: entity.AttendanceID
	};
	$scope.row = entity;

	$scope.reject = function() {
		DisputeSvc.UpdateRecord(rejectEntity).then(
			function(response) {
				disputes.splice(disputes.indexOf(entity), 1);
				toastr.success("Dispute rejected!");
			},
			function(errmsg) {
				toastr.error(errmsg.data, errmsg.statusText, { 
					closeButton: true,
					progressBar: true,
					timeOut: 30000,
			 	});
			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});

app.controller("ApproveDisputeModalCtrl", function($scope, $modalInstance, entity, disputes, DisputeSvc, toastr) {
	var approveEntity = {
		ID: entity.ID,
		Verdict: true,
		Points: entity.DisputePoints,
		AttendanceID: entity.AttendanceID
	};
	$scope.row = entity;

	$scope.approve = function() {
		DisputeSvc.UpdateRecord(approveEntity).then(
			function(response) {
				disputes.splice(disputes.indexOf(entity), 1);
				toastr.success("Dispute approved!");
			},
			function(errmsg) {
				toastr.error(errmsg.data, errmsg.statusText, { 
					closeButton: true,
					progressBar: true,
					timeOut: 30000,
			 	});
			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});