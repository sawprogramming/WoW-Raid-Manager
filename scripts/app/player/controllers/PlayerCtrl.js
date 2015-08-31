app.controller('PlayerCtrl', function($scope, $modal, PlayerSvc) {
	$scope.model = {
		Players: [],
		AjaxContent: {
			Players: { status: 'loading', message: '' }
		}
	};
	var vm = $scope.model;

	$scope.Refresh = function() {
		vm.AjaxContent.Players.status = "loading";
		
		PlayerSvc.GetPlayers()
			.success(function(data) {
				vm.Players = data;

				// transform ClassID to ClassStyle
				for(var i = 0; i < vm.Players.length; ++i) {
					vm.Players[i].ClassStyle = ClassIdToCss(parseInt(vm.Players[i].ClassID));
				}

				vm.AjaxContent.Players.status = "success";
			})
			.error(function(errmsg) {
				vm.AjaxContent.Players.status = "error";
				vm.AjaxContent.Players.message = errmsg;
			});
	};
	$scope.Refresh();

	$scope.Add = function() {
		var modalInstance = $modal.open({
			templateUrl: plugin_url.app + '/player/templates/addPlayerModal.html',
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
			templateUrl: plugin_url.app + '/player/templates/deletePlayerModal.html',
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
			templateUrl: plugin_url.app + '/player/templates/editPlayerModal.html',
			controller: 'EditPlayerModalCtrl',
			resolve: {
				entity: function() {
					return record;
				}
			}
		});
	};
});