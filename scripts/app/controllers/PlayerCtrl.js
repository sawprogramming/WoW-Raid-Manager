app.controller('PlayerCtrl', function($scope, $modal, PlayerSvc, DTOptionsBuilder) {
	$scope.model = {
		Players: [],
		dtOptions: null,
		NewPlayer: {
			Name: null,
			ClassID: 0
		}
	};
	var vm = $scope.model;

	function RefreshPlayers() {
		PlayerSvc.GetPlayers().then(
			function(response) {
				vm.Players = response.data;

				// transform ClassID to CSS class name
				angular.forEach(vm.Players, function(value, key) {
					value.ClassID = ClassIdToCss(parseInt(value.ClassID));
				});
			},
			function(errmsg) {

			}
		);
	}
	$scope.Refresh = RefreshPlayers;

	$scope.populate = function() {
		RefreshPlayers();

		vm.dtOptions = DTOptionsBuilder.newOptions()
							   		   .withPaginationType('full_numbers')
							   		   .withDisplayLength(15);
	};
	$scope.populate();

	$scope.Add = function() {
		var modalInstance = $modal.open({
			templateUrl: 'addRowModal.html',
			controller: 'AddPlayerModalCtrl'
		});
	};

	$scope.Remove = function(index) {
		var modalInstance = $modal.open({
			templateUrl: 'deleteRowModal.html',
			controller: 'DeletePlayerModalCtrl',
			resolve: {
				entities: function() {
					return vm.Players;
				},
				index: function() {
					return index;
				}
			}
		});
	};
});


app.controller("DeletePlayerModalCtrl", function($scope, $modalInstance, entities, index, PlayerSvc) {
	$scope.row = entities[index];

	$scope.delete = function() {
		PlayerSvc.DeletePlayer($scope.row.ID).then(
			function(response) {
				entities.splice(index, 1);
			},
			function(errmsg) {

			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});

app.controller("AddPlayerModalCtrl", function($scope, $modalInstance, PlayerSvc) {
	$scope.NewPlayer = {
		Name: null,
		ClassID: null
	};

	$scope.add = function() {
		PlayerSvc.AddPlayer($scope.NewPlayer).then(
			function(response) {
			},
			function(errmsg) {

			}
		);
		$scope.cancel();
	};

	$scope.cancel = function() {
		$modalInstance.dismiss('cancel');
	}
});