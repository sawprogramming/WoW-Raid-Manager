app.controller("DeletePlayerModalCtrl", function($scope, $modalInstance, toastr, entity, players, PlayerSvc) {
	$scope.row = entity;
	$scope.AjaxForm = 'ready';

	$scope.delete = function() {
		$scope.AjaxForm = 'processing';

		PlayerSvc.DeletePlayer($scope.row.ID)
			.success(function(data) {
				toastr.success("Player deleted!");
				players.splice(players.indexOf(entity), 1);
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