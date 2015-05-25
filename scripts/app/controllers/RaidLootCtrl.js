app.controller("RaidLootCtrl", function($scope, RaidLootSvc, DTOptionsBuilder) {
	$scope.model = {
		RaidLoot: [],
		dtOptions: null
	};
	var vm = $scope.model;

	function Refresh() {
		RaidLootSvc.GetAll().then(
			function(response) {
				vm.RaidLoot = response.data;

				// transform data into proper stuff for view
				angular.forEach(vm.RaidLoot, function(value, key) {
					value.ClassID = ClassIdToCss(parseInt(value.ClassID));
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
		vm.dtOptions = DTOptionsBuilder.newOptions()
							   .withPaginationType('full_numbers')
							   .withDisplayLength(15);
	};
	$scope.populate();

	$scope.Remove = function(index) {
		RaidLootSvc.Delete(vm.RaidLoot[index].ID).then(
			function(response) {
				vm.RaidLoot.splice(index, 1);
			},
			function(errormsg) {

			}
		);
	};

	$scope.RefreshTable = Refresh;
});