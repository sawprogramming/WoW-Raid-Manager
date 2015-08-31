app.controller("RaidLootCtrl", function($scope, $modal, RaidLootSvc) {
	$scope.model = {
		RaidLoot: [],
		AjaxContent: {
			RaidLoot: { status: 'loading', message: '' }
		}
	};
	var vm = $scope.model;

    $scope.RefreshLootLinks = RefreshLootLinks;

	function Refresh() {
		vm.AjaxContent.RaidLoot.status = 'loading';

		RaidLootSvc.GetAll()
			.success(function(data) {
				vm.RaidLoot = data;

				// transform data into proper stuff for view
				angular.forEach(vm.RaidLoot, function(value, key) {
					value.ClassStyle = ClassIdToCss(parseInt(value.ClassID));
					value.Item = BuildLootURL(value.Item);
				});

				vm.AjaxContent.RaidLoot.status = 'success';
			    RefreshLootLinks();
			})
			.error(function(errmsg) {
				vm.AjaxContent.RaidLoot.status = 'error';
				vm.AjaxContent.RaidLoot.message = errmsg;
			});
	}

	$scope.populate = function() {
		Refresh();
	};
	$scope.populate();

	$scope.Remove = function(row) {
		var modalInstance = $modal.open({
			templateUrl: plugin_url.app + '/raid-loot/templates/deleteLootModal.html',
			controller: 'DeleteRaidLootModalCtrl',
			resolve: {
				entity: function() {
					return row;
				},
				records: function() {
					return vm.RaidLoot;
				}
			}
		});
	};

	$scope.RefreshTable = Refresh;
});