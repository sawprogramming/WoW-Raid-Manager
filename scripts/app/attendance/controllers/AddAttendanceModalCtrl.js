app.controller("AddAttendanceModalCtrl", function($scope, $uibModalInstance, toastr, entities, AttendanceSvc) {
	$scope.row = {
		Points: null,
		ClassID: null,
		PlayerID: null,
		Date: new Date()
	};
	$scope.AjaxForm = 'ready';

	$scope.save = function(form) {
		if(!form.$invalid && $scope.row.Points != null) {
			$scope.AjaxForm = 'processing';

			AttendanceSvc.AddRecord($scope.row)
				.success(function(data) {
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
		}
	};

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');
	};
});