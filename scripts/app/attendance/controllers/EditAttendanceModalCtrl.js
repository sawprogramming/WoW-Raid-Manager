app.controller("EditAttendanceModalCtrl", function($scope, $modalInstance, toastr, entity, AttendanceSvc) {
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
	$scope.AjaxForm = 'ready';

	$scope.save = function(form) {
		if(!form.$invalid) {
			$scope.AjaxForm = 'processing';

			AttendanceSvc.UpdateRecord($scope.row)
				.success(function(data) {
					entity.Name = data.Name;
					entity.Date = data.Date;
					entity.Points = data.Points;
					entity.ClassID = data.ClassID;
					entity.PlayerID = data.PlayerID;
					entity.ClassName = data.ClassName;
					entity.ClassStyle = ClassIdToCss(parseInt(data.ClassID));

					toastr.success("Record updated!");
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
		$modalInstance.dismiss('cancel');
	};
});