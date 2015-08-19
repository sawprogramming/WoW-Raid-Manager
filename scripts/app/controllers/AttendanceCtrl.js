app.controller("AttendanceCtrl", function($scope, $modal, AttendanceSvc, PlayerSvc) {
	$scope.model = {
		AttendanceEntities: []
	};
	var vm = $scope.model;

	$scope.RefreshRecords = function() {
		AttendanceSvc.GetAll().then(
			function(response) {
				vm.AttendanceEntities = response.data;

				// transform ClassID to ClassStyle
				for(var i = 0; i < vm.AttendanceEntities.length; ++i) {
					vm.AttendanceEntities[i].ClassStyle = ClassIdToCss(parseInt(vm.AttendanceEntities[i].ClassID));
				}
			},
			function(errmsg) {

			}
		);
	}
	$scope.RefreshRecords();

	$scope.AddRecord = function() {
		var modalInstance = $modal.open({
			templateUrl: 'addRowModal.html',
			controller: 'AddAttndModalCtrl',
			resolve: {
				entities: function() {
					return vm.AttendanceEntities;
				}
			}
		});
	};

	$scope.DeleteRecord = function(record) { 
		var modalInstance = $modal.open({
			templateUrl: 'deleteRowModal.html',
			controller: 'DeleteAttndModalCtrl',
			resolve: {
				entity: function() {
					return record;
				},
				entities: function() {
					return vm.AttendanceEntities;
				}
			}
		});
	};

	$scope.EditRecord = function(record) { 
		var modalInstance = $modal.open({
			templateUrl: 'editRowModal.html',
			controller: 'EditAttndModalCtrl',
			resolve: {
				entity: function() {
					return record;
				}
			}
		});
	};

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};
});

app.controller("EditAttndModalCtrl", function($scope, $modalInstance, toastr, entity, AttendanceSvc) {
	$scope.reset = function() {
		$scope.row = {
			ID: entity.ID,
			Name: entity.Name,
			Date: entity.Date,
			Points: entity.Points,
			ClassID: entity.ClassID,
			PlayerID: entity.PlayerID,
			ClassName: entity.ClassName,
			ClassStyle: entity.ClassStyle
		};

		// fix for date being one off on edit
		var d = new Date($scope.row.Date);
		d.setMinutes( d.getMinutes() + d.getTimezoneOffset());
		$scope.row.Date = d;
	};
	$scope.reset();

	$scope.save = function(form) {
		if(!form.$invalid) {
			AttendanceSvc.UpdateRecord($scope.row).then(
				function(response) {
					var data = response.data;

					// update the row on success
					entity.Name = data.Name;
					entity.Date = data.Date;
					entity.Points = data.Points;
					entity.ClassID = data.ClassID;
					entity.PlayerID = data.PlayerID;
					entity.ClassName = data.ClassName;
					entity.ClassStyle = ClassIdToCss(parseInt(data.ClassID));

					toastr.success("Record updated!");
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
		}
	};

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});

app.controller("DeleteAttndModalCtrl", function($scope, $modalInstance, toastr, entity, entities, AttendanceSvc) {
	$scope.row = entity;

	$scope.delete = function() {
		AttendanceSvc.DeleteRecord($scope.row.ID).then(
			function(response) {
				toastr.success("Record deleted!");
				entities.splice(entities.indexOf(entity), 1);
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

app.controller("AddAttndModalCtrl", function($scope, $modalInstance, toastr, entities, AttendanceSvc) {
	$scope.row = {
		Points: null,
		ClassID: null,
		PlayerID: null,
		Date: new Date()
	};

	$scope.save = function(form) {
		if(!form.$invalid && $scope.row.Points != null) {
			AttendanceSvc.AddRecord($scope.row).then(
				function(response) {
					var data = response.data;

					// add the record to the attendance array
					entities.unshift({
						ID: data.ID,
						Name: data.Name,
						Date: data.Date,
						Points: data.Points,
						ClassID: data.ClassID,
						PlayerID: data.PlayerID,
						ClassName: data.ClassName,
						ClassStyle: ClassIdToCss(parseInt(data.ClassID))
					});

					toastr.success("Record added!");
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
		}
	};

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});