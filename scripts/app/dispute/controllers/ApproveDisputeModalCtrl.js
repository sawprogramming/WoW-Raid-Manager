app.controller("ApproveDisputeModalCtrl", function($scope, $modalInstance, entity, disputes, DisputeSvc, toastr) {
	var approveEntity = {
		ID: entity.ID,
		Verdict: true,
		Points: entity.DisputePoints,
		AttendanceID: entity.AttendanceID
	};
	$scope.row = entity;
	$scope.AjaxForm = 'ready';

	$scope.approve = function() {
		$scope.AjaxForm = 'processing';

		DisputeSvc.UpdateRecord(approveEntity)
			.success(function(data) {
				disputes.splice(disputes.indexOf(entity), 1);
				toastr.success("Dispute approved!");
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