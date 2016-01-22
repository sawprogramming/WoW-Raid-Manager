app.controller("AttendanceCtrl", function($scope, $uibModal, AttendanceSvc, PlayerSvc) {
	$scope.model = {
		AttendanceEntities: [],
		AjaxContent: {
			Attendance: { status: 'loading', message: '' }
		}
	};
	var vm = $scope.model;

	$scope.RefreshRecords = function() {
		vm.AjaxContent.Attendance.status = "loading";

		AttendanceSvc.GetAll()
			.success(function(data) {
				vm.AttendanceEntities = data;
				
				// transform ClassID to ClassStyle
				for(var i = 0; i < vm.AttendanceEntities.length; ++i) {
					vm.AttendanceEntities[i].ClassStyle = ClassIdToCss(parseInt(vm.AttendanceEntities[i].ClassID));
				}

				vm.AjaxContent.Attendance.status = "success";
			})
			.error(function(errmsg) {
				vm.AjaxContent.Attendance.status = "error";
				vm.AjaxContent.Attendance.message = errmsg;
			});
	}
	$scope.RefreshRecords();

	$scope.AddRecord = function() {
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/attendance/templates/addAttendanceModal.html',
			controller: 'AddAttendanceModalCtrl',
			resolve: {
				entities: function() {
					return vm.AttendanceEntities;
				}
			}
		});
	};

	$scope.DeleteRecord = function(record) { 
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/attendance/templates/deleteAttendanceModal.html',
			controller: 'DeleteAttendanceModalCtrl',
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
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/attendance/templates/editAttendanceModal.html',
			controller: 'EditAttendanceModalCtrl',
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