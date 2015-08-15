app.controller("RaidLootCtrl", function($scope, $modal, RaidLootSvc) {
	$scope.model = {
		RaidLoot: [],
		currentPage: 1
	};
	var vm = $scope.model;

    $scope.RefreshLootLinks = RefreshLootLinks;

	function Refresh() {
		RaidLootSvc.GetAll().then(
			function(response) {
				vm.RaidLoot = response.data;

				// transform data into proper stuff for view
				angular.forEach(vm.RaidLoot, function(value, key) {
					value.ClassStyle = ClassIdToCss(parseInt(value.ClassID));
					value.Item = BuildLootURL(value.Item);
				});


			   RefreshLootLinks();
			},
			function(errmsg) {

			}
		);
	}

	$scope.populate = function() {
		Refresh();
	};
	$scope.populate();

	$scope.Remove = function(row) {
		var modalInstance = $modal.open({
			templateUrl: 'deleteRowModal.html',
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

app.controller("DeleteRaidLootModalCtrl", function($scope, $modalInstance, entity, records, RaidLootSvc) {
	$scope.row = entity;
	RefreshLootLinks();

	$scope.delete = function() {
		RaidLootSvc.Delete($scope.row.ID).then(
			function(response) {
				// remove the player from the players array
				records.splice(records.indexOf(entity), 1);
				RefreshLootLinks();
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

function RefreshLootLinks() {
    setTimeout(function () {
        $WowheadPower.refreshLinks();
    }, 25);
}