app.controller('PlayerCtrl', function($scope, $uibModal, toastr, PlayerSvc, OptionSvc) {
	$scope.model = {
		Options: {},
		Players: [],
		AjaxContent: {
			Players: { status: 'loading', message: '' }
		}
	};
	$scope.AjaxForm = 'ready';
	var vm = $scope.model;

	$scope.Refresh = function() {
		vm.AjaxContent.Players.status = "loading";
		
		OptionSvc.GetOptions()
			.success(function(data) {
				vm.Options = data;
			});

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
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/player/templates/addPlayerModal.html',
			controller: 'AddPlayerModalCtrl',
			resolve: {
				players: function() {
					return vm.Players;
				},
				region: function() {
					return vm.Options.wro_region;
				}
			}
		});
	};

	$scope.Remove = function(record) {
		var modalInstance = $uibModal.open({
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
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/player/templates/editPlayerModal.html',
			controller: 'EditPlayerModalCtrl',
			resolve: {
				entity: function() {
					return record;
				},
				region: function() {
					return vm.Options.wro_region;
				}
			}
		});
	};

	$scope.SaveSettings = function() {
		var usedOptions = [
			{ "key": "wro_region",        "value": vm.Options.wro_region },
			{ "key": "wro_default_realm", "value": vm.Options.wro_default_realm },
			{ "key": "wro_faction",       "value": vm.Options.wro_faction }
		];

		$scope.AjaxForm = "processing";
		OptionSvc.UpdateOptions(usedOptions)
			.success(function(data) {
				toastr.success("Preferences updated!");
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
});