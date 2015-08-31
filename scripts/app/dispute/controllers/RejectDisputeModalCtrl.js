app.controller("RejectDisputeModalCtrl", function($scope, $modalInstance, entity, disputes, DisputeSvc, toastr) {
	var rejectEntity = {
		ID: entity.ID,
		Verdict: false,
		Points: entity.Points,
		AttendanceID: entity.AttendanceID
	};
	$scope.row = entity;
	$scope.AjaxForm = 'ready';

	$scope.reject = function() {
		$scope.AjaxForm = 'processing';

		DisputeSvc.UpdateRecord(rejectEntity)
			.success(function(data) {
				disputes.splice(disputes.indexOf(entity), 1);
				toastr.success("Dispute rejected!");
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