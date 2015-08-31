app.controller("DeleteAttendanceModalCtrl", function($scope, $modalInstance, toastr, entity, entities, AttendanceSvc) {
	$scope.row = entity;
	$scope.AjaxForm = 'ready';

	$scope.delete = function() {
		$scope.AjaxForm = 'processing';

		AttendanceSvc.DeleteRecord($scope.row.ID)
			.success(function(data) {
				toastr.success("Record deleted!");
				entities.splice(entities.indexOf(entity), 1);
				$scope.cancel();
			})
			.error(function(message, status) {
				toastr.error(message, status, { 
					closeButton: true,
					progressBar: true,
					timeOut: 30000,
			 	});
			})
			.finally(function() {
				$scope.AjaxForm = 'ready';
			});
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	};
});