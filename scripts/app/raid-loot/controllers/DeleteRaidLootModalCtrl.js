app.controller("DeleteRaidLootModalCtrl", function($scope, $uibModalInstance, toastr, entity, records, RaidLootSvc) {
	$scope.row = entity;
	$scope.AjaxForm = 'ready';
	RefreshLootLinks();

	$scope.delete = function() {
		$scope.AjaxForm = 'processing';

		RaidLootSvc.Delete($scope.row.ID)
			.success(function(data) {
				toastr.success("Record deleted!");
				records.splice(records.indexOf(entity), 1);
				$scope.cancel();
				RefreshLootLinks();
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
		$uibModalInstance.dismiss('cancel');
	};
});