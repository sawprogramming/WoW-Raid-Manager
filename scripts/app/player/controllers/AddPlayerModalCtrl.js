app.controller("AddPlayerModalCtrl", function($scope, $uibModalInstance, toastr, region, players, PlayerSvc) {
	$scope.NewPlayer = {
		Name: null,
		UserID: null,
		ClassID: null
	};
	$scope.AjaxForm = 'ready';
	$scope.region = region;

	$scope.add = function(form) {
		if(!form.$invalid) {
			$scope.AjaxForm = 'processing';
			$scope.NewPlayer.Name = $scope.NewPlayer.Name.charAt(0).toUpperCase() + $scope.NewPlayer.Name.slice(1).toLowerCase();
			
			PlayerSvc.AddPlayer($scope.NewPlayer)
				.success(function(data) {
					players.push({
						ID: data.ID,
						Name: data.Name,
						Realm: data.Realm,
						UserID: data.UserID,
						ClassID: data.ClassID,
						Username: data.Username,
						ClassName: data.ClassName,
						RealmName: data.RealmName,
						ClassStyle: ClassIdToCss(parseInt(data.ClassID))
					});

					toastr.success("Player added!");
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

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');
	};
});