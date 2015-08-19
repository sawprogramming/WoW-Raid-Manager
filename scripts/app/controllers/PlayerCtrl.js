app.controller('PlayerCtrl', function($scope, $modal, PlayerSvc) {
	$scope.model = {
		Players: []
	};
	var vm = $scope.model;

	$scope.Refresh = function() {
		PlayerSvc.GetPlayers().then(
			function(response) {
				vm.Players = response.data;

				// transform ClassID to ClassStyle
				for(var i = 0; i < vm.Players.length; ++i) {
					vm.Players[i].ClassStyle = ClassIdToCss(parseInt(vm.Players[i].ClassID));
				}
			},
			function(errmsg) {

			}
		);
	}
	$scope.Refresh();

	$scope.Add = function() {
		var modalInstance = $modal.open({
			templateUrl: 'addRowModal.html',
			controller: 'AddPlayerModalCtrl',
			resolve: {
				players: function() {
					return vm.Players;
				}
			}
		});
	};

	$scope.Remove = function(record) {
		var modalInstance = $modal.open({
			templateUrl: 'deleteRowModal.html',
			controller: 'DeletePlayerModalCtrl',
			resolve: {
				entity: function() {
					return record;
				},
				players: function() {
					return vm.Players;
				}
			}
		});
	};

	$scope.Edit = function(record) {
		var modalInstance = $modal.open({
			templateUrl: 'editRowModal.html',
			controller: 'EditPlayerModalCtrl',
			resolve: {
				entity: function() {
					return record;
				}
			}
		});
	};
});


app.controller("DeletePlayerModalCtrl", function($scope, $modalInstance, toastr, entity, players, PlayerSvc) {
	$scope.row = entity;

	$scope.delete = function() {
		PlayerSvc.DeletePlayer($scope.row.ID).then(
			function(response) {
				toastr.success("Player deleted!");
				players.splice(players.indexOf(entity), 1);
			},
			function(errmsg) {
				toastr.error(errmsg.data, errmsg.statusText, { 
					closeButton: true,
					progressBar: true,
					timeOut: 30000,
			 	});
			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});

app.controller("AddPlayerModalCtrl", function($scope, $modalInstance, toastr, players, PlayerSvc) {
	$scope.NewPlayer = {
		Name: null,
		UserID: null,
		ClassID: null
	};

	$scope.add = function(form) {
		if(!form.$invalid) {
			$scope.NewPlayer.Name = $scope.NewPlayer.Name.charAt(0).toUpperCase() + $scope.NewPlayer.Name.slice(1).toLowerCase();
			
			PlayerSvc.AddPlayer($scope.NewPlayer).then(
				function(response) {
					players.push({
						ID: response.data.ID,
						Name: response.data.Name,
						UserID: response.data.UserID,
						ClassID: response.data.ClassID,
						Username: response.data.Username,
						ClassName: response.data.ClassName,
						ClassStyle: ClassIdToCss(parseInt(response.data.ClassID))
					});

					toastr.success("Player added!");
				},
				function(errmsg) {
					toastr.error(errmsg.data, errmsg.statusText, { 
						closeButton: true,
						progressBar: true,
						timeOut: 30000,
				 	});
				}
			);
			$scope.cancel();
		}
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	};
});

app.controller("EditPlayerModalCtrl", function($scope, $modalInstance, toastr, entity, PlayerSvc) {
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

	$scope.save = function(form) {
		if(!form.$invalid) {
			$scope.row.Name = $scope.row.Name.charAt(0).toUpperCase() + $scope.row.Name.slice(1).toLowerCase();

			PlayerSvc.EditPlayer($scope.row).then(
				function(response) {
					entity.Name = response.data.Name;
					entity.UserID = response.data.UserID;
					entity.ClassID = response.data.ClassID;
					entity.Username = response.data.Username;
					entity.ClassName = response.data.ClassName;
					entity.ClassStyle = ClassIdToCss(parseInt(response.data.ClassID));

					toastr.success("Player updated!");
				},
				function(errmsg) {
					toastr.error(errmsg.data, errmsg.statusText, { 
						closeButton: true,
						progressBar: true,
						timeOut: 30000,
				 	});
				}
			);
			$scope.cancel();
		}
	};

	$scope.$watch('HasUserID', function(value) {
		if(value == false) {
			$scope.row.UserID = null;
		}
	});

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	};
});