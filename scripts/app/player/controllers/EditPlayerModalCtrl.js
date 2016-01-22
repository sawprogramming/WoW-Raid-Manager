app.controller("EditPlayerModalCtrl", function($scope, $uibModalInstance, toastr, entity, PlayerSvc) {
	$scope.reset = function() {
		$scope.row = {
			ID: entity.ID,
			Name: entity.Name,
			UserID: entity.UserID,
			ClassID: entity.ClassID,
			ClassName: entity.ClassName,
			ClassStyle: entity.ClassStyle
		};
		$scope.HasUserID = entity.UserID == null ? false : true;
	};
	$scope.reset();
	$scope.AjaxForm = 'ready';

	$scope.save = function(form) {
		if(!form.$invalid) {
			$scope.AjaxForm = 'processing';
			$scope.row.Name = $scope.row.Name.charAt(0).toUpperCase() + $scope.row.Name.slice(1).toLowerCase();

			PlayerSvc.EditPlayer($scope.row)
				.success(function(data) {
					entity.Name = data.Name;
					entity.UserID = data.UserID;
					entity.ClassID = data.ClassID;
					entity.Username = data.Username;
					entity.ClassName = data.ClassName;
					entity.ClassStyle = ClassIdToCss(parseInt(data.ClassID));

					toastr.success("Player updated!");
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

	$scope.$watch('HasUserID', function(value) {
		if(value == false) {
			$scope.row.UserID = null;
		}
	});

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');
	};
});